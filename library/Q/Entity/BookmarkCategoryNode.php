<?php
namespace Q\Entity;
/**
*
* @Entity
* @Table(name="bookmarkCategoryNodes")
* @author tqii
*
*
**/
class BookmarkCategoryNode /*extends Base*/{
	/**
	* @var string $id
	* @column(name="refId", type="string", length=36, nullable=false, unique="true")
	* @Id
	**/
	private $refId;


	/**
	 *
	 * @ManyToOne(targetEntity="Category", cascade={"all"}, fetch="EAGER")
	 * @JoinColumn(name="categoryRefId", referencedColumnName="refId")
	 *
	 **/
	private $category;


	/**
	 *
	 * @ManyToOne(targetEntity="Bookmark", cascade={"all"}, fetch="EAGER")
	 * @JoinColumn(name="bookmarkRefId", referencedColumnName="refId")
	 *
	 **/
	private $bookmark;


	/**
	 * @column(type="datetime", nullable=false)
	 * @var datetime
	 **/

	private $created;

public function __construct(){
	if (!$this->refId){$this->refId =  \Q\Utils::newGuid();}
	$this->created=new \DateTime(date("Y-m-d H:i:s"));
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
	$this->$property=$value;
}
}