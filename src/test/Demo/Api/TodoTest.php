<?php

namespace Demo\Api;

class TodoTest extends \PHPUnit_Framework_TestCase {
	public function setup() {
	}
	public function testApiMapper() {
		// given
		$todo = new Todo(12, "foo", "new");

		// when

		$vo = $todo->marshal();
		$entity = Todo::unmarshal($vo);

		// then

		$this->assertEquals($todo, $entity, "api should be able to marshal/unmarshal without losing anything");
	}

}
