install: #install project
	composer install
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 bin
test:
	composer exec --verbose phpunit tests