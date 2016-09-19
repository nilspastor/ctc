<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		
<?php

echo (!empty($titre))?'<title>'.$titre.'</title>':'<title>Choisis Ton Camp</title>';

?>
		
		<link rel="stylesheet" href="css/ctc.min.css" />
	</head>
	
<?php

$lvl=(isset($_SESSION['level']))?(int)$_SESSION['level']:1;
$id=(isset($_SESSION['id']))?(int)$_SESSION['id']:0;
$pseudo=(isset($_SESSION['pseudo']))?$_SESSION['pseudo']:'';

include("./includes/functions.php");
include("./includes/constants.php");

?>
	
	<body>