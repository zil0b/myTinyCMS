<?php

require_once CLA . "/utilities.class.php" ;
require_once CLA . "/templateControler.class.php" ;

require_once FUN . "/common.inc.php" ;
require_once CLA . "/contentPane.class.php" ;

require_once FUN . "/uploader.inc.php" ;

$_SESSION["path"] = $_path = "./Owners/" . (@$_REQUEST['entry']?$_REQUEST['entry']:"Root") ;

//
$tmp = new templateControler
				(
					array("path"=>$_path
					. "/Templates/default.xml")
				);

# GET FRAMESET
	
	if(isset($_REQUEST['page']))
	{
		setFrameset($_REQUEST['page']);
	}
	
//echo print_r_html($_SESSION) ;
	
# STATE

	$flg = $tmp->getNode("//template/framesets/frameset[@id='".@(($_SESSION[FRAMESET])?$_SESSION[FRAMESET]:'common')."']");

	// frameset choice
	if($flg)
	{
		$tmp->initFrame(
				$flg
		) ;
	}else{
		$tmp->initFrame(
			$tmp->getNode("//template/framesets/frameset[@id='common']")
		) ;
	}
	
	// authentification
	$tmp->setTag("customer","<input type='hidden' value='".(@$_REQUEST['entry']?$_REQUEST['entry']:"Dummy")."' id='customerName' />
		<input type='hidden' value='".md5(@$_SESSION["autho"]["credit"])."' id='userKey' />
		<input type='hidden' value='" . (@$_cms['status']?1:0) . "' id='contentKey' />
		<input type='hidden' value='0' id='iCounter' />
	" );
	
	if(@$_SESSION["autho"]["credit"])
		{
			$tmp->setTag("logged","<input type='hidden' value='1' id='iLogged' />" );
		}
		
	// if cms entry - display menu
	if(@$_cms["status"] == 1)
		{
			$tmp->setTag("menuPane", $_container["content"]->data["menuPane"] );
			
			if(@$_SESSION['autho']['credit'])
				switch(true)
				{
					case ( isset($_REQUEST["crop"]) && $_REQUEST["crop"] != 1 && !@$_REQUEST["page"]==1 ) :
						
						$tmp->setTag("bottomPane", $_container["content"]->getCropPane(@$_REQUEST["crop"]) );
						break;
						
					case  ( isset($_REQUEST["object"]) &&  $_REQUEST["object"]== 1 ) :						
						
						$tmp->setTag("bottomPane", $_container["content"]->getObjectPane( ));
						break;
						
					case   ( isset($_REQUEST["object"]) &&  $_REQUEST["object"]==2 ) :						
						
						$tmp->setTag("bottomPane", $_container["content"]->getFilerPane( ));
						break;
						
					case  isset( $_REQUEST["template"] ) :						
						
						$tmp->setTag("bottomPane", $_container["content"]->getTemplatePane( $_REQUEST["template"], (@$_REQUEST['type']==2?2:1) ));
						break;					
					
					case  isset( $_REQUEST["page"] ) :
						
						$tmp->setTag("bottomPane", $_container["content"]->getPagePane(  ));
						break;
						
					case  isset( $_REQUEST["user"] ) :
						
						$tmp->setTag("bottomPane", $_container["content"]->getUserPane(  ));
						break;
						
					case ( @$_REQUEST["crop"] == 1 ) :
						
						$tmp->setTag("bottomPane", $_container["content"]->getNewCrop() );
						break;
				}
		}
		
	$tmp->getAnchors( $tmp->data['feed'][0]->nodeValue ) ;
	$tmp->anchorContentReplacer() ;
	
	// customer name
	$tmp->setFrame() ;
	
# OUTPUT
	echo $tmp->data['frameset'] ;

?>