<?php

namespace Demo\Client;

use Demo\Api\Todo;
use GuzzleHttp\Client;

class TodoClient {
	private $client;

	public function __construct(Client $client, $baseUrl) {
		$this->client = $client;
		$this->baseUrl = $baseUrl;
	}

	public function getAll() {
		$resp = $this->client->get($this->baseUrl."/todo");
		return array_map(function($vo) {
			return Todo::unmarshal($vo);
		}, $resp->json());
	}
	public function getWeatherAppropriate($city, $state) {
		$resp = $this->client->get(sprintf("%s/todo/weather?city=%s&state=%s", $this->baseUrl, $city, $state));
		return array_map(function($vo) {
			return Todo::unmarshal($vo);
		}, $resp->json());
	}

	public function add(Todo $todo) {
		$resp = $this->client->post($this->baseUrl."/todo", ['json' => $todo->marshal()]);
		return Todo::unmarshal($resp->json());
	}
	
	public function save(Todo $todo) {
		$resp = $this->client->put($this->baseUrl."/todo/".$todo->getId(), ['json' => $todo->marshal()]);
		return Todo::unmarshal($resp->json());
	}

	public function get($id) {
		$resp = $this->client->get($this->baseUrl."/todo/".$id);
		return Todo::unmarshal($resp->json());
	}

	public function delete($id) {
		$resp = $this->client->delete($this->baseUrl."/todo/".$id);
	}
	
}
