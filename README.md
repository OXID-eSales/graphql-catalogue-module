# oxid-esales/graphql-catalogue

[![Build Status](https://flat.badgen.net/travis/OXID-eSales/graphql-catalogue-module/?icon=travis&label=build&cache=300&scale=1.1)](https://travis-ci.com/OXID-eSales/graphql-catalogue-module)
[![PHP Version](https://flat.badgen.net/packagist/php/OXID-eSales/graphql-catalogue/?cache=300&scale=1.1)](https://github.com/oxid-esales/graphql-catalogue-module)
[![Stable Version](https://flat.badgen.net/packagist/v/OXID-eSales/graphql-catalogue/latest/?label=latest&cache=300&scale=1.1)](https://packagist.org/packages/oxid-esales/graphql-catalogue)

This module provides [GraphQL](https://www.graphql.org) queries and mutations for the [OXID eShop](https://www.oxid-esales.com/) allowing access to:
- Manufacturers and vendors
- Categories
- Products and their attributes
- Reviews
- Ratings

## Usage

This assumes you have OXID eShop (at least `oxid-esales/oxideshop_ce: v6.5.0` component, which is part of the `v6.2.0` compilation) up and running.

### Install

```bash
$ composer require oxid-esales/graphql-catalogue
```

After requiring the module, you need to head over to the OXID eShop admin and activate the GraphQL Catalogue module. If you did not had the `oxid-esales/graphql-base` module already installed, composer will do that for you, but don't forget to also activate this in the OXID eShop admin.

### How to use

A good starting point is to check the [How to use section in the GraphQL Base Module](https://github.com/OXID-eSales/graphql-base-module/#how-to-use)

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

## Contributing

You like to contribute? 🙌 AWESOME 🙌\
Go and check the [contribution guidelines](CONTRIBUTING.md)

## Build with

- [GraphQLite](https://graphqlite.thecodingmachine.io/)

## License

GPLv3, see [LICENSE file](LICENSE).
