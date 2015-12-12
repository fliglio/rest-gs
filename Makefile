NAME=rest-gs

LOCAL_DEV_PORT=8080

LOCAL_DEV_IMAGE=fliglio/localdev
TEST_IMAGE=fliglio/test


clean:
	rm -rf build

run:
	docker run -p $(LOCAL_DEV_PORT):80 -v $(CURDIR)/:/var/www/ $(LOCAL_DEV_IMAGE)


test: unit-test component-test

unit-test:
	php ./vendor/bin/phpunit -c phpunit.xml --testsuite unit

component-test: _test-run _do-component-test _test-stop _test-rm


_test-run:
	@mkdir -p build/test/log
	@ID=$$(docker run -t -d -p 80 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/build/test/log/:/var/log/nginx/ --name $(NAME)-test $(TEST_IMAGE)) && \
		echo $$ID > build/test/id && \
		PORT=$$(docker inspect --format='{{(index (index .NetworkSettings.Ports "80/tcp") 0).HostPort}}' $$ID ) && \
		echo $$PORT > build/test/port
	@echo "Bootstrapping component tests..."
	@sleep 2

_do-component-test:
	@PORT=$$(cat build/test/port) && \
		SVC_PORT=$$PORT php ./vendor/bin/phpunit -c phpunit.xml --testsuite component

_test-stop:
	@ID=$$(docker ps | grep -F "$(NAME)-test" | awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker stop $$ID); fi

_test-rm:
	@ID=$$(docker ps -a | grep -F "$(NAME)-test "| awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker rm $$ID); fi
