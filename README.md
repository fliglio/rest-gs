[![Build Status](https://travis-ci.org/fliglio/rest-gs.svg)](https://travis-ci.org/fliglio/rest-gs)

# REST Getting Started

### Test it out!
Start a docker container mounting the project inside to support live editing

	make run

and in another terminal...
	
	make migrate


now you can excercise the rest api a little

	$ curl -s localhost:8080/todo | jq .                                                        master ✗ 13:46:08
	[]
	$ curl -s -X POST localhost:8080/todo -d '{"description": "take out the trash", "status": "new"}' | jq .
	{
	  "id": "5669d68a8430b",
	  "status": "new",
	  "description": "take out the trash"
	}
	$ curl -s localhost:8080/todo/5669d68a8430b | jq .                                          master ✗ 13:46:43
	{
	  "id": "5669d68a8430b",
	  "status": "new",
	  "description": "take out the trash"
	}
	$ curl -s -X PUT localhost:8080/todo/5669d68a8430b -d '{"description": "take out the trash", "status": "open"}' | jq .
	{
	  "id": "5669d68a8430b",
	  "status": "open",
	  "description": "take out the trash"
	}
	$ curl -s -X POST localhost:8080/todo -d '{"description": "get some milk", "status": "new"}' | jq .
	{
	  "id": "5669d73c96c8b",
	  "status": "new",
	  "description": "get some milk"
	}
	$ curl -s localhost:8080/todo | jq .                                                        master ✗ 13:49:26
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
	$ curl -s localhost:8080/todo?status=open | jq .                                       4 ↵  master ✗ 13:52:27
	[
	  {
		"id": "5669d68a8430b",
		"status": "open",
		"description": "take out the trash"
	  }
	]
	$ curl -s -X DELETE localhost:8080/todo/5669d68a8430b | jq .                                master ✗ 13:53:02
	$ curl -s localhost:8080/todo | jq .                                                        master ✗ 13:53:06
	[
	  {
		"id": "5669d73c96c8b",
		"status": "new",
		"description": "get some milk"
	  }
	]

