<?php 
	
	//
	$_ = explode(":",@$_REQUEST['id'])  ;
	$_qry = $_REQUEST['qry']  ;
	$_content = ""  ;
	
	switch( true )
	/**
	 * content type switcher
	 */
		{
			
			case ($_[0] == "lay") :
			// single query order : template request
			
				$_content = requestForLayout( $_[1], $_REQUEST['owner'], @$_REQUEST['template']?$_REQUEST['template']:'default' ) ;
				break ;
				
			case ($_[0] == "data") :
			// single query order : data request

				$_content = requestForData( $_[1], $_REQUEST['owner'] ) ;
				break ;
				
			case(@$_qry == 2) :
			// single query order : formatData
				
				$_content = formatData(@$_REQUEST['content']) ;
				break ;
				
			case(@$_qry == 3) :
			// composed query instruction : 
				
				//$_content = ;
				break ;
				
			case(@$_qry == 4) :
			// single query order : getCustomerEntryList
				
				require_once BCK . "/services.inc.php" ;
				
				if( @$_SESSION["autho"]["credit"] )
				{
					$_adminPage = new adminPage() ;
					$_adminPage->prepareAdminPage( getCustomerEntryList( OWN ) ) ;
					$_content = $_adminPage->str ;
				} else {
					$_content = "no access granted" ;
				}
				break ;
				
			case (@$_qry == 5) :
			// create entries for customer
				$_content = $_REQUEST['nu']." !.added [ refresh the page ]" ;
				mkdir( OWN . "/" . $_REQUEST['nu'] , 0755);
					mkdir( OWN . "/" . $_REQUEST['nu']."/Contents" , 0755);
						copy( OWN . "/Root/Dummies/1.xml", OWN . "/" . $_REQUEST['nu'] . "/Contents/1.xml" ) ;
						copy( OWN . "/Root/Dummies/user.xml", OWN . "/" . $_REQUEST['nu'] . "/Contents/user.xml" ) ;
						copy( OWN . "/Root/Dummies/page.xml", OWN . "/" . $_REQUEST['nu'] . "/Contents/page.xml" ) ;
					mkdir( OWN . "/" . $_REQUEST['nu']."/Templates" , 0755);
						copy( OWN . "/Root/Dummies/default.xml", OWN . "/" . $_REQUEST['nu'] . "/Templates/default.xml" ) ;
						copy( OWN . "/Root/Dummies/admin.xml", OWN . "/" . $_REQUEST['nu'] . "/Templates/admin.xml" ) ;
					mkdir( OWN . "/" . $_REQUEST['nu']."/Users" , 0755);
					mkdir( OWN . "/" . $_REQUEST['nu']."/Tmp" , 0755);
					mkdir( OWN . "/" . $_REQUEST['nu']."/Objects" , 0755);
				break ;
				
			case (@$_qry == 6) :
			// save file modified
				
				require_once "services.inc.php" ;
				
				$_content = $_REQUEST['path']." !.saved" ;
				file_put_contents($_REQUEST['path'], noHtml($_REQUEST['content']));
				doFlush();
				break ;
				
			case (@$_qry == 7) :
			// 
				$_content = "> crop page" ;
				break;
				
			case (@$_qry == 8) :
			// 
				$_content = "> item page" ;
				break;
				
			case (@$_qry == 9) :
			// 
				$_content = "> page page" ;
				break;
				
			case (@$_qry == 15) :
				$_content = getCropItem($_REQUEST['item'],$_REQUEST['crop']) ;
				break;
			
			case (@$_qry == 16) :
					$_content = getFileOptions($_REQUEST['target']) ;
					break;
			
			case (@$_qry == 17) :
				$_content = getFileContent($_REQUEST['item']) ;
				break;
						
			case (@$_qry == 20) :
				$_content = saveCropItem($_REQUEST['value'],$_REQUEST['owner']) ;
				break;
			
			case (@$_qry == 23) :
				$_content = saveFileItem($_REQUEST['value'],$_REQUEST['owner']) ;
				break;
				;
			case (@$_qry == 21) :
				$_content = saveCropNewItem($_REQUEST['value'],$_REQUEST['owner']) ;
				break;
				
			case (@$_qry == 22) :
				$_content = saveNewCrop($_REQUEST["owner"],$_REQUEST['value']) ;
				break;
				
			case (@$_qry == 25) :
				$_content = saveUser($_REQUEST['owner'], $_REQUEST['value'], $_REQUEST['key'], 1) ;
				break;
				
			case (@$_qry == 26) :
				$_content = saveUser($_REQUEST['owner'], $_REQUEST['value'], $_REQUEST['key'], 2) ;
				break;
				
			case (@$_qry == 30) :
				$_content = saveCrop($_REQUEST['owner'], $_REQUEST['id'], $_REQUEST['data'], $_REQUEST['key']) ;
				break;
				
			case (@$_qry == 35) :
				$_content = savePage($_REQUEST['owner'], $_REQUEST['value'], $_REQUEST['key'],1) ;
				break;
				
			case (@$_qry == 36) :
				$_content = savePage($_REQUEST['owner'], $_REQUEST['value'], $_REQUEST['key'],2) ;
				break;
				
			case (@$_qry == 37) :
				$_content = saveFrameset($_REQUEST['owner'],$_REQUEST['item'], $_REQUEST['value'],$_REQUEST['key']) ;
				break;
				
			case (@$_qry == 40) :

				$_content = getTemplateItem($_REQUEST['itemValue'],$_REQUEST['template'],$_REQUEST['nodeName']) ;

				break;
							case (@$_qry == 41) :

				$_content = saveTemplateItem($_REQUEST['pageType'],$_REQUEST['value'],$_REQUEST['owner']) ;

				break;

			case (@$_qry == 42) :

				$_content = saveTemplateNewItem($_REQUEST['pageType'],$_REQUEST['value'],$_REQUEST['owner']) ;

				break;
		}

# OUTPUT

switch(true)
	{	
		case ($_qry==6):
			echo $_content ; 
			break;
			
		case ($_qry==15) :
			echo $_content;
			break;
			
		case ($_qry==16) :
			echo $_content;
			break;

		case ($_qry==17) :
			echo $_content;
			break;	
						
		case ($_qry==20) :
			echo $_content;
			break;
		case ($_qry==21) :
			echo $_content;
			break;
			
		case ($_qry==22) :
			echo $_content;
			break;
			
		case ($_qry==23) :
			echo $_content;
			break;
			
		case ($_qry==25) :
			echo $_content;
			break;
			
		case ($_qry==26) :
			echo $_content;
			break;
			
		case ($_qry==30) :
			echo $_content;
			break;
			
		case ($_qry==35) :
			echo $_content;
			break;
			
		case ($_qry==36) :
			echo $_content;
			break;
			
		case ($_qry==37) :
			echo $_content;
			break;
			
		case (@$_qry == 40) :

			echo $_content;

			break;
			
		case (@$_qry == 41) :

			echo $_content;

			break;
			
		case (@$_qry == 42) :

			echo $_content;

			break;
		default:
			echo @$_REQUEST['id'] . "~~" . ( @$_[1] ? $_[1] : $_[0] ) . "~~" . $_content ; 
			break;
	}

	
?>