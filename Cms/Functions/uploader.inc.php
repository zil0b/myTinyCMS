<?php

// upload fnujctionnality

$dossier = @$_SESSION["path"].'/Objects/';
$fichier = basename(@$_FILES['flz']['name']);
$taille_maxi = 100000;
$taille = filesize(@$_FILES['flz']['tmp_name']);
$extensions = array('.png', '.gif', '.jpg', '.jpeg','.txt','.htm','.zip');
$extension = strrchr(@$_FILES['flz']['name'], '.'); 
//Dיbut des vיrifications de sיcuritי...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     //$erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
}
if($taille>$taille_maxi)
{
     //$erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ְֱֲֳִֵַָֹÊֻּֽ־ֿׂ׃װױײÙÚÛÜÝאבגדהוחטיךכלםמןנעףפץצשתûü‎ÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     if(move_uploaded_file(@$_FILES['flz']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que חa a fonctionnי...
     {
          //echo 'file Uploaded !';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          //echo 'Echec de l\'upload !';
     }
}
else
{
     //echo $erreur;
}

?>