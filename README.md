# oxid-esales/graphql-catalogue

[![Build Status](https://travis-ci.com/OXID-eSales/graphql-catalogue-module.svg?token=zzAxEw7xZ3Tyg3ueXC3g&branch=master)](https://travis-ci.com/OXID-eSales/graphql-catalogue-module)

This module provides:
- stuff to be done

## Usage

This assumes you have OXID eShop (at least `OXID-eSales/oxideshop_ce: v6.5.0` component, which is part of the `6.2.0` compilation) up and running.

### Install

```bash
$ composer require oxid-esales/graphql-catalogue
```

After requiring the module, you need to head over to the OXID eShop admin and
activate the GraphQL Catalogue module.

### How to use

TBD

## Testing

### Linting, Syntax and static analysis

```bash
$ composer test
```

### Unit tests

- install this module into a running OXID eShop
- change the `test_config.yml`
  - add `oe/graphql-catalogue` to the `partial_module_paths`
  - set `activate_all_modules` to `true`

```bash
$ ./vendor/bin/runtests
```

## Build with

- [GraphQLite](https://graphqlite.thecodingmachine.io/)

## License

TBD
