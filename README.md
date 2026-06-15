# Utils Package for LiveControls
 ![Release Version](https://img.shields.io/github/v/release/live-controls/utils)
 ![Packagist Version](https://img.shields.io/packagist/v/live-controls/utils?color=%23007500)

An utilities package for Laravel 11+, it includes various categories of useful methods to ease the life of a developer and
speed up the process.

**To use the old system with a single Utils class, use the versions v1.x or use the v1 branch**

## Classes

### AB
An easy way to make AB tests based on variants.

#### getVariantForModel
Gets the variant for the model (Either 1 or 2) based on their primaryKey. Use the salt to have different outcomes for different tests

Example:
```php
$model = \App\Models\User::find(1);

$variant = \LiveControls\Utils\AB::getVariantForModel($model, 'feature_a');

dd($variant); //Returns either 1 or 2
```

#### hasVariant
Check if the Model is inside the selected variant based on their primaryKey. Use the salt to have different outcomes for different tests

Example:
```php
$variant = 1; //Can also be A or 2/B
$model = \App\Models\User::find(1);

$hasVariant = hasVariant($model, $variant, 'feature_a');

dd($hasVariant); //Would return true if model is inside variant with the selected salt
```


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

In your Model add the following:
```php
use \LiveControls\Utils\HasABTests;
```

You can then use it the following:
```php
$model = \App\Models\User::find(1);
$variant = 1; //Can be either 1/A or 2/B
$salt = 'feature_1'; //Can be a name of the feature you want to check or can be leave out to have it general.
$model->hasVariant($variant, $salt); //Will return true if its the models variant

$model->getVariantForModel($salt); //Returns either 1 or 2, depending on the models variant.
$model->has_variant_a; //Returns true if model has variant a. Does not support a salt.
$model->has_variant_b; //Returns true if model has variant b. Does not support a salt.
```