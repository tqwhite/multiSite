<?php
namespace Q\Entity;
/**
*
* @Entity
* @Table(name="bookmarks")
* @author tqii
*
*
**/
class Bookmark /*extends Base*/{
	/**
	* @var string $id
	* @column(name="refId", type="string", length=36, nullable=false, unique="true")
	* @Id
	**/
	private $refId;

	/**
	 * @column(type="string", length=300, nullable=false, unique="true")
	 * @var string
	 **/
	private $uri;

	/**
	 * @column(type="string", length=300, nullable=true)
	 * @var string
	 **/
	private $anchor;

	/**
	 * @column(type="string", length=60, nullable=false, unique="true")
	 * @var string
	 **/

	private $shortId;

	/**
	 * @column(type="integer", nullable=true)
	 * @var string
	 **/

	private $accessCount;


	/**
	 * @column(type="datetime", nullable=false)
	 * @var datetime
	 **/

	private $created;

public function __construct(){
	if (!$this->refId){$this->refId =  \Q\Utils::newGuid();}
	$this->created=new \DateTime(date("Y-m-d H:i:s"));
	$this->accessCount=0;
	if (!$this->shortId){$this->shortId =  substr($this->refId, 0, 6);}
}

public function __get($property){
	switch($property){
		case 'created':
			return $this->created->format("Y-m-d H:i:s");
			break;
		default:
			return $this->$property;
			break;
	}
}

public function __set($property, $value){
	switch ($property){
		case 'PLACEHOLDER':
			$this->$property=md5($value);
		break;
		default:
			$this->$property=$value;
		break;
	}
}
}