<?php		   
	if(!defined('FWSECURITY')) die("");
	// Preload script
	
	// Output
	
	// Meta tags
	$this->meta["title"]="System informations";
	$this->meta["description"]="";
	$this->meta["keywords"]="";	

	// Content page
	$this->output["type"]="html";	  
	
	ob_start();			
?>
	<h1>System informations</h1>
                        <h2>System</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th width=120>Hardware</th>
                                        <td><span id="hardwarename"></span><br/><span id="cpuname"></span></td>
                                        <th width=120>OS</th>
                                        <td><span id="osname"></span><br/><span id="uptime"></span></td>
                                    </tr>
                                    <tr>
                                        <th rowspan=2>CPU Load</th>
                                        <td rowspan=2><span id="cpucharge"></span></td>
										<th>Memory</th>
										<td><span id="memory"></span></td>
									</tr>
									<tr>
                                        <th>Process</th>
                                        <td><span id="tasks"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Identification</th>
                                        <td colspan=3><span id="sysname"></span>/<span id="ip"></span></td>
									</tr>
									<tr>
                                        <th>Network interface</th>
                                        <td colspan=3 id="network" valign=top style="vertical-align:top;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h2>Storage</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
										<th>Mount name</th>
                                        <th>Used</th>
                                        <th>Free</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="drives">
  
                                </tbody>
                            </table>
                        </div>

					
					
					
<script language="" src="/js/filesize.min.js"></script>			

<script>
function updateData(view) {  
	if (window.XMLHttpRequest) { syncSiteCall=new XMLHttpRequest(); }
	else { syncSiteCall=new ActiveXObject("Microsoft.XMLHTTP"); }
	syncSiteCall.onreadystatechange=function() {
		if (syncSiteCall.readyState==4 && syncSiteCall.status==200) {
			var response=syncSiteCall.responseText;	
			var values=jQuery.parseXML(response);	
			$xml = $( values );
			if($xml){
				$system = $xml.find( "Vitals" );
				$network = $xml.find( "NetDevice" );
				$hardware = $xml.find( "Hardware" );
				$memory = $xml.find( "Memory" );
				$files = $xml.find( "FileSystem" );
				$mbinfo = $xml.find( "MBInfo" );
				$temperatures = $mbinfo.find( "Temperature" );
				$temperatures = $temperatures.find( "Item" );
				$cpu=$hardware.find( "CPU" );
				$cpus=$cpu.find( "CpuCore" );			
				memUse=$memory.attr("Used");
				memFree=$memory.attr("Free");
				memTotal=$memory.attr("Total");
				$hdds=$files.find( "Mount" );	
				document.getElementById("sysname").innerHTML=$system.attr("Hostname");
				document.getElementById("ip").innerHTML=$system.attr("IPAddr");
				document.getElementById("osname").innerHTML=$system.attr("Distro");
				document.getElementById("uptime").innerHTML=$system.attr("Uptime");
				document.getElementById("cpuname").innerHTML=$cpus.attr("Model")+" ("+$cpus.length+"X)";
				document.getElementById("hardwarename").innerHTML=$hardware.attr("Name");
				document.getElementById("cpucharge").innerHTML=Math.round( $system.attr("CPULoad"))+"% average, <span id=\"temp\"></span><div  style=\"width:200px;border:1px solid #d0d0d0; border-radius:3px; height:15px; overflow:hidden; display:block; text-align:right; background:url('/imgs/bgpc.png') no-repeat;background-size:"+Math.round($system.attr("CPULoad"))+"% 100%\"><\/div>";
				x=0;
				$.each($cpus, function(index, value) {
					document.getElementById("cpucharge").innerHTML+=" - CPU "+x+" : "+Math.round(Math.round($(value).attr("CpuSpeed"))/10)/100+" GHz, Load "+Math.round($(value).attr("Load"))+"%, <span id=\"temp"+x+"\"></span><br/>";
					x++;
				}); 	
				
				document.getElementById("tasks").innerHTML=$system.attr("ProcessesRunning")+" actives, "+$system.attr("ProcessesSleeping")+" sleeps, "+$system.attr("Processes")+" total";
				document.getElementById("memory").innerHTML=Math.round((( memUse / memTotal ) * 100)) + "% - " + filesize(memUse, {round: 2}) + " / " + filesize(memTotal, {round: 2})+"<div  style=\"width:200px;border:1px solid #d0d0d0; border-radius:3px; height:15px; overflow:hidden; display:block; text-align:right; background:url('/imgs/bgpc.png') no-repeat;background-size:"+Math.round((( memUse / memTotal ) * 100))+"% 100%\"><\/div>";

				document.getElementById("network").innerHTML="";
				$.each($network, function(index, value) {
					document.getElementById("network").innerHTML+="<div style=\"display:inline-block; float:left; margin:5px;\">"+$(value).attr("Name")+" - DOWN:"+filesize($(value).attr("RxBytes"))+" - UP:"+filesize($(value).attr("TxBytes"))+"<br//>"+$(value).attr("Info").replace(/;/g,"<br//>")+"<//div>";	
				}); 
							

				var dvUsed=0;
				var dvFree=0;
				var dvTotal=0;
				document.getElementById("drives").innerHTML="";
				$.each($hdds, function(index, value) {
					dvFree += Math.round($(value).attr("Free"));
					dvUsed += Math.round($(value).attr("Total")-$(value).attr("Free"));
					dvTotal += Math.round($(value).attr("Total"));
					pcFree = Math.round((dvFree*100)/dvTotal)+"%";
					pcUsed = Math.round((dvUsed*100)/dvTotal)+"%";
					document.getElementById("drives").innerHTML+='<tr><th>'+$(value).attr("MountPoint")+' (<small>'+$(value).attr("Name")+'</small>) <span id="hdd'+$(value).attr("Name").replace("/dev/","_").replace("1","")+'"></span></th><td style="text-align:right; background:url(\'/imgs/bgpc.png\') no-repeat;background-size:'+Math.round((($(value).attr("Total")-$(value).attr("Free"))*100)/$(value).attr("Total"))+'% 100%"><span style=\"float:left;\">('+Math.round((($(value).attr("Total")-$(value).attr("Free"))*100)/$(value).attr("Total"))+'%)<\/span> '+filesize($(value).attr("Total")-$(value).attr("Free"))+'</td><td style="text-align:right; background:url(\'/imgs/bgpc.png\') no-repeat;background-size:'+Math.round(($(value).attr("Free")*100)/$(value).attr("Total"))+'% 100%"><span style=\"float:left;\">('+Math.round(($(value).attr("Free")*100)/$(value).attr("Total"))+'%)<\/span> '+filesize($(value).attr("Free"))+'</td><td style="text-align:right;">'+filesize($(value).attr("Total"))+'</td></tr>';					
				}); 				
				document.getElementById("drives").innerHTML+='<tr><th>Totals</th><th style="text-align:right; background:url(\'/imgs/bgpc.png\') no-repeat;color:#000000;background-size:'+pcUsed+' 100%"><span style=\"float:left;\">('+pcUsed+')<\/span> '+filesize(dvUsed)+'</th><th style="text-align:right;color:#000000; background:url(\'/imgs/bgpc.png\') no-repeat;background-size:'+pcFree+' 100%"><span style=\"float:left;\">('+pcFree+')<\/span> '+filesize(dvFree)+'</th><th style="text-align:right;">'+filesize(dvTotal)+'</th></tr>';
				
				var mycpu=null;
				thiscpu=0;
				$.each($temperatures, function(index, value) {
					if ($(value).attr("Label")=="temp1 (acpitz)") document.getElementById("temp").innerHTML="("+$(value).attr("Value")+"/"+$(value).attr("Max")+"°C)";
					else if(thiscpu<x) {
						mycpu=$(value).attr("Value");
						document.getElementById("temp"+thiscpu).innerHTML=mycpu+"°C";
						mycpu=null;
						thiscpu++;
					}
					else {
						hddTempName=$(value).attr("Label").split(" ");
						//document.getElementById("uptime").innerHTML+="hdd"+hddTempName[0].replace("/dev/","_")+"\n";
						if(document.getElementById("hdd"+hddTempName[0].replace("/dev/","_"))){
							document.getElementById("hdd"+hddTempName[0].replace("/dev/","_")).innerHTML="("+$(value).attr("Value")+"/"+$(value).attr("Max")+"°C)";
						}
					}
				}); 				
				
				var seconds = $system.attr("Uptime");
				day = 86400;
				hour = 3600;
				min = 60;
				
				dd = Math.floor(seconds / day);
				seconds = ( seconds - ( dd * day));
				hh = Math.floor(seconds / hour);
				seconds = ( seconds - ( hh * hour));
				mm = Math.floor(seconds / min);
				seconds = ( seconds - ( mm * min));
				ss = Math.floor(seconds);
				// These lines ensure you have two-digits
				if (hh < 10) {hh = "0"+hh;}
				if (mm < 10) {mm = "0"+mm;}
				if (ss < 10) {ss = "0"+ss;}
				// This formats your string to HH:MM:SS
				var t = dd + " jrs, " + hh+":"+mm+":"+ss;
				document.getElementById("uptime").innerHTML=t;
				setTimeout("updateData();",10000);
			}
			else{

			}
		}
	}
	syncSiteCall.open("GET","/sysinfo/index.php?disp=xml&r="+Math.random(),true);
	syncSiteCall.send();
}	 



$(function() {
	updateData();
});	
</script>
<?php $this->output["content"]=ob_get_clean();	
				
				
				
			
				

