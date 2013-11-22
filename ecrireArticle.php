<?php
	session_start();

	if(isset($_SESSION["user"])){
		//$articles = $_GET("user") . ' - ' . $_GET("id");
		
		include 'fonctions.php';
		$connexion = connexion();
		
		if($connexion){
			$rq_contenu = '	SELECT 		usagers.id_usager AS usager,
										usagers.nom AS nom,
										usagers.email AS email
							FROM		usagers
							WHERE 		usagers.email ="'.$_SESSION["user"].'"';
							
			$resultats = mysqli_query($connexion, $rq_contenu);
			$articles = "";
			if($resultats) {			
				$rangee = mysqli_fetch_assoc($resultats);
								
				$articles .= '	<form name="modifieArticle" method="POST" action="operations.php">
								Titre : <input type="text" name="titre" size="80" value=""></input><br>
								Auteur : <span>'.$rangee["nom"].'</span></br></br>
								<textarea name="texte" rows="40" cols="70"></textarea><br>';
					
				$articles .= '	Mots cles : <input type="text" name="listeMotsCles"  size="25" ></input>';
					
				
				$articles .= '	<input type="hidden" name="action" value="ecrire"/>
								<input type="hidden" name="id" value="'.$rangee["usager"].'"/>					
								<input type="submit" value="Enregistrer"/>';
			}
			else {
				echo "ERROR REQ";
			}
		}
		else {
			$articles = '<h4>Aucune information trouv&eacute;e.</h4>';
		}
	}
	else {
		header("location:contenu.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta content="text/html" charset="ISO-8859-1" http-equiv="Content-Type" />
<html>
<head>
<title>Blog multi-usagers avec mots-clés par Luis Rosas & Cinq-Mars</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
	<div id="container">
		<div id="head">
			<h1>Tp2 - Blog : &Eacute;crire un article </h1>
		</div>
		<div id="colGauche">
			<div>
				<form name="loginForm" action="operations.php?action=logout&logout=true" method="POST">
					<div class="<?php if(isset($_SESSION["user"])){ echo 'loginHidden'; } else {echo '';} ?>">
					<label>Courriel:</label>
					<input type="text" name="userClear"/>
					<label>Mot de passe:</label>
					<input type="password" name="passClear"/>	
					<input type="hidden" name="grainSel" value="<?php echo $_SESSION["grainSel"]; ?>"/>
					<input type="button" value="Login" onclick="encrypteForm();"/>
					<h4><a href="inscription.php">S'inscrire</a></h4>
					</div>
					<br/>
					<h4><a href="pagemotscles.php">Liste des mots-cl&eacute;s</a></h4>
					<?php
						if (isset($_SESSION["user"])){
							echo '	<h4><a href="ecrireArticle.php">&Eacute;crire un article</a></h4>';
						}
					?>
					<h4><a href="contenu.php">Retour &agrave; la page d'accueil</a></h4>
					<?php
						if (isset($_SESSION["user"])){
							echo '	<input type="submit" value="Se d&eacute;connecter" />';
						}
					?>
				</form>
				<form name="formEncrypte" method="GET" action="Authentification.php">
					<input type="hidden" name="user" value=""/>
					<input type="hidden" name="pass" value=""/>
				</form>
				
			</div>
		</div>
		<div id="colDroite">
			<?php
				echo $articles;
			?>
			<p>Par : Luis Rosas & Cinq-Mars</p>
		</div>
		
	</div>
</body>
</html>