# Utils Package for LiveControls
 ![Release Version](https://img.shields.io/github/v/release/live-controls/utils)
 ![Packagist Version](https://img.shields.io/packagist/v/live-controls/utils?color=%23007500)

An utilities package for Laravel 11+, it includes various categories of useful methods to ease the life of a developer and
speed up the process.

**To use the old system with a single Utils class, use the versions v1.x**

## Classes

### AB
An easy way to make AB tests based on variants.

### Arrays
An array helper to add some functions that are present in Laravel collections, but not in PHP arrays and more.

### BBCodes
Contains helpers to convert BBCodes.

### Blogging
Contains helpers to make it easier for creating blogs.

### CSV
Contains methods to handle CSV files like import, export and more.

### Files
Contains helpers to handle Files like get their Mimetypes and more.

### Numbers
Contains helpers to handle numbers with ease.

### Others
Several methods that do not fit into other categories.

### SocialSecurity
Contains helpers to handle brazilian social security numbers.

### Str
Contains helpers to manipulate strings.

### Time
Contains helpers to Carbon time objects and more.

## Enums

#### SocialSecurityNumberTypes
Contains several types of brazilian social security numbers to ease their check and manipulation. Is used by the SocialSecurity class.

## Exceptions

#### DeprecatedException
An exception that can be used in testing to show deprecated methods.

#### NotImplementedException
An exception that can be used to show not implemented methods.

## Traits

#### HasABTests
A trait that will handle AB tests for Laravel models.