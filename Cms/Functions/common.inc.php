<?php
/*
		common application functiunalities 

		would be fine to merge all request to requestForData

*/

require_once CLA . "/utilities.class.php" ;
require_once CLA . "/dataManager.class.php" ;
require_once CLA . "/templateControler.class.php" ;

function requestForLayout($pTerm,$pOwner="Root",$pTemplate="default")
/**
 * layout request
 */
	{
		$tmp = new templateControler(array("path"=>"./Owners/" . $pOwner . "/Templates/" . $pTemplate . ".xml")) ;
		$node = $tmp->getNode('//page[@id="' . $pTerm . '"]') ;
		
	return $node[0]->nodeValue ;
	}

function requestForData($pTerm,$pOwner)
/**
 * data request
 */
	{
		$_lst = explode("|",$pTerm) ;
		
		$_manager = new dataManager() ;
		$_manager->data['path'] =  $_manager->data['root']  . $pOwner . "/Contents/" . $_lst[0] . ".xml" ; //
		$_document = $_manager->initialize() ;
		 
		if($_lst[1])
			$_manager->data['query_1'] = array("attribute"=>"number", "value"=>$_lst[1]) ;
		if($_lst[2])
			$_manager->data['query_2'] = array("attribute"=>"id", "value"=>$_lst[2]) ;
		
		$res = dataManager::getContentByData(
			dataManager::getContentByData($_document, $_manager->data['query_1'])
			,$_manager->data['query_2']) ;
		
		$node = isset($_lst[3]) ? $_lst[3] : "content" ;
		$str = $res->getElementsByTagName($node) ;
	
	return $str->item(0)->nodeValue ;
	}

function getPagesPanel($pEntry)
	{
		if( @$_SESSION["autho"]["admin"] || @$_SESSION["autho"]["moderator"] )
		{
			$dom = new DOMDocument() ;
			$dom->load("./Owners/" . $pEntry . "/Contents/page.xml") ;
			
			$root = $dom->getElementsByTagName("pages");
			
			$str = "
			
			<script>
			
				function pageEdit(pVal)
					{

						var tab =[] ;
						
						if($('number_'+pVal).innerHTML.substring(0,1) == '<')
							{
								$('number_'+pVal).innerHTML = tab[0] = $('iNum_'+pVal).value ;
								$('id_'+pVal).innerHTML = tab[1] = $('iId_'+pVal).value ;
								$('entry_'+pVal).innerHTML = tab[2] = $('iEnt_'+pVal).value ;
								
								$('b'+pVal).innerHTML = '[edit]';
								
//alert($('userKey').value);
								
								new Ajax.Request('./main.php',
                                {
                                    method:'get',
                                    parameters:'qry=35&item=' + pVal + '&value=' + tab + '&owner=' + $('customerName').value + '&key='+$('userKey').value,
                                    onSuccess: function(transport)
                                    {
                                        $('cComPage').innerHTML = transport.responseText ;
                                      	location.href='".($_SERVER['PHP_SELF'].'?'."entry=".$_REQUEST['entry'])."&page=1';
                                    },
                                    onFailure: function(){ }
                                });
							}
						else
							{
								$('number_'+pVal).innerHTML = \"<input id='iNum_\"+pVal+\"' type='text' value='\"+ ($('number_'+pVal).innerHTML) +\"' />\" ;
								$('id_'+pVal).innerHTML = \"<input id='iId_\"+pVal+\"' type='text' value='\"+ ($('id_'+pVal).innerHTML) +\"' />\" ;
								$('entry_'+pVal).innerHTML = \"<input id='iEnt_\"+pVal+\"' type='text' value='\"+ ($('entry_'+pVal).innerHTML) +\"' />\" ;

								$('b'+pVal).innerHTML = '[save]';
								
							}
					}	
					
					function saveNewPage()
						{
							var tab = [] ;
							tab[0] = $('iNumNew').value;
							tab[1] = $('iIdNew').value;
							tab[2] = $('iEntNew').value;

							new Ajax.Request('./main.php',
                               {
                                   method:'get',
                                   parameters:'qry=36&value=' + tab+ '&owner=' + $('customerName').value +'&key=' + $('userKey').value,
                                   onSuccess: function(transport)
                                   {
                                       $('cComPage').innerHTML = transport.responseText ;
                                       location.href='".($_SERVER['PHP_SELF'].'?'."entry=".$_REQUEST['entry'])."&page=1';
                                        $('new').innerHTML = '';
                                   },
                                   onFailure: function(){ }
                               });
                               
                               ;
						}
					
						function addPage()
						{
							$('new').innerHTML = '<p/><table width=\"600\"><tr class=\"title\"><td onclick=\"saveNewPage();\" class=\"specButton\"><b>[save]</b></td><td><input type=\"text\" id=\"iNumNew\"/></td><td><input type=\"text\" id=\"iIdNew\"/></td><td><input type=\"text\" id=\"iEntNew\"/></td></tr></table><p/>' ;
						}
						
						function saveFrameset(pItem, pValue)
						{
							new Ajax.Request('./main.php',
                               {
                                   method:'get',
                                   parameters:'qry=37&owner=' + $('customerName').value +'&item='+pItem+'&value=' + pValue +'&key=' + $('userKey').value,
                                   onSuccess: function(transport)
                                   {
                                       $('j_'+pItem).innerHTML = '~';
                                       $('cComPage').innerHTML = transport.responseText ;
                                   },
                                   onFailure: function(){ }
                               });
						}
						
						function changeFrameset(pItem)
						{
							var fld = $('frameset_'+pItem);
							if(fld.innerHTML.substring(0,1)!='<')
							{
								fld.innerHTML = '<input id=\"i_'+pItem+'\"type=\"text\" value=\"'+fld.innerHTML+'\" />';
								$('j_'+pItem).innerHTML = '!';
							}
							else
							{	
								saveFrameset(pItem,$('i_'+pItem).value);
								fld.innerHTML =  $('i_'+pItem).value ;
							}
						}
			</script>
			
			
			<table width='600'>
				<tr class='title'>
				<td/>
				<td>number</td>
				<td>id</td>
				<td>entry</td>
				</tr>
			";
			$i = 0 ;
			foreach($root->item(0)->childNodes as $v)
			{
				if($v->nodeType != XML_TEXT_NODE)
				{
					@$str .= "<tr><td><span onclick='pageEdit(".$i.");'><b id='b".$i."' class='specButton'>[edit]</b></td><td id='number_".$i."'>". $v->getAttribute("number") . "</td><td id='id_".$i."'>" . $v->getAttribute("id")  . "</td><td id='entry_".$i."'>" . $v->getAttribute("entry") . "</td><td id='frameset_".$i."'>" . $v->getAttribute("frameset") . "</td><td width='5'><span id='j_".$i."' style='cursor:pointer;' onclick='changeFrameset(".$i.");'>~</span></td></tr>" ;
				}
				$i++ ;
			}
			return $str .= "</table> <hr /><div id='new'></div><span class='specButton' onclick='addPage();'>[add page]</span> <span id='cComPage' class='green'></span>" ;
		}
	}
	
function reformat($pContent)
	{
		$in   = array("``i", "``o","``q","``e","``u","``a"); 
		$out = array("=","\"", "?","&amp;","%","#");
				
	return stripslashes(str_replace($in,$out,$pContent)) ;
	}
	
function formatData($pContent)
/**
 * data formatter
 */
	{
		$pContent = reformat($pContent);
		
		$_pattern =  '/(\[)([a-zA-Z0-9]*)(:)?([a-zA-Z0-9]*)?(\[)([<>\-(\"\'a-zA-Z0-9:\[\] ]*)(\]\])/' ;
		
		$_manager = new dataManager() ;
		// TODO
		$_manager->data['path'] =  $_manager->data['root']  . "Root/Contents/" . "1.xml"; //$_lst[0] . 
		$_document = $_manager->initialize() ;
		
		$flg = preg_match($_pattern, $pContent, $_matches )? true: false ;
		if ( $flg  )
		{
			// save feed
			$_manager->data['query_1'] = array("attribute"=>"id", "value"=>$_matches[4], "node"=>"content",
         		"replacement"=>$_matches[6]) ;
			// query test
			$_manager->setContentByData($_document, $_manager->data['query_1']) ;
		
			$_str = preg_replace($_pattern, ("<" . $_matches[2] . " id='".$_matches[4]."'>" . $_matches[6] . "</" . $_matches[2] . ">"), $pContent) ;
		} else {
			$_str = $pContent ;
		}
		
	return $_str ;
	}

function setFrameset($pId)
	{
		$dom = new DOMDocument() ;
		$fileName = "./Owners/" . $_REQUEST['entry'] . '/Contents/page.xml' ;
		$dom->load($fileName) ;
		
		$node = $dom->getElementsByTagName("pages") ;
		
		foreach($node->item(0)->childNodes as $v )
			if($v->nodeType != XML_TEXT_NODE)
				if($v->getAttribute("id") == $pId)
				{
					$_SESSION['FRAMESET'] = $v->getAttribute("frameset");
				}
	}
	
function getPage($pId)
	{
		$dom = new DOMDocument() ;
		$fileName = "./Owners/" . $_REQUEST['entry'] . '/Contents/page.xml' ;
		$dom->load($fileName) ;
		
		$node = $dom->getElementsByTagName("pages") ;
		
		foreach($node->item(0)->childNodes as $v )
			if($v->nodeType != XML_TEXT_NODE)
				if($v->getAttribute("id") == $pId)
				{
					return "<div id='".$v->getAttribute("entry") . "'></div>" ;
				}
	}
	
function getFileList($pPath)
/*
 *  Lists all the files in a directory
 */
	{
		$i = 0 ; $lst = "" ;
			if (@$handle = opendir($pPath))
				while (false !== ($file = readdir($handle)))
					{
						if ( $file == "." || $file == ".." || preg_match("/page/",$file) ||  preg_match("/user/",$file)  ) 
							{}
						else 
							{
								@$lst[@++$i]=$pPath . "/" . $file ;
							}
					}
	
	return $lst ;
	}

function getObjectsList($pPath)
/*
 *  Lists all the files in a directory
 */
	{
		$i = 0 ; $lst = "" ;
			if (@$handle = opendir($pPath))
				while (false !== ($file = readdir($handle)))
					{
						if ( $file == "." || $file == ".."  ) 
							{}
						else 
							{
								@$lst[@++$i]=$pPath . "/" . $file ;
							}
					}
	
	return $lst ;
	}
	
function getCropOptions($pDoc)
{
	$dom = new DOMDocument();
	$dom->load( $pDoc );
	
	$nodes = $dom->getElementsByTagName("fields");
	
	foreach( $nodes->item(0)->childNodes as $v )
		{
			if($v->nodeType != XML_TEXT_NODE)
			@$opt .= "<option value='" . $v->getAttribute("number") . "'>" . $v->getAttribute("number") . " | " . $v->getAttribute("id") . "</option>" ;	
		}
return $opt ;
}

function getTemplateOptions($pNodeName, $pDoc)
{
	$dom = new DOMDocument();
	$dom->load( $pDoc );
	
	$nodes = $dom->getElementsByTagName($pNodeName);
	
	foreach( $nodes as $v )
		{
			if($v->nodeType != XML_TEXT_NODE)
			@$opt .= "<option value='" . $v->getAttribute("id") . "'>" . $v->getAttribute("id") . "</option>" ;	
		}
	
return @$opt ;
}

function getCropItem($pItemValue,$pCrop)
{
	$dom =new DOMDocument();
	$dom->load($pCrop);
	
	$node =  $dom->getElementsByTagName("fields");
	
	foreach( $node->item(0)->childNodes as $v )
		{
			if($v->nodeType != XML_TEXT_NODE && $v->getAttribute("number") == trim($pItemValue))
			{
				
				$subNodes = $v->childNodes ;
				
				foreach($subNodes as $u)
				{
					if($u->nodeName == "subject")
						$tab = $u->nodeValue ;
						
					if($u->nodeName == "content")
						$tab .= "-@-" . $u->nodeValue;
						
				}
				return @$tab ;
			}
		}
}

function getFileOptions($pTarget)
{

	$dir = opendir($pTarget);
	
	while($file = readdir($dir)) {
		if($file != '.' && $file != '..')
		{
			if(is_dir($pTarget.$file))
				@$tab .= '<option>[] '.$file.'</option>';
			else
				@$tab .= '<option>'.$file.'</option>';
		}
	}
	
	closedir($dir);
	
return @$tab ;
}

function getFileContent($pItemValue)
{
	$lines = file($pItemValue);
	
	$tmp = explode("/",$pItemValue);
	foreach($lines as $line)
	{
	    @$tab .= $line;
	}
	
return $tmp[count($tmp)-1].'-@-'.utf8_encode($tab);
}

function getTemplateItem($pItem, $pTemplate, $pNodeName)
{
	$dom =new DOMDocument();
	$dom->load($pTemplate);
	
	$node =  $dom->getElementsByTagName($pNodeName);
	
	foreach( $node as $v )
		{
			if($v->nodeType != XML_TEXT_NODE && $v->getAttribute("id") == trim($pItem))
				{
				$tab = $v->getAttribute('id') ;
				$tab .= "-@-" . $v->nodeValue;
				
				return @$tab ;
				}
		}
}

function saveCropItem($pValue,$pOwner)
{
	
	$pValue = reformat($pValue) ;
	
	$tab = explode("-@-",$pValue) ;
	$dom =new DOMDocument();
	$dom->load($tab[1]);
	
	$node =  $dom->getElementsByTagName("fields");
	
	$topic = $dom->getElementsByTagName("topic");
	$topic->item(0)->nodeValue = $tab[2];
	$scope = $dom->getElementsByTagName("scope");
	$scope->item(0)->nodeValue = $tab[3];
	
	foreach( $node->item(0)->childNodes as $v )
		{
			if($v->nodeType != XML_TEXT_NODE && $v->getAttribute("number") == $tab[0])
			{
				
				try
				{			
					$subNodes = $v->childNodes ;
					
					foreach($subNodes as $u)
					{
						if($u->nodeName == "subject")
							$u->nodeValue = $tab[4] ;
						
						$string = str_replace('-0-', '?', $tab[5]);
						$string = str_replace('-1-','#', $string);
						$string = str_replace('-2-','%', $string);
						$string = str_replace('-3-','&', $string);
							
						if($u->nodeName == "content")
							$u->nodeValue = $string ;
					}

					$dom->save($tab[1]);
					
					return "Crop item saved !" ;
				}
				catch(Exception $ex)
				{
					return "Error when saving" ;
				}
				
			}
		}
}

function saveFileItem($pValue,$pOwner)
{

	$pValue = reformat($pValue) ;
	$tab = explode("-@-",$pValue) ;

	try
	{
		$f = fopen($tab[0].$tab[1], 'w');
		$string = str_replace('-0-', '?', $tab[2]);
		$string = str_replace('-1-','#', $string);
		$string = str_replace('-2-','%', $string);
		$string = str_replace('-3-','&', $string);
		
		fwrite($f, $string);
		fclose($f);
		
		return "File item saved !" ;
	}
	catch(Exception $ex)
	{
		return "Error when saving" ;
	}

}

function saveTemplateItem($pPageType,$pValue,$pOwner)
{
	
	$pValue = reformat($pValue) ;
	
	$tab = explode("-@-",$pValue) ;
	$dom =new DOMDocument();
	$dom->load($tab[0]);

	$node = $dom->getElementsByTagName($pPageType);
	
	foreach( $node as $v )
		{
			if($v->nodeType != XML_TEXT_NODE && $v->getAttribute("id") == $tab[1])
			{
				try
				{			
					$string = str_replace('-0-', '?', $tab[2]);
					$string = str_replace('-1-','#', $string);
					$string = str_replace('-2-','%', $string);
					$string = str_replace('-3-','&', $string);
					
					$v->setAttribute('id',$tab[1]) ;
					$v->nodeValue = $string ;
					
					$dom->save($tab[0]);
					
					return "Crop item saved !" ;
				}
				catch(Exception $ex)
				{
					return "Error when saving" ;
				}
			}
		}
}

function saveCropNewItem($pValue,$pOwner)
{
	
	$tab = explode("-@-",reformat($pValue)) ;
	$dom =new DOMDocument();
	$dom->load($tab[1]);
	
	$_fields =  $dom->getElementsByTagName("fields");
	
	$_fields->item(0)->setAttribute("count", $cpt = ((int)$_fields->item(0)->getAttribute("count"))+1) ;

	try
	{	
		$node = $dom->createElement("field");
		
		$node->setAttribute("id",$tab[4]) ;
		$node->setAttribute("number",$cpt) ;
		
		$subject = $dom->createElement("subject");
		$subject->nodeValue=$tab[4];
		
		$string = str_replace('-0-', '?', $tab[5]);
		$string = str_replace('-1-','#', $string);
		$string = str_replace('-2-','%', $string);
		$string = str_replace('-3-','&', $string);
		
		$content = $dom->createElement("content");
		$ct = $content->ownerDocument->createCDATASection("\n" . $string . "\n");
		$content->appendChild($ct);
		
		$node->appendChild($subject) ;
		$node->appendChild($content) ;
		
		$_fields->item(0)->appendChild($node) ;
		
		$dom->save($tab[1]);
		
		return "New item saved !" ;
	}
	catch(Exception $ex)
	{
		return "Error when saving" ;
	}
}


function saveTemplateNewItem($pPageType,$pValue,$pOwner)
{
	
	$pValue = reformat($pValue) ;
	
	$tab = explode("-@-",$pValue) ;
	$dom =new DOMDocument();
	$dom->load($tab[0]);
	
	switch($pPageType)
	{
		case 'content':
			$node =  $dom->getElementsByTagName("framesets"); $node = $node->item(0);
			break;
			
		case 'page':
			$node =  $dom->getElementsByTagName("pages"); $node = $node->item(0);
			break;
		
		case 'token':
			$node =  $dom->getElementsByTagName("tokens"); $node = $node->item(0);
			break;
		
		case 'script':
			$node =  $dom->getElementsByTagName("scripts"); $node = $node->item(0);
			break;
		
		case 'style':
			$node =  $dom->getElementsByTagName("styles"); $node = $node->item(0);
			break;
			
		case 'frameset':
			$node =  $dom->getElementsByTagName("framesets"); $node = $node->item(0);
			break;
	}
	
	try
	{	
		$item = $dom->createElement($pPageType);
		
		$string = str_replace('-0-','?', $tab[2]);
		$string = str_replace('-1-','#', $string);
		$string = str_replace('-2-','%', $string);
		$string = str_replace('-3-','&amp;', $string);
		
		$item->setAttribute("id",$tab[1]) ;
		$item->nodeValue = $string ;
		$node->appendChild($item) ;

		$dom->save($tab[0]);
		
		return "New item saved !" ;
	}
	catch(Exception $ex)
	{
		return "Error when saving" ;
	}
}


function createCrop($pOwner,$pPath)
{
	copy( OWN . "/Root/Dummies/1.xml", $pPath ) ;
	
return 'One new crop created but without data !';
}

function saveCrop($pOwner, $pId, $pData,$pKey)
{

	$pData = reformat($pData) ;
	
	try
	{
		$tab = explode("|",$pId) ;
		$dom =new DOMDocument();
		$cropName = './Owners/'.$pOwner.'/Contents/'.$tab[0].".xml" ;
		
		if(is_file($cropName))
			{
				$dom->load($cropName);
				
				$nodes = $dom->getElementsByTagName("fields") ;
				$flg = false;
				foreach($nodes->item(0)->childNodes as $v)
				{
					if($v->nodeType != XML_TEXT_NODE)
					{
						if($v->getAttribute("number")==@$tab[1])
						{
							foreach($v->childNodes as $u)
							if($u->nodeType != XML_TEXT_NODE && $u->nodeName=="content")
							{
								$u->nodeValue = $pData ;$flg=true;
							}
						}
					}
				}
				$dom->save($cropName) ;
				return $flg ? 'Crop updated !' : 'Crop missing reference(s) !' ;
			} else {
				createCrop($pOwner,$cropName) ;
			}
	} 
	catch(Exception $ex)
	{
		return $ex;
	}
}

function saveNewCrop($pOwner,$pValue)
{
	try
	{
		$tab = explode("-@-",$pValue) ;
		
		if(!is_file('Owners/'.(@$_REQUEST["entry"]?$_REQUEST["entry"]:'Dummy').'/Contents/'.$tab[0]))
		{
			
			$dom =new DOMDocument("1.0");
		
			$root = $dom->createElement("fields");
				$root->setAttribute("count", 0) ;
	
			$topic = $dom->createElement("topic");
				$topic->nodeValue = $tab[1];
			$scope = $dom->createElement("scope");
				$scope->nodeValue = $tab[2];
			
				$root->appendChild($topic) ;
				$root->appendChild($scope) ;
			
				$dom->appendChild($root) ;
				
			$dom->save('./Owners/'.$pOwner.'/Contents/'.$tab[0].".xml");
		
			return "New crop created !" ;
		} else {
			return "Crop already exists !" ;
		}
	}
	catch(Exception $ex)
	{
		return "Error when saving" ;
	}
}

function saveUser($pOwner,$pValue,$pKey,$pStatus)
	{
		require_once CLA . "/dataManager.class.php" ;
		require_once CLA . "/utilities.class.php" ;
		
		$_manager = new dataManager() ;
		//
		$_manager->data['path'] =  $_manager->data['root']  . $pOwner . "/Contents/user.xml" ; //
		
		$_manager->data['query_1'] = array(
					array("attribute"=>"key", "value"=>$pKey)
					) ;
		
		$_res = utilities::getLineByAttribute($_manager->initialize(), $_manager->data['query_1']) ;
		
		$flg=false;
		
//var_dump($_res);
		if(isset($_res["login"]))
		{
			$flg = true;
		}
		
		$tab = explode(",", $pValue) ;
		
		//TODO
		switch ($pStatus)
		{
			case 1 :
				
				if($flg)
				{
					$dom = new DOMDocument() ;
					$userFile = "./Owners/" . $pOwner . "/Contents/user.xml" ;
					$dom->load($userFile) ;
					
					$root = $dom->getElementsByTagName("users");
					$i=0;
					
					$nodes = $root->item(0)->childNodes ;
					
					$nodes->item($_REQUEST["item"])->setAttribute("login",$tab[0]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("pass",$tab[1]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("forname",$tab[2]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("lastname",$tab[3]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("customer",$tab[4]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("key",md5($tab[2])) ;
				
					$dom->save($userFile) ;
					
					return 'User updated';
					
				}else{
					
					return 'Nothing has been done';
				
				}
				break;
				
			case 2 :
				
				if($flg)
				{
					try
					{
						$dom = new DOMDocument() ;
						$userFile = "./Owners/" . $pOwner . "/Contents/user.xml" ;
						$dom->load($userFile) ;
						
						$root = $dom->getElementsByTagName("users");
						
						$new = $dom->createElement("user");
						
						$new->setAttribute("login",$tab[0]);
						$new->setAttribute("pass",$tab[1]);
						$new->setAttribute("forname",$tab[2]);
						$new->setAttribute("lastname",$tab[3]);
						$new->setAttribute("customer",$tab[4]);
						$new->setAttribute("key",md5($tab[2]));
						
						$root->item(0)->appendChild($new);
						
						$dom->save($userFile);
						
						return 'New user add !' ;
					} 
					catch (Exception $ex) {
						
						return $ex ;
					}

				
				}else{

					return 'Nothing has been done' ;
				
				}
				break;
		}
	}

function saveFrameset($pOwner,$pItem,$pValue,$pKey)
{
		require_once CLA . "/dataManager.class.php" ;
		require_once CLA . "/utilities.class.php" ;
		
		$_manager = new dataManager() ;
		//
		$_manager->data['path'] =  $_manager->data['root']  . $pOwner . "/Contents/user.xml" ; //
		
		$_manager->data['query_1'] = array(
					array("attribute"=>"key", "value"=>$pKey)
					) ;
		
		$_res = utilities::getLineByAttribute($_manager->initialize(), $_manager->data['query_1']) ;
		
		$flg=false;
//var_dump($_res);
		if(isset($_res["login"]))
		{
			$flg = true;
		}
		
		if($flg)
		{
			$dom = new DOMDocument() ;
			$pageFile = "./Owners/" . $pOwner . "/Contents/page.xml" ;
			$dom->load($pageFile) ;
			
			$root = $dom->getElementsByTagName("pages") ;
			$i=0;
			
			$nodes = $root->item(0)->childNodes ;
			
			$nodes->item($_REQUEST["item"])->setAttribute("frameset",$pValue) ;
			
			$dom->save($pageFile) ;
			
			return 'Page updated !' ;
			
		}
}

function savePage($pOwner,$pValue,$pKey,$pStatus)
	{
		require_once CLA . "/dataManager.class.php" ;
		require_once CLA . "/utilities.class.php" ;
		
		$_manager = new dataManager() ;
		//
		$_manager->data['path'] =  $_manager->data['root']  . $pOwner . "/Contents/user.xml" ; //
		
		$_manager->data['query_1'] = array(
					array("attribute"=>"key", "value"=>$pKey)
					) ;
		
		$_res = utilities::getLineByAttribute($_manager->initialize(), $_manager->data['query_1']) ;
		
		$flg=false;
//var_dump($_res);
		if(isset($_res["login"]))
		{
			$flg = true;
		}
		
		$tab = explode(",", $pValue) ;
		
		//TODO
		switch ($pStatus)
		{
			case 1 :
				
				if($flg)
				{
					$dom = new DOMDocument() ;
					$pageFile = "./Owners/" . $pOwner . "/Contents/page.xml" ;
					$dom->load($pageFile) ;
					
					$root = $dom->getElementsByTagName("pages");
					$i=0;
					
					$nodes = $root->item(0)->childNodes ;
					
					$nodes->item($_REQUEST["item"])->setAttribute("number",$tab[0]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("id",$tab[1]) ;
					$nodes->item($_REQUEST["item"])->setAttribute("entry",$tab[2]) ;
					
					$dom->save($pageFile) ;
					
					return 'Page updated';
					
				}else{
					
					return 'Nothing has been done';
				
				}
				break;
				
			case 2 :
				
				if($flg)
				{
					try
					{
						$dom = new DOMDocument() ;
						$pageFile = "./Owners/" . $pOwner . "/Contents/page.xml" ;
						$dom->load($pageFile) ;
						
						$root = $dom->getElementsByTagName("pages");
						
						$new = $dom->createElement("page");
						
						$new->setAttribute("number",$tab[0]);
						$new->setAttribute("id",$tab[1]);
						$new->setAttribute("entry",$tab[2]);
						
						$root->item(0)->appendChild($new);
						
						$dom->save($pageFile);
						
						return 'New page add !' ;
					} 
					catch (Exception $ex) {
						
						return $ex ;
					}

				
				}else{

					return 'Nothing has been done' ;
				
				}
				break;
		}
	}
	
function getItemsByName($pNodeName, $pDocumentName)
	{
		$dom = new DOMDocument() ;
		$dom->load($pDocumentName) ;
		
	return $dom->getElementsByTagName($pNodeName) ;
	}

function getNodeList($pNodeName,$pPath)
/*
 *  Lists all the files in a directory
 */
	{
	
	return xml2phpArray(simplexml_load_string(file_get_contents($pPath)),array()); 
	}
	
function getAttribute($node)
{// >((dom)node) ((array)tab)>

    $tab=array() ;
    foreach($node->attributes() as $k1->$v1)
    {
		$tab[$k1->{''}->name]=$k1->{''}->value ;
    }
	
  return $tab;
 }//

function xml2phpArray($xml,$arr){ 
    $iter = 0; 
        foreach($xml->children() as $b){ 
                $a = $b->getName(); 
                if(!$b->children()){ 
                        $arr[$a] = trim($b[0]); 
                } 
                else{ 
                        $arr[$a][$iter] = array(); 
                        $arr[$a][$iter] = xml2phpArray($b,$arr[$a][$iter]); 
                } 
        $iter++; 
        } 
        return $arr; 
}

function print_r_html($data,$return_data=false)
/*
 * Print_r_htl modified to return array
 */
	{
	    $data = print_r($data,true) ;
	    $data = str_replace( " ","&nbsp;", $data) ;
	    $data = str_replace( "\r\n","<br/>\r\n", $data) ;
	    $data = str_replace( "\r","<br/>\r", $data) ;
	    $data = str_replace( "\n","<br/>\n", $data) ;
	 
	    if (!$return_data)
	        return $data ;
	    else
	        return $data ;
	}
	
	
?>