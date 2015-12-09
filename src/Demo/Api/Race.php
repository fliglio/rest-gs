<?php

namespace Demo\Api;

use Fliglio\Web\MappableApi;
use Fliglio\Web\MappableApiTrait;

class Race implements MappableApi {
	use MappableApiTrait;

	private $race;
	private $status;

	public function __construct($race, $status) {
		$this->race = $race;
		$this->status = $status;
	}

	public function setRace($r) {
		$this->race = $r;
		return $this;
	}

	public function setStatus($s) {
		$this->status = $s;
		return $this;
	}

	public function getRace() {
		return $this->race;
	}

	public function getStatus() {
		return $this->status;
	}


}
