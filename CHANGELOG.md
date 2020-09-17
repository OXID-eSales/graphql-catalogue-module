# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [unreleased]

### Added

- Default sorting for Products and Categories
- Language relation for product reviews.
- ProductStock::getEshopModel method [PR-14](https://github.com/OXID-eSales/graphql-catalogue-module/pull/14)

### Changed

- Repository::delete() takes loaded BaseModel as argument.
- `OxidEsales\GraphQL\Catalogue\Product\DataType\Price` moved to `OxidEsales\GraphQL\Catalogue\Shared\DataType\Price`
- Replaced Product field Category with Categories
- ProductStock DataType now implements DataType [PR-14](https://github.com/OXID-eSales/graphql-catalogue-module/pull/14)

### Removed

- `\OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase` moved to GraphQL Base Module

## [0.1.0] 2020-06-23

Initial release 🎉

[0.1.0]: https://github.com/OXID-eSales/graphql-catalogue-module/releases/tag/v0.1.0
