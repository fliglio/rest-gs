<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;

use Demo\Db\TodoDbm;
use Demo\Resource\TodoResource;

use Doctrine\Common\Cache\MemcacheCache;

class DemoConfiguration extends DefaultConfiguration {

	// Database Mapper
	protected function getDbm() {
		$mem = new \Memcache();
		$mem->connect('localhost', 11211);

		$cache = new MemcacheCache();
		$cache->setMemcache($mem);

		return new TodoDbm($cache);
	}

	// Todo Resource
	protected function getTodoResource() {
		return new TodoResource($this->getDbm());
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


