<?php
	session_start();
	
	if(isset($_REQUEST["action"])){
		
		include 'fonctions.php';
		$connexion = connexion();
		
		if($connexion) {
		
			switch($_REQUEST["action"]){
				case "ajout":
					if(valideContenu()){
						$requete = "INSERT INTO articles(nom, adresse, description) VALUES ('" . mysqli_real_escape_string($connexion, $_REQUEST["nom"]) . "', '" . mysqli_real_escape_string($connexion, $_REQUEST["adresse"]) . "', '" . mysqli_real_escape_string($connexion, $_REQUEST["description"]) . "')"; 
						$resultats = mysqli_query($connexion, $requete);
						header("Location:contenu.php");
					}
					break;
				case "modify":
					if($_REQUEST["id"]){
						if(valideContenu()){
							$requete = 'UPDATE articles SET articles.titre="' . mysqli_real_escape_string($connexion, $_REQUEST["titre"]).'", articles.texte="' . mysqli_real_escape_string($connexion, $_REQUEST["texte"]).'" WHERE id_article=' .mysqli_real_escape_string($connexion, $_REQUEST["id"]); 
							$resultats = mysqli_query($connexion, $requete);
							header("Location:contenu.php");
						}
						if(isset($_REQUEST["listeMotsCles"])){
							$listeMotsCles = explode("&",$_REQUEST["listeMotsCles"]);
							
							foreach($listeMotsCles AS $motcle){
								$requeteIdMotCle = 'SELECT * 
													FROM motcles_article
													INNER JOIN motcles 			ON motcles_article.id_motcle = motcles.id_mot
													WHERE motcles.mot="'.$motcle.'"
													AND motcles_article.id_article = '.$_REQUEST["id"];
								$resultatsIdMotCle = mysqli_query($connexion, $requeteIdMotCle);
								$rangeeIdMotCle = mysqli_fetch_assoc($resultatsIdMotCle);
								
								if(!$rangeeIdMotCle){
									nouveauMotCle($motcle,$_REQUEST["id"]);
								}
							}
							header("Location:contenu.php");
							
						}							
						else{
							header("Location: modifierArticle.php?messageErreur=Erreur mots cles!");
						}
					}
					else{
						echo "<span>Information n'ai pas trouv&eacute;</span>";
					}
					break;
				case "ecrire":
					if($_REQUEST["id"]){
						if(valideContenu()){
							
							$requete = 'INSERT INTO articles (titre,texte,id_usager)
										VALUES 	("'.$_REQUEST["titre"].'","'
													.$_REQUEST["texte"].'","'
													.$_REQUEST["id"].'")';
							
							$resultats = mysqli_query($connexion, $requete);
							
							if(isset($_REQUEST["listeMotsCles"])){
								$listeMotsCles = explode("&",$_REQUEST["listeMotsCles"]);
																
								$requete1 = 'SELECT articles.id_article AS id_article
											FROM articles 
											WHERE articles.titre="'.$_REQUEST["titre"].'"
											AND articles.texte="'.$_REQUEST["texte"].'"
											AND articles.id_usager="'.$_REQUEST["id"].'"';
								$resultats1 = mysqli_query($connexion, $requete1);
								$rangee = mysqli_fetch_assoc($resultats1);
								$id_articleCherche = $rangee["id_article"];
								
								foreach($listeMotsCles AS $motcle){
									nouveauMotCle($motcle,$id_articleCherche);
								}
								header("Location:contenu.php");
								
							}							
							else{
								header("Location: ecrireArticle.php?messageErreur=Liste mots cles n'a pas &eacute;t&eacute; trouv&eacute;e");
							}
						}
						else{
							header("Location: ecrireArticle.php?messageErreur=Problème du contenu");
						}
					}
					else{
						header("Location: ecrireArticle.php?messageErreur=Problème ID");
					}
					break;
				case "supprimer":
					if(isset($_REQUEST["id"])){
						$requete = "DELETE FROM articles WHERE  id=" . mysqli_real_escape_string($connexion, $_REQUEST["id"]); 
						$resultats = mysqli_query($connexion, $requete);
						header("Location:contenu.php");	
					}
					break;
				case "logout":
					if(isset($_REQUEST["logout"])){
					//d&eacute;truire la session de l'usager et d&eacute;truire la variable session user
						unset($_SESSION["user"]);
						session_destroy();
						header("Location: contenu.php");
					}	
					break;
				case "nouvel":
					if(isset($_REQUEST["user"]) && isset($_REQUEST["courriel"]) && isset($_REQUEST["pass"])){
						$requete = "SELECT usagers.email AS email from usagers WHERE usagers.email = '" . mysqli_real_escape_string($connexion, $_REQUEST["courriel"]). "'";
						$resultats = mysqli_query($connexion, $requete);
						
						$rangee = mysqli_fetch_assoc($resultats);
						$rest = $rangee["email"];
						
						if($rest != $_REQUEST["courriel"]){
						
							$req_inscription = 'INSERT INTO usagers (nom,email,pass) VALUES ("'
									.mysqli_real_escape_string($connexion, $_REQUEST["user"]).'","'
									.mysqli_real_escape_string($connexion, $_REQUEST["courriel"]).'","'
									.mysqli_real_escape_string($connexion, $_REQUEST["pass"]).'")';
							
							$resultatsInscription = mysqli_query($connexion, $req_inscription);
							if($resultatsInscription){
								header('Location:Authentification.php?user='.$_REQUEST["courriel"].'&pass='.$_REQUEST["pass"].'&inscription=true');
							}
							else {
								header("Location: inscription.php?messageErreur=Problème d'inscription");
							}							
						}
						else {
							header("Location: inscription.php?messageErreur=Ce courriel est d&eacute;j&agrave; utilis&eacute;, ERREUR");
						}
					}
					else {
						header("Location: inscription.php?messageErreur=Un champs n'est pas rempli");	
					}
					break;
				default:
					header("Location:contenu.php?messageErreur=DEFAULT");
			}
		}
	}
	else {
		header("Location:contenu.php?messageErreur=ACTION");
	}

	function valideContenu(){
		if(isset($_REQUEST["id"]) && isset($_REQUEST["titre"]) && isset($_REQUEST["texte"]))
		{
			if(trim($_REQUEST["id"]) !== "" && trim($_REQUEST["titre"]) !== "" && trim($_REQUEST["texte"]) !== "")
				return true;			
		}
			
		return false;
	}
	function nouveauMotCle($motcle,$id_articleCherche){
		$connexion = connexion();
		$listeMotsCles = "";
		$requete2 = 'SELECT motcles.mot AS mot FROM motcles WHERE mot="'.mysqli_real_escape_string($connexion, $motcle).'"';
		$resultats2 = mysqli_query($connexion, $requete2);
		$motTrouve = mysqli_fetch_assoc($resultats2);
		if($motTrouve["mot"] != $motcle){
			$requete3 = 'INSERT INTO motcles (mot) 
						VALUES ("'.$motcle.'")';
			$resultats3 = mysqli_query($connexion, $requete3);
		}
		
	
		$requete4 = 'SELECT motcles.id_mot AS id_motcle
					FROM motcles
					WHERE motcles.mot="'.$motcle.'"';
		
		$resultats4 = mysqli_query($connexion, $requete4);
		$idmotcleResultat = mysqli_fetch_assoc($resultats4);
				
		$id_motcleString = $idmotcleResultat["id_motcle"];
		
		$requete6 = 'INSERT INTO motcles_article (id_motcle,id_article) 
					VALUES("'.$id_motcleString.'","'.$id_articleCherche.'")';
		$resultats6 = mysqli_query($connexion, $requete6);
		
	}
	
?>
