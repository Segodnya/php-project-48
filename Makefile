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
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

# Example usage
run:
	./bin/gendiff ./assets/file1.json ./assets/file2.json
