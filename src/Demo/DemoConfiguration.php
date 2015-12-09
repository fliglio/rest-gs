<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;

use Demo\Resource\HelloWorld;


class DemoConfiguration extends DefaultConfiguration {

	public function getRoutes() {

		// Resources
		$resource = new HelloWorld();

		

		return [
			// Life Form Scanner
			RouteBuilder::get()
				->uri('/hello')
				->resource($resource, 'test')
				->method(Http::METHOD_GET)
				->build(),
					
		];
	}
}


