<?php

class templateControler
{
/**
 * Control the templates
 *
 * @var (mixed) ($domDoc, $data, $stack)
 */
	
	// 
	private $pattern 
		= array(
			'inc'=>'/{{inc:[a-zA-Z]*[\[\]\*a-zA-Z0-9_]*}}/'		// inclusion patterns
			,''=>''
		);
	
	protected $domDoc, $stack;
	
	//
	public $data;
	
	/*
	public function __construct()
	{//
		$this->domDoc = new DOMDocument();
		$this->initialize();
	}
	*/
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $pVar
	 * @return templateControler
	 */
	public function templateControler($pVar)
	{
		$this->domDoc = new DOMDocument();
		$this->setter($pVar);
		$this->initialize();
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $pVar
	 */
	public function setter($pVar)
	{//
		 $this->data = $pVar;
	}
	
// TODO GETTER
	
	public function initialize()
	{//
		$this->domDoc->load( $this->data['path'] );
	}
	
	public function initFrame($pDomValue)
	/**
	 * 
	 */
	{//
		$this->data['frameset'] = trim($pDomValue[0]->nodeValue) ;
	}
	
	/**
	 * Enter description here...
	 *
	 */
	public function getNode($pXPath) 
	{//
		$this->data['feed'] = utilities::getNodes($this->domDoc, $pXPath);
		
	return  $this->data['feed'];
	}
	
	/**
	 * getContent retourne le contenu du/des noeud demandés
	 * 
	 * > (nodeName, nodeId) - (string) >
	 */
	public function getContent($nodeName,$nodeId)
	{//
		// set expression
		$_expr = "//" . $nodeName . ($nodeId&&$nodeId!='*'?"[@id='$nodeId']":"");
		$set = $this->getNode($_expr);
		$content=null;
		if($set)
			foreach($set as $k=>$v)
			{
				$content .= "\r\n" . ($v->nodeValue)."\r\n" ;
			}
		
	return $content?$content:"";
	}
	
	/**
	 * replace anchor by content
	 */
	public function anchorContentReplacer()
	{//
		
		$i=0;
		foreach($this->data['result'][0] as $k=>$v)
		{
			// formatting anchor's syllabes
			$v = str_replace(array('{','[','}',']'),array('',' ','',''),$v);
			$tmp = explode(":",$v);$_ = explode(' ',$tmp[1]);
			
			// replacing
			$this->data['result'][0][$i++] = array("node"=>$_[0],"value"=>$this->getContent($_[0], @$_[1]),"id"=>@$_[1]);
		}
	}
	
	public function setTag($pAnchor,$pValue)
	/**
	 * put the frameset out
	 */
	{
			$this->data['frameset'] = preg_replace('/{{inc:' . $pAnchor . '}}/',$pValue, $this->data['frameset'] ) ;
	}
	
	public function setFrame()
	/**
	 * put the frameset out
	 */
	{
		foreach($this->data['result'][0] as $v)
		{
			if( $v['node'] != 'page' || isset($_REQUEST['admin']))
				$this->data['frameset'] = preg_replace('/{{inc:' . $v['node'] . '[\[\]\*'.$v['id'].']*}}/', $v['value'], $this->data['frameset'] ) ;
			else
			{
//setFrameset(@$_REQUEST['page']); // --> on the entry.inc.php
				$this->data['frameset'] = preg_replace('/{{inc:' . $v['node'] . '[\[\]\*'.$v['id'].']*}}/', getPage(@$_REQUEST['page']?$_REQUEST['page']:$v['id']), $this->data['frameset'] ) ;
			}
		}
	}
	
	public function getAnchors($pFeed)
	{// 
		
		preg_match_all($this->pattern['inc'], $pFeed, $this->data['result']);
		
	return $this->data['result']; // on prefere les données centralisées dans la classe
	}
	
	public function publicTemplate()
	{//
		return $this->domDoc->saveXML();
	}
	
}

?>