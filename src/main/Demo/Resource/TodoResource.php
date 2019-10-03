<?php

namespace Demo\Resource;

use Demo\Api\Todo;
use Demo\Db\TodoDbm;
use Demo\Weather\Client\WeatherClient;
use Fliglio\Http\Exceptions\NotFoundException;
use Fliglio\Http\Http;
use Fliglio\Http\ResponseWriter;
use Fliglio\Logging\FLog;
use Fliglio\Web\Entity;
use Fliglio\Web\GetParam;
use Fliglio\Web\PathParam;

class TodoResource {
	use FLog;

	private $db;
	private $weather;

	public function __construct(TodoDbm $db, WeatherClient $weather) {
		$this->db = $db;
		$this->weather = $weather;
	}
	
	// GET /todo
	public function getAll(GetParam $status = null) {
		$todos = $this->db->findAll(is_null($status) ? null : $status->get());
		return array_map(function($todo) {
			return $todo->marshal();
		}, $todos);
	}
	// GET /todo/weather
	public function getWeatherAppropriate(GetParam $city, GetParam $state, GetParam $status = null) {
		$status = is_null($status) ? null : $status->get();

		$weather = $this->weather->getWeather($city->get(), $state->get());

		$this->log()->debug(print_r($weather->marshal(), true));

		$outdoorWeather = $weather->getDescription() == "Clear";
		$todos = $this->db->findAll($status, $outdoorWeather);
		return array_map(function($todo) {
			return $todo->marshal();
		}, $todos);
	}

	// GET /todo/:id
	public function get(PathParam $id) {
		$todo = $this->db->find($id->get());
		if (is_null($todo)) {
			throw new NotFoundException();
		}
		return $todo->marshal();
	}

	// POST /todo
	public function add(Entity $entity, ResponseWriter $resp) {
		$todo = $entity->bind(Todo::getClass());
		$this->db->save($todo);
		$resp->setStatus(Http::STATUS_CREATED);
		return $todo->marshal();
	}

	// PUT /todo/:id
	public function update(PathParam $id, Entity $entity) {
		$todo = $entity->bind(Todo::getClass());
		$todo->setId($id->get());
		$this->db->save($todo);
		return $todo->marshal();
	}

	// DELETE /todo/:id
	public function delete(PathParam $id) {
		$todo = $this->db->find($id->get());
		if (is_null($todo)) {
			throw new NotFoundException();
		}
		$this->db->delete($todo);
	}

}

