<?php

session_start();

$titre = "Choisis Ton Camp - Inscription";

include("includes/identifiants.php");
include("includes/debut.php");

?>

		<div id="conteneur_reg">
			<div id="cadre_reg">
				<div id="form_reg">

<?php

if ($id!=0) erreur_co();

if (empty($_POST['pseudo'])) {
	
	echo '<form method="post" action="registre.php">
		<fieldset class="fieldset_reg">
			<label for="prenom">Prénom : </label><input name="prenom" type="text" id="prenom" /><br />
			<label for="nom">Nom : </label><input name="nom" type="text" id="nom" /><br />
			<label for="pseudo">Login : </label><input name="pseudo" type="text" id="pseudo" /><br />
			<label for="age">Âge : </label><input name="age" type="number" min="18" max="85" id="age" /><br />
			<label for="signe">Signe : </label><select name="signe" id="signe">
				<option value="Bélier">Bélier</option>
				<option value="Taureau">Taureau</option>
				<option value="Gémeaux">Gémeaux</option>
				<option value="Cancer">Cancer</option>
				<option value="Lion">Lion</option>
				<option value="Vierge">Vierge</option>
				<option value="Balance">Balance</option>
				<option value="Scorpion">Scorpion</option>
				<option value="Sagittaire">Sagittaire</option>
				<option value="Capricorne">Capricorne</option>
				<option value="Verseau">Verseau</option>
				<option value="Poissons">Poissons</option>
			</select><br />
			<label for="sexe">Sexe : </label><select name="sexe" id="sexe">
				<option value="Femme">Femme</option>
				<option value="Homme">Homme</option>							
			</select><br />
			<label for="niveau">Niveau : </label><select name="niveau" id="niveau">
				<option value="Débutant">Débutant</option>
				<option value="Amateur">Amateur</option>
				<option value="Intermédiaire">Intermédiaire</option>
				<option value="Pro">Pro</option>
				<option value="Légende">Légende</option>
			</select><br />
			<label for="mail">E-mail : </label><input name="email" type="email" id="email" /><br />
			<label for="password">Mdp : </label><input name="password" type="password" id="password" /><br />
			<label for="confirm">Mdp (bis) : </label><input name="confirm" type="password" id="confirm" /><br />
			</fieldset>';
	
}

else {

	$pseudo_erreur = NULL;
	$mdp_erreur = NULL;
	$mdp_erreur2 = NULL;
	$email_erreur1 = NULL;
	$email_erreur2 = NULL;
	
	$i = 0;
	$temps = time();
	$prenom = $_POST['prenom'];
	$nom = $_POST['nom'];
	$pseudo = $_POST['pseudo'];
	$age = $_POST['age'];
	$signe = $_POST['signe'];
	$sexe = $_POST['sexe'];
	$niveau = $_POST['niveau'];
	$email = $_POST['email'];
	$pass = sha1($_POST['password']);
	$confirm = sha1($_POST['confirm']);
	
	$query=$db->prepare('SELECT COUNT(*) AS nbr FROM ctc_joueurs WHERE pseudo_joueur =:pseudo');
	$query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
	$query->execute();
	$pseudo_dispo=($query->fetchColumn()==0)?1:0;
	$query->closeCursor();
	
	if(!$pseudo_dispo) {
		$pseudo_erreur = "Ce pseudo n\'est pas disponible";
		$i++;
	}

	if (strlen($_POST['password']) < 3 || strlen($_POST['password']) > 18) {
        $mdp_erreur2 = "Votre mot de passe doit contenir entre 3 et 18 caractères";
        $i++;
    }
	
	if ($pass != $confirm || empty($confirm) || empty($pass)) {
		$mdp_erreur = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
		$i++;
	}
	
	$query=$db->prepare('SELECT COUNT(*) AS nbr FROM ctc_joueurs WHERE email_joueur =:email');
	$query->bindValue(':mail',$email, PDO::PARAM_STR);
	$query->execute();
	$mail_dispo=($query->fetchColumn()==0)?1:0;
	$query->closeCursor();
	
	if(!$mail_dispo) {
		$email_erreur1 = "Cette adresse mail n\'est pas disponible";
		$i++;
	}
	
	if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email)) {
        $email_erreur2 = "Votre adresse mail n\'est pas au format valide";
        $i++;
    }
	
	if ($id==0) {
   
        $query=$db->prepare('INSERT INTO ctc_joueurs (prenom_joueur, nom_joueur, pseudo_joueur, age_joueur, signe_joueur, sexe_joueur, niveau_joueur, email_joueur, mdp_joueur, inscrit_joueur, derniere_visite_joueur, avatar_joueur) 
		VALUES (:prenom, :nom, :pseudo, :age, :signe, :sexe, :niveau, :email, :pass, :temps, :chxun, :avatar)');
		$query->bindValue(':prenom', $prenom, PDO::PARAM_STR);
		$query->bindValue(':nom', $nom, PDO::PARAM_STR);
		$query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
		$query->bindValue(':age', $age, PDO::PARAM_INT);
		$query->bindValue(':signe', $signe, PDO::PARAM_STR);
		$query->bindValue(':sexe', $sexe, PDO::PARAM_STR);
		$query->bindValue(':niveau', $niveau, PDO::PARAM_STR);
		$query->bindValue(':pass', $pass, PDO::PARAM_STR);
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->bindValue(':temps', $temps, PDO::PARAM_INT);
		$query->bindValue(':chxun', 1471748355, PDO::PARAM_INT);
		$query->bindValue(':avatar', 'ctcavatar.png', PDO::PARAM_STR);
		$query->execute();

		$_SESSION['pseudo'] = $pseudo;
		$_SESSION['id'] = $db->lastInsertId(); ;
		$_SESSION['level'] = 2;
		$query->closeCursor();
		
		$headers .= 'From: choisistoncamp' . "\r\n";
		$message_mail = 'Bienvenue sur le site de ChoisisTonCamp.com !
		
		Voici vos identifiants, vous pourrez les modifier dans la section Mon Compte du site :
		
		Login : '.$_POST['pseudo'].'
		Mot de passe : '.$_POST['password'].'';
		$titre_mail = 'ChoisisTonCamp - Rappel de vos identifiants';
		
		mail($_POST['email'], $titre_mail, $message_mail, $headers);

		header('Location: choix.php');
		exit;
			
	}
	
	else {
		
		echo '<p>Erreurs détectés</p>';
		echo '<p>'.$i.' erreur(s)</p>';
        echo '<p>'.$pseudo_erreur.'</p>';
        echo '<p>'.$mdp_erreur.'</p>';
        echo '<p>'.$mdp_erreur2.'</p>';
        echo '<p>'.$email_erreur1.'</p>';
        echo '<p>'.$email_erreur2.'</p>';

        echo'<p>Cliquez <a href="./register.php">ici</a> pour recommencer</p>';
		
	}

}

?>

</div>
				<div id="cgu_reg">
					<div id="avertissement"><p>Tout joueur s’inscrivant ici devra être prêt à tout pour soutenir son équipe, quelle qu’elle soit, dans la quête de la victoire. Tout démissionnaire sera considéré comme un lâche jusqu’au prochain match et sera traité sans ménagement.<br />
Vous serez tenus de prendre parti sur chaque dilemme proposé, exit le «c’est pas si simple» et le «ni l’un ni l’autre». CTC n’a pas pour vocation de donner une vision éclairée d’une problématique ni d’élever le débat, les choix qui sont proposés sont volontairement tranchés et antagoniques.<br />
L’équipe de CTC se réserve le droit de blacklister un utilisateur en cas d’affront grave aux régles.<br />
<br />
Carton Jaune :<br /><br />
Un joueur s’étant engagé à venir défendre les couleurs de son équipe et annulant sa participation moins d’un jour avant le match se verra sanctionné d’un carton jaune. Son nom de famille sera alors remplacé sur le site par «le lache» jusqu’à au prochain.<br />
<br />
Carton Rouge :<br /><br />
Au bout de la deuxième démission, «le lâche» deviendra «la pute» et sera banni du site jusqu’à nouvel ordre. Si toufetois il souhaite expier ses fautes pour obtenir notre pardon il devra faire preuve d’humilité et se representer à un match avec une offrande à la hauteur du préjudice causé.</p><br />
					</div>

<script>

	function FormOk() {
		if(document.getElementById('ok').checked == true) {document.getElementById('btnvalider').disabled = false }
		if(document.getElementById('ok').checked == false) {document.getElementById('btnvalider').disabled = true }
	}

</script>
					
					<div id="check_cgu">
						<input type="checkbox" name="pasok" id="pasok" onClick="FormOk()" /><label for="pasok">Je trouve tout ceci un peu démesuré...</label><br />

						<input type="checkbox" name="ok" id="ok" onClick="FormOk()" /><label for="ok">J’ai lu et j’accepte les conditions</label><br /><br />

						<div id="div_bouton_valide"><input type="submit" id="btnvalider" value="" class="bouton_valider" disabled/><br /><br /></div>	
	</form>
					</div>

				</div>
			</div>
		</div>
	</body>
</html>