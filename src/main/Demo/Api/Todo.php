<?php

namespace Demo\Api;

use Fliglio\Web\MappableApi;
use Fliglio\Web\MappableApiTrait;

class Todo implements MappableApi {
	use MappableApiTrait;

	const STATUS_NEW = 'new';
	const STATUS_OPEN = 'open';
	const STATUS_CLOSED = 'closed';

	private $id;
	private $status;
	private $description;
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
