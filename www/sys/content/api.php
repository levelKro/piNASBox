<?php
	$this->meta["title"]="";
	$this->meta["description"]="";
	$this->meta["keywords"]="";
	$this->output["type"]="raw"; 
	ob_start();	
	switch($this->params['get']['t']){
		case 'version':
			echo "1.0a";
		break;
		default:
	}
	$this->output["content"]=ob_get_clean();	