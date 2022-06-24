<?php
	// Functions
	
	// UTF8 to RAW
	function utfclean($road){
		if(mb_detect_encoding($road, "UTF-8") != "UTF-8") {
			$path=$road;
			$road=utf8_encode($road);
		}
		else  {
			$path=utf8_decode($road);
		}
		return $path;
	}	
	function ifutf($road){
		if($road!=utf8_decode($road)) return true;
		else return false;
	}		
	// Get URL for Back Link
	function backurl($path){
		$result=array();
		if(!is_array($path)) $path=explode("/",$path);
		for($x=0;$x<(count($path)-1);$x++){
			$result[]=$path[$x];
		}
		return str_replace("/mnt","",str_replace("/mnt/","",implode("/",$result)));
	}
	function findFile($path){
		if(file_exists($path)) return $path;
		elseif(file_exists(utf8_encode($path))) return utf8_encode($path);
		else return false;
	}
	

	// Chemin depuis l'URL
	$road=@urldecode($_GET['r']);
	// Sécurise le chemin contre le Hack
	$road=str_replace("/.","",str_replace("/..","",$road));
	// Vrai chemin
	$dir="/mnt/".$road;
	$path=utfclean($road);	
	$dirs=array();
	/*
	$dirs[]=array(
		"name"=>"rtorrent",
		"title"=>"rTorrent",
		"desc"=>"Dossier de téléchargement de rTorrent"
	);
	*/
	$dirs[]=array(
		"name"=>"hdd2000gb",
		"title"=>"Storage 2000GB",
		"desc"=>"Séries télé"
	);
	$dirs[]=array(
		"name"=>"hdd1500gb",
		"title"=>"Storage 1500GB",
		"desc"=>"Films, Documentaires, Spectacles, Humours, Clips et DVD"
	);
	$dirs[]=array(
		"name"=>"hdd1000gb",
		"title"=>"Storage 1000GB",
		"desc"=>"Logiciels et Jeux"
	);
	$dirs[]=array(
		"name"=>"hdd320gba",
		"title"=>"Storage 320GB A",
		"desc"=>""
	);
	$dirs[]=array(
		"name"=>"hdd320gbb",
		"title"=>"Storage 320GB B",
		"desc"=>""
	);
	
	$dirs[]=array(
		"name"=>"usb40gb",
		"title"=>"Storage 40GB USB",
		"desc"=>""
	);
	
	$dirs[]=array(
		"name"=>"usb100gb",
		"title"=>"Storage 100GB USB",
		"desc"=>""
	);
	echo '	
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Explorateur Intranet
					<small>Naviguer parmis les fichiers hébergés sur le serveur réseau</small>
				</h1>
				<ol class="breadcrumb">
					<li><i class="fa fa-circle"></i>  <a href="/">LANBOX</a></li>
					<li class="active">Explorateur</li>
					<li>'.str_replace("/","</li><li>",$path).'</li>
				</ol>
			</div>
		</div>
		<!-- /.row -->
		<!--- page ici -->';
	// Aucun chemin, l'index
	if(empty($road)){
		echo '
		<div class="row">
			<div class="col-lg-12">
				<h2>Partages principaux</h2>
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:32px;"></th>
								<th>Nom</th>
								<th>Détails</th>
							</tr>
						</thead>
						<tbody>';
		foreach($dirs as $cdir){ 

		echo '
		
							<tr>
								<th style="text-align:center;"><i class="fa fa-folder"></i></th>
								<td><a href="/?page=intranet&r='.$cdir['name'].'">'.$cdir['title'].'</a></td>
								<td>'.$cdir['desc'].'</td>
							</tr>';
		}
		echo '
						</tbody>
					</table>
				</div>
			</div>					
		</div>';
	}
	elseif($path=findFile($dir)){
		$way=explode("/",$path);
		if(@opendir($dir)){
			echo '
		<div class="row">
			<div class="col-lg-12">
				<h2>Dossier '.utf8_decode($way[(count($way)-1)]).'</h2>
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th style="width:32px;"></th>
									<th>Nom</th>
									<th style="width:100px;">Taille</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th style="text-align:center;"><i class="fa fa-folder"></i></th>
									<td><a href="/?page=intranet&r='.backurl($path).'">.. Dossier Parent</a></td><!-- remplacer par lien dur -->
									<td></td>
								</tr>';
			$list=listdir($dir);
			sort($list);
			$dirs=array();
			$files=array();
			foreach($list as $item){
				$item['name']=utf8_encode($item['name']);
				if(@opendir($dir."/".$item['name']) && !in_array($item['name'],array(".",".."))) $dirs[]=array("name"=>$item['name']);
				elseif(!in_array($item['name'],array(".",".."))) $files[]=array("name"=>$item['name'],"size"=>$item['size']);
			}
			foreach($dirs as $item){
				echo '
								<tr>
									<th style="text-align:center;"><i class="fa fa-folder"></i></th>
									<td><a href="/?page=intranet&r='.urlencode($road.'/'.$item['name']).'">'.utf8_decode($item['name']).'</a></td>
									<td></td>
								</tr>';				
			}
			foreach($files as $item){
				echo '
								<tr>
									<th style="text-align:center;"><i class="fa fa-file"></i></th>
									<td><a href="/?page=intranet&r='.urlencode($road.'/'.$item['name']).'">'.utf8_decode($item['name']).'</a></td>
									<td style="text-align:right;">'.size($item['size']).'</td>
								</tr>';
			}
			echo '
							</tbody>
						</table>
					</div>
				</div>					
			</div>';
		}    	
		else{
			$size=filesize($dir);
			echo '
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Document '.utf8_decode($way[(count($way)-1)]).'</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th>Source du partage</th>
                                        <td>'.str_replace("/mnt","",utf8_decode(str_replace("/".$way[(count($way)-1)],"",$path))).'</td>
                                    </tr>
                                    <tr>
                                        <th>Lien du document</th>
                                        <td><a href="http://'.$_SERVER["HTTP_HOST"].str_replace("mnt/","get/",utf8_decode($dir)).'">http://'.$_SERVER["HTTP_HOST"].str_replace("mnt/","get/",utf8_decode($dir)).'</a></td>
                                    </tr>
                                    <tr>
                                        <th>Taille du document</th>
                                        <td>'.(($size>1)?size($size):"n/a").'</b> <i><small>('.(($size>1)?$size.' octets':'n/a').')</small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
						<p><a href="/?page=intranet&r='.backurl($path).'">Retourner au dossier</a></p>
                    </div>
				</div>		';		
		}
	}
	else{
		echo "<p>Erreur, le dossier ou document '<i>".$dir."</i>' n'a pas été trouvé.</p><!-- ".$dir." -->";
	}
//phpinfo();
?>

			
						<!-- fin de page -->		
				
				
				
			
				

