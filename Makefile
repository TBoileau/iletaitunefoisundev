DOCKER_COMPOSE = docker-compose
EXEC_APACHE = $(DOCKER_COMPOSE) exec apache
EXEC_COMPOSER = $(DOCKER_COMPOSE) exec apache composer
EXEC_PHP = $(DOCKER_COMPOSE) exec apache php
EXEC_SYMFONY = $(DOCKER_COMPOSE) exec apache php bin/console

install: build up composer-install

reset: down install

build:
	@echo "\nBuilding local images...\e[0m"
	@$(DOCKER_COMPOSE) build

up:
	@echo "\nUp environment...\e[0m"
	@$(DOCKER_COMPOSE) up -d --remove-orphans

down:
	@echo "\nDown environment...\e[0m"
	@$(DOCKER_COMPOSE) kill
	@$(DOCKER_COMPOSE) down --remove-orphans

start:
	@echo "\nStart containers...\e[0m"
	@$(DOCKER_COMPOSE) unpause || true
	@$(DOCKER_COMPOSE) start || true

stop:
	@echo "\nStop containers...\e[0m"
	@$(DOCKER_COMPOSE) pause || true

initialize:
	@echo "\nInitialize dev environment...\e[0m"
	make generate-keypair env=dev
	make prepare env=dev
	@echo "\nInitialize test environment...\e[0m"
	make generate-keypair env=test
	make prepare env=test

generate-keypair:
	@echo "\nGenerate keypair...\e[0m"
	@$(EXEC_SYMFONY) lexik:jwt:generate-keypair --overwrite -n --env=$(env)

composer-install:
	@echo "\nInstall dependencies...\e[0m"
	$(EXEC_COMPOSER) install

composer-update:
	@echo "\Update dependencies...\e[0m"
	$(EXEC_COMPOSER) update

analyse: composer-valid container-linter mapping-valid phpcpd phpstan

phpstan:
	@echo "\nRunning phpstan...\e[0m"
	$(EXEC_PHP) vendor/bin/phpstan analyse --configuration=phpstan.neon

php-cs-fixer:
	@echo "\nRunning php-cs-fixer...\e[0m"
	@$(EXEC_PHP) vendor/bin/php-cs-fixer fix

phpcpd:
	@echo "\nRunning phpcpd...\e[0m"
	@$(EXEC_PHP) vendor/bin/phpcpd src --exclude src/Admin/Controller

container-linter:
	@echo "\nRunning container linter...\e[0m"
	@$(EXEC_SYMFONY) lint:container

composer-valid:
	@echo "\nRunning container valid...\e[0m"
	$(EXEC_COMPOSER) valid

mapping-valid:
	@echo "\nRunning mapping valid...\e[0m"
	@$(EXEC_SYMFONY) doctrine:schema:valid --skip-sync

tests:
	@echo "\nRunning tests...\e[0m"
	@$(EXEC_PHP) --memory-limit=256M bin/phpunit

unit-tests:
	@echo "\nRunning unit tests...\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=unit

component-tests:
	@echo "\nRunning component tests...\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=component

integration-tests:
	@echo "\nRunning integration tests...\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=integration

functional-tests:
	@echo "\nRunning functional tests...\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=functional

database:
	echo "\nSetup database...\e[0m"
	$(EXEC_SYMFONY) doctrine:database:drop --if-exists --force --env=$(env)
	$(EXEC_SYMFONY) doctrine:database:create --env=$(env)
	$(EXEC_SYMFONY) doctrine:schema:update --force --env=$(env)

fixtures:
	@echo "\nLoad fixtures...\e[0m"
	$(EXEC_SYMFONY) doctrine:fixtures:load -n --env=$(env)

fix: php-cs-fixer

prepare: database fixtures
