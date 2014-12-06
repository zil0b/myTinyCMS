<?php 

	class contentPane
	/*
	 * this class contains the mod menu treats
	 */
		{
			public $data ;
			
			public function setFeed()
			/*
			 * only letters and numbers can be placed in data feed name data["abc.."]
			 */
				{

					$this->data["menuPane"] =  '
					
				         <div class="panel">
				         
	                        <span onclick="showHide($(\'cMenu\'),1);collapse(this);" class="click">[+]</span>
	                        <span id="cMenu" class="click">  <a href="'.$_SERVER['PHP_SELF'].'?entry='.(@$_REQUEST["entry"]?$_REQUEST["entry"]:'Dummy').'&crop=1">!.crop</a> | <a href="'.$_SERVER['PHP_SELF'].'?entry='.(@$_REQUEST["entry"]?$_REQUEST["entry"]:'Dummy').'&page=1">!.page</a>
	                            <p />
	                            
	                            	<form action="" method="post" name="form1">
	                            		
										{{loginPane}}
										
									</form>
	                        
								{{cropPane}}
								{{userPane}}
		                    	{{pagePane}}	
									
		                    	{{templatePane}}
		                    	{{objectPane}}
								
	                        </span>
	                        
	                    </div>
	                    
						' ;
					
					$this->data["loginPane"] = '
					
						<span onclick="showHide($(\'loginPane\'),2);">:: Login Pane ::</span>
	                   <span>
		                   <div>
	                          	
	                            	<input id="iLogin" name="iLogin" type="text" value=" " style="width:96%;" /><br/>
	                            	<input id="iButton" type="submit" value="log on"  style="width:100%;" />
	                            
	                       </div>
                       </span>
                       ' ;
					
				}
			
			public function setTagFeed($pTag,$pFeed)
				{
					$this->data[$pTag] = $pFeed ;
				}
			
			public function contentPane()
				{
						$this->init(); 
				}
			
			public function placePane()
				{
					preg_match_all('/{{[a-z0-9A-Z]*}}/',$this->data["menuPane"],$matches) ;

					foreach($matches[0] as $v)
						{
							$v = str_replace(array('{','[','}',']'),array('',' ','',''),$v);
							if(!$this->special($v))
								{
									$this->data["menuPane"] = preg_replace('/{{'.$v.'}}/',@$this->data[$v],$this->data["menuPane"])	;
								}
						}
				}
			
			public function special($pTag)
				{
				
				return false ;
				}
				
			public function tagReserved()
				{
					switch (true)
						{
							case (isset( $_SESSION["autho"]['credit'])) :
								$this->data["loginPane"] = "<div>
									<br/>
									<input type='submit' value='unlog' name='iUnlog' style='width:96%;' />
								</div>" ;
								
								# CROP PANE
																
								// set the crop list
								$tmp = getFileList($_SESSION["path"] . "/Contents") ;
								
								$this->data["cropPane"] = "<p/><div class='dark' onclick='showHide($(\"cCropPane\"),1)' style='cursor:pointer;'>:: Crop pane ::</div><p/><div id='cCropPane'>" ;
								foreach($tmp as $v)
									{
										$this->data["cropPane"] .= '<a href="
											' . ($_SERVER["PHP_SELF"]) . '?entry=' . @$_REQUEST["entry"] . '&crop=' . $v . '">' . substr($v,8,((int)strlen($v))-8) . '</a><br />' ;
									}
								$this->data["cropPane"] .= "</div>" ;
								
								# USER PANE
								$this->data["userPane"] = "<p/><a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&user=1'>[ users ]</a> ";
								
								# PAGE PANE
								$this->data["pagePane"] = "<a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&page=1'>[ pages ]</a>";
								
								# TEMPLATE PANE
								// set the template list
								$tmp = getFileList($_SESSION["path"] . "/Templates") ;
								$this->data["templatePane"] = "<p/><div class='dark' onclick='showHide($(\"cTemplatePane\"),1)' style='cursor:pointer;'>:: Template pane ::</div><p/><div id='cTemplatePane'>" ;
								foreach($tmp as $v)
									{
										$this->data["templatePane"] .= '<a href="
										' . ($_SERVER["PHP_SELF"]) . '?entry=' . @$_REQUEST["entry"] . '&template=' . $v . '">' . substr($v,8,((int)strlen($v))-8) . '</a><br />' ;
									}
								$this->data["templatePane"] .= "</div>" ;
								
								# OBJECT PANE
								// set the template list
								$tmp = getObjectsList($_SESSION["path"] . "/Objects") ;
								$this->data["objectPane"] = "<p/><div class='dark' onclick='showHide($(\"cObjectPane\"),1)' style='cursor:pointer;'>:: Object pane ::</div><p/>
								<a href='".($_SERVER["PHP_SELF"]) . '?entry=' . @$_REQUEST["entry"] . "&object=1'>upload</a> | 
								<a href='".($_SERVER["PHP_SELF"]) . '?entry=' . @$_REQUEST["entry"] . "&object=2'>filing</a><p/>
								<div id='cObjectPane'>" ;
								
								if(count($tmp)>0&&$tmp!='')
									foreach($tmp as $v)
										{
											$this->data["objectPane"] .= substr($v,8,((int)strlen($v))-8) . '<br />' ;
										}
								
								$this->data["objectPane"] .= "</div>" ;
								
								break ;
						}
				}
			
			public function getObjectPane()
				{
					
					return "<div class='bottomTool' style=''>
					
						<div onclick='showHide($(\"cObjectPanel\"));collapse(this);' class='click'>[_]</div>
						<div id='cObjectPanel'>
							
							<form method='POST' action='' enctype='multipart/form-data'>
								 <!-- On limite le fichier à 100Ko -->
								 <input type='hidden' name='MAX_FILE_SIZE' value='100000'>
								 <input type='file' name='flz'>
								 <input type='submit' name='envoyer' value='send' style='width:50px;'>
							</form>
						
					</div></div>
					";
					
				}
			
			public function getFilerPane()
				{
						return "
						
						<script>
						
							function segmentContent(pStr)
	                            /**
	                            
	                            */
	                            {
	                                var reg=new RegExp('-@-', 'g') ;
	                                
	                            return pStr.split(reg) ;
	                            }
	                            
							function getFileOptions(pTarget)
								{
									new Ajax.Request('./main.php',
	                                {
	                                    method:'post',
	                                    parameters:'qry=16&target=' + pTarget.value,
	                                    onSuccess: function(transport)
	                                    {
	                                    	$('cCropSelect').innerHTML = transport.responseText ;
	                                    	
	                                    },
	                                    onFailure: function(){ 
											//alert('error');
										},
	                                    onComplete: function() 
	                                    {
											
										}
	                                });
								}
							
							function getFileContent(pPath,pFileName)
								{
									if(pFileName.substring(0,2)!='[]') // check if is a directory
									{
										new Ajax.Request('./main.php',
		                                {
		                                    method:'post',
		                                    parameters:'qry=17&item=' + pPath + pFileName,
		                                    onSuccess: function(transport)
		                                    {
		                                    	var nfo = segmentContent(transport.responseText) ;
		                                    	$('cTextSubject').value = nfo[0] ;
		                                        $('cTextContent').value = nfo[1] ;
		                                    },
		                                    onFailure: function(){ }
		                                });
	                                }
								}
							
							String.prototype.multiReplace = function ( replacements ) {
								var str = this;
								for (i = 0; i < replacements.length; i++ ) {
									str = str.replace(replacements[i][0], replacements[i][1]);
								}
								return str;
							};
							
							function saveFileItem()
								{
									var tab = $('cTopic').value
										+'-@-'
										+$('cTextSubject').value
										+ '-@-'
										+$('cTextContent').value;
									
									var replacements = [
										[/\?/g, '-0-'],
										[/\#/g, '-1-'],
										[/\%/g, '-2-'],
										[/&/g, '-3-']
									]
									
									tab = tab.multiReplace(
										replacements
									);
									
//alert(tab);
									
									new Ajax.Request('./main.php',
		                                {
		                                    method:'post',
		                                    parameters:'qry=23&owner='+$('customerName').value+'&value='+tab,
		                                    onSuccess: function(transport)
		                                    {
		                                        $('cComCrop').innerHTML = transport.responseText ;
		                                    },
		                                    onFailure: function(){ }
		                                });
								}
								
						</script>
						
						<div class='bottomTool' style=''>
							
							<form action='' method='post' name='frm2'>
							
							<div onclick='showHide($(\"cCropPanel\"));collapse(this);' class='click'>[_]</div>
							<div id='cCropPanel'>
							
								<table width='600'>
								
									<tr>
										<td width='50'>Id</td>
										<td width='500' id='cCropId'></td>
									</tr>
									<tr>
										<td>Target</td>
										<td>
										
											<input style='width:87%;font-size:11px;font-family:verdana;' id='cTopic' type='text' value='".@$this->data["list"]["target"]."' />
											<b><a style='cursor:pointer;' onclick='getFileOptions($(\"cTopic\"));'>[ go ]</a></b>
										
										</td>
									</tr>
									
									<tr>
										
										<td>Item</td>
										<td>
											<select class='encoder' id='cCropSelect' onchange='getFileContent($(\"cTopic\").value,this.value,\"\");'>
												
											</select>
										</td>
										
									</tr>
									
									<tr>
									
										<td colspan='2'>
																			
											<p>:: Filer Item ::  <span class='specButton' onclick='saveFileItem()'>[ save ]</span></span>
											
											<span id='cComCrop' class='green'></span>
											
											</p>
											<input type='text' value='' id='cTextSubject' class='encoder'/>
											<textarea id='cTextContent' rows='10' class='encoder'></textarea>
											
										</td>
									
									</tr>
								</table>
							</div>						
						
						
						</form></div>
						";
				}
				
			public function getNewCrop()
				{

						return "
						
						<script>
						
							function saveNewCrop()
								{
									var tab = $('cCropId').value
										+ '-@-'
										+$('cTopic').value
										+'-@-'
										+$('cScope').value;
										
									new Ajax.Request('./main.php',
	                                {
	                                    method:'post',
	                                    parameters:'qry=22&value=' + tab + '&owner=" . $_REQUEST["entry"] . "',
	                                    onSuccess: function(transport)
	                                    {
	                                        $('cComCrop').innerHTML = transport.responseText ;
	                                    },
	                                    onFailure: function(){ }
	                                });
								}
								
						</script>
						
						<div class='bottomTool' style=''>
							
							<form action='' method='post' name='frm2'>
						
							<div onclick='showHide($(\"cCropPanel\"));collapse(this);' class='click'>[_]</div>
							<div id='cCropPanel'>
							
								<table width='600'>
								
									<tr>
										<td width='50'>Id</td>
										<td width='500'>
											<input type='text' value='' class='encoder' id='cCropId'/>
										</td>
									</tr>
									<tr>
										<td>Topic</td>
										<td><input class='encoder' id='cTopic' type='text' value='' /></td>
									</tr>
									<tr>
										<td>Scope</td>
										<td><input class='encoder' id='cScope' type='text' value=''/></td>
									</tr>
									
									<tr>
										<td colspan='2'>
											<p><span class='specButton' onclick='saveNewCrop();/* location.href=\"".($_SERVER['PHP_SELF'].'?'."entry=".$_REQUEST['entry'])."\";*/'><b>[ save ]</b></span> <span id='cComCrop' class='green'></span></p>
										<td>
									</tr>
									
								</table>
							</div>						
						
						
						</form></div>
						";
					
				}
				
			public function getTemplatePane($pTemplate, $pType)
				{

						return "
						
						<script>
							 function segmentContent(pStr)
	                            /**
	                            
	                            */
	                            {
	                                var reg=new RegExp('-@-', 'g') ;
	                                
	                            return pStr.split(reg) ;
	                            }
							function getTemplateContent(item , templateName, nodeName)
								{
									
									new Ajax.Request('./main.php',
	                                {
	                                    method:'post',
	                                    parameters:'qry=40&itemValue='+item+'&template=' + templateName + '&nodeName='+ nodeName,
	                                    onSuccess: function(transport)
	                                    {
	                                    	var nfo = segmentContent(transport.responseText) ;
	                                    	$('cTextSubject').value = nfo[0] ;
	                                        $('cTextContent').value = trim(nfo[1]) ;
	                                    },
	                                    onFailure: function(){ }
	                                });

								}
							String.prototype.multiReplace = function ( replacements ) {
								var str = this;
								for (i = 0; i < replacements.length; i++ ) {
									str = str.replace(replacements[i][0], replacements[i][1]);
								}
								return str;
							};
							
							function saveNewItemTemplate(pPageType)
								{
									var tab = $('cFile').innerHTML
										+'-@-'
										+$('cTextSubject').value
										+'-@-'
										+$('cTextContent').value ;
										
//alert(tab);
									
									var replacements = [
										[/\?/g, '-0-'],
										[/\#/g, '-1-'],
										[/\%/g, '-2-'],
										[/&/g, '-3-']
									]
									
									tab = tab.multiReplace(
										replacements
									);
									
									new Ajax.Request('./main.php',
		                                {
		                                    method:'post',
		                                    parameters:'qry=42&owner='+$('customerName').value+'&pageType='+pPageType+'&value=' + tab,
		                                    onSuccess: function(transport)
		                                    {
		                                        $('cComTemplate').innerHTML = transport.responseText ;
		                                    },
		                                    onFailure: function(){ },
											onComplete:function()
											{
												location.href=document.URL;
											}
		                                });
								}
								
							function saveTemplateItem(pPageType)
								{
									var tab = $('cFile').innerHTML
										+'-@-'
										+$('cTextSubject').value
										+'-@-'
										+reformat($('cTextContent').value);

									var replacements = [
										[/\?/g, '-0-'],
										[/\#/g, '-1-'],
										[/\%/g, '-2-'],
										[/&/g, '-3-']
									]
									
									tab = tab.multiReplace(
										replacements
									);
									
									new Ajax.Request('./main.php',
		                                {
		                                    method:'post',
		                                    parameters:'qry=41&owner='+$('customerName').value+'&pageType='+pPageType+'&value=' + tab,
		                                    onSuccess: function(transport)
		                                    {
		                                        $('cComTemplate').innerHTML = transport.responseText ;
		                                    },
		                                    onFailure: function(){ }
		                                });
								}
								
						</script>
						
						<div class='bottomTool' style=''>
							
							<form action='' method='post' name='frm2'>
							
							<div onclick='showHide($(\"cTemplatePanel\"));collapse(this);' class='click'>[_]</div>
							<p/>
							<div id=''>
							
								<a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&template=".$_REQUEST['template']."&pageType=style'>[ style ]</a> 
								<a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&template=".$_REQUEST['template']."&pageType=script'>[ script ]</a>
								<a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&template=".$_REQUEST['template']."&pageType=page'>[ page ]</a>
								<a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&template=".$_REQUEST['template']."&pageType=token'>[ token ]</a>
								<a href='".$_SERVER['PHP_SELF']."?entry=".$_REQUEST['entry']."&template=".$_REQUEST['template']."&pageType=frameset'>[ frameset ]</a>
								
							</div>
							<p/>
							<div id='cTemplatePanel'>
							
								<table width='600'>
								
									<tr>
										<td width='50'>File</td>
										<td width='500' id='cFile'>".$_REQUEST["template"]."</td>
									</tr>

									<tr>
										
										<td>Item</td>
										<td>
											<select class='encoder' id='cTemplateSelect' onchange='getTemplateContent(this.value,\"".@$_REQUEST['template']."\",\"".@$_REQUEST["pageType"]."\");'>
												" . getTemplateOptions(isset($_REQUEST['pageType'])?$_REQUEST['pageType']:'script', $_REQUEST['template']) . "
											</select>
										</td>
										
									</tr>
									
									<tr>
									
										<td colspan='2'>
																			
											<p>:: Template Item ::  <span class='specButton' onclick='saveTemplateItem(\"".@$_REQUEST['pageType']."\")'>[ save</span> | <span class='specButton' onclick='saveNewItemTemplate(\"".@$_REQUEST['pageType']."\");'>new ]</span>
											
											<span id='cComTemplate' class='green'></span>
											
											</p>
											<input type='text' value='' id='cTextSubject' class='encoder'/>
											<textarea id='cTextContent' rows='10' class='encoder'></textarea>
											
										</td>
									
									</tr>
								</table>
							</div>						
						
						</form>
						
						</div>
						";
				}
			
			public function getCropPane($pCrop)
				{
					
						$this->getFieldList("field",$pCrop) ;
						
						// set crop dropdownlist
						
						$lst = "<select>" ;$i=1;
						foreach($this->data["list"] as $k=>$v)
						{
							$lst .= "<option value='".$i++."'></option>" ;
						}
						
						$lst .= "</select>" ;
						
						return "
						
						<script>
						
							function segmentContent(pStr)
	                            /**
	                            
	                            */
	                            {
	                                var reg=new RegExp('-@-', 'g') ;
	                                
	                            return pStr.split(reg) ;
	                            }
	                            
							function setNodes(pNode)
								{
	                               var dataNodes = _.oa.getNodesWithId(pNode,'|');
	                               
	                               for(i=0;i<dataNodes.length;i++)
	                                {
	                                	var dataContent = reformat(dataNodes[i].innerHTML);
	                                    new Ajax.Request('./main.php',
	                                    {
	                                        method:'post',
	                                        parameters:'qry=30&id='+dataNodes[i].id+'&owner='+$('customerName').value+'&data='+dataContent+'&key='+$('userKey').value,
	                                        onSuccess: function(trs)
	                                        {
	                                        	$('cComCrop').innerHTML = trs.responseText;
	                                        },
	                                        onFailure:function() {}
	                                    });
	                                }
								
								}
						
							function getCropContent(itemValue,cropName)
								{
									new Ajax.Request('./main.php',
	                                {
	                                    method:'post',
	                                    parameters:'qry=15&item=' + itemValue + '&crop=' + cropName,
	                                    onSuccess: function(transport)
	                                    {
	                                    	var nfo = segmentContent(transport.responseText) ;
	                                    	$('cTextSubject').value = nfo[0] ;
	                                        $('cTextContent').value = nfo[1] ;
	                                    },
	                                    onFailure: function(){ }
	                                });
								}
						
							function saveNewItemCrop()
								{
									var tab = $('cCropSelect').value+'-@-'+$('cCropId').innerHTML
										+ '-@-'
										+$('cTopic').value
										+'-@-'
										+$('cScope').value
										+'-@-'
										+$('cTextSubject').value
										+'-@-'
										+$('cTextContent').value ;
										
									var replacements = [
										[/\?/g, '-0-'],
										[/\#/g, '-1-'],
										[/\%/g, '-2-'],
										[/&/g, '-3-']
									]
									
									tab = tab.multiReplace(
										replacements
									);
									
									new Ajax.Request('./main.php',
		                                {
		                                    method:'get',
		                                    parameters:'qry=21&owner='+$('customerName').value+'&value=' + tab,
		                                    onSuccess: function(transport)
		                                    {
		                                        $('cComCrop').innerHTML = transport.responseText ;
		                                    },
		                                    onFailure: function(){ },
											onComplete:function()
											{
												location.href=document.URL;
											}
		                                });
								}
							String.prototype.multiReplace = function ( replacements ) {
								var str = this;
								for (i = 0; i < replacements.length; i++ ) {
									str = str.replace(replacements[i][0], replacements[i][1]);
								}
								return str;
							};
							
							function saveCropItem()
								{
									var tab = $('cCropSelect').value+'-@-'+$('cCropId').innerHTML
										+ '-@-'
										+$('cTopic').value
										+'-@-'
										+$('cScope').value
										+'-@-'
										+$('cTextSubject').value
										+'-@-'
										+reformat($('cTextContent').value);
										
									var replacements = [
										[/\?/g, '-0-'],
										[/\#/g, '-1-'],
										[/\%/g, '-2-'],
										[/&/g, '-3-']
									]
									
									tab = tab.multiReplace(
										replacements
									);
									
									new Ajax.Request('./main.php',
		                                {
		                                    method:'get',
		                                    parameters:'qry=20&owner='+$('customerName').value+'&value=' + tab,
		                                    onSuccess: function(transport)
		                                    {
		                                        $('cComCrop').innerHTML = transport.responseText ;
		                                    },
		                                    onFailure: function(){ }
		                                });
								}
								
						</script>
						
						<div class='bottomTool' style=''>
							
							<form action='' method='post' name='frm2'>
							
							<div onclick='showHide($(\"cCropPanel\"));collapse(this);' class='click'>[_]</div>
							<div id='cCropPanel'>
							
								<table width='600'>
								
									<tr>
										<td width='50'>Id</td>
										<td width='500' id='cCropId'>".$_REQUEST["crop"]."</td>
									</tr>
									<tr>
										<td>Topic</td>
										<td><input class='encoder' id='cTopic' type='text' value='".@$this->data["list"]["topic"]."' /></td>
									</tr>
									<tr>
										<td>Scope</td>
										<td><input class='encoder' id='cScope' type='text' value='".@$this->data["list"]["scope"]."'/></td>
									</tr>
									
									<tr>
										
										<td>Item</td>
										<td>
											<select class='encoder' id='cCropSelect' onchange='getCropContent(this.value,\"".$_REQUEST['crop']."\");'>
												".getCropOptions($_REQUEST['crop'])."
											</select>
										</td>
										
									</tr>
									
									<tr>
									
										<td colspan='2'>
																			
											<p>:: Crop Item ::  <span class='specButton' onclick='saveCropItem()'>[ save</span> | <span class='specButton' onclick='saveNewItemCrop();'>new ]</span>
											
											<span id='cComCrop' class='green'></span>
											
											</p>
											<input type='text' value='' id='cTextSubject' class='encoder'/>
											<textarea id='cTextContent' rows='10' class='encoder'></textarea>
											
										</td>
									
									</tr>
								</table>
							</div>						
						
						
						</form></div>
						";
				}

			public function getFieldList($pNodeName,$pCrop)
				{
					
					$this->data["list"] = getNodeList($pNodeName,$pCrop) ;
					
				}
				
			public function getPagePane( )
				{
						return "<div class='bottomTool'><span onclick='showHide($(\"cPagePanel\"));collapse(this);' class='click'>[_]</span>
						<div id='cPagePanel'><p>:: Page Pane :: </p>
						"
							. getPagesPanel($_REQUEST["entry"]) .
						"
						
						</div>
						</div>";
				}
				
			public function getUserPane( )
				{
					
					$dom = new DOMDocument() ;
					$dom->load("./Owners/" . (@$_REQUEST["entry"]?$_REQUEST["entry"] : 'Dummy'). '/Contents/user.xml') ;
					
					$users = $dom->getElementsByTagName("users") ;
					
					$val = "
					
					<script>
					
						function userEdit(pVal)
						/*
							edit the user line
						*/
							{

								var tab =[] ;
							
								if($('login_'+pVal).innerHTML.substring(0,1) == '<')
									{
										$('login_'+pVal).innerHTML = tab[0] = $('iLog_'+pVal).value ;
										$('pass_'+pVal).innerHTML = tab[1] = $('iPas_'+pVal).value ;
										$('forname_'+pVal).innerHTML = tab[2] = $('iFor_'+pVal).value ;
										$('lastname_'+pVal).innerHTML = tab[3] = $('iLas_'+pVal).value ;
										$('customer_'+pVal).innerHTML = tab[4] = $('iCus_'+pVal).value ;
										
										$('b'+pVal).innerHTML = '[edit]';
										
										new Ajax.Request('./main.php',
		                                {
		                                    method:'get',
		                                    parameters:'qry=25&item=' + pVal + '&value=' + tab + '&owner=' + $('customerName').value + '&key='+$('userKey').value,
		                                    onSuccess: function(transport)
		                                    {
		                                        $('cComUser').innerHTML = transport.responseText ;
												location.href='".($_SERVER['PHP_SELF'].'?'."entry=".$_REQUEST['entry'])."&user=1';
		                                    },
		                                    onFailure: function(){ }
		                                });
									}
								else
									{
										$('login_'+pVal).innerHTML = \"<input id='iLog_\"+pVal+\"' type='text' value='\"+ ($('login_'+pVal).innerHTML) +\"' />\" ;
										$('pass_'+pVal).innerHTML = \"<input id='iPas_\"+pVal+\"' type='text' value='\"+ ($('pass_'+pVal).innerHTML) +\"' />\" ;
										$('forname_'+pVal).innerHTML = \"<input id='iFor_\"+pVal+\"' type='text' value='\"+ ($('forname_'+pVal).innerHTML) +\"' />\" ;
										$('lastname_'+pVal).innerHTML = \"<input id='iLas_\"+pVal+\"' type='text' value='\"+ ($('lastname_'+pVal).innerHTML) +\"' />\" ;
										$('customer_'+pVal).innerHTML = \"<input id='iCus_\"+pVal+\"' type='text' value='\"+ ($('customer_'+pVal).innerHTML )+\"' />\" ;
										
										$('b'+pVal).innerHTML = '[save]';
										
									}
							}
						
						function saveNew()
							{
								var tab = [] ;
								tab[0] = $('iLogNew').value;
								tab[1] = $('iPasNew').value;
								tab[2] = $('iForNew').value;
								tab[3] = $('iLasNew').value;
								tab[4] = $('iCusNew').value;

								new Ajax.Request('./main.php',
                                {
                                    method:'get',
                                    parameters:'qry=26&value=' + tab+ '&owner=' + $('customerName').value +'&key=' + $('userKey').value,
                                    onSuccess: function(transport)
                                    {
                                        $('cComUser').innerHTML = transport.responseText ;
										location.href='".($_SERVER['PHP_SELF'].'?'."entry=".$_REQUEST['entry'])."&user=1';
                                         $('new').innerHTML = '';
                                    },
                                    onFailure: function(){ }
                                });
                                
                                ;
							}
						
							function addUser()
							{
								$('new').innerHTML = '<p/><table width=\"600\"><tr class=\"title\"><td onclick=\"saveNew();\" class=\"specButton\"><b>[save]</b></td><td><input type=\"text\" id=\"iLogNew\"/></td><td><input type=\"text\" id=\"iPasNew\"/></td><td><input type=\"text\" id=\"iForNew\"/></td><td><input type=\"text\" id=\"iLasNew\"/></td><td><input type=\"text\" id=\"iCusNew\"/></td></tr></table><p/>' ;
							}

					</script>
					
					<table width='600'>
						<tr class='title'><td/>
						<td>login</td>
						<td>pass</td>
						<td>lastname</td>
						<td>forename</td>
						<td>customer</td>
						</tr>" ;
					
					$i = 0;
					foreach($users->item(0)->childNodes as $v)
					{
						if($v->nodeType != XML_TEXT_NODE)
						{
							if(isset($_SESSION['autho']['admin']))
							{
								$val .= "<tr>
									<td onclick='userEdit(".$i.");'><b id='b".$i."' class='specButton'>[edit]</b></td><td id='login_".$i."'>" . trim($v->getAttribute("login")) . "</td><td id='pass_".$i."'>" . trim($v->getAttribute("pass")) . "</td><td id='lastname_".$i."'>" . trim($v->getAttribute("lastname")) . "</td><td id='forname_".$i."'>" . trim($v->getAttribute("forname")) . "</td><td id='customer_".$i."'>" . trim($v->getAttribute("customer")) ."</td>
									</tr>" ;
							}
						}
						$i++;
					}
					if(!isset($_SESSION['autho']['admin']))
						$val .= "<tr><td colspan='5'>You're not authorized to modify the privileges</td></tr>";
						
					$val .= "</table>";
					
				return "<div class='bottomTool'>" . $val . "<hr /><div id='new'></div><span class='specButton' onclick='addUser();'>[add user]</span> <span id='cComUser' class='green'></span></div>";
				}
			
			public function init()
				{
					$this->setFeed() ;
					$this->tagReserved() ;
					
					$this->placePane() ;
				}
				
		}

# Ouput
		
		$_container["content"] = new contentPane() ;

?>
