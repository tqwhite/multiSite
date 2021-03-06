<?php
namespace Q\Entity;
/**
*
* @Entity
* @Table(name="categories")
* @author tqii
*
*
**/
class Category /*extends Base*/{
	/**
	* @var string $id
	* @column(name="refId", type="string", length=36, nullable=false, unique="true")
	* @Id
	**/
	private $refId;

	/**
	 * @column(type="string", length=60, nullable=false)
	 * @var string
	 **/
	private $name;


	/**
	 *
	 * @param \Doctrine\Common\Collections\Collection $property
	 * @OneToMany(targetEntity="BookmarkCategoryNode", mappedBy="bookmark", cascade={"persist", "remove"});
	 **/

	private $bookmarkNodes;



	/**
	 * @column(type="datetime", nullable=false)
	 * @var datetime
	 **/

	private $created;

	/**
	 * @column(type="boolean", nullable=true)
	 * @var integer
	 **/

	private $alreadyInHelix;

	/**
	 * @column(type="boolean", nullable=true)
	 * @var integer
	 **/

	private $suppressDisplay;



public function __construct(){
	if (!$this->refId){$this->refId =  \Q\Utils::newGuid();}
	$this->created=new \DateTime(date("Y-m-d H:i:s"));

	$this->students = new \Doctrine\Common\Collections\ArrayCollection();
	$this->gradeLevelNodes = new \Doctrine\Common\Collections\ArrayCollection();
	$this->offeringSchoolNodes = new \Doctrine\Common\Collections\ArrayCollection();
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