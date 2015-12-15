<?php

namespace Demo;

use Demo\Weather\Client\WeatherClient;
use Demo\Weather\Api\Weather;

class WeatherClientStubFactory extends \PHPUnit_Framework_TestCase {

	public function create() {


		$stub = $this->getMockBuilder('\Demo\Weather\Client\WeatherClient')
			->disableOriginalConstructor()
			->getMock();


		$stub->method('getWeather')
			->will($this->returnCallback(function($city, $state) {
				if ($city == "Austin") {
					return new Weather(80, "Clear");
				} else {
					return new Weather(80, "Rainy");
				}
			}));
	
		return $stub;
	}
}
