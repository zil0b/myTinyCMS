<?php
@session_start();
require_once "./config.inc.php" ;
// check existance
if ( !@$_REQUEST['qry'] )
?>