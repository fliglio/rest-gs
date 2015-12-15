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

	public function findAll($status = null, $outdoorWeather = null) {
		$args = [];
		$sql = "SELECT `id`, `description`, `status`, `outdoor` FROM Todo";
		if (!is_null($outdoorWeather) || !is_null($status)) {
			
			if (!is_null($outdoorWeather) && !is_null($status)) {
				$sql .= " WHERE `outdoor` = :outdoor AND `status` = :status";
				$args[':outdoor'] = $outdoorWeather;
				$args[':status'] = $status;
			} else if (!is_null($outdoorWeather)) {
				$sql .= " WHERE `outdoor` = :outdoor";
				$args[':outdoor'] = $outdoorWeather;
			} else {
				$sql .= " WHERE `status` = :status";
				$args[':status'] = $status;
			}
		}
		$sql .= " ORDER BY `id` ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute($args);
		$vo = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return Todo::unmarshalCollection($vo);
	}

	public function find($id) {
		$stmt = $this->db->prepare("SELECT `id`, `description`, `status`, `outdoor` FROM Todo WHERE id = :id");
		$stmt->execute([":id" => $id]);
		$vo = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (empty($vo)) {
			return null;
		}
		return Todo::unmarshal($vo);
	}

	public function save(Todo $todo) {
		if (is_null($todo->getId())) {
			$sql = "INSERT INTO Todo (`description`, `status`, `outdoor`) VALUES (:desc, :status, :outdoor)";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				":desc" => $todo->getDescription(),
				":status" => $todo->getStatus(),
				":outdoor" => $todo->getOutdoor(),
			]);
			$todo->setId($this->db->lastInsertId());
		} else {
			
			$sql = "UPDATE Todo SET `description` = :desc, `status` = :status, `outdoor` = :outdoor WHERE `id` = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				":id" => $todo->getId(),
				":desc" => $todo->getDescription(),
				":status" => $todo->getStatus(),
				":outdoor" => $todo->getOutdoor(),
			]);
		}
		return $todo;
	}

	public function delete(Todo $todo) {
		$sql = "DELETE FROM Todo WHERE `id` = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			":id" => $todo->getId(),
		]);
	}
}
