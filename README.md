## Somfy Php Framework

![logo0](https://www.somfypro.nl/image/journal/article?img_id=73615698&t=1567601687139)	![logo1](https://d1yjjnpx0p53s8.cloudfront.net/styles/logo-thumbnail/s3/062015/php_0.png?itok=W6WL-Rbh)	

## Background
This project's goal is to provide anyone who needs to script automation, a collection of functions that call Somfy APIs.

## Functions Reference

The below section is a list of all existing functions in this framework.

### Index
````
syGetDevices($tahomaPin,$token)
syGetToken($syUsername,$syPassword)
syLedOff($tahomaPin,$token,$LEDiD)
syLedOn($tahomaPin,$token,$LEDiD)
syLedSetIntensity($tahomaPin,$token,$LEDiD,$intensity)
syRoofClose($tahomaPin,$token,$roofID)
syRoofOpen($tahomaPin,$token,$roofID)
syRoofSetOrientation($tahomaPin,$token,$roofID,$position)
syRoofStop($tahomaPin,$token,$roofID)
syScreenDown($tahomaPin,$token,$screenID)
syScreenStop($tahomaPin,$token,$screenID)
syScreenUp($tahomaPin,$token,$screenID)
````

### Explanation

> **syGetDevices($tahomaPin,$token)**

This function is listing all devices associated with your Tahoma gateway.

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 


- Usage sample : 

```php
$var=syGetDevices($tahomaPin,$token);
var_dump($var);
```

The above will display : 

```
  [0]=>
  array(3) {
    ["name"]=>
    string(6) "Led 1 "
    ["url"]=>
    string(27) "io://20xx-30xx-05xx/5101624"
    ["type"]=>
    string(11) "DimmerLight"
  }
  [1]=>
  array(3) {
    ["name"]=>
    string(5) "Led 2"
    ["url"]=>
    string(27) "io://20xx-30xx-05xx/5839490"
    ["type"]=>
    string(11) "DimmerLight"
  }

```

> **syGetToken($syUsername,$syPassword)**

This function is generating a Somfy developer token to use in your apps.

| Input | Description |
| ----------|----------|
| `$syUsername`   | Your Somfy username (usually email address) | 
| `$syPassword`   | Your Somfy password | 


- Usage sample : 

```php
$var=syGetToken($syUsername,$syPassword);
var_dump($var);
```

The above will display : 

```
string(20) "650635243e54fd50afb0" 
```

> **syLedOff($tahomaPin,$token,$LEDiD)**

This function is switching LED off at address $LEDiD

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$LEDiD`   | IO address of your LED | 


- Usage sample : 

```php
$var=syLedOff($tahomaPin,$token,$LEDiD);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syLedOn($tahomaPin,$token,$LEDiD)**

This function is switching LED on at address $LEDiD

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$LEDiD`   | IO address of your LED | 


- Usage sample : 

```php
$var=syLedOn($tahomaPin,$token,$LEDiD);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syLedSetIntensity($tahomaPin,$token,$LEDiD,$intensity)**

This function is setting LED intensity from 0% to 100%

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$LEDiD`   | IO address of your LED | 
| `$intensity`   | Intensity in %age from 0 to 100 | 


- Usage sample : 

```php
$var=syLedSetIntensity($tahomaPin,$token,$LEDiD,$intensity);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syRoofClose($tahomaPin,$token,$roofID)**

This function is fully closing door blades

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$roofID`   | IO address of your roof | 


- Usage sample : 

```php
$var=syRoofClose($tahomaPin,$token,$roofID);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syRoofOpen($tahomaPin,$token,$roofID)**

This function is fully opening roof blades

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$roofID`   | IO address of your roof | 


- Usage sample : 

```php
$var=syRoofOpen($tahomaPin,$token,$roofID);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syRoofSetOrientation($tahomaPin,$token,$roofID,$position)**

This function is setting orientation of your roof blades

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$roofID`   | IO address of your roof | 
| `$position`   | Position of your blades from 0 to 255 | 


- Usage sample : 

```php
$var=syRoofSetOrientation($tahomaPin,$token,$roofID,$position);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syRoofStop($tahomaPin,$token,$roofID)**

This function is stopping roof blades movement

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$roofID`   | IO address of your roof | 


- Usage sample : 

```php
$var=syRoofStop($tahomaPin,$token,$roofID);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syScreenDown($tahomaPin,$token,$screenID)**

This function is deploying a solar screen protection

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$screenID`   | IO address of your screen | 


- Usage sample : 

```php
$var=syScreenDown($tahomaPin,$token,$screenID);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syScreenStop($tahomaPin,$token,$screenID)**

This function stops a moving screen

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$screenID`   | IO address of your screen | 


- Usage sample : 

```php
$var=syScreenStop($tahomaPin,$token,$screenID);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

> **syScreenUp($tahomaPin,$token,$screenID)**

This function is retracting a solar screen protection

| Input | Description |
| ----------|----------|
| `$tahomaPin`   | Your Tahoma PIN | 
| `$token`   | Your Somfy Developer token | 
| `$screenID`   | IO address of your screen | 


- Usage sample : 

```php
$var=syScreenUp($tahomaPin,$token,$screenID);
var_dump($var);
```

The above will display : 

```
string(50) "{"execId": "3fa85f64-5717-4562-b3fc-2c963f66afa6"}"
```

## Todo List

Need to fix the roof positionning function, need to figure out how it works, so far this is not working as expecting.

## Versioning

v 0.1

## Thanks to

Somfy [GitHub](https://github.com/Somfy-Developer/Somfy-TaHoma-Developer-Mode) and this amazing post in French [here](https://community.jeedom.com/t/commande-somfy-tahoma-avec-l-api-locale/106397).

## Authors

Frederic Lhoest - *[@flhoest](https://twitter.com/flhoest)*

> Disclaimer : This documentation has been generated with *docGen.php v0.3*. An Open Source initiative producing easy and accurate documentation of php codes in seconds.
Available on [gitHub](https://github.com/flhoest/docGen).
