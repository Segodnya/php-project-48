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

test-with-coverage:
	vendor/bin/phpunit --coverage-clover tests/reports/coverage.xml

# Example usage
run:
	./bin/gendiff ./assets/file1.json ./assets/file2.json
