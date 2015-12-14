<?php

namespace Demo\Weather\Api;

use Fliglio\Web\MappableApi;
use Fliglio\Web\MappableApiTrait;

class Weather implements MappableApi {
	use MappableApiTrait;

	private $temp;
	private $description;

	public function __construct($temp = null, $description = null) {
		$this->setTemp($temp);
		$this->setDescription($description);
	}

	public function setTemp($temp) {
		$this->temp = $temp;
		return $this;
	}
	public function getTemp() {
		return $this->temp;
	}
	public function setDescription($desc) {
		$this->description = $desc;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}

}

