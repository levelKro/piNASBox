	function requestFile(view) {  
		if (window.XMLHttpRequest) { RQ=new XMLHttpRequest(); }
		else { RQ=new ActiveXObject("Microsoft.XMLHTTP"); }
		RQ.onreadystatechange=function() {
			if (RQ.readyState==4 && RQ.status==200) {
				var result=RQ.responseText;		
				if(result=="nofile"){ 
					document.getElementById("getimg").src='/imgs/file_error.png';
					document.getElementById("getinfo").innerHTML='<b>ERREUR</b> : No file specified.'; 
				}
				else if(result=="nolink"){ 
					document.getElementById("getimg").src='/imgs/file_error.png';
					document.getElementById("getinfo").innerHTML='<b>ERREUR</b> : Can\'t create download link.'; 
				}
				else if(result=="nofound"){ 
					document.getElementById("getimg").src='/imgs/file_error.png';
					document.getElementById("getinfo").innerHTML='<b>ERREUR</b> : File not found'; 
				}
				else{
					document.getElementById("getimg").src='/imgs/file_download.png';
					document.getElementById("getinfo").innerHTML='Download is ready.<br><br><div style="text-align:center"><a href="'+result+'"><img src="/imgs/file_downloadnow.png"></a></div>';	
					window.location=result;
				}
			}
		}
		RQ.open("GET","/file?r="+Math.random(),true);
		RQ.send();	
	}	
