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

class TodoResource {

	private $db;

	public function __construct(TodoDbm $db) {
		$this->db = $db;
	}
	
	// GET /todo
	public function getAll(GetParam $status = null) {
		$todos = $this->db->findAll(is_null($status) ? null : $status->get());
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

