<?php

namespace Demo\Api;

use Fliglio\Web\MappableApi;
use Fliglio\Web\MappableApiTrait;

/**
 * @SWG\Definition(@SWG\Xml(name="Title"))
 */
class Todo implements MappableApi {
	use MappableApiTrait;

	const STATUS_NEW = 'new';
	const STATUS_OPEN = 'open';
	const STATUS_CLOSED = 'closed';

	/**
	 * Id
	 * @var int
	 * @SWG\Property()
	 */
	private $id;
	/**
	 * Status
	 * @var string
	 * @SWG\Property(example="new")
	 */
	private $status;
	/**
	 * Description
	 * @var string
	 * @SWG\Property(example="Get milk")
	 */
	private $description;
	/**
	 * Will this todo be completed outdoors
	 * @var bool
	 * @SWG\Property(example=false)
	 */
	private $outdoor;

	public function __construct($id = null, $description = null, $status = self::STATUS_NEW, $outdoor = false) {
		$this->setId($id);
		$this->setDescription($description);
		$this->setStatus($status);
		$this->setOutdoor($outdoor);
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getId() {
		return $this->id;
	}
	public function setDescription($desc) {
		$this->description = $desc;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	public function getStatus() {
		return $this->status;
	}

	public function setOutdoor($od) {
		$this->outdoor = $od;
		return $this;
	}
	public function getOutDoor() {
		return $this->outdoor;
	}

}
