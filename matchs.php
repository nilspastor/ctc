<?php

session_start();

$titre = "Choisis Ton Camp - Matchs";

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

$query=$db->prepare('SELECT id_choix, choix_1, choix_2, nb_choix_1, nb_choix_2 FROM ctc_choix ORDER BY date_choix DESC');
$query->execute();
$tab_choix = array();
$i=0;

while ($data_chx=$query->fetch()) {

	$tab_choix[$i]['id_choix'] = $data_chx['id_choix'];
	$tab_choix[$i]['choix_1'] = $data_chx['choix_1'];
	$tab_choix[$i]['choix_2'] = $data_chx['choix_2'];
	$tab_choix[$i]['nb_choix_1'] = $data_chx['nb_choix_1'];
	$tab_choix[$i]['nb_choix_2'] = $data_chx['nb_choix_2'];
	$i++;
	
}

$query->closeCursor();

	echo '<div id="conteneur_main">
				
			<div id="cadre_main">
			
				<div id="main_tete">

					<div id="tete_gauche">

						<img src="css/logo.png" id="tete_logo" />

					</div>

					<div id="tete_droite">
				
						<div id="tete_box1">

							<span class="tete_prenom">'.$data['prenom_joueur'].'<br>
							'.$data['nom_joueur'].'</span><br>
							<span class="tete_link"><a href="meschoix.php">Mes choix</a><br>
							<a style="color: #9C9B9B;" href="#">Mes dispos</a><br>
							<a href="profil.php?j='.$id.'&action=modifier">Mon compte</a></span>

						</div>
						
						<div id="tete_box2">
							<img src="./img/avatars/'.$data['avatar_joueur'].'" id="tete_avatar" alt="Avatar désactivé" />
						</div>
					
					</div>
					
				</div>
				
				<div id="main_navi">
				
						<div class="navi_link"><a href="matchs.php">MATCHS</a></div>
						<div class="navi_link2"><a style="color: #9C9B9B;" href="#">CALENDRIER</a></div>
						<div class="navi_link3"><a style="color: #9C9B9B;" href="#">TOP JOUEUR</a></div>
				
				</div>
				




				<div id="main_boucle_matchs">

					<div id="boucle_matchs_cols">
						
						<div id="matchs_col1">EQUIPES</div>

						<div id="matchs_col2">%</div>

						<div id="matchs_col3">FAVORIS</div>

					</div>';
				
foreach ($tab_choix as $leschoix) {

	$nombre1 = $leschoix['nb_choix_1'];
	$nombre2 = $leschoix['nb_choix_2'];
	$total = $nombre1 + $nombre2;
	$pourcent = PourcentChoix($nombre1,$nombre2);


	echo '<div class="boucle_matchs_boxes">

					<div class="matchs_box1">'.mb_strtoupper($leschoix['choix_1']).' vs '.mb_strtoupper($leschoix['choix_2']).'</div>';


	if ($pourcent >= 0 && $pourcent <= 2.5) {
		echo '<div class="matchs_box2"><img src="img/camembert0.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 2.5 && $pourcent <= 7.5) {
		echo '<div class="matchs_box2"><img src="img/camembert5.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 7.5 && $pourcent <= 12.5) {
		echo '<div class="matchs_box2"><img src="img/camembert10.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 12.5 && $pourcent <= 17.5) {
		echo '<div class="matchs_box2"><img src="img/camembert15.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 17.5 && $pourcent <= 22.5) {
		echo '<div class="matchs_box2"><img src="img/camembert20.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 22.5 && $pourcent <= 27.5) {
		echo '<div class="matchs_box2"><img src="img/camembert25.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 27.5 && $pourcent <= 32.5) {
		echo '<div class="matchs_box2"><img src="img/camembert30.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 32.5 && $pourcent <= 37.5) {
		echo '<div class="matchs_box2"><img src="img/camembert35.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 37.5 && $pourcent <= 42.5) {
		echo '<div class="matchs_box2"><img src="img/camembert40.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 42.5 && $pourcent <= 47.5) {
		echo '<div class="matchs_box2"><img src="img/camembert45.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 47.5 && $pourcent <= 52.5) {
		echo '<div class="matchs_box2"><img src="img/camembert50.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 52.5 && $pourcent <= 57.5) {
		echo '<div class="matchs_box2"><img src="img/camembert55.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 57.5 && $pourcent <= 62.5) {
		echo '<div class="matchs_box2"><img src="img/camembert60.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 62.5 && $pourcent <= 67.5) {
		echo '<div class="matchs_box2"><img src="img/camembert65.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 67.5 && $pourcent <= 72.5) {
		echo '<div class="matchs_box2"><img src="img/camembert70.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 72.5 && $pourcent <= 77.5) {
		echo '<div class="matchs_box2"><img src="img/camembert75.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 77.5 && $pourcent <= 82.5) {
		echo '<div class="matchs_box2"><img src="img/camembert80.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 82.5 && $pourcent <= 87.5) {
		echo '<div class="matchs_box2"><img src="img/camembert85.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 87.5 && $pourcent <= 92.5) {
		echo '<div class="matchs_box2"><img src="img/camembert90.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 92.5 && $pourcent <= 97.5) {
		echo '<div class="matchs_box2"><img src="img/camembert95.png" title="'.$total.' votes" /></div>';
	}
	elseif ($pourcent > 97.5 && $pourcent <= 100) {
		echo '<div class="matchs_box2"><img src="img/camembert100.png" title="'.$total.' votes" /></div>';
	}


	echo '<div class="matchs_box3">
						<div class="matchs_box3_sub1"><img src="css/stars5.png" /></div>
						<div class="matchs_box3_sub2"><a href="#">votez !</a></div>
					</div>

				</div>';


	$query=$db->prepare('SELECT j.id_joueur id_j, j.prenom_joueur prenom_j, j.nom_joueur nom_j, cj.id_selecteur id_selecteur_cj, cj.bool_chx_selec bool_selec_cj FROM ctc_choix_joueurs cj JOIN ctc_joueurs j ON cj.id_selecteur = j.id_joueur WHERE cj.id_selection = :id_chx_en_cours AND cj.bool_chx_selec = 1');
	$query->bindValue(':id_chx_en_cours', $leschoix['id_choix'], PDO::PARAM_INT);
	$query->execute();
	$tab_equipe1 = array();
	$i=0;

	while ($data_eqp1=$query->fetch()) {

		$tab_equipe1[$i]['id_j'] = $data_eqp1['id_j'];
		$tab_equipe1[$i]['prenom_j'] = $data_eqp1['prenom_j'];
		$tab_equipe1[$i]['nom_j'] = $data_eqp1['nom_j'];
		$tab_equipe1[$i]['bool_selec_cj'] = $data_eqp1['bool_selec_cj'];
		$i++;
		
	}

	$query->closeCursor();

	$query=$db->prepare('SELECT j.id_joueur id_j, j.prenom_joueur prenom_j, j.nom_joueur nom_j, cj.id_selecteur id_selecteur_cj, cj.bool_chx_selec bool_selec_cj FROM ctc_choix_joueurs cj JOIN ctc_joueurs j ON cj.id_selecteur = j.id_joueur WHERE cj.id_selection = :id_chx_en_cours AND cj.bool_chx_selec = 2');
	$query->bindValue(':id_chx_en_cours', $leschoix['id_choix'], PDO::PARAM_INT);
	$query->execute();
	$tab_equipe2 = array();
	$i=0;

	while ($data_eqp2=$query->fetch()) {

		$tab_equipe2[$i]['id_j'] = $data_eqp2['id_j'];
		$tab_equipe2[$i]['prenom_j'] = $data_eqp2['prenom_j'];
		$tab_equipe2[$i]['nom_j'] = $data_eqp2['nom_j'];
		$tab_equipe2[$i]['bool_selec_cj'] = $data_eqp2['bool_selec_cj'];
		$i++;
		
	}

	$query->closeCursor();


	echo '<ul class="navi_equipes">
	<li class="toggleSubMenu"><span>Equipes</span>
	<ul class="subMenu">';

	if (!empty($tab_equipe1)) {

		echo '<div>';

		foreach ($tab_equipe1 as $lequipe1) {

			echo '<li><a href="profil.php?j='.$lequipe1['id_j'].'">'.$lequipe1['prenom_j'].' '.$lequipe1['nom_j'].'</a></li>';

		}

		echo '</div>';

	}

	else {

		echo '<div><li><a href="#"></a> - </li></div>';

	}

	if (!empty($tab_equipe2)) {

		echo '<div>';

		foreach ($tab_equipe2 as $lequipe2) {

			echo '<li><a href="profil.php?j='.$lequipe2['id_j'].'">'.$lequipe2['prenom_j'].' '.$lequipe2['nom_j'].'</a></li>';

		}

		echo '</div>';

	}

	else {

		echo '<div><li><a href="#"> - </a></li></div>';

	}

	echo '</ul></li></ul>';

	echo '<div class="barre_boucle"></div>';

}

echo '<br /></div>
			</div>
		</div>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

		<script type="text/javascript">
		$(document).ready( function () {
		    $(".navi_equipes ul.subMenu").hide();
		    $(".navi_equipes li.toggleSubMenu span").each( function () {
		        var ImageSpan = \'<img src="img/fleche_haut.png" />\';
		        $(this).replaceWith(\'<a href="" class="bouton_deroulant" title="Cliquez pour afficher">\' + ImageSpan + \'</a>\') ;
		    });
			$(".navi_equipes li.toggleSubMenu > a").click( function () {
		        if ($(this).next("ul.subMenu:visible").length != 0) {
		            $(this).next("ul.subMenu").slideUp("normal");
		        }
		        else {
		            $(".navi_equipes ul.subMenu").slideUp("normal");
		            $(this).next("ul.subMenu").slideDown("normal");
		        }
		        return false;
		    });    
		});
		</script>

	</body>
</html>';