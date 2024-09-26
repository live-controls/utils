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

#### array_has_duplicates

```php
$array['someValue', 'someOtherValue'];
echo array_has_duplicates($array); //returns false
$array2['key1' => 'someValue', 'key2' => 'someValue'];
echo array_has_duplicates($array2); //returns true
$array3['1984', 1984];
echo array_has_duplicates($array3, true); //returns false, because type of value 0 is different than from value 1
$array4[1984, 1984];
echo array_has_duplicates($array4, true); //returns true, because type of value 0 and value 1 are identical
```

#### array_get_duplicates

```php
$array['someValue', 'someOtherValue'];
echo array_get_duplicates($array); //returns an empty array
$array2['key1' => 'someValue', 'key2' => 'someValue'];
echo array_get_duplicates($array2); //returns an array ['someValue']
$array3['1984', 1984];
echo array_get_duplicates($array3, true); //returns an empty array, because type of value 0 is different than from value 1
$array4[1984, 1984];
echo array_get_duplicates($array4, true); //returns an array [1984], because type of value 0 and value 1 are identical
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

### Blogging

#### estimatedReadingTime
Calculates the estimated reading time of a text in minutes, based on the words per minute

```php
$text = "This is a text with 400 words [...]"; //Imagine this text as 400 words long
echo estimatedReadingTime($text); //Would return 2 (minutes) as the default words per minute rate is 200
echo estimatedReadingTime($text, 100); //Would return 4 (minutes) as the second parameter of the function acts as words per minute 
```

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
echo leadingZeros(10, 1, true); //throws exception because third parameter (isMax) is true and the number has more than 1 digit (second parameter {length})
```

#### number2Currency
Transforms a number into its currency counterpart. Needs NumberFormatter for it to work!

```php
echo number2Currency(1000.20, 'pt_br', 'USD'); //returns $1.000,20
```

#### array2String
Converts an array to string with a delimiter

```php
echo array2String(['a','b','c'], '; '); //returns a; b; c
```

#### isNullOrEmpty
Checks if the string is null or empty

```php
echo isNullOrEmpty(' '); //returns true
```

#### calculateFormulas
TODO, do not use as this is in the testing phase!

#### toInteger
Removes all non-numeric characters and leading zeros from a string and returns an integer

```php
echo toInteger('a1234'); //returns 1234
echo toInteger('a01234'); //IMPORTANT, this would also return 1234 as an integer cant start with 0. In this case use toNumeric()
```

#### toNumeric
Removes all non-numeric characters from a string and returns an integer or string if it starts with a 0

```php
echo toNumeric('a01234'); //returns a string 01234
echo toNumeric('a1234'); //returns an integer 1234
```

#### stringContains
Checks if a string contains a certain needle

```php
echo stringContains('Test', 'T'); //returns true
echo stringContains('Test', 'Q'); //returns false
```

#### isValidCPF
Checks if the CPF number is valid

```php
echo isValidCPF('UM_CPF_VALIDO'); //returns true if cpf is valid
```

#### isValidCNPJ
Checks if the CNPJ number is valid

```php
echo isValidCNPJ('UM_CNPJ_VALIDO'); //returns true if cnpj is valid
```

#### toLatin
Converts string to a string with only latin characters

```php
echo toLatin('càdé'); //returns cade
```

#### importCSV
Imports CSV from a file and returns an array with headers as first line

```php
echo importCSV('test.csv'); //returns the content of the file test.csv as array with its first line as header
```
