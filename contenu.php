<?php
	session_start();
	
	include 'fonctions.php';
	
	$connexion = connexion();
	
	if($connexion){
		$articles = "";
		if(!(isset($_REQUEST["motcle"]))){
			$rq_contenu = '	SELECT 		articles.id_article AS id_article,
										articles.titre AS titre,
										articles.texte AS texte,
										usagers.id_usager AS usager,
										usagers.nom AS nom,
										usagers.email AS email
							FROM 		articles
							INNER JOIN 	usagers 		ON articles.id_usager = usagers.id_usager
							ORDER BY articles.id_article DESC';	
		}
		else{
			$rq_contenu = '	SELECT 		articles.id_article AS id_article,
										articles.titre AS titre,
										articles.texte AS texte,
										usagers.id_usager AS usager,
										usagers.nom AS nom,
										usagers.email AS email
							FROM 		articles
							INNER JOIN 	usagers 		ON articles.id_usager = usagers.id_usager
							INNER JOIN 	motcles_article	ON articles.id_article = motcles_article.id_article
							INNER JOIN 	motcles			ON motcles_article.id_motcle = motcles.id_mot
							WHERE 		motcles.mot = "'.mysqli_real_escape_string($connexion, $_REQUEST["motcle"]).'"';	
		}
		$resultats 	= mysqli_query($connexion, $rq_contenu);
		$count 		= mysqli_num_rows($resultats);
		if($resultats && $count > 0) {			
			while($rangee = mysqli_fetch_assoc($resultats))	{
			
				$articles .= '	<h2>'.$rangee["titre"].'<span class="hidden">'.$rangee["id_article"].'</span></h2>';
				if(isset($_SESSION["user"]) && ($_SESSION["user"] == $rangee["email"])){
					$articles .= '	<h5><a href="modifierArticle.php?id='.$rangee["id_article"].'&user='.$rangee["usager"].'">Modifier article</a></h5>';
				}													
				$articles .= '	<p>'.$rangee["texte"].'</p>
								<h4>Auteur: '.$rangee["nom"].'</h4>';
				
				$rq_motsCles = '	SELECT 		articles.id_article AS id_article,
												motcles_article.id_motcle AS id_motcle,
												motcles_article.id_article AS id_motcle_article,
												motcles.mot AS motcle
									FROM 		articles 
									INNER JOIN 	motcles_article	ON articles.id_article = motcles_article.id_article
									INNER JOIN 	motcles			ON motcles_article.id_motcle = motcles.id_mot 
									WHERE 		articles.id_article = '.$rangee["id_article"].'';
				$resultatsMotsCles = mysqli_query($connexion, $rq_motsCles);
				while($rangeeMotsCles = mysqli_fetch_assoc($resultatsMotsCles)){
					$articles .= '<span class="motcle">'.$rangeeMotsCles["motcle"].' </span>';
				}
				$articles .= '</br></br><hr>';
			}
		}
		else {
			$articles = '<h4>Aucun article trouvé pour ce mot clé</h4>';
		}
	}
	else {
		$articles = '<h4>Aucune information trouv&eacute;e.</h4>';
	}
	
	if(!isset($_SESSION["grainSel"])){
		$_SESSION["grainSel"] = rand(1, 1000);
	}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta content="text/html" charset="ISO-8859-1" http-equiv="Content-Type" />
<html>
<head>
<title>Blog multi-usagers avec mots-clés</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="scripts/encryption.js"></script> 
	<script type="text/javascript">
		function encrypteForm(){
			var grainSel = document.loginForm.grainSel.value;
			var password = MD5(grainSel + MD5(document.loginForm.passClear.value));
			
			document.formEncrypte.user.value = document.loginForm.userClear.value;
			document.formEncrypte.pass.value = password;
			
			document.formEncrypte.submit();			
		}	
	</script>	
</head>
<body>
	<div id="container">
		<div id="head">
			<h1>Blog multi-usagers avec mots-clés : Articles</h1>
			<?php
			if (isset($_GET["messageErreur"])){
				echo $_GET["messageErreur"];
			}
			?>
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
					<h4><a href="pagemotscles.php">Liste des mots-cl&eacute;s</a></h4>
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
			<p>Par : Luis Rosas & Cinq-Mars</p>
		</div>
	</div>
</body>
</html>