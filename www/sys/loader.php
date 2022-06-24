<?php
	DEFINE("FWSECURITY",time());
	
	/********************************
	
	Kore FrameWork Lite
	version 1.0 Beta									
	
	auteur Mathieu Légaré (levelKro) <levelkro@yahoo.ca>
	creation date 2015
	
	structure
	
	/sys/ => source
	/sys/content/ => les pages
	/sys/helpers/ => les plugins
	
	********************************/
	
	
	
	class FWKore { 				  
		// Valeur utilisé dans la class 
		private 	$timer=null;
		
		public 		$Config=null;	// Les paramètres de configuration
		public 		$db=null;		// mySQL Medoo (support courriel
		public 		$mail=null;		// Pear Mail (gestion courriel)
		private 	$smtp=null;		// Pear Mail (smtp support)
		
		// Informations Version pour accès rapide
		public 		$Name=null;
		public		$Version=null;
		public 		$Build=null;
		public 		$Author=null;
		
		// Information utilisable
		public 		$uri=null;				// URI complet normal
		public 		$params=null;			// URI Parameters fournis dans l'URI 
										//	- $params->post[]
										//	- $params->get[]

		public 		$data=null;				// Array des données, $data[nom_du_array] -> reste des données pour le view
		public 		$meta=null;				// Array des infos Metas (titres, Descriptions,....)
		public 		$output=null;			// Array des infos de sortie

		private 	$headerFiles=null;		// Array des fichiers à ajouter
		private 	$headerCodes=null;		// Array des codes à ajouter
		

	    public function __call($method, $args){
	        if (isset($this->$method)) {
    	        $func = $this->$method;
        	    return call_user_func_array($func, $args);
	        }
    	}		 				 
		
		function __construct() { 
			// Start Timer Page
			session_start(); 
			self::timer(true);	
			// Copyright
			$this->Name="Kore Framework";
			$this->Version="0.8";
			$this->Build="SE";
			$this->Author="levelKro.net, Mathieu Légaré";
			// Charge config
			include(dirname(__FILE__)."/config.inc.php");
			// Security
			$this->params=array("post"=>$_POST,"get"=>$_GET);
			unset($_REQUEST);
			unset($_POST);
			unset($_GET);			
			// Setup outils
			//self::setupMail();	
			self::setupDataBase();
			self::instance($this,"default");		 			
		}

		/**********
		 *	timer
		 **********/	
		function timer($start=false){
			// Calcul d'exécution
			if($start) $this->timer=microtime(true);
			else return (microtime(true)-$this->timer);
		}
		function getMicroTime($time){
			$xt=explode(" ",$time);		   
			$xt_mtime=explode(".",$xt[0]);
			$xt_mtime=str_split($xt_mtime[1],5);		
			return $xt_mtime[0];
		}
		function getSecondTime($time){
			$xt=explode(" ",$time);		   
			$xt_time=$xt[1];	 
			return $xt_time;
		}
		function getFullTime($time){
			$xt=explode(" ",$time);		   
			$xt_time=$xt[1];
			$xt_mtime=explode(".",$xt[0]);
			$xt_mtime=str_split($xt_mtime[1],5);	
			return $xt_time.".".$xt_mtime[0];	
		}			
		
		function simpleSize($bytes) {
			if ($bytes < 1000 * 1024) return number_format($bytes / 1024, 2) . " KB";
			elseif ($bytes < 1000 * 1048576) return number_format($bytes / 1048576, 2) . " MB";
			elseif ($bytes < 1000 * 1073741824) return number_format($bytes / 1073741824, 2) . " GB";
			else return number_format($bytes / 1099511627776, 2) . " TB";
		}
		/**********
		 *	setups
		 **********/			
		function setupDataBase() {
			//Medoo SQL Framework 
			$this->addLog("4","Loader - Construction de la base de donnée");
			require_once(dirname(__FILE__)."/helpers/medoo.min.php");
			if($this->Config['sql']){
				$this->db=new medoo(array('database_type' => $this->Config['sql_type'],'database_name' => $this->Config['sql_table'],'server' => $this->Config['sql_host'],'username' => $this->Config['sql_user'],'password' =>$this->Config['sql_pass']));
			}
			// Security
			$this->Config['sql_host']=null;
			$this->Config['sql_type']=null;
			$this->Config['sql_user']=null;
			$this->Config['sql_table']=null;
			$this->Config['sql_pass']=null;
		}		
		function setupMail() {		 
			//PearMail
			require_once(dirname(__FILE__)."/helpers/pearMail/PEAR.php");
			require_once(dirname(__FILE__)."/helpers/pearMail/mail.php");
			require_once(dirname(__FILE__)."/helpers/pearMail/Mail/mime.php");		
			$this->smtp = @Mail::factory('smtp',array ('host' => $this->Config['smtp_host'],'port' => $this->Config['smtp_port'],'auth' => true,'username' => $this->Config['smtp_user'],'password' => $this->Config['smtp_pass']));			
			$this->mail = new Mail_mime();
			// Security
			$this->Config['smtp_host']=null;
			$this->Config['smtp_port']=null;
			$this->Config['smtp_user']=null;
			$this->Config['smtp_pass']=null;			
		} 						  
		
		/**********
		 *	functions
		 **********/			
		function sendMail($source,$sourcename,$target,$targetname,$title,$message){
			// Envoyer un courriel
			$headers = array ('From' => $sourcename.' <'.$source.'>','To' => $targetname.' <'.$target.'>','Subject' => $title);								
	        $this->mail->setHTMLBody($message);
	        $mail_body = $this->mail->get();
			$headers=$this->mail->headers($headers);
			$mail = $this->smtp->send($target, $headers, $mail_body);
			if (PEAR::isError($mail)) { return false; }
			else { return true; }
		}
		
		function getURI($uri=""){
			// Créer un array du chemin demandé
			if(empty($uri)) $uri=str_replace("BEGINURI".$this->Config['uri_root'],"","BEGINURI".$_SERVER['REQUEST_URI']);
			$uri=explode("?",$uri);
			$uri=explode("/",$uri[0]);	
			if(empty($uri[0])) $uri=array("home");	
			return $uri;
		}						
		function loadPage($url=""){
			if(!is_array($url)) $url=$this->getURI($url);
			if($url[0]=="download" || $url[0]=="get"){
				$page=$url[0];									   
				$this->data['fullpath']=urldecode(str_replace($url[0]."/","",implode("/",$url)));	 
			}
			else{
				$page=implode("-",$url);
			}
			if(file_exists($this->Config['path_root'].'sys/content/'.$page.'.php')) include($this->Config['path_root'].'sys/content/'.$page.'.php');
			else $this->showError(404,$url);
		}
		function loadUser(){ } 
		function loadLogin(){ } 
		function showError($id,$msg){	   
			$this->meta["title"]="Erreur ".$id;
			$this->meta["description"]="";
			$this->meta["keywords"]="";	
			$this->output["type"]="html";	  
			ob_start();			
			echo "<h1>Erreur</h1><p>".(($msg)?$msg:'Une erreur est survenue, réessayer.')."</p>";	
			$this->output["content"]=ob_get_clean();	
					
		}	 
		function showPage(){			
			if($this->params['get']['thm'])	$thm=$this->params['get']['thm'];
			else $thm=$this->Config['theme'];
			if($this->params['get']['thm']!="" && file_exists($this->Config['path_root'].'themes/'.$this->Config['theme']."/")) $thm=$this->params['get']['thm'];
			if($this->output["type"]=="html") require_once($this->Config['path_root'].'themes/'.$thm."/header.php");
			echo $this->output["content"];
			if($this->output["type"]=="html") require_once($this->Config['path_root'].'themes/'.$thm."/footer.php");		
		}
		function headerAdd($type,$file){
			if(!is_array($this->headerFiles)) $this->headerFiles=array();
			$this->headerFiles[]=array('type'=>$type,'file'=>$file) ;
		}
		function headerShow() {
			ob_start();
			for($x=0;$x<count($this->headerFiles);$x++){
				switch($this->headerFiles[$x]['type']) {
					case 'js':
						echo '<script type="text/javascript" src="'.$this->headerFiles[$x]['file'].'"></script>';
					break;	
					case 'css':
						echo '<link type="text/css" href="'.$this->headerFiles[$x]['file'].'" rel="stylesheet" />';
					break;	
					case 'code':
						echo $this->headerFiles[$x]['file'];						
					break;					
					default: 
						echo '<!-- Type: '.$this->headerFiles[$x]['type'].' n\'est pas supporté -->';
				}
			} 
			return ob_get_clean();	
		}
	function decodeID($str){
		return intval($str,36);
	}	
	function encodeID($val){
		return base_convert($val, 10, 36);
	}				
	}
