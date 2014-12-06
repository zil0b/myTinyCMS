<?php
@session_start();
require_once "./config.inc.php" ;
// check existanceif(is_file(OWN.'/'.@$_REQUEST['entry'].'/Contents/1.xml')) $flg=true;
if ( !@$_REQUEST['qry'] )	/*			 *   	if a distant request has not been done	 */	{		if(@$flg)			include_once FNT."/entry.inc.php" ;		else			echo "Page not exists !" ;	}else	{		require_once FUN."/common.inc.php" ;		include_once BCK."/responder.inc.php" ;	}
?>