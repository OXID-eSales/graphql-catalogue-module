# oxid-esales/graphql-catalogue

[![Build Status](https://img.shields.io/travis/com/OXID-eSales/graphql-catalogue-module/master.svg?style=for-the-badge&logo=travis)](https://travis-ci.com/OXID-eSales/graphql-catalogue-module) [![PHP Version](https://img.shields.io/packagist/php-v/oxid-esales/graphql-catalogue.svg?style=for-the-badge)](https://github.com/oxid-esales/graphql-catalogue-module) [![Stable Version](https://img.shields.io/packagist/v/oxid-esales/graphql-catalogue.svg?style=for-the-badge&label=latest)](https://packagist.org/packages/oxid-esales/graphql-catalogue)

This module provides [GraphQL](https://www.graphql.org) queries and mutations for the [OXID eShop](https://www.oxid-esales.com/) allowing access to:
- Manufacturers and vendors
- Categories
- Products and their attributes
- Reviews
- Ratings

## Usage

This assumes you have OXID eShop (at least `OXID-eSales/oxideshop_ce: v6.5.0` component, which is part of the `6.2.0` compilation) up and running.

### Install

```bash
$ composer require oxid-esales/graphql-catalogue
```

After requiring the module, you need to head over to the OXID eShop admin and
activate the GraphQL Catalogue module.

## Testing

### Linting, syntax check, static analysis and unit tests

```bash
$ composer test
```

### Integration/Acceptance tests

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

GPLv3, see [LICENSE file](LICENSE).
