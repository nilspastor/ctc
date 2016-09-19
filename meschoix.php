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

$query=$db->prepare('SELECT avatar_joueur, prenom_joueur, nom_joueur FROM ctc_joueurs WHERE id_joueur = :id');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
$query->closeCursor();

echo '<div id="conteneur_main">
		
			<div id="cadre_main">
			
				<div id="main_tete">

					<div id="tete_gauche">

						<a href="matchs.php"><img src="css/logo.png" id="tete_logo" /></a>

					</div>

					<div id="tete_droite">
				
						<div id="tete_box1">
							<span class="tete_prenom">'.$data['prenom_joueur'].'<br>
							'.$data['nom_joueur'].'</span><br>
							<span class="tete_link"><a href="matchs.php">Matchs</a><br>
							<a style="color: #9C9B9B;" href="#">Calendrier</a><br>
							<a style="color: #9C9B9B;" href="#">Top Joueur</a></span>
						</div>
						
						<div id="tete_box2">
							<img src="./img/avatars/'.$data['avatar_joueur'].'" id="tete_avatar" alt="Avatar désactivé" />
						</div>
					
					</div>
					
				</div>
				
				<div id="main_navi">
				
						<div class="navi_link"><a href="meschoix.php">MES CHOIX</a></div>
						<div class="navi_link2"><a style="color: #9C9B9B;" href="#">MES DISPOS</a></div>
						<div class="navi_link3"><a href="profil.php?j='.$id.'&action=modifier">MON COMPTE</a></div>
				
				
				</div>
				
				<div id="main_boucle_choix">

					<div class="boucle_choix_propose">
						
						<p><a href="nvchoix.php"><img src="img/fleche_droite_blanche.png" /> Proposer un nouveau thème de match</a></p>

					</div>

					<div class="barre_boucle"></div>';

$query=$db->prepare('SELECT j.id_selection id_selec, j.bool_chx_selec bool_chx, c.choix_1 chx_un, c.choix_2 chx_deux 
FROM ctc_choix_joueurs j RIGHT JOIN ctc_choix c 
ON j.id_selection = c.id_choix 
WHERE j.id_selecteur = :id 
ORDER BY c.date_choix DESC');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();

while ($data2 = $query->fetch()) {

if ($data2['bool_chx'] == 1) {
	$style_chx1 = '#FFFFFF;';
	$style_chx2 = '#9C9B9B;';
}
else {
	$style_chx1 = '#9C9B9B;';
	$style_chx2 = '#FFFFFF;'; 
}

echo '<div class="boucle_choix_boxes"><div class="chx_box_gauche" style="color:'.$style_chx1.'">'.mb_strtoupper($data2['chx_un']).'</div><div class="chx_box_centre"> vs </div><div class="chx_box_droite" style="color:'.$style_chx2.'">'.mb_strtoupper($data2['chx_deux']).'</div></div><div class="barre_boucle"></div>';

}

$query->closeCursor();

echo '</div>
			</div>
		</div>
	</body>
</html>';