<?php

namespace Demo\Client;

use GuzzleHttp\Client;
use Demo\Api\Todo;
use Fliglio\Http\Http;

class TodoClient {
	private $client;

	public function __construct(Client $client, $baseUrl) {
		$this->client = $client;
		$this->baseUrl = $baseUrl;
	}

	public function getAll() {
		$resp = $this->client->get($this->baseUrl."/todo");

		return Todo::unmarshalCollection($resp->json());
	}
	public function getWeatherAppropriate($city, $state) {
		$resp = $this->client->get(sprintf("%s/todo/weather?city=%s&state=%s", $this->baseUrl, $city, $state));

		return Todo::unmarshalCollection($resp->json());
	}

	public function add(Todo $todo) {
		$resp = $this->client->post($this->baseUrl."/todo", ['json' => $todo->marshal()]);
		
		$body = json_decode($resp->getBody(), true);
		return Todo::unmarshal($body);
	}
	
	public function save(Todo $todo) {
		$resp = $this->client->put($this->baseUrl."/todo/".$todo->getId(), ['json' => $todo->marshal()]);
		
		$body = json_decode($resp->getBody(), true);
		return Todo::unmarshal($body);
	}

	public function get($id) {
		$resp = $this->client->get($this->baseUrl."/todo/".$id);

		$body = json_decode($resp->getBody(), true);
		return Todo::unmarshal($body);
	}

	public function delete($id) {
		$resp = $this->client->delete($this->baseUrl."/todo/".$id);
	}
	
}
