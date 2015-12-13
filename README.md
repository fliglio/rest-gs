[![Build Status](https://travis-ci.org/fliglio/rest-gs.svg)](https://travis-ci.org/fliglio/rest-gs)

# REST Getting Started

### Test it out!
Start a docker container mounting the project inside to support live editing:

	make run

In another terminal apply database migrations to your container:
	
	make migrate


Now you can excercise the rest api a little:

	$ curl -s localhost:8080/todo | jq .
	[]
	$ curl -s -X POST localhost:8080/todo -d '{"description": "take out the trash", "status": "new"}' | jq .
	{
	  "id": "5669d68a8430b",
	  "status": "new",
	  "description": "take out the trash"
	}
	$ curl -s localhost:8080/todo/5669d68a8430b | jq .
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
	$ curl -s localhost:8080/todo | jq .
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
	$ curl -s localhost:8080/todo?status=open | jq .
	[
	  {
		"id": "5669d68a8430b",
		"status": "open",
		"description": "take out the trash"
	  }
	]
	$ curl -s -X DELETE localhost:8080/todo/5669d68a8430b | jq .
	$ curl -s localhost:8080/todo | jq .
	[
	  {
		"id": "5669d73c96c8b",
		"status": "new",
		"description": "get some milk"
	  }
	]

