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

/**
 * @SWG\Swagger(
 *     @SWG\Info(
 *         title="Todo Resource"
 *     )
 * )
 */
class TodoResource {

	private $db;
	private $weather;

	public function __construct(TodoDbm $db, WeatherClient $weather) {
		$this->db = $db;
		$this->weather = $weather;
	}
	
	/**
	 * @SWG\Get(
	 *     path="/todo",
	 *     summary="list todos",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Filter by Status",
     *         in="query",
     *         name="status",
     *         required=false,
     *         type="string"
     *     ),
	 *     @SWG\Response(response="200", description="list of todos", @SWG\Schema(ref="#/definitions/Todo"))
	 * )
	 */
	public function getAll(GetParam $status = null) {
		$todos = $this->db->findAll(is_null($status) ? null : $status->get());
		return Todo::marshalCollection($todos);
	}

	/**
	 * @SWG\Get(
	 *     path="/todo/weather",
	 *     summary="list todos appropriate for current weather",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="City",
     *         in="query",
     *         name="city",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="State",
     *         in="query",
     *         name="state",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Filter by Status",
     *         in="query",
     *         name="status",
     *         required=false,
     *         type="string"
     *     ),
	 *     @SWG\Response(response="200", description="list of todos", @SWG\Schema(ref="#/definitions/Todo"))
	 * )
	 */
	public function getWeatherAppropriate(GetParam $city, GetParam $state, GetParam $status = null) {
		$status = is_null($status) ? null : $status->get();

		$weather = $this->weather->getWeather($city->get(), $state->get());

		error_log(print_r($weather->marshal(), true));

		$outdoorWeather = $weather->getDescription() == "Clear";
		$todos = $this->db->findAll($status, $outdoorWeather);
		return Todo::marshalCollection($todos);
	}

	/**
	 * @SWG\Get(
	 *     path="/todo/{id}",
	 *     summary="a todo",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of todo to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
	 *     @SWG\Response(response="200", description="todo", @SWG\Schema(ref="#/definitions/Todo")),
	 *     @SWG\Response(response="404", description="todo not found")
	 * )
	 */
	public function get(PathParam $id) {
		$todo = $this->db->find($id->get());
		if (is_null($todo)) {
			throw new NotFoundException();
		}
		return $todo->marshal();
	}

	/**
	 * @SWG\Post(
	 *     path="/todo",
	 *     summary="add a todo",
     *     consumes={"application/json"},
     *     produces={"application/json"},
	 *     @SWG\Parameter (
	 *         name="body",
	 *         in="body",
	 *         @SWG\Schema(ref="#/definitions/Todo")
	 *     ),
	 *     @SWG\Response(response="201", description="create a todo", @SWG\Schema(ref="#/definitions/Todo")),
	 *     @SWG\Response(response="500", description="an error occured")
	 * )
	 */
	public function add(Entity $entity, ResponseWriter $resp) {
		$todo = $entity->bind(Todo::getClass());
		$this->db->save($todo);
		$resp->setStatus(Http::STATUS_CREATED);
		return $todo->marshal();
	}

	/**
	 * @SWG\Put(
	 *     path="/todo/{id}",
	 *     summary="save a todo",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of todo to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
	 *     @SWG\Parameter (
	 *         name="body",
	 *         in = "body",
	 *         @SWG\Schema(ref="#/definitions/Todo")
	 *     ),
	 *     @SWG\Response(response="200", description="save a todo", @SWG\Schema(ref="#/definitions/Todo")),
	 *     @SWG\Response(response="404", description="todo not found"),
	 *     @SWG\Response(response="500", description="an error occured")
	 * )
	 */
	public function update(PathParam $id, Entity $entity) {
		$todo = $entity->bind(Todo::getClass());
		$todo->setId($id->get());
		$this->db->save($todo);
		return $todo->marshal();
	}

	/**
	 * @SWG\Delete(
	 *     path="/todo/{id}",
	 *     summary="delete a todo",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of todo to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
	 *     @SWG\Response(response="200", description="save a todo"),
	 *     @SWG\Response(response="404", description="todo not found"),
	 *     @SWG\Response(response="500", description="an error occured")
	 * )
	 */
	public function delete(PathParam $id, ResponseWriter $resp) {
		$todo = $this->db->find($id->get());
		if (is_null($todo)) {
			throw new NotFoundException();
		}
		$this->db->delete($todo);
		$resp->setStatus(Http::STATUS_NO_CONTENT);
	}

}

