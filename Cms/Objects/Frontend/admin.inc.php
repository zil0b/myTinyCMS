<?php

//
require_once CLA . "/utilities.class.php" ;require_once CLA . "/templateControler.class.php" ;require_once FUN. "/common.inc.php" ;
//$tmp = new templateControler(array("path"=>"./Owners/".(@$_REQUEST["entry"]?@$_REQUEST["entry"]:"Dummy")."/Templates/admin.xml"));
# STATE
	// get the content main page	$tmp->initFrame(		$tmp			->getNode("//template/frameset/content")		 ) ;
	// authentification	$tmp->setTag("customer","<input type='hidden' value='".(@$_REQUEST['entry']?$_REQUEST['entry']:"Root")."' id='customerName' />" );
	if(@$_SESSION["autho"]["credit"])		$tmp->setTag("logged","<input type='hidden' value='1' id='iLogged' />" );
	$tmp->getAnchors( $tmp->data['feed'][0]->nodeValue ) ;	$tmp->anchorContentReplacer() ;	$tmp->setFrame() ;
# OUTPUT	echo $tmp->data['frameset'] ;
?>