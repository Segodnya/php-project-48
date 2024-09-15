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

stan:
	vendor/bin/phpstan analyse src tests bin --level 9

# Record a Cast

ascii-rec:
	asciinema rec demo.cast

ascii-upload:
	asciinema upload demo.cast

# Example usage

run-json:
	./bin/gendiff tests/Fixtures/file1.json tests/Fixtures/file2.json

run-yaml:
	./bin/gendiff tests/Fixtures/file1.yml tests/Fixtures/file2.yaml

run-recursive:
	./bin/gendiff tests/Fixtures/file3.json tests/Fixtures/file4.json

run-plain:
	./bin/gendiff tests/Fixtures/file3.json tests/Fixtures/file4.json --format=plain
