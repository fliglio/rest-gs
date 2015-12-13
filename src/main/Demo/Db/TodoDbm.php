<?php

namespace Demo\Db;

use Demo\Api\Todo;
use Doctrine\Common\Cache\Cache;

class TodoDbm {
	
	const INDEX = 'index';

	private $cache;
	private $db;
	
	public function __construct(\PDO $db) {
		$this->db = $db;
	}

	public function findAll($status = null) {
		$stmt = $this->db->prepare("SELECT `id`, `description`, `status` FROM Todo");
		$stmt->execute();
		$vo = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return Todo::unmarshalCollection($vo);
	}

	public function find($id) {
		$stmt = $this->db->prepare("SELECT `id`, `description`, `status` FROM Todo WHERE id = :id");
		$stmt->execute([":id" => $id]);
		$vo = $stmt->fetch(\PDO::FETCH_ASSOC);

		return Todo::unmarshal($vo);
	}

	public function save(Todo $todo) {
		if (is_null($todo->getId())) {
			$sql = "INSERT INTO Todo (`description`, `status`) VALUES (:desc, :status)";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				":desc" => $todo->getDescription(),
				":status" => $todo->getStatus(),
			]);
			$todo->setId($this->db->lastInsertId());
		} else {
			
			$sql = "UPDATE Todo SET `description` = :desc, `status` = :status WHERE `id` = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				":id" => $todo->getId(),
				":desc" => $todo->getDescription(),
				":status" => $todo->getStatus(),
			]);
		}
		return $todo;
	}

	public function delete(Todo $todo) {
		if ($this->find($todo->getId())) {
			$sql = "DELETE FROM Todo WHERE `id` = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				":id" => $todo->getId(),
			]);
		}
	}
}
