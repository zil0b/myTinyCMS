<?php
@session_start();
require_once "./config.inc.php" ;
$_cms['status'] = 1 ; $flg = false;
// check existanceif(is_file(OWN.'/'.@$_REQUEST['entry'].'/Contents/1.xml')) $flg=true;
if ( !isset($_REQUEST['qry']) && $flg)// if not an ajax request but a normal page load	{		require_once BCK . "/authorize.inc.php" ;	}
if ( !isset($_REQUEST['qry']))	/*			 *   	if a distant request has not been done	 */	{		if ( @$_REQUEST['admin'] )		// if admin entry is asked			{				if($flg)					include_once FNT."/admin.inc.php" ;				else					echo "Page not exists !" ;			}		else		// otherwise default entry			{				if($flg)					include_once FNT."/entry.inc.php" ;				else					echo "Page not exists !" ;			}	}else	{		require_once FUN."/common.inc.php" ;		include_once BCK."/responder.inc.php" ;	}
?>