install: #install project
	composer install
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 bin src tests
test:
	composer exec --verbose phpunit tests
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml