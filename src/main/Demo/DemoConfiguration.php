<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;

use Demo\Db\TodoDbm;
use Demo\Resource\TodoResource;
use Demo\Weather\Client\WeatherClient;

use GuzzleHttp\Client;

class DemoConfiguration extends DefaultConfiguration {

	// Database Mapper
	protected function getDbm() {
		$dsn = "mysql:host=localhost;dbname=todo";
		$db = new \PDO($dsn, 'admin', 'changeme', [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return new TodoDbm($db);
	}

	protected function getWeatherClient() {
		$driver = new Client();
		return new WeatherClient($driver, "http://api.openweathermap.org", "9cf059608c1d11796ac81d9db2f14c53");
	}

	// Todo Resource
	protected function getTodoResource() {
		return new TodoResource($this->getDbm(), $this->getWeatherClient());
	}

	public function getRoutes() {

		$resource = $this->getTodoResource();

		return [
			RouteBuilder::get()
				->uri('/todo')
				->resource($resource, 'getAll')
				->method(Http::METHOD_GET)
				->build(),
			RouteBuilder::get()
				->uri('/todo/weather')
				->resource($resource, 'getWeatherAppropriate')
				->method(Http::METHOD_GET)
				->build(),
			RouteBuilder::get()
				->uri('/todo/:id')
				->resource($resource, 'get')
				->method(Http::METHOD_GET)
				->build(),
			RouteBuilder::get()
				->uri('/todo')
				->resource($resource, 'add')
				->method(Http::METHOD_POST)
				->build(),
			RouteBuilder::get()
				->uri('/todo/:id')
				->resource($resource, 'update')
				->method(Http::METHOD_PUT)
				->build(),
			RouteBuilder::get()
				->uri('/todo/:id')
				->resource($resource, 'delete')
				->method(Http::METHOD_DELETE)
				->build(),
					
		];
	}

}


