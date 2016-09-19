<?php

function erreur_co() 
{
	header('refresh:3;url=matchs.php');
	exit('<p>Vous êtes déjà connecté(e). Vous allez être redirigé(e)...</p>');
}

function erreur_not_co() 
{
	header('refresh:3;url=connexion.php');
	exit('<p>Vous devez être connecté pour accéder à cette page. Vous allez être redirigé(e)...</p>');
}

function move_avatar($avatar) 
{
   $extension_upload = strtolower(substr(strrchr($avatar['name'], '.')  ,1));
   $name = time();
   $nomavatar = str_replace(' ','',$name).".".$extension_upload;
   $name = "./img/avatars/".str_replace(' ','',$name).".".$extension_upload;
   move_uploaded_file($avatar['tmp_name'],$name);
   return $nomavatar;
}

function PourcentChoix($nbcalc1, $nbcalc2) 
{
   $pourcentchoix2 = $nbcalc2 * 100 / ($nbcalc1+$nbcalc2);
   return $pourcentchoix2;
}