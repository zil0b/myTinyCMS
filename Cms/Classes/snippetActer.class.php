<?php

class SnippetActer{//
	protected $_type,$_content,$_stack;
# GET/SET
	public function setType()	{//		list($_1,$_2)=explode('/',$this->_type);		$this->actCode(array($_1,$_2));	}
	public function setContent($pContent)	{//		$this->_content = $pContent;	}
# STATE
	/*	 * act statements	 */	public function actCode($pType)	{//		switch(true)		{			case $pType[0]=="text"&&$pType[0]=="php"://								$this->write();				break;
			case $pType[0]=="text"&&$pType[0]=="javascript":				$this->inject();//				break;
			case $pType[0]=="action"&&$pType[0]=="php":				$this->unstack();$this->act();				break;		}	}
	/*	 * stack statements	 */	public function unstack()	{//		$_this->_stack = explode(";",$this->_content);	}
	/*	 * execute statements	 */	public function act()	{//		foreach($this->_stack as $v)			eval($v);	}
}
?>