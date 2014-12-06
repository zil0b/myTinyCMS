<?php 
class utilities{   public static function getNodes($pDomDoc, $pXpathString)	   /**	    * get the node from DomDoc with XpathString	    */	   {	        $xp = new DOMXPath($pDomDoc);	        $xp->registerNamespace('x', 'http://www.w3.org/1999/xhtml');	        $xp->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');	        $xp->registerNamespace('i18n', 'http://apache.org/cocoon/i18n/2.1');
	        $ret = array();	        $nodes = $xp->query($pXpathString);	        foreach ($nodes as $node)	        {	            array_push($ret, $node);	        }
	   	return count($ret)?$ret:false;		}
	public static function isContent($pPattern, $pFeed)		/**		 * check is pattern in feed		 */		{			return preg_match('/('.$pPattern.')/',$pFeed)?true:false;		}
	public static function searchNodesByContent($pDocument, $pQueries)		/**		 * return node matching request by content		 */		{			$_fields = $pDocument->getElementsByTagName('fields'); $i = 0;
			// ceci est un exemple de mauvais code de service, il ne fonctionne que pour un nombre limité de structure de crop			// c'est donc un code métier à surcharger.			foreach ($_fields->item(0)->childNodes as $u)			{				if ($u->nodeType != XML_TEXT_NODE)				{					foreach ( $u->childNodes as $v  )					{						if( $v->nodeName == $pQueries['node'] && ! utilities::isContent ( $pQueries['value'] , $v->nodeValue ) )						{							@$tab[++$i] = $u ;						}					}				}			}
			// removing			if(isset($tab))				foreach($tab as $v)					$_fields->item(0)->removeChild($v) ;
			/*	DEPRECIEE - Malheureusement, ce code ne marche une fois appelée par getNodes 			for($i=0; $i<count($pNodeSet); $i++)			{				$_content = $pNodeSet[$i]->childNodes ;				// cette fonction ne descend qu'à un niveau 				foreach($_content as $v)				{					if( $v->nodeName == $pQueries['node'] && ! utilities::isContent ( $pQueries['value'] , $v->nodeValue ) )					{						$pNodeSet[$i]->parentNode->removeChild($pNodeSet[$i]) ;					}				}			}			*/		
		return $pDocument ;		}
	public static function searchNodesByAttribute($pDocument, $pQueries)		/**		 * return node matching request by attribute		 */		{
			$_fields = $pDocument->getElementsByTagName('fields');$i=0;
			// ceci est un exemple de mauvais code de service, il ne fonctionne que pour un nombre limité de structure de crop			// c'est donc un code métier à surcharger.			foreach ($_fields->item(0)->childNodes as $u)			{				if ($u->nodeType != XML_TEXT_NODE)				{					if( !($u->getAttribute($pQueries['attribute']) == $pQueries['value'])  ) // 1:1 match						@$tab[++$i] = $u ;				}			}			
			// removing 			if(isset($tab))				foreach($tab as $v)					$_fields->item(0)->removeChild($v) ;
		return $pDocument ;		}
	public static function getAttributesContents($pNode)		/**		 * 		 */		{			foreach ($pNode->attributes as $attrName => $attrNode)			{			        $_tab[$attrName] = $attrNode->value ;			}
		return $_tab ;		}
	public static function getLineByAttribute($pDocument, $pQueries)		/**		 * return node matching request by attribute		 */		{
			$_users = $pDocument->getElementsByTagName('user');
			// ceci est un exemple de mauvais code de service, il ne fonctionne que pour un nombre limité de structure de crop			// c'est donc un code métier à surcharger.			foreach ($_users as $u)			{				if ($u->nodeType != XML_TEXT_NODE)				{					$_flg = false ;$i=$j=0;					foreach($pQueries as $_qa )					{						if( ($u->getAttribute($_qa['attribute']) == trim($_qa['value']))  )						{ // 1:1 match							++$j;						} else {							--$j;						}						$i++;					}					if($i==$j)						return utilities::getAttributesContents($u) ;				}			}
		return false ;		}
	public static function writeContentByContent($pDocument, $pQueries)		/**		 * write node content matching request by attribute		 */		{
			$_fields = $pDocument->getElementsByTagName('fields');			$_flg = false ;			foreach ($_fields->item(0)->childNodes as $u)				{					if ($u->nodeType != XML_TEXT_NODE)						{							foreach ( $u->childNodes as $v  )								{									if( $v->nodeName == $pQueries['node'] && utilities::isContent ( $pQueries['value'] , $v->nodeValue ) )										{											$v->nodeValue = $pQueries['content'] ;											$_flg = true ;										}								}						}				}
		return $_flg ;		}

	public static function writeContentByAttribute($pDocument, $pQueries)		/**		 * write node content matching request by attribute		 */		{
			$_fields = $pDocument->getElementsByTagName('fields');			$_flg = false ;			foreach ($_fields->item(0)->childNodes as $u)				{
					if ($u->nodeType != XML_TEXT_NODE)						{							if( $u->getAttribute($pQueries['attribute']) == $pQueries['value']  ) // 1:1 match								{									$v = $u->getElementsByTagName($pQueries['node']) ;									$ct = $v->item(0)->ownerDocument->createCDATASection("\n" . $pQueries['replacement'] . "\n");									$v->item(0)->appendChild($ct) ;									$_flg = true ;								}						}				}
			if (!$_flg)				// attribute is note set yet 				{					$node = $_fields->item(0)->firstChild->nextSibling->cloneNode(true) ;					$_fields->item(0)->appendChild($node) ;					// filling id					$_fields->item(0)->lastChild->setAttribute("id", $pQueries['value']) ;					// filling number					$_fields->item(0)->setAttribute("count", $cpt = ((int)$_fields->item(0)->getAttribute("count"))+1) ;					$_fields->item(0)->lastChild->setAttribute("number", $cpt) ;					utilities::writeContentByAttribute($pDocument, $pQueries) ;				}
		return $_flg ;		}	
	public static function deleteContentByContent($pDocument, $pQueries)		/**		 * delete node matching request by attribute		 */		{			$_fields = $pDocument->getElementsByTagName('fields');			$_flg = false ;			foreach ($_fields->item(0)->childNodes as $u)			{				if ($u->nodeType != XML_TEXT_NODE)				{					foreach ( $u->childNodes as $v  )					{						if( $v->nodeName == $pQueries['node'] && utilities::isContent ( $pQueries['value'] , $v->nodeValue ) )						{							$_fields->item(0)->removeChild($u) ;							$_flg = true ;						}					}				}			}
		return $_flg ;		}
	public static function deleteContentByAttribute($pDocument, $pQueries)		/**		 * delete  node matching request by attribute		 */		{	
			$_fields = $pDocument->getElementsByTagName('fields');			$_flg = false ;			foreach ($_fields->item(0)->childNodes as $u)			{				if ($u->nodeType != XML_TEXT_NODE)					if( $u->getAttribute($pQueries['attribute']) == $pQueries['value'] ) // 1:1 match					{						$_fields->item(0)->removeChild($u) ;						$_flg = true ;					}			}
		return $_flg ;		}
}


?>