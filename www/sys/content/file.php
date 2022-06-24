<?php


	$this->meta["title"]="";
	$this->meta["description"]="";
	$this->meta["keywords"]="";
	$this->output["type"]="raw"; 
	ob_start();	

	if (!isset($_SESSION['file'])) {
		echo "nofile";
	} 
	else {
		$file=$_SESSION['file'];
		$id=$this->encodeID(time());
		$path=$file;
		$path_array=$this->getURI($path);
		$path_file=$path_array[(count($path_array)-1)];
		$path_dir=str_replace($path_file,"",$path);
		$source=$this->Config['path_files'].$path_dir;

		if(file_exists($source)){
			//if(symlink($source,$target)){ $id
				if(file_exists($source)){
				echo $this->Config['uri_get'].$path_dir."/".$path_file;
			}
			else{
				echo "nolink";

			}
		}
		else{
			echo "nofound:".$source;
		}
	}
	
	$this->output["content"]=ob_get_clean();	


