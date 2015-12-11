<?php

namespace Demo;


use Demo\Db\TodoDbm;

use Doctrine\Common\Cache\MemcacheCache;

class TestDemoConfiguration extends DemoConfiguration {

	// Database Mapper
	protected function getDbm() {
		error_log("test");
		$mem = new \Memcache();
		$mem->connect('localhost', 11211);

		$cache = new MemcacheCache();
		$cache->setMemcache($mem);

		return new TodoDbm($cache);
	}

}



