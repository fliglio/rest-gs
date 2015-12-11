<?php

namespace Demo\Db;

use Demo\Api\Todo;
use Doctrine\Common\Cache\Cache;

class TodoDbm {
	
	const INDEX = 'index';

	private $cache;
	
	public function __construct(Cache $cache) {
		$this->cache = $cache;
	}

	public function findAll($status = null) {
		$todos = [];
		foreach ($this->getIndex() as $id) {
			$todo = Todo::unmarshal($this->cache->fetch($id));
			if (is_null($status) || $todo->getStatus() == $status) {
				$todos[] = $todo;
			}
		}
		return $todos;
	}

	public function find($id) {
		if (!$this->cache->contains($id)) {
			return null;
		}

		return Todo::unmarshal($this->cache->fetch($id));
	}

	public function save(Todo $todo) {
		if (is_null($todo->getId())) {
			$todo->setId(uniqid());
		}
		$this->cache->save($todo->getId(), $todo->marshal());
		$this->addToIndex($todo->getId());
		return $todo;
	}

	public function delete(Todo $todo) {
		if ($this->find($todo->getId())) {
			$this->cache->delete($todo->getId());
			$this->removeFromIndex($todo->getId());
		}
	}
	
	private function getIndex() {
		if (!$this->cache->contains(self::INDEX)) {
			$this->cache->save(self::INDEX, []);
		}
		
		return $this->cache->fetch(self::INDEX);
	}
	
	private function addToIndex($id) {
		$idx = $this->getIndex();
		$idx[] = $id;
		$this->cache->save(self::INDEX, array_unique($idx));
	}
	private function removeFromIndex($id) {
		$idx = $this->getIndex();
		$this->cache->save(self::INDEX, array_diff($idx, [$id]));
	}
}
