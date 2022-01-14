.PHONY: build tests

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
	php vendor/bin/phpstan analyse src/ --configuration=phpstan.neon

php-cs-fixer:
	@echo "\nRunning phpinsights...\e[0m"
	php vendor/bin/php-cs-fixer fix

phpcpd:
	@echo "\nRunning phpcpd...\e[0m"
	php vendor/bin/phpcpd src

churn-php:
	@echo "\nRunning churn-php...\e[0m"
	php vendor/bin/churn run --configuration=churn.yml

container-linter:
	@echo "\nRunning container linter...\e[0m"
	php bin/console lint:container

composer-valid:
	@echo "\nRunning container valid...\e[0m"
	composer valid

tests:
	@echo "\nRunning tests...\e[0m"
	php bin/phpunit
