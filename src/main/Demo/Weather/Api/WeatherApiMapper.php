<?php

namespace Demo\Weather\Api;

use Fliglio\Web\ApiMapper;

class WeatherApiMapper implements ApiMapper {

	public function marshal($entity) {
		return [
			'temp'      => $entity->getTemp(),
			'description' => $entity->getDescription(),
		];
	}

	public function unmarshal($vo) {
		return new Weather(
			isset($vo['temp']) ? $vo['temp'] : null,
			isset($vo['description']) ? $vo['description'] : null
		);
	}
}

