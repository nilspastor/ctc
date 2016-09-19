<?php

session_start ();

$titre = "Choisis Ton Camp - Mon Compte";

include("includes/identifiants.php");
include("includes/debut.php");

if ($id == 0) {
	header('refresh:3;url=connexion.php');
	exit('<div id="conteneur"><p style="color: white;">Vous devez être connecté(e) pour accéder à cette page.<br />
		Vous allez être redirigé(e) dans quelques secondes...</p></div></body></html>');
}

$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'lire';
$joueur = isset($_GET['j'])?(int) $_GET['j']:'';

?>

		<div id="conteneur">
			<div id="cadre_compte">

<?php

switch($action)

{

    case "lire":

    	$query=$db->prepare('SELECT avatar_joueur, prenom_joueur, nom_joueur, age_joueur, signe_joueur, sexe_joueur, niveau_joueur, inscrit_joueur FROM ctc_joueurs WHERE id_joueur=:joueur');
		$query->bindValue(':joueur',$joueur, PDO::PARAM_INT);
    	$query->execute();
    	$data=$query->fetch();

	    echo '<p><img src="./img/avatars/'.$data['avatar_joueur'].'" id="tete_avatar" alt="Avatar désactivé" /></p>';
		echo '<p><br />'.stripslashes(htmlspecialchars($data['prenom_joueur'])).' '.stripslashes(htmlspecialchars($data['nom_joueur'])).'<br />';
		echo 'Âge : '.$data['age_joueur'].'<br />';
		echo 'Signe : '.$data['signe_joueur'].'<br />';
		echo 'Sexe : '.$data['sexe_joueur'].'<br />';
		echo 'Niveau : '.$data['niveau_joueur'].'<br />';
		echo 'Ce joueur est inscrit depuis le <strong>'.date('d/m/Y',$data['inscrit_joueur']).'</strong></p>';

    	$query->closeCursor();

    break;


    case "modifier":

    	if (empty($_POST['sent'])) {

	        if ($id==0) erreur_not_co();

	        $query=$db->prepare('SELECT avatar_joueur, prenom_joueur, nom_joueur, pseudo_joueur, age_joueur, signe_joueur, sexe_joueur, niveau_joueur, email_joueur, mdp_joueur FROM ctc_joueurs WHERE id_joueur=:id');
			$query->bindValue(':id',$id,PDO::PARAM_INT);
			$query->execute();
			$data=$query->fetch();

	        echo '<form method="post" action="profil.php?action=modifier" enctype="multipart/form-data">

			<fieldset><legend>Identifiants</legend>
			Pseudo : <strong>'.stripslashes(htmlspecialchars($data['pseudo_joueur'])).'</strong><br />       
			<label for="password">Nouveau mot de Passe :</label>
			<input type="password" name="password" id="password" /><br />
			<label for="confirm">Confirmer le mot de passe :</label>
			<input type="password" name="confirm" id="confirm" /><br />
			<label for="email">E-Mail :</label>
			<input type="text" name="email" id="email" value="'.stripslashes($data['email_joueur']).'" />
			</fieldset>

	        <fieldset><legend>Avatar</legend>
			<img src="./img/avatars/'.$data['avatar_joueur'].'" alt="pas d\'avatar" /><br /><br />
			<label for="avatar">Changer votre image :</label>
			<input type="file" name="avatar" id="avatar" /><br /> (Max : 100ko / 120x120px)<br /><br />
			<label><input type="checkbox" name="delete" value="Delete" /> Supprimer l\'avatar</label>
			</fieldset>

	        <p><input type="submit" value="Modifier son profil" /><input type="hidden" id="sent" name="sent" value="1" /></p>
	        </form>
	         <a href="meschoix.php"><img src="img/flecheblanche_8x9.png" /> Revenir à mes choix</a><br />
	         <a style="margin-top: 5px;" href="deconnexion.php"><img src="img/flecheblanche_8x9.png" /> Se déconnecter</a>
	        ';

	        $query->closeCursor();   

	    }   

    else {

		$mdp_erreur = NULL;
		$mdp_erreur2 = NULL;
		$email_erreur1 = NULL;
		$email_erreur2 = NULL;
	    $avatar_erreur = NULL;
	    $avatar_erreur1 = NULL;
	    $avatar_erreur2 = NULL;
	    $avatar_erreur3 = NULL;

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

		if (strlen($_POST['password']) < 3 || strlen($_POST['password']) > 18) {
	        $mdp_erreur2 = "Votre mot de passe doit contenir entre 3 et 18 caractères";
	        $i++;
	    }

    	if ($pass != $confirm || empty($confirm) || empty($pass)) {
        	$mdp_erreur = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
        	$i++;
    	}

    	$query=$db->prepare('SELECT email_joueur FROM ctc_joueurs WHERE id_joueur =:id'); 
	    $query->bindValue(':id',$id,PDO::PARAM_INT);
	    $query->execute();
	    $data=$query->fetch();

    	if (strtolower($data['email_joueur']) != strtolower($email)) {

		    $query=$db->prepare('SELECT COUNT(*) AS nbr FROM ctc_joueur WHERE email_joueur =:mail');
	        $query->bindValue(':mail',$email,PDO::PARAM_STR);
	        $query->execute();
	        $mail_free=($query->fetchColumn()==0)?1:0;
	        $query->closeCursor();

	        if(!$mail_free)
	        {
	            $email_erreur1 = "Votre adresse email est déjà utilisé par un membre";
	            $i++;
	        }

	        if (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
	        {
	            $email_erreur2 = "Votre adresse mail n'est pas au format valide";
	            $i++;
	        }

    	}
 
    	if (!empty($_FILES['avatar']['size'])) {

	        $maxsize = 100024;
	        $maxwidth = 120;
	        $maxheight = 120;
	        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' );
	 
	        if ($_FILES['avatar']['error'] > 0) {
	        	$avatar_erreur = "Erreur lors du tranfsert de l'avatar : ";
	        }
	        
	        if ($_FILES['avatar']['size'] > $maxsize) {
		        $i++;
		        $avatar_erreur1 = "Le fichier est trop gros :
		        (<strong>".$_FILES['avatar']['size']." Octets</strong>
		        contre <strong>".$maxsize." Octets</strong>)";
	        }
	 
	        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
	        
	        if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight) {
		        $i++;
		        $avatar_erreur2 = "Image trop large ou trop longue :
		        (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
		        <strong>".$maxwidth."x".$maxheight."</strong>)";
	        }
	 
	        $extension_upload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.')  ,1));
	        
	        if (!in_array($extension_upload,$extensions_valides) )
	        {
	                $i++;
	                $avatar_erreur3 = "Extension de l'avatar incorrecte";
	        }
    	}

    	if ($i == 0) {

        	if (!empty($_FILES['avatar']['size'])) {

                $nomavatar=move_avatar($_FILES['avatar']);
                $query=$db->prepare('UPDATE ctc_joueurs SET avatar_joueur = :avatar WHERE id_joueur = :id');
				$query->bindValue(':avatar',$nomavatar,PDO::PARAM_STR);
				$query->bindValue(':id',$id,PDO::PARAM_INT);
				$query->execute();
				$query->closeCursor();

        	}

			if (isset($_POST['delete'])) {

	            $query=$db->prepare('UPDATE ctc_joueurs SET avatar_joueur=0 WHERE id_joueur = :id');
				$query->bindValue(':id',$id,PDO::PARAM_INT);
				$query->execute();
				$query->closeCursor();

        	}

        	echo'<h1>Modification réussie !</h1>';

			$query=$db->prepare('UPDATE ctc_joueurs SET mdp_joueur = :mdp, email_joueur=:mail, age_joueur=:age, signe_joueur=:signe, sexe_joueur=:sexe, niveau_joueur=:niveau WHERE id_joueur=:id');
			$query->bindValue(':mdp',$pass,PDO::PARAM_INT);
			$query->bindValue(':mail',$email,PDO::PARAM_STR);
			$query->bindValue(':age',$age,PDO::PARAM_INT);
			$query->bindValue(':signe',$signe,PDO::PARAM_STR);
			$query->bindValue(':sexe',$sexe,PDO::PARAM_STR);
			$query->bindValue(':niveau',$niveau,PDO::PARAM_STR);
			$query->bindValue(':id',$id,PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
			
			header('refresh:3;url=profil.php?action=modifier');
			exit('<p>Cette page va se recharger...</p>');

    	}

    	else {

	        echo'<h1>Couille dans le potage détectée</h1>';
	        echo'<p><br />Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';
	        echo'<p>'.$i.' erreur(s)</p>';
	        echo'<p>'.$mdp_erreur.'</p>';
        	echo '<p>'.$mdp_erreur2.'</p>';
	        echo'<p>'.$email_erreur1.'</p>';
	        echo'<p>'.$email_erreur2.'</p>';
	        echo'<p>'.$avatar_erreur.'</p>';
	        echo'<p>'.$avatar_erreur1.'</p>';
	        echo'<p>'.$avatar_erreur2.'</p>';
	        echo'<p>'.$avatar_erreur3.'</p>';
			
			header('refresh:6;url=profil.php?action=modifier');
			exit('<p><br />Cette page va se recharger...</p>');

    	}

    } //Fin else

    break;

	default;

		echo '<p>Cette action est impossible</p>';

} //Fin switch

?>
		</div>
	</body>
</html>

