.PHONY: build tests

PHP_XDEBUG = php
PHP = XDEBUG_MODE=off php

install: composer-install

composer-install:
	@echo "\nInstall dependencies...\e[0m"
	composer install

composer-update:
	@echo "\Update dependencies...\e[0m"
	composer update

analyse: composer-valid container-linter phpcpd churn-php phpstan

phpstan:
	@echo "\nRunning phpstan...\e[0m"
	$(PHP) vendor/bin/phpstan analyse src/ --configuration=phpstan.neon

php-cs-fixer:
	@echo "\nRunning phpinsights...\e[0m"
	@$(PHP) vendor/bin/php-cs-fixer fix

phpcpd:
	@echo "\nRunning phpcpd...\e[0m"
	@$(PHP) vendor/bin/phpcpd src

churn-php:
	@echo "\nRunning churn-php...\e[0m"
	@$(PHP) vendor/bin/churn run --configuration=churn.yml

container-linter:
	@echo "\nRunning container linter...\e[0m"
	@$(PHP) bin/console lint:container

composer-valid:
	@echo "\nRunning container valid...\e[0m"
	composer valid

tests:
	@echo "\nRunning tests...\e[0m"
	@$(PHP) bin/phpunit

tests-coverage:
	@echo "\nRunning tests coverage...\e[0m"
	$(PHP_XDEBUG) bin/phpunit

unit-tests:
	@echo "\nRunning unit tests...\e[0m"
	@$(PHP) bin/phpunit --testsuite=unit

component-tests:
	@echo "\nRunning component tests...\e[0m"
	@$(PHP) bin/phpunit --testsuite=component

integration-tests:
	@echo "\nRunning integration tests...\e[0m"
	@$(PHP) bin/phpunit --testsuite=integration

smoke-tests:
	@echo "\nRunning smoke tests...\e[0m"
	@$(PHP) bin/phpunit --testsuite=smoke

functional-tests:
	@echo "\nRunning functional tests...\e[0m"
	@$(PHP) bin/phpunit --testsuite=functional

end-to-end-tests:
	@echo "\nRunning end to end tests...\e[0m"
	@$(PHP) bin/phpunit --testsuite=end-to-end