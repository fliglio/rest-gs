docker-build:
	docker build -t fliglio-app -f docker/Dockerfile docker

docker-start:
	mkdir -p log
	docker run -t -d -p 80:80 -v $(CURDIR)/:/var/www/ -v $(CURDIR)/log/:/var/log/apache2/ --name fliglio-app fliglio-app

docker-stop:
	docker kill fliglio-app && docker rm fliglio-app
