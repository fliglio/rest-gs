NAME=rest-gs
DB_NAME=todo

LOCAL_DEV_PORT=8080

LOCAL_DEV_IMAGE=fliglio/local-dev
TEST_IMAGE=fliglio/test


clean: localdev-clean test-clean
	rm -rf build


localdev-clean:
	@ID=$$(docker ps | grep -F "$(NAME)" | awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker kill $$ID); fi
	@ID=$$(docker ps -a | grep -F "$(NAME) "| awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker rm $$ID); fi

run: _localdev-stop _localdev-rm
	docker run -p $(LOCAL_DEV_PORT):80 -p 3306 -v $(CURDIR)/:/var/www/ --name $(NAME) $(LOCAL_DEV_IMAGE) 

migrate:
	docker run -v $(CURDIR)/:/var/www/ -e "DB_NAME=$(DB_NAME)" --link $(NAME):localdev $(LOCAL_DEV_IMAGE) /usr/local/bin/migrate.sh


test: unit-test component-test

unit-test:
	php ./vendor/bin/phpunit -c phpunit.xml --testsuite unit

component-test: test-run do-component-test test-clean


test-run:
	@mkdir -p build/test/log
	@ID=$$(docker run -t -d -p 80 -p 3306 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/build/test/log/:/var/log/nginx/ --name $(NAME)-test $(TEST_IMAGE)) && \
		echo $$ID > build/test/id && \
		PORT=$$(docker inspect --format='{{(index (index .NetworkSettings.Ports "80/tcp") 0).HostPort}}' $$ID ) && \
		echo $$PORT > build/test/port
	@echo "Bootstrapping component tests..."
	@sleep 3
	docker run -v $(CURDIR)/:/var/www/ -e "DB_NAME=$(DB_NAME)" --link $(NAME)-test:localdev $(TEST_IMAGE) /usr/local/bin/migrate.sh

do-component-test:
	@PORT=$$(cat build/test/port) && \
		SVC_PORT=$$PORT php ./vendor/bin/phpunit -c phpunit.xml --testsuite component

test-clean:
	@ID=$$(docker ps | grep -F "$(NAME)-test" | awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker kill $$ID); fi
	@ID=$$(docker ps -a | grep -F "$(NAME)-test "| awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker rm $$ID); fi
