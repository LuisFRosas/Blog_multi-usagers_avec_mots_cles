<?php
	session_start();
	
	if(isset($_REQUEST["user"]) && isset($_REQUEST["pass"]))
	{
		include 'fonctions.php';
	
		$connexion = connexion();
		
		if($connexion) {
	
			//envoi de la requête
			$requete = "SELECT * from usagers where email = '" . mysqli_real_escape_string($connexion, $_REQUEST["user"]). "'";
			$resultats = mysqli_query($connexion, $requete);
			
			if($resultats)
			{
				$rangee = mysqli_fetch_assoc($resultats);
				if($rangee)
				{
					if(!(isset($_REQUEST["inscription"]))){
						
						//logique de validation du mot de passe
						$motPasseMD5 = $rangee["pass"];
						$motPasseGrainSel = md5($_SESSION["grainSel"] . $motPasseMD5);
						
						if($motPasseGrainSel == $_REQUEST["pass"])
						{
							$_SESSION["user"] = $_REQUEST["user"];
							//$_SESSION["adresse"] = $_SERVER["REMOTE_ADDR"];
							header("Location: contenu.php");
						}
						else
						{
							header("Location: contenu.php?messageErreur=Mauvaise combinaison username-password.ENTREE");
						}
					}
					else{
						if($_REQUEST["inscription"] == 'true'){
							$_SESSION["user"] = $_REQUEST["user"];
							header("Location: contenu.php");
						}
					}
				}
				else
				{
					header("Location: contenu.php?messageErreur=Mauvaise combinaison username-password.REQUETE");	
				}
			}	
		}
		else
			header("Location: contenu.php?messageErreur=Mauvaise combinaison username-password.CONNEXION");
	}
	else
		header("Location: contenu.php?messageErreur=REQUEST");
?>
