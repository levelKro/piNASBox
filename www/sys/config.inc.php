<?php		
	if(!defined('FWSECURITY')) die("");	 
	/********************************
	
	Kore FrameWork Lite
	version 1.0 Beta									
	
	auteur Mathieu Légaré (levelKro) <levelkro@yahoo.ca>
	creation date 08-2015 ~ 09-2015
	
	********************************/
	
	$this->Config=array(
	"path_root"         =>"/home/pi/pinasbox/www/", // Chemin de base du site web
	"path_files"        =>"/home/pi/pinasbox/www/get/", // Chemin vers les fichiers pour téléchargement
	"theme"             =>"default", // Theme a utiliser
	"uri_root"          =>"/", // Chemin URL après le domaine
	"uri_get"			=>"/get/", // Download file, path to real tmp folder
	"sql"				=>false, // Use SQL
	"sql_host"          =>"localhost", // Host du serveur SQL
	"sql_type"          =>"mysql", // Type de base SQL
	"sql_user"          =>"", // Nom d'utilisateur SQL
	"sql_table"         =>"", // Table à utiliser sur le SQL
	"sql_pass"          =>"", // Mot de passe du SQL
	"smtp_host"         =>"localhost", // Host du serveur SMTP de courriel
	"smtp_port"         =>"25", // Port du serveur SMTP
	"smtp_user"         =>"", // Username SMTP
	"smtp_pass"         =>"", // Password SMTP
	"down_wait"         =>5, // Délais avant début du téléchargement
	"down_timeout"		=>120, // Délais pour expiration d'un transfert
	"login"				=>false, // Active le système de login
	"login_helper"		=>"", // Le Helper à charger pour l'identification
	"site_name"			=>"NASBOX Web Interface", // Nom du site
	"site_url"			=>"http://".$_SERVER['SERVER_NAME'], // URL pour le site
	);
