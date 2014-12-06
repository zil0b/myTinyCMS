<?php

class dataManager
{//
	private $pattern;
	protected $stack;	//	public
		$domDoc,
		$crop				= array(					"std"=> '<?xml version="1.0" encoding="UTF-8"?>					<crop><topic /><fields /></crop>'					,""=>''				),
		$data				= array(					"root"=>"./Owners/"				);
# TODO GETTER/SETTER	
	public function setDocument($pPath)		{			$this->domDoc->load( $pPath );		}
	public static function getNodeByData($pData)	/**	 * get the content from data	 */		{			$res = xpathExtension::getNodes($this->domDoc, $pXPath);		return $res['content'];		}
	public function dispatch($pData)	/**	 * switch direction by value	 */		{		}
	public static function getContentByData($pDocument, $pData)	/**	 * get the content from data	 */		{			if(@$pData['attribute'])				{					return utilities::searchNodesByAttribute($pDocument, $pData) ;				}			else				{					return utilities::searchNodesByContent($pDocument, $pData) ;					}		}
	public function setContentByData($pDocument,$pData)	/**	 * set the content to data	 */		{			if(@$pData['attribute'])			{				utilities::writeContentByAttribute($pDocument, $pData) ;			}			else			{				utilities::writeContentByContent($pDocument, $pData) ;			}			$this->saveDocument();		}
	public static function setCropForCustomer($pPath, $pCrop)	/**	 * set crop to customer directory	 */		{			file_put_contents($pPath, trim($pCrop));		}
# STATE	public function dataManager()		{			$this->initialize();					}
	public function initialize()	/**	 * init	 */		{			$this->domDoc = new DOMDocument();			if(@$this->data['path'])				{					$this->domDoc->load( $this->data['path'] );				}
		return $this->domDoc;		}
	public function saveDocument()	/**	 * save document	 */		{			$this->domDoc->save($this->data['path']) ;		}}
?>