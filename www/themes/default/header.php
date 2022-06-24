<?php
	if(!defined('FWSECURITY')) die("");
	function dropMenu($ar){
		foreach($ar as $n=>$v){	
			if(!is_array($v)) {
				echo "<li><a href=\"".$v."\" title=\"".$n."\">".$n."</a></li>";
			}
		}
	}
	$page=$this->getURI();
	$page=$page[0];
?><!doctype html>
<html lang="fr" class="no-js">
<head>
	<base href="http://<?php echo $_SERVER['SERVER_NAME']; ?>:<?php echo $_SERVER['SERVER_PORT']; ?>/">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<script src="<?php echo $this->Config['uri_root']; ?>themes/default/js/jquery-2.1.4.js"></script>
	<script src="<?php echo $this->Config['uri_root']; ?>themes/default/js/jquery.menu-aim.js"></script>    
	<link rel="stylesheet" href="<?php echo $this->Config['uri_root']; ?>themes/default/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $this->Config['uri_root']; ?>themes/default/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $this->Config['uri_root']; ?>themes/default/js/modernizr.js"></script> <!-- Modernizr -->
	<script src="/js/xml2json.js"></script>
	<link rel="stylesheet" href="./css/table.css"> <!-- CSS reset -->
	<meta name="description" content="<?php echo $this->meta["description"]; ?>">
	<meta name="keywords" content="<?php echo $this->meta["keywords"]; ?>">  	
	<base href="<?php echo $this->Config['site_url']; ?>" />
	<title><?php echo $this->Config['site_name']; ?> - <?php echo $this->meta["title"]; ?></title>
</head>
<body>
	<header class="cd-main-header">
		<a href="#0" class="cd-logo"><img src="<?php echo $this->Config['uri_root']; ?>themes/default/img/cd-logo.png" alt="Logo"></a>
		
		<a href="#0" class="cd-nav-trigger">Menu<span></span></a>

		<nav class="cd-nav">
			<ul class="cd-top-nav">

			<li class="has-children account">
					<a href="#0">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAPDSURBVHhe7ZlPSBRhGMYtIgg6dAiUEjrkpT9egqgORQXaYkitlpptpGZhFBgePFTURaIslMAwKrwk/RHpUBQ6K21kZKeIsIzanc0OgXQI6mA7Y/v1vrvfIu682drMjjsz7wM/XJx3vu99Hueb+WbNY7FYLBaLxWKxWCxWSgOfJosUVXuE4Gf5a9a/9PJLfElQ1duUiDYJPwUiP7fhMVnGoqSE9Qq44sZTwaWDx7BGlrNSUqK/1kA4ChUaBdbiOfJ07yo0KpYqEb09qGoxKqjZ0WJ4Lo4hh/OOhBALlPDUgWBE+0qHMwdgDBwLx5TDu1tKOFYcjOjPyDDMAGPi2HIa9ykUFctgyV2F+5dOBmABODbOgXPJaZ2v5HKN1cM9a4IynR20CZzT8ct66HNsAyytEdqkDcDc2INsxzkKfRDLYSldhxv8b9KYnUAP2Av2JNvLXZ0XYqES1ZrgXvSNNDOPYE/YG/Yo280tQXOb4S/9mmo+l8AesVfZ9vxrKBLPh8Z64H01nt5sriJ77cHepQ37FQqJRbAsmqGZ7+kNOgXsHT2gF2nLHgWj+nZF1d9STTmRhBfwJO1lT8p4fAXsse44ablmStITeAOP0q516hsVi4dUrRUu95/U5G4CPaJX9Cztm9OgqpfChnSMmszVgGf0LmOYu+B9sgA2oQ/Iwb0EZIBZyFgylxKeqiUH9CCYhYwlc3GA03CAJrE8wMdjP8SZ7vvi5IVuV4Be0BPlFbE8QJywpLbRVaAnyitieYDNF2+QTTgZ9ER5RTwfYNmpalF+bo/wHTtEHkc4QILSujpxcKBYNEbyk3wsEJXXdpC1HCBB9b1N0+GlCOeL8tN+Qy0HSFD/ZpUxQKCqd4uhlgMk4ABNUn2Xl7ApEg+RJzMfIhVdO8laDnAWypprktuYo7yNyRq2BujGV7mz3X2kV8TyAPHFGyekXsydCHqx9csEr8EBmoQDNInlAfa+UEXliVbyZuxE0At6orwilgfYcqWHbMTJoCfKK2J5gLwPzEBuCdB3PCD23domavo2Cn9niSgNNJB1HCDB7pYq0fCucMaXCYGna8kQOUCCQHDdjPBS+DtKDLUcIMGR9yvJAHE5p9dygASHR1aTAe6/udVQywES+C/vMoTXMFoofE0BQy0H+Bf2tvvgSixKPEwCg+sT/+Kk6jhAk9gb4CUXBgieKK+I5QF29D8nm3Aynf3DpFfE8gCR28Nh0fXwlStAL5THFFkJ0EtwgCbhAE3CAZqEAzQJB2iS/wqQxWKxWCwWi8VisbKkvLw/UbQdvnh8bj8AAAAASUVORK5CYII=">
						Système
					</a>
					<ul>
				<li><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>:9000/" target="_blank">piWebCTRL</a></li>
				<li><a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>:10000/" target="_blank">Webmin</a></li>
</ul>				
</li>					
			</ul>
		</nav>
	</header> <!-- .cd-main-header -->

	<main class="cd-main-content">
		<nav class="cd-side-nav">
			<ul>
				<li class="cd-label">Main</li>
				<li class="home<?php if($page=="home") {echo ' active';} ?>">
					<a href="/home">Accueil</a>
				</li>
				<li class="explorer<?php if($page=="explorer") {echo ' active';} ?>">
					<a href="/explorer">Explorateur</a>
				</li>
				<li class="system<?php if($page=="system") {echo ' active';} ?>">
					<a href="/system">Système</a>
				</li>
			</ul>
			<ul>
				<li class="cd-label">Powered by</li>
				<li class="action-btn"><a href="https://levelkro.net" target="_blank">Kore Framework</a></li>
			</ul>
		</nav>

		<div class="content-wrapper">
	