DOCKER_COMPOSE = docker-compose
EXEC_SYMFONY = $(DOCKER_COMPOSE) exec -T php php bin/console
EXEC_COMPOSER = $(DOCKER_COMPOSE) exec -T php composer
EXEC_PHP = $(DOCKER_COMPOSE) exec -T php php
EXEC_NG = $(DOCKER_COMPOSE) exec -T node ng
EXEC_NPM = $(DOCKER_COMPOSE) exec -T node npm
CURRENT_PROJECT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

## Protect targets
.PHONY: help routes start stop down build up initialize php-cs-fixer phpcpd phpstan tests database fix fixtures cc prepare

help:
	 @grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[33m %s\n\033[0m", $$1, $$2}'

install: build up composer-install npm-install

reset: down install

analyse: composer-valid container-linter mapping-valid phpcpd phpstan ng-lint

prepare: database fixtures

container-stop: ## Stop all docker's container
	@echo -e "\e[32mStopping docker container\e[m"
	$(DOCKER_COMPOSE) stop

container-down: ## Remove all docker's container
	@echo -e "\e[32mRemoving docker container\e[0m"
	$(DOCKER_COMPOSE) down

container-remove: ## Removes stopped service containers
	@echo -e "\e[32mRemoving docker container(s)\e[0m"
	$(DOCKER_COMPOSE) rm

build: ## Build all dockers images in local ways
	@echo -e "\e[32mBuilding local images...\e[0m"
	@$(DOCKER_COMPOSE) build

up: ## Builds, (re)creates, starts, and attaches to containers for a service
	@echo -e "\e[32mUp environment...\e[0m"
	test -d $(CURRENT_PROJECT_DIR)client/node_modules || mkdir $(CURRENT_PROJECT_DIR)client/node_modules
	@$(DOCKER_COMPOSE) up -d --remove-orphans

down: ## Stops containers and removes containers, networks, volumes, and images created by up.
	@echo -e "\e[32mDown environment...\e[0m"
	@$(DOCKER_COMPOSE) kill
	@$(DOCKER_COMPOSE) down --remove-orphans

start: ## Starts existing containers for a service.
	@echo -e "\e[32mStart containers...\e[0m"
	@$(DOCKER_COMPOSE) unpause || true
	@$(DOCKER_COMPOSE) start || true

stop: ## Stops running containers without removing them.
	@echo -e "\e[32mStop containers...\e[0m"
	@$(DOCKER_COMPOSE) pause || true

initialize: ## Initialize specify environment
	@echo -e "\e[32mInitialize dev environment...\e[0m"
	make generate-keypair env=dev
	make prepare env=dev
	@echo -e "\e[32mInitialize test environment...\e[0m"
	make generate-keypair env=test
	make prepare env=test

generate-keypair: ## Create secure keypair
	@echo -e "\e[32mGenerate keypair...\e[0m"
	@$(EXEC_SYMFONY) lexik:jwt:generate-keypair --overwrite -n --env=$(env)

npm-install: ## Command reads the composer.json file to resolves the dependencies, and installs them.
	@echo -e "\e[32mInstall dependencies...\e[0m"
	$(EXEC_NPM) install

composer-install: ## Command reads the composer.json file to resolves the dependencies, and installs them.
	@echo -e "\e[32mInstall dependencies...\e[0m"
	$(EXEC_COMPOSER) install

composer-update: ## Resolve all dependencies of the project and write the exact versions into composer.lock
	@echo -e "\e[32mUpdate dependencies...\e[0m"
	$(EXEC_COMPOSER) update

routes: ## Visualize routes
	@echo -e "\e[32mGetting routes...\e[0m"
	$(EXEC_SYMFONY) debug:router

phpstan: ## Search for possible errors
	@echo -e "\e[32mRunning phpstan...\e[0m"
	$(EXEC_PHP) vendor/bin/phpstan analyse --configuration=phpstan.neon --memory-limit=4G

php-cs-fixer: ## Corrects the code to meet the standards
	@echo -e "\e[32mRunning php-cs-fixer...\e[0m"
	@$(EXEC_PHP) vendor/bin/php-cs-fixer fix

phpcpd: ## Detects code duplicates
	@echo -e "\e[32mRunning phpcpd...\e[0m"
	@$(EXEC_PHP) vendor/bin/phpcpd src --exclude src/Admin/Controller

container-linter: ## Guarantees that the arguments injected in the services correspond to the type declarations.
	@echo -e "\e[32mRunning container linter...\e[0m"
	@$(EXEC_SYMFONY) lint:container

composer-valid: ## Checks if your composer.json is valid.
	@echo -e "\e[32mRunning composer validate...\e[0m"
	$(EXEC_COMPOSER) valid

mapping-valid:
	@echo -e "\e[32mRunning mapping valid...\e[0m"
	@$(EXEC_SYMFONY) doctrine:schema:valid --skip-sync

tests:  ## Run all tests
	@echo -e "\e[32mRunning tests...\e[0m"
	@$(EXEC_PHP) bin/phpunit
	@$(EXEC_NG) test --watch=false

unit-tests: ## Run all unit tests
	@echo -e "\e[32mRunning unit tests...\e[0m"
	@echo -e "\e[1;93mReminder :To test is to doubt :)\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=unit

component-tests: ## Run all component tests
	@echo -e "\e[32mRunning component tests...\e[0m"
	@echo -e "\e[1;93mReminder :To test is to doubt :)\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=component

integration-tests: ## Run all integration tests
	@echo -e "\e[32mRunning integration tests...\e[0m"
	@echo -e "\e[1;93mReminder :To test is to doubt :)\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=integration

functional-tests: ## Run all functional tests
	@echo -e "\e[32mRunning functional tests...\e[0m"
	@echo -e "\e[1;93mReminder :To test is to doubt :)\e[0m"
	@$(EXEC_PHP) bin/phpunit --testsuite=functional

database: ## Create database for project
	@echo -e "\e[32mSetup database...\e[0m"
	$(EXEC_SYMFONY) doctrine:database:drop --if-exists --force --env=$(env)
	$(EXEC_SYMFONY) doctrine:database:create --env=$(env)
	$(EXEC_SYMFONY) doctrine:schema:update --force --env=$(env)

fixtures: ## Creates the false data necessary for development
	@echo -e "\e[32mLoad fixtures...\e[0m"
	make graph env=$(env)
	$(EXEC_SYMFONY) doctrine:fixtures:load -n --env=$(env)

cc: ## Clear the cache of the specify env
	@echo -e "\e[32mCache clear...\e[0m"
	$(EXEC_SYMFONY) cache:clear --env=$(env)

cc-test: ## Clear the cache for the test environment
	@echo -e "\e[32mCache test clear...\e[0m"
	make cc env=test

cc-dev: ## Clear the cache for the dev environment
	@echo -e "\e[32mCache dev clear...\e[0m"
	make cc env=dev

ng-lint: ## Lint angular
	@echo -e "\e[32mLint angular...\e[0m"
	$(EXEC_NG) lint

fix: php-cs-fixer

prepare: database fixtures

prepare-test: ## Create the database and the fake data necessary for the testing
	@echo -e "\e[32mPrepare test is started...\e[0m"
	make database env=test
	make fixtures env=test

prepare-dev: ## Create the database and the fake data necessary for the development
	@echo -e "\e[32mPrepare dev is started...\e[0m"
	make database env=dev
	make fixtures env=dev

graph: ## Setup graph database
	@echo -e "\e[32mSetup graph database...\e[0m"
	$(EXEC_SYMFONY) app:neo4j:delete-nodes --env=$(env)

graph-dev: ## Setup graph database for env
	echo -e "\e[32mSetup graph database for dev env...\e[0m"
	make graph env=dev

graph-test: ## Setup graph database for test
	echo-e "\e[32mSetup graph database for test env...\e[0m"
	make graph env=test