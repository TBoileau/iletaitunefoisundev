DOCKER_COMPOSE = docker-compose
EXEC_APACHE = $(DOCKER_COMPOSE) exec apache
EXEC_SYMFONY = $(DOCKER_COMPOSE) exec apache php bin/console

analyse: composer-unused container-linter security-checker phpmd phpcpd churn-php phpstan phpinsights

phpstan:
	@echo "\nRunning phpstan...\e[0m"
	@$(EXEC_APACHE) phpstan analyse src/ --configuration=phpstan.neon

phpinsights:
	@echo "\nRunning phpinsights...\e[0m"
	@$(EXEC_APACHE) phpinsights -n --config-path=phpinsights.php

phpmd:
	@echo "\nRunning phpmd...\e[0m"
	@$(EXEC_APACHE) phpmd src/ text .phpmd.xml

phpcpd:
	@echo "\nRunning phpcpd...\e[0m"
	@$(EXEC_APACHE) phpcpd src

churn-php:
	@echo "\nRunning churn-php...\e[0m"
	@$(EXEC_APACHE) churn run --configuration=churn.yml

composer-unused:
	@echo "\nRunning composer-unused...\e[0m"
	@$(EXEC_APACHE) composer-unused

container-linter:
	@echo "\nRunning container linter...\e[0m"
	@$(EXEC_SYMFONY) lint:container

security-checker:
	@echo "\nRunning container linter...\e[0m"
	@$(EXEC_APACHE) symfony check:requirements

fix:
	@echo "\nRunning php-cs-fixer...\e[0m"
	@$(EXEC_APACHE) php-cs-fixer fix src

