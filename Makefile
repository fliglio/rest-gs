docker-build:
	docker build -t fliglio-app -f docker/Dockerfile docker

run:
	docker run -p 80:80 -v $(CURDIR)/:/var/www/ fliglio-app


test:
	php ./vendor/bin/phpunit -c phpunit.xml --testsuite unit

component-test: _test-run _do-component-test _test-stop


_test-run:
	mkdir -p build/test/log
	docker run -t -d -p 8000:80 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/build/test/log/:/var/log/nginx/ --name fliglio-test fliglio-test
	sleep 2

_do-component-test:
	php ./vendor/bin/phpunit -c phpunit.xml --testsuite component

_test-stop:
	docker kill fliglio-test && docker rm fliglio-test

