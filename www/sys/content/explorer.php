<?php
	if(!defined('FWSECURITY')) die("");
	// Meta tags
	$this->meta["title"]="Files explorer";
	$this->meta["description"]="";
	$this->meta["keywords"]="";	
	$this->output["type"]="html";	
	
	// Preload script

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

	function backurl($src){
		if($src=="") return false;
		else {
			$result=array();
			if(!is_array($src)) $src=explode("/",$src);
			for($x=0;$x<count($src)-1;$x++){
				$result[]=$src[$x];
			}
			return ((count($result)>=1)?implode("/",$result):"");
		}
	}
	function findFile($path){
		if(file_exists($path)) return $path;
		elseif(file_exists(utf8_encode($path))) return utf8_encode($path);
		else return false;
	}
	function listdir($dir){
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			if($filename!="." && $filename!=".." && $filename!="lost+found" && substr($filename,0,1)!="."){
    			$files[] = array("name"=>utf8_decode($filename),"size"=>filesize($dir."/".$filename));
			}
		}
		sort($files);
		return $files;
	} 	 
	
	function size($bytes, $decimals = 2) {
    	$size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
	    $factor = floor((strlen($bytes) - 1) / 3);
    	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}		
	$request_raw	=	$this->params['get']['r'];
	$request		=	str_replace("..","",str_replace("/.","",str_replace("/..","",$request_raw)));
	$dir			=	$this->Config["path_files"].$request;

	// Output
	ob_start();		
	
	echo '	
		<h1>File explorer</h1>
			<ol class="breadcrumb">
				<li><a href="/explorer"><b>NASBOX</b></a></li>
				<li class="active">'.str_replace("/","</li><li class=\"active\">",$request).'</li>
			</ol>';
	// Aucun chemin, l'index
	if(findFile($dir)){
		$way=explode("/",$request);

		if(@opendir($dir)){
			echo '
				<h2>Dossier '.$way[(count($way)-1)].'</h2>
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th style="width:32px;"></th>
									<th>Name</th>
									<th style="width:100px;">Size</th>
								</tr>
							</thead>
							<tbody>
							'; if(backurl($request)!==false){ echo '
								<tr>
									<th style="text-align:center;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAEnSURBVDhPY6AYxM667BM/80orDCfMuFwVWn+FDSpNGOQvv718251//2F43dWf/7IW39jk1n7CGB/2aD0kitUAEN544/f/Bac/48WxMy814zQAhldd/oFVMwiHTD47B+QSsAFbbv/937vnxf9+JNy96+n/5ade/z9w+zNO3Ljx9g2wAUvPff2/89qH//c//CMJt2++tw1swOSDr/9fffULqyJ8uGr1zTlgAybsfY5VAT586+2f/xkLLteADZh2gHQDDt7+/Dd0whkbsAEzD5FuwPwjzz77dR7hZcheemvtytOvsSrCh3t33H8BTgep868fPPHgK1ZF+HDj+ju3wQYULLl+7e77v1gV4cPFy66fBxuQMOtCfdrcq0dIxTFTz8WBDaAMMDAAAM0Ts1N1UoY7AAAAAElFTkSuQmCC"></th>
									<td><a href="/explorer'.((backurl($request)!="")?'?r='.backurl($request):"").'">.. Parent folder</a></td>
									<td></td>
								</tr>';
							}
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
									<th style="text-align:center;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACDSURBVDhPY6AYxM667BM/80orDCfMuFwVWn+FDSpNGOQvv718251//2F43dWf/zIWXt/i2n48hhB26ThswZCz9MbB/j0v/sNw757n/yftffL/wO3PBHH5yltPGKrW3nlw/8O//+RgkN5RA0YNoI4BKbMuNqXNvXqEHAzSC81S5AIGBgAS2d+GAhQgCwAAAABJRU5ErkJggg=="></th>
									<td><a href="/explorer?r='.(($request)?$request.'/':'').$item['name'].'">'.$item['name'].'</a></td>
									<td></td>
								</tr>';				
			}
			foreach($files as $item){
				echo '
								<tr>
									<th style="text-align:center;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACRSURBVDhPY4ABl47Dcm7tJ4yxYff2Yy6h9Vd4oEqxg9YNN9fcev7lPzY8Z/+jf1WrbzzHa0jXltvL/+MA68+8+H/15a//FStvvMBpCCED7n/4h98QfAYcuP72/6pTL8B47sHH/7MXXD4B1YYA+AxAByC1UG0IMGrAqAEggC8zoWOQWqg2BMCXndExSC1EFwMDAL/Vg8oID6m4AAAAAElFTkSuQmCC"></th>
									<td><a href="/explorer?r='.(($request)?$request.'/':'').$item['name'].'">'.$item['name'].'</a></td>
									<td style="text-align:right;">'.size($item['size']).'</td>
								</tr>';
			}
			echo '
							</tbody>
							<tfoot>
							</tfoot>
						</table>
					</div>
';
		}    	
		else{
			$size=filesize($dir);
			echo '
                        <h2>Document '.$way[(count($way)-1)].'</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th>Windows share folder</th>
                                        <td>'.str_replace($this->Config["path_files"],"",str_replace("/".$way[(count($way)-1)],"",$dir)).'</td>
                                    </tr>
                                    <tr>
                                        <th>Download link</th>
                                        <td><a href="download/'.str_replace($this->Config["path_files"],"",$dir).'">/download/'.str_replace($this->Config["path_files"],"",$dir).'</a></td>
                                    </tr>
                                    <tr>
                                        <th>Size of file</th>
                                        <td>'.(($size>1)?size($size):"n/a").'</b> <i><small>('.(($size>1)?$size.' bytes':'n/a').')</small></td>
                                    </tr>
                                </tbody>
							<tfoot>
							</tfoot>
								
                            </table>
                        </div>
						<p><a href="/explorer?r='.backurl($request).'">Back to folder</a></p>
                    </div>
				</div>		';		
		}
	}
	else{
		echo "<h1>Erreur</h1><p>The folder '<i>".$dir."</i>' was not found.</p>";
	}
	$this->output["content"]=ob_get_clean();
