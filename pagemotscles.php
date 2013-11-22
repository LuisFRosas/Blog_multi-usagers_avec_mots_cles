<?php
	session_start();
	
	include 'fonctions.php';
	
	$connexion = connexion();
	
	$motsCles = "";
	$count = 36;
	
	if($connexion){
		$rq_motcles = '	SELECT motcles.mot AS motcle, 
						COUNT( * ) AS occurence
						FROM motcles_article
						INNER JOIN motcles ON motcles_article.id_motcle = motcles.id_mot
						GROUP BY motcle
						ORDER BY occurence DESC';
		$resultats = mysqli_query($connexion, $rq_motcles);
		while($rangeeMotsCles = mysqli_fetch_assoc($resultats)){
			$count-=3;
			if($count < 10){
				$count = 10;
			}
			$motsCles .= '<a style="font-size:'.$count.'px" href="contenu.php?motcle='.$rangeeMotsCles["motcle"].'"> - '.$rangeeMotsCles["motcle"].'</a><br/><br/>';
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta content="text/html" charset="ISO-8859-1" http-equiv="Content-Type" />
<html>
<head>
<title>Blog multi-usagers avec mots-clés par Luis Rosas & Cinq-Mars</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="scripts/encryption.js"></script> 
	<script type="text/javascript">
	</script>	
</head>
<body>
	<div id="container">
		<div id="head">
			<h1>Tp2 - Blog : Liste de mots clés</h1>
			<!--<?php
			if (isset($_GET["messageErreur"])){
				echo $_GET["messageErreur"];
			}
			?>
			-->
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
				echo $motsCles;
			?>
			<p>Par : Luis Rosas & Cinq-Mars</p>
		</div>
	</div>
</body>
</html>