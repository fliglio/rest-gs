NAME=rest-gs
DB_NAME=todo

LOCAL_DEV_PORT=8000

LOCAL_DEV_IMAGE=fliglio/local-dev
TEST_IMAGE=fliglio/test


clean: clean-localdev clean-test
	rm -rf build

#
# Local Dev
#

clean-localdev:
	@ID=$$(docker ps | grep -F "$(NAME)" | awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker kill $$ID); fi
	@ID=$$(docker ps -a | grep -F "$(NAME) "| awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker rm $$ID); fi

run: clean-localdev
	docker run -p $(LOCAL_DEV_PORT):80 -p 3306 -v $(CURDIR)/:/var/www/ --name $(NAME) $(LOCAL_DEV_IMAGE) 

migrate:
	docker run -v $(CURDIR)/:/var/www/ -e "DB_NAME=$(DB_NAME)" --link $(NAME):localdev $(LOCAL_DEV_IMAGE) /usr/local/bin/migrate.sh


#
# Test
#

test: unit-test component-test

unit-test:
	php ./vendor/bin/phpunit -c phpunit.xml --testsuite unit

component-test: clean-test component-test-setup component-test-run component-test-teardown

clean-test:
	@ID=$$(docker ps | grep -F "$(NAME)-test" | awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker kill $$ID); fi
	@ID=$$(docker ps -a | grep -F "$(NAME)-test "| awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker rm $$ID); fi
	rm -rf build/test

component-test-setup:
	@mkdir -p build/test/log
	@ID=$$(docker run -t -d -p 80 -p 3306 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/build/test/log/:/var/log/nginx/ --name $(NAME)-test $(TEST_IMAGE)) && \
		echo $$ID > build/test/id && \
		IP=$$(docker inspect --format='{{ .NetworkSettings.Gateway }}' $$ID ) && \
		PORT=$$(docker inspect --format='{{(index (index .NetworkSettings.Ports "80/tcp") 0).HostPort}}' $$ID ) && \
		echo $$IP > build/test/ip && \
		echo $$PORT > build/test/port
	@echo "Bootstrapping component tests..."
	@sleep 3
	docker run -v $(CURDIR)/:/var/www/ -e "DB_NAME=$(DB_NAME)" --link $(NAME)-test:localdev $(TEST_IMAGE) /usr/local/bin/migrate.sh

component-test-run:
	@PORT=$$(cat build/test/port) && \
		IP=$$(cat build/test/ip) && \
		SVC_PORT=$$PORT SVC_IP=$$IP php ./vendor/bin/phpunit -c phpunit.xml --testsuite component

component-test-teardown:
	@ID=$$(docker ps | grep -F "$(NAME)-test" | awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker kill $$ID); fi
	@ID=$$(docker ps -a | grep -F "$(NAME)-test "| awk '{ print $$1 }') && \
		if test "$$ID" != ""; then X=$$(docker rm $$ID); fi


