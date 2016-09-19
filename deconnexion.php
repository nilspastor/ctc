<?php

session_start();

session_destroy();

$titre="Déconnexion";

include("includes/debut.php");

?>

<div id="conteneur">

<?php

header('refresh:2;url=connexion.php');
exit('<div="cadre_compte"><p style="color: white;">Vous êtes à présent déconnecté(e). <br/>
	Vous allez être redirigé(e) dans quelques secondes...</p></div></div></body></html>');

?>