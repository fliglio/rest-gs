<?php

namespace Demo;


use Demo\Db\TodoDbm;

use Doctrine\Common\Cache\MemcacheCache;

class TestDemoConfiguration extends DemoConfiguration {

	protected function getWeatherClient() {
		error_log("using stub weather client");

		$fac = new WeatherClientStubFactory();
		return $fac->create();
	}

}



