<?php

namespace Demo;

use Demo\Client\TodoClient;
use Demo\Api\Todo;
use GuzzleHttp\Client;

class CrudTest extends \PHPUnit_Framework_TestCase {

	private $client;

	public function setup() {
		$driver = new Client([
			'base_uri' => 'http://localhost:'.getenv('SVC_PORT'),
		]);

		$this->client = new TodoClient($driver);
	}

	public function teardown() {
		$todos = $this->client->getAll();
		foreach ($todos as $todo) {
			$this->client->delete($todo->getId());
		}
	}

	public function testAdd() {
		// given
		$todo = new Todo(null, "hello", "new");
		
		// when
		$out = $this->client->add($todo);

		// then
		$todo->setId($out->getId());
		$this->assertEquals($out, $todo, "created todo should return value");
		
		$out2 = $this->client->get($out->getId());
		$this->assertEquals($out, $out2, "created todo should return value");
	}

	public function testGetAll() {
		// given
		$todo1 = new Todo(null, "hello", "new");
		$todo2 = new Todo(null, "world", "new");
		$this->client->add($todo1);
		$this->client->add($todo2);
		

		// when
		$todos = $this->client->getAll();
		// then
		$expected = [
			$todo1->setId($todos[0]->getId()),
			$todo2->setId($todos[1]->getId()),
		];
		$this->assertEquals($expected, $todos, "should get back both created todos");
	}

}
