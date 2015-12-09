<?php

namespace Demo\Resource;

use Fliglio\Web\Body;
use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;
use Fliglio\Web\Entity;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;


class HelloWorld {

	public function test(GetParam $msg) {
		return $msg->get();
	}

}

