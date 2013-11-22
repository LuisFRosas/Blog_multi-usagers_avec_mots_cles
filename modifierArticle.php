<?php
	session_start();

	if(isset($_SESSION["user"])){
		//$articles = $_GET("user") . ' - ' . $_GET("id");
		
		include 'fonctions.php';
		$connexion = connexion();
		
		if($connexion){
			$rq_contenu = '	SELECT 		articles.id_article AS id_article,
										articles.titre AS titre,
										articles.texte AS texte,
										usagers.id_usager AS usager,
										usagers.nom AS nom,
										usagers.email AS email
							FROM 		articles
							INNER JOIN 	usagers 		ON articles.id_usager = usagers.id_usager 
							WHERE 		articles.id_article='.$_GET["id"].' 
							AND			usagers.id_usager='.$_GET["user"].'';
							
			$resultats = mysqli_query($connexion, $rq_contenu);
			$articles = "";
			$titre = "";
			$autor = "";
			if($resultats) {			
				while($rangee = mysqli_fetch_assoc($resultats))
				{				
					if($titre != $rangee["titre"] && $autor != $rangee["usager"]){
						$articles .= '	<form name="modifieArticle" method="POST" action="operations.php">
										Titre : <input type="text" name="titre" size="80" value="'.$rangee["titre"].'"></input></br>
										Auteur : <span>'.$rangee["nom"].'</span></br></br>
										<textarea name="texte" rows="40" cols="70">'.$rangee["texte"].'</textarea></br>';
						$titre = $rangee["titre"];
						$autor = $rangee["usager"];
						$id_article = $_GET["id"];
					}
					$rq_motsCles = '	SELECT 		motcles.mot AS motcle
										FROM 		articles
										INNER JOIN 	motcles_article	ON articles.id_article = motcles_article.id_article
										INNER JOIN 	motcles			ON motcles_article.id_motcle = motcles.id_mot 
										WHERE 		motcles_article.id_article = '.$rangee["id_article"].'';
										
					$resultatsMotsCles = mysqli_query($connexion, $rq_motsCles);
					$listeMotsCles = "";
					while($rangeeMotsCles = mysqli_fetch_assoc($resultatsMotsCles)){
						$listeMotsCles .= $rangeeMotsCles["motcle"].'&';
					}
					$articles .= 'Mots cles<input type="text" name="listeMotsCles"  size="25" value="'.$listeMotsCles.'" ></input>';
					
				}
				$articles .= '			<input type="hidden" name="action" value="modify"/>
										<input type="hidden" name="id" value="'.$_GET["id"].'"/>				
										<input type="submit" value="Enregistrer"/>';
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
			<h1>Blog multi-usagers avec mots-clés :  Modifier l'article</h1>
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
					<h4><a href="contenu.php">Retour &agrave; la page d'accueil</a></h4>
					<?php
						if (isset($_SESSION["user"])){
							echo '	<h4><a href="ecrireArticle.php">&Eacute;crire un article</a></h4>
									<input type="submit" value="Se d&eacute;connecter" />';
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
		</div>
	</div>
</body>
</html>