[![Build Status](https://travis-ci.org/fliglio/rest-gs.svg)](https://travis-ci.org/fliglio/rest-gs)

# REST Getting Started

### Build Docker Image
build a docker image to run the app

	make docker-build

### Create Docker Container
start a docker container, mount project inside to support live editing

	make docker-start


### Test it out!
excercise the rest api a little

	$ curl -s localhost/todo | jq .                                                        master ✗ 13:46:08
	[]
	$ curl -s -X POST localhost/todo -d '{"description": "take out the trash", "status": "new"}' | jq .
	{
	  "id": "5669d68a8430b",
	  "status": "new",
	  "description": "take out the trash"
	}
	$ curl -s localhost/todo/5669d68a8430b | jq .                                          master ✗ 13:46:43
	{
	  "id": "5669d68a8430b",
	  "status": "new",
	  "description": "take out the trash"
	}
	$ curl -s -X PUT localhost/todo/5669d68a8430b -d '{"description": "take out the trash", "status": "open"}' | jq .
	{
	  "id": "5669d68a8430b",
	  "status": "open",
	  "description": "take out the trash"
	}
	$ curl -s -X POST localhost/todo -d '{"description": "get some milk", "status": "new"}' | jq .
	{
	  "id": "5669d73c96c8b",
	  "status": "new",
	  "description": "get some milk"
	}
	$ curl -s localhost/todo | jq .                                                        master ✗ 13:49:26
	[
	  {
		"id": "5669d68a8430b",
		"status": "open",
		"description": "take out the trash"
	  },
	  {
		"id": "5669d73c96c8b",
		"status": "new",
		"description": "get some milk"
	  }
	]
	$ curl -s localhost/todo?status=open | jq .                                       4 ↵  master ✗ 13:52:27
	[
	  {
		"id": "5669d68a8430b",
		"status": "open",
		"description": "take out the trash"
	  }
	]
	$ curl -s -X DELETE localhost/todo/5669d68a8430b | jq .                                master ✗ 13:53:02
	$ curl -s localhost/todo | jq .                                                        master ✗ 13:53:06
	[
	  {
		"id": "5669d73c96c8b",
		"status": "new",
		"description": "get some milk"
	  }
	]


### Tear Down Docker
kill and remove the container

	make docker-stop



