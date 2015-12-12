clean:
	rm -rf build

docker-build:
	docker build -t fliglio-app -f docker/Dockerfile docker

run:
	docker run -p 80:80 -v $(CURDIR)/:/var/www/ fliglio-app


test: unit-test component-test

unit-test:
	php ./vendor/bin/phpunit -c phpunit.xml --testsuite unit

component-test: _test-run _do-component-test _test-stop


_test-run:
	mkdir -p build/test/log
	ID=$$(docker run -t -d -p 80 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/build/test/log/:/var/log/nginx/ --name fliglio-test fliglio-test) && \
		echo $$ID > build/test/id && \
		PORT=$$(docker inspect --format='{{(index (index .NetworkSettings.Ports "80/tcp") 0).HostPort}}' $$ID ) && \
		echo $$PORT > build/test/port
	sleep 2

_do-component-test:
	PORT=$$(cat build/test/port) && \
		SVC_PORT=$$PORT php ./vendor/bin/phpunit -c phpunit.xml --testsuite component

_test-stop:

	docker kill fliglio-test && docker rm fliglio-test

