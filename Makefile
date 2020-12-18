include .env
all: help

help:
	@echo "Usage: make <command> ";\
	echo "Commands: ";\
	echo "    help:                Shows the help. Exactly what you see now";\
	echo "    install:             Recreate container";\
	echo "    uninstall:           Destroy container";\
	echo "    reinstall:           Uninstall and install the application ";\
	echo "    start:               Start all containers";\
	echo "    stop:                Stop all containers";\
	echo "    ssh:                 Get cli access to apache container";\

install: hosts container composer assets
uninstall: hosts-clean container-clean
reinstall: uninstall install
start:
	@docker-compose start
stop:
	@docker-compose stop || true
ssh:
	@docker exec -it $(DOCKER_CONTAINER_NAME) bash
hosts:
	@sudo -- sh -c -e "echo '127.0.0.1 $(DOCKER_UMC_URL)' >> /etc/hosts";
hosts-clean:
	@sudo sed -i".bak" "/$(DOCKER_UMC_URL)/d" /etc/hosts
container:
	$(info creating container)
	docker-compose up -d
container-clean:
	$(info removing container)
	@docker rm $(DOCKER_CONTAINER_NAME) -f
image-clean:
	$(info removing image)
	@docker rmi $(DOCKER_IMAGE) -f
composer:
	$(info running composer install)
	@docker exec -it $(DOCKER_CONTAINER_NAME) composer install || true
assets:
	@docker exec -it $(DOCKER_CONTAINER_NAME) bin/console assets:install
