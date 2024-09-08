install:
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.yml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

stan:
	vendor/bin/phpstan analyse src tests bin --level 9

# Example usage

run-json:
	./bin/gendiff tests/Fixtures/file1.json tests/Fixtures/file2.json

run-yaml:
	./bin/gendiff tests/Fixtures/file1.yml tests/Fixtures/file2.yaml
