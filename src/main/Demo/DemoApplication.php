<?php

namespace Demo;

use Fliglio\Fli\DefaultResolverApp;
use Fliglio\Fli\ResolverAppMux;
use Fliglio\Logging\FLogContext;
use Fliglio\Logging\FLogger;
use Fliglio\Logging\FLogRegistry;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DemoApplication extends ResolverAppMux {
	public function __construct(DemoConfiguration $cfg) {
		parent::__construct();

		$logger = new Logger("Demo");
		$handler = new StreamHandler('php://stderr');
		$handler->setFormatter(new JsonFormatter());
		$logger->pushHandler($handler);

		FLogRegistry::set(new FLogger($logger , new FLogContext()));

		$fli = new DefaultResolverApp();
		$fli->configure($cfg);

		$this->addApp($fli);
	}

}
