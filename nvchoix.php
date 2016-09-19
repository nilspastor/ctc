<?php

session_start();

$titre = "Choisis Ton Camp - Nouveau Choix";

include("includes/identifiants.php");
include("includes/debut.php");

if ($id == 0) {
	header('refresh:3;url=connexion.php');
	exit('<div id="conteneur"><p style="color: white;">Vous devez être connecté(e) pour accéder à cette page.<br />
		Vous allez être redirigé(e) dans quelques secondes...</p></div></body></html>');
}

?>

		<div id="conteneur">
		
		
			<div id="cadre_choix">

<?php

if (isset($_POST['choix1'], $_POST['choix2'])) {

	$temps = time();
	$choix1 = $_POST['choix1'];
	$choix2 = $_POST['choix2'];
	$popularite = 0;

	$query=$db->prepare('INSERT INTO ctc_choix (id_auteur, choix_1, choix_2, date_choix, popularite_choix, nb_choix_1, nb_choix_2) VALUES (:id, :choix1, :choix2, :temps, :popularite, 0, 0)');
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	$query->bindValue(':choix1', $choix1, PDO::PARAM_STR);
	$query->bindValue(':choix2', $choix2, PDO::PARAM_STR);
	$query->bindValue(':temps', $temps, PDO::PARAM_INT);
	$query->bindValue(':popularite', $popularite, PDO::PARAM_INT);
	$query->execute();
	$query->closeCursor();

	echo '<div>Choix enregistré !</div>';

}

?>
			
				<div id="choix_tete">Créer un nouveau choix</div>
				
				<div id="choix_corps">

					<form method="post" action="nvchoix.php">

					<label for="choix1"></label><input name="choix1" type="text" id="champ_choix_1" /> VS
					
					<label for="choix2"></label><input name="choix2" type="text" id="champ_choix_2" />
				
				</div>
				
				<div id="nvchx_pied"><div><input type="submit" value="" class="bouton_valider" /><br /><br /></div>
				<div><a href="meschoix.php"><img src="img/flecheblanche_8x9.png" /> Revenir à mes choix</a></div></div></form>
			
			</div>
		
		
		</div>
	</body>
</html>