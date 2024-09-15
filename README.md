# Diff Generator

### Hexlet tests and linter status:
[![Actions Status](https://github.com/Segodnya/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/Segodnya/php-project-48/actions) [![ci](https://github.com/Segodnya/php-project-48/actions/workflows/ci.yml/badge.svg)](https://github.com/Segodnya/php-project-48/actions/workflows/ci.yml) [![Maintainability](https://api.codeclimate.com/v1/badges/e1cc75fd5034915e0723/maintainability)](https://codeclimate.com/github/Segodnya/php-project-48/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/e1cc75fd5034915e0723/test_coverage)](https://codeclimate.com/github/Segodnya/php-project-48/test_coverage)

## Manual

```
./bin/gendiff -h
```

## Usage

```
./bin/gendiff tests/Fixtures/file1.json tests/Fixtures/file2.json
./bin/gendiff tests/Fixtures/file1.yml tests/Fixtures/file2.yaml
```
or
```
make run-json
make run-yaml
```

## ASCII Cast

- JSON

[![asciicast](https://asciinema.org/a/675093.svg)](https://asciinema.org/a/675093)

- YAML

[![asciicast](https://asciinema.org/a/675184.svg)](https://asciinema.org/a/675184)

- Recursive

[![asciicast](https://asciinema.org/a/676076.svg)](https://asciinema.org/a/676076)

- Plain Format

[![asciicast](https://asciinema.org/a/676108.svg)](https://asciinema.org/a/676108)
