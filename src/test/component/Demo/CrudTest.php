<?php

namespace Demo;

use Demo\Client\TodoClient;
use Demo\Api\Todo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CrudTest extends \PHPUnit_Framework_TestCase {

	private $client;

	public function setup() {
		$driver = new Client();

		$this->client = new TodoClient($driver, 
			sprintf("http://%s:%s", getenv('LOCALDEV_PORT_80_TCP_ADDR'), 80));
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

	public function testGet() {
		// given
		$todo = $this->client->add(new Todo(null, "Hello World", "new"));
		
		// when

		$found = $this->client->get($todo->getId());

		// then
		$this->assertEquals($found, $todo, "GET should return todo by id");
	}
	public function testSave() {
		// given
		$todo = $this->client->add(new Todo(null, "Hello World", "new"));
		
		// when
		$todo->setStatus("open");
		$todo->setDescription("foo");

		$updated = $this->client->save($todo);


		// then
		$found = $this->client->get($todo->getId());

		$this->assertEquals($updated, $todo, "updates should be saved");
		$this->assertEquals($found, $todo, "updates should be saved");
	}

	public function testGetAll() {
		// given
		$todo1 = $this->client->add(new Todo(null, "Watch TV", "new", false));
		$todo2 = $this->client->add(new Todo(null, "Walk in the park", "new", true));

		// when
		$todos = $this->client->getAll();


		// then
		$this->assertEquals([$todo1, $todo2], $todos, "should get back both created todos");

	}

	public function testDelete() {
		// given
		$todo = $this->client->add(new Todo(null, "Hello Galaxy", "new"));
		
		// when
		$this->client->delete($todo->getId());
		
		// then
		try {
			$found = $this->client->get($todo->getId());
			$this->fail("should have thrown an exception");
		} catch (ClientException $e) {
		}

	}
}
