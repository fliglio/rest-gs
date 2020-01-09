<?php

namespace Demo\Api;

use Fliglio\Web\ApiMapper;

class TodoApiMapper implements ApiMapper {

	public function marshal($entity) {
		return [
			'id'          => $entity->getId(),
			'status'      => $entity->getStatus(),
			'description' => $entity->getDescription(),
			'outdoor'     => $entity->getOutdoor(),
		];
	}

	public function unmarshal($vo) {
		return new Todo(
			isset($vo['id']) ? $vo['id'] : null,
			isset($vo['description']) ? $vo['description'] : null,
			isset($vo['status']) ? $vo['status'] : null,
			isset($vo['outdoor']) ? (bool)$vo['outdoor'] : null
		);
	}
}
