<?php

namespace Demo\Resource;

use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;
use Fliglio\Web\Entity;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;
use Fliglio\Http\Exceptions\NotFoundException;

use Demo\Api\Todo;
use Demo\Db\TodoDbm;

use Demo\Weather\Api\Weather;
use Demo\Weather\Client\WeatherClient;

class TodoResource {

	private $db;
	private $weather;

	public function __construct(TodoDbm $db, WeatherClient $weather) {
		$this->db = $db;
		$this->weather = $weather;
	}
	
	// GET /todo
	public function getAll(GetParam $status = null) {
		$todos = $this->db->findAll(is_null($status) ? null : $status->get());
		return Todo::marshalCollection($todos);
	}
	// GET /todo/weather
	public function getWeatherAppropriate(GetParam $city, GetParam $state, GetParam $status = null) {
		$status = is_null($status) ? null : $status->get();

		$weather = $this->weather->getWeather($city->get(), $state->get());

		error_log(print_r($weather->marshal(), true));

		$outdoorWeather = $weather->getDescription() == "Clear";
		$todos = $this->db->findAll($status, $outdoorWeather);
		return Todo::marshalCollection($todos);
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

