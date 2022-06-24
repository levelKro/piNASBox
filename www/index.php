<?php  				
	/* debug */
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);
	ini_set("display_errors", 1);
	
	session_start();
	if($_GET['page'] && file_exists("./content/".$_GET['page'].".php")) $page=$_GET['page'];
	else $page="sysinfo";
			

	/* Kore Framework Index Loader */
	require_once("./sys/loader.php"); // Charge le framework
	$site=new FWKore();	// Enregistre le framework et l'initialise
	$site->loadLogin(); // Charge le système d'identification
	$site->loadUser(); // Charge le système de profil utilisateur
	$site->loadPage(); // Lance le script de la page
	$site->showPage(); // Affiche le rendu, utile de retiré pour aucun rendu du code
