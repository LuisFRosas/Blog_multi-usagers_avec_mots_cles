<?php
	function connexion(){
		$connexion = mysqli_connect("localhost", "???", "????", "?????");
		if (!$connexion) {
			header("location: contenu.php?messageErreur=D&eacute;sol&eacute; erreur de connexion");
		}
		//mysqli_query("SET NAMES 'utf8'");
		return $connexion;
	}
?>