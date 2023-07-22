# Utils Package for LiveControls
 ![Release Version](https://img.shields.io/github/v/release/live-controls/utils)
 ![Packagist Version](https://img.shields.io/packagist/v/live-controls/utils?color=%23007500)

Utilities Package for LiveControls. Some of the other LiveControls packages will depend on this library.

## Usage

### Arrays

#### array_get
```php
$array['val1' => 'Value1', 'val2' => 'Value2'];
echo array_get('val1', $array); // 'Value1'
echo array_get('val3', $array); // null
echo array_get('val3', $array, 'no_value'); // 'no_value'
```

#### array_remove
```php
$array['val1' => 'someValue', 'val2' => 'someOtherValue'];
array_remove('val1', $array);
echo $array; //['val2' => 'someOtherValue']
```

#### array_remove_value

```php
$array ['someValue', 'someOtherValue'];
array_remove_value('someValue', $array);
echo $array; //['someOtherValue']
```

### BBCodes

#### Valid bb tags
* [b][/b]
* [i][/i]
* [s][/s]
* [u][/u]
* [img][/img]
* [img=varxvar][/img]
* [center][/center]
* [justify][/justify]
* [right][/right]
* [ul][/ul]
* [ol][/ol]
* [li][/li]
* [url=][/url]
* [url][/url]
* [email=][/email]
* [size=][/size]
* [color=][/color]
* [hr]
* [sub][/sub]
* [sup][/sup]

### Utils

#### countNumber
Counts the amonut of numbers inside a number

```php
$var = 1234;
echo countNumber($var); //'4'
```

#### calculateDaysInMonth
Calculates the days between $fromDay and $toDay over a specific month

```php
echo calculateDaysInMonth(0,5,11,2022); //returns the days in month as integer
```

#### number2Text
Transforms the number in cents to its textform. Needs NumberFormatter for it to work!

```php
echo number2Text(100, 'pt_BR'); //returns the text representation of the number by its locale
```

#### leadingZeros
Adds leading zeros to a number

```php
echo leadingZeros(10, 4, false); //returns 0010
echo leadingZeros(10, 1, true); //throws exception because thrid parameter (isMax) is true and the number has more than 1 digit (second parameter {length})
```

#### number2Currency
Transforms a number into its currency counterpart. Needs NumberFormatter for it to work!

```php
echo number2Currency(1000.20, 'pt_br', 'USD'); //returns $1.000,20
```