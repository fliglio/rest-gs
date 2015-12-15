<?php

namespace Demo;

use Fliglio\Fli\DefaultResolverApp;
use Fliglio\Fli\ResolverAppMux;

class DemoApplication extends ResolverAppMux {
	public function __construct(DemoConfiguration $cfg) {
		parent::__construct();

		$fli = new DefaultResolverApp();
		$fli->configure($cfg);

		$this->addApp($fli);
	}

}
