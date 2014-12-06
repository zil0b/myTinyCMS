<?php 
/*
 * Backoffices Services 
 * 
 * here comes all the backoffice treatments as 
 * 
 */

function noHtml ($pString)
	{
		$_in = array('/&lt;/','/&gt;/') ;
		$_out = array('<','>') ;
		
	return preg_replace($_in,$_out,$pString) ;
	}
	
function doFlush ()
	{
	    // check that buffer is actually set before flushing
	    if (ob_get_length())
		    {           
		        @ob_flush();
		        @flush();
		        @ob_end_flush();
		    }   
	    @ob_start();
	}
		
function getUserList ()
	{
		
	return $lst ;
	}

function getCustomerEntryList( $pPath, &$_lst=array() )
/*
 * get recursively the files in a given initial path
 */
	{
		$tmp = getFileList( $pPath ) ;
		try
			{
				if ( $tmp )
					foreach ( @$tmp as $k=>$v )
						{
							if( is_dir($v) )
								{
									$_lst[$pPath][$v] = array() ;
									getCustomerEntryList ( $v, $_lst[$pPath] ) ;	
								} 
							else 
								{
									$_lst[$pPath][@++$i] = $v ;
								}
						}
			}
		catch (Exception $ex) {}
	
	return $_lst ;
	}

class adminPage
/*
 * prepare admin page
 */
	{
		
		public $str = "
				
				<div id='editPanel' class='adminMenu'>
					
					<input type='text' id='iCustomer' value=':: add customer ::' onclick='this.value=\"\";' /> 
					<input type='button' value='do' onclick='setContent(\"addUserCom\",\"qry=5&nu=\"+$(\"iCustomer\").value) ;' /> 
					
					<div id='addUserCom' class='green'></div>
					
					<input type='hidden' name='iPath' id='iPath' value='' />
					
					<div id='cMod' style='display:none' ><!-- -->
						
						<textarea id='iMod' rows='10' cols='100' style='width:100%;font-size:10px;font-family:verdana;'>content</textarea>
						<input type='button' value='save' onclick='saveMod();' />
						
					</div>
					
				</div>
				
				<br /><br /><br /><br /><br /><br /><br /><br />
				<br /><br /><br /><br /><br /><br /><br /><br />
				
		";
		
		function deployArray ( $pArray, &$str  )
		/*
		 * 
		 */
			{
//echo "tmp:".count($pArray) ;
				try
					{
						if (is_array($pArray))
						
							foreach( @$pArray as $k=>$v )
								{
									$this->str .= ( preg_match( '/\//', $k ) ? "<p><b>" . ((substr_count($k,'/')>2)?"&nbsp;&nbsp;<span class='dirPath'>" . $k:$k) . "</span></b></p>": "" ) . ( ! is_array( $v ) ? " &nbsp;&nbsp;&nbsp;<a style='cursor:pointer;' onclick='editFileContent(\"$v\");'>[edit]</a> <a href='$v'>" . $v . "</a>" : "" )  . " <br />" ;
									if( count( $v ) )
										$this->deployArray( $v, $this->str ) ;
								}
								
					} catch( Exception $ex ) {}
			}
			
			function prepareAdminPage( $pArray )
			/*
			 * 
			 */
				{
					$tmp = array () ;
					$this->deployArray( $pArray, $tmp ) ;
				}
	}

class cropPage
	{
		public function cropPage()
			{
				
			}

		public function getCropList()
			{
				
			}
	}
	
	
?>