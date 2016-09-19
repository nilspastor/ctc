<?php

session_start();

$titre = "Choisis Ton Camp - Mes Choix";

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

if (isset($_GET['ids'])) {
		
	$temps = time();
	$id_selec = $_GET['ids'];
	$date_selec = $_GET['dts'];
	$chx_selec = $_GET['chx'];

	$query=$db->prepare('INSERT INTO ctc_choix_joueurs(id_selecteur, id_selection, date_choix_joueur, bool_chx_selec) VALUES(:id, :id_selec, :temps, :chx_selec)');
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	$query->bindValue(':id_selec', $id_selec, PDO::PARAM_INT);
	$query->bindValue(':temps', $temps, PDO::PARAM_INT);
	$query->bindValue(':chx_selec', $chx_selec, PDO::PARAM_INT);
	$query->execute();
	$query->closeCursor();

	if ($chx_selec == 1) {
		$query=$db->prepare('UPDATE ctc_choix SET nb_choix_1 = nb_choix_1 + 1 WHERE id_choix = :id_chx');
		$query->bindValue(':id_chx', $id_selec, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
	}

	if ($chx_selec == 2) {
		$query=$db->prepare('UPDATE ctc_choix SET nb_choix_2 = nb_choix_2 + 1 WHERE id_choix = :id_chx');
		$query->bindValue(':id_chx', $id_selec, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
	}

	$query=$db->prepare('UPDATE ctc_joueurs SET derniere_visite_joueur = :date_selec WHERE id_joueur=:id');
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	$query->bindValue(':date_selec', $date_selec, PDO::PARAM_INT);
	$query->execute();
	$query->closeCursor();

	$query=$db->prepare('SELECT j.derniere_visite_joueur dernier_chx, c.date_choix date_chx, c.id_choix id_chx, c.choix_1 chx_un, c.choix_2 chx_deux 
	FROM ctc_joueurs j RIGHT JOIN ctc_choix c
	ON j.id_joueur = :id
	WHERE j.derniere_visite_joueur < c.date_choix
	ORDER BY c.date_choix LIMIT 1');
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	$query->execute();
	$data = $query->fetch();
	$query->closeCursor();

	if (empty($data)) {

		include('includes/choixajour.php');
		header('refresh:3;url=matchs.php');
		exit;

	}

	else { 

		include('includes/fairechoix.php');

	}

}

else {

	$query=$db->prepare('SELECT j.derniere_visite_joueur dernier_chx, c.date_choix date_chx, c.id_choix id_chx, c.choix_1 chx_un, c.choix_2 chx_deux 
	FROM ctc_joueurs j RIGHT JOIN ctc_choix c
	ON j.id_joueur = :id
	WHERE j.derniere_visite_joueur < c.date_choix
	ORDER BY c.date_choix LIMIT 1');
	$query->bindValue(':id', $id, PDO::PARAM_INT);
	$query->execute();
	$data = $query->fetch();
	$query->closeCursor();

	if (empty($data)) {
		
		

		include('includes/choixajour.php');
		header('refresh:3;url=matchs.php');
		exit;

	}

	else { 

		include('includes/fairechoix.php');

	}

}