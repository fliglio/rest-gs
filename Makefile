docker-build:
	docker build -t fliglio-app -f docker/Dockerfile docker

docker-start:
	mkdir -p log
	docker run -t -d -p 80:80 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/log/:/var/log/nginx/ --name fliglio-app -d fliglio-app

docker-stop:
	docker kill fliglio-app && docker rm fliglio-app

test:
	php ./vendor/bin/phpunit -c phpunit.xml

