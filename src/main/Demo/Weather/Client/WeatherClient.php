<?php

namespace Demo\Weather\Client;

use Demo\Weather\Api\Weather;
use GuzzleHttp\Client;

class WeatherClient {
	const URI = '/data/2.5/weather?units=imperial&q=';
	private $client;
	private $baseUrl;
	private $api;
	

	public function __construct(Client $client, $baseUrl, $api) {
		$this->client = $client;
		$this->baseUrl = $baseUrl;
		$this->api = $api;
	}

	public function getWeather($city, $state) {
		$url = sprintf("%s%s%s,%s&appid=%s", $this->baseUrl, self::URI, $city, $state, $this->api);
		error_log("WEATHER URL: ".$url);
		$resp = $this->client->get($url);
		
		$vo = $resp->json();

		if (isset($vo['main']) && isset($vo['main']['temp'])) {
			return new Weather($vo['main']['temp'], $vo['weather'][0]['main']);
		}
		return null;
	}
}

