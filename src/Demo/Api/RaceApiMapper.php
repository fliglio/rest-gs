<?php

namespace Demo\Api;

use Fliglio\Web\ApiMapper;

class RaceApiMapper implements ApiMapper {

	public function marshal($entity) {
		return [
			'race'   => $entity->getRace(),
			'status' => $entity->getStatus(),
		];
	}

	public function unmarshal($vo) {
		return new Race(
			isset($vo['race']) ? $vo['race'] : null,
			$vo['status']
		);
	}
}
