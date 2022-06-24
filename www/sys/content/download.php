<?php
	if(!defined('FWSECURITY')) die("");
	/***
	 *	 Affichage de téléchargement
	 *
	 ***/	
	 
	if (!isset($this->params['get']['file'])) $_SESSION['file']=null;
	else $_SESSION['file']=urldecode($this->params['get']['file']);
	if(is_dir($this->Config['path_files'].$this->data['fullpath']) || !file_exists($this->Config['path_files'].$this->data['fullpath'])) $this->showError(404,"Le fichier '<b>".$this->data['fullpath']."</b>' n'existe pas."); 
	else{	   
		$this->data['filesize']=filesize($this->Config['path_files'].$this->data['fullpath']);
		$path=$this->getURI($_SESSION['file']);
		$file=$path[(count($path)-1)];	
		// Configuration des META TAGS 
		$this->meta["title"]="Downloading ".$file;
		$this->meta["description"]="";
		$this->meta["keywords"]="";
		$this->output["type"]="html";   
	

		// Début d'enregistrement du OUTPUT principal
		ob_start();	 
		echo '

		<script src="/js/get.js" type="text/javascript"></script>
			<h1>Download</h1>
		';
		if(!isset($_SESSION['file'])){
			echo '
			<table style="margin:0 auto; width:60%; min-width:320px">
				<tr>
					<td style="padding:0.5em; width:64px; vertical-align:top;"><img src="/imgs/file_error.png" id="getimg" width="64" height="64"/></td>
					<td style="padding:1em; vertical-align:top;">
						<h3>'.$file.'</h3>
						<p>Can\'t enable your download session. Please contact the administrator if problem continue.</p>
					</td>
				</tr>
			</table>';
		}
		else{
			echo '
			<table class="table" style="margin:0 auto; width:60%; min-width:320px">
				<tr>
					<td style="padding:0.5em; width:64px; vertical-align:top;"><img src="/imgs/file_loading.gif" id="getimg" width="64" height="64"/></td>
					<td style="padding:1em; vertical-align:top;">
						<h3>'.$file.'</h3>
						<p>Size : '.$this->simpleSize($this->data['filesize']).'</p>
					</td></tr><tr><td colspan="2">
						<p id="getinfo" style="padding:0 15px; text-align:center;">Please wait, download starting in few seconds.</p>
					</td>
				</tr>
				
			</table>
					
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					setTimeout("requestFile();",5000);
				});
			</script>
			
			';
		}
		$this->output["content"]=ob_get_clean();
	}
