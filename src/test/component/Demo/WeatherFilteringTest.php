<?php

namespace Demo;

use Demo\Client\TodoClient;
use Demo\Api\Todo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class WeatherFilteringTest extends \PHPUnit_Framework_TestCase {

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

	public function testGetWeatherAppropriate() {
		// given
		$todo1 = $this->client->add(new Todo(null, "Watch TV", "new", false));
		$todo2 = $this->client->add(new Todo(null, "Walk in the park", "new", true));
		

		// when
		$outdoorTodos = $this->client->getWeatherAppropriate('Austin', 'Texas');
		$indoorTodos = $this->client->getWeatherAppropriate('Seattle', 'Washington');


		// then
		$this->assertEquals([$todo1], $indoorTodos, "it's rainy, so get indoor todos");

		$this->assertEquals([$todo2], $outdoorTodos, "it's clear, so get outdoor todos");
	}

}

