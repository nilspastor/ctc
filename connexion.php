<?php

session_start();

$titre = "Choisis Ton Camp - Connexion";

include("includes/identifiants.php");
include("includes/debut.php");

?>

		<div id="conteneur">
			<div id="cadre_log">
				<div id="form_log">

<?php 

if ($id != 0) erreur_co(); 

if (!isset($_POST['pseudo'])) {
	
	echo '<form method="post" action="connexion.php"><fieldset class="fieldset_log">
		  <label for="pseudo"><span class="logpw">Login : </span></label><input name="pseudo" type="text" id="pseudo" /><br />
		  <label for="password"><span class="logpw">Mdp : </span></label><input name="password" type="password" id="password" /><br /><br />
		  <input type="submit" value="" class="bouton_valider" />
		  </fieldset>
		  <p class="mdp_oublie">Mot de passe oublié ?</p>
		  <a href="registre.php" class="pas_inscrit">Pas encore inscrit</a>
		  </form>';
}

else {
	
	$message='';
	
	if (empty($_POST['pseudo']) || empty($_POST['password'])) {
		$message = '<p>une erreur s\'est produite pendant votre identification.
		Vous devez remplir tous les champs</p>
		<p>Cliquez <a href="./connexion.php">ici</a> pour revenir</p>';
	}
	
	else {
		$query=$db->prepare('SELECT mdp_joueur, id_joueur, rang_joueur, pseudo_joueur FROM ctc_joueurs WHERE pseudo_joueur = :pseudo');
		$query->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
		$query->execute();
		$data=$query->fetch();
	
		if ($data['mdp_joueur'] == sha1($_POST['password'])) {
			$_SESSION['pseudo'] = $data['pseudo_joueur'];
			$_SESSION['level'] = $data['rang_joueur'];
			$_SESSION['id'] = $data['id_joueur'];
			header('Location: choix.php');
			exit;
		}
	
		else {
			$message = '<p>Erreur : identifiants incorrects<br />
			Cliquez <a href="./connexion.php">ici</a> pour revenir à la page précédente</p>';
		}
	
	$query->closeCursor();

	}

	echo $message;
	
}

?>			
					
				</div>
				<div id="ctcquoi"><h1>CTC c’est quoi ?</h1><br />
				<p>Crée dans le but de lutter contre l’inertie ambiante dans nos cercles amicaux, de faciliter l’organisation des matchs et d’arbitrer la bataille perpétuelle entre nos différents idéaux, choisistoncamps.com sera dorénavant votre plateforme de référence en matière de football.<br />
				En vous inscrivant, il vous sera demandé de prendre franchement parti dans un camp, d’en porter les couleurs et d’en défendre les valeurs et l’honneur lors nos matchs de foot.<br />
				Frileux et prudents s’abstenir, ici pas de place pour la demi-mesure.</p></div>
			</div>
		</div>
	</body>
</html>