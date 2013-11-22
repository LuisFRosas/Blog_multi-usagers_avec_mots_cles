<?php
	session_start();
	
	include 'fonctions.php';
	
	$connexion = connexion();
	
	if (isset($_SESSION["user"])){
		header('location: contenu.php');
	}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta content="text/html" charset="ISO-8859-1" http-equiv="Content-Type" />
<html>
<head>
<title>Inscription : Blog multi-usagers avec mots-clés par Luis Rosas & Cinq-Mars</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="scripts/encryption.js"></script> 
	<script type="text/javascript">
		function encrypteForm(){
			var password = MD5(document.inscriptionForm.passwordUsager.value);
			
			document.formEncrypte.user.value = document.inscriptionForm.nomUsager.value;
			document.formEncrypte.courriel.value = document.inscriptionForm.courrielUsager.value;
			document.formEncrypte.pass.value = password;
			
			document.formEncrypte.submit();			
		}	
	</script>
</head>
<body>
	<div id="container">
		<div id="head">
			<h1>Blog multi-usagers avec mots-clés</h1>
		</div>
		<div id="colGauche">
			<div>				
				<h4><a href="pagemotscles.php">Liste des mots-cl&eacute;s</a></h4>
				<h4><a href="contenu.php">Retour &agrave; la page d'accueil</a></h4>
			</div>
		</div>
		<div id="colDroite">
			
			<div style="color:red"><?php if (isset($_GET["messageErreur"]))	echo $_GET["messageErreur"]; ?></div>
			
			<form name="inscriptionForm" method="POST">
				<label>Nom:</label>
				<input type="text" name="nomUsager"/></br>
				<label>Courriel:</label>
				<input type="text" name="courrielUsager"/></br>
				<label>Mot de passe:</label>
				<input type="password" name="passwordUsager"/></br>
				<input type="button" value="S'inscrire" onclick="encrypteForm();"/>
			</form>
			<form name="formEncrypte" method="GET" action="operations.php">
				<input type="hidden" name="user" value=""/>
				<input type="hidden" name="courriel" value=""/>
				<input type="hidden" name="pass" value=""/>
				<input type="hidden" name="action" value="nouvel"/>
			</form>
		</div>
	</div>
</body>
</html>