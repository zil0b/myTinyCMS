<?php
/* * Authorize component */
require_once CLA . "/dataManager.class.php" ;require_once CLA . "/utilities.class.php" ;
$_manager = new dataManager() ;//$_manager->data['path'] =  $_manager->data['root']  . (@$_REQUEST["entry"]?$_REQUEST["entry"]:"Root") . "/Contents/user.xml" ; //$_credit = isset($_SESSION['autho']['credit'])?trim($_SESSION['autho']['credit']):null ;//if ( isset($_REQUEST['iLogin']) )	$_credit = trim($_REQUEST['iLogin']) ;
if( isset($_REQUEST['iUnlog']))	{		unset($_SESSION["autho"]["credit"]) ;		unset($_credit) ;	}try	{		# QUERY TESTS			$_manager->data['query_1'] = array(					array("attribute"=>"pass", "value"=>@$_credit)) ;
			// query test			$_res = utilities::getLineByAttribute($_manager->initialize(), $_manager->data['query_1']) ;
			if(isset($_res["login"]))			{				$_SESSION["autho"]["credit"] =  $_res["pass"] ;			}			else			{				unset($_SESSION["autho"]["credit"]) ;			}			# REGION [0] - Keep order of privileges			if(preg_match('/u/',@$_res['right']))			{				$_SESSION["autho"]["user"] =  1 ;				unset($_SESSION["autho"]["moderator"]) ;				unset($_SESSION["autho"]["admin"]) ;			}
			if(preg_match('/m/',@$_res['right']))			{				$_SESSION["autho"]["moderator"] =  1 ;				unset($_SESSION["autho"]["admin"]) ;			}
			if(preg_match('/a/',@$_res['right']))			{				$_SESSION["autho"]["admin"] =  1 ;			}			# END REGION [0]
	} catch(Exception $ex) { }//var_dump($_res);
?>