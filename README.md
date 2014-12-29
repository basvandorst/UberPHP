UberPHP
=========
[![Build Status](https://scrutinizer-ci.com/g/basvandorst/UberPHP/badges/build.png?b=master)](https://scrutinizer-ci.com/g/basvandorst/UberPHP/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/basvandorst/UberPHP/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/basvandorst/UberPHP/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/basvandorst/UberPHP/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/basvandorst/UberPHP/?branch=master)

**TLDR;** Uber API PHP client with OAuth authentication

In this GitHub repository you can find the PHP implementation of the 
Uber API. The current version of UberPHP combines the V1 Uber API 
with a proper OAuth authentication.

_"We believe that any app with a map is a potential Uber API partner. To kick 
things off, we’re launching with 11 fantastic companies, all of whom have 
already integrated with the API and are rolling out in cities around the 
world: Expensify, Hinge, Hyatt Hotels & Resorts, Momento, OpenTable, 
Starbucks Coffee Company, Tempo Smart Calendar, Time Out, TripAdvisor, 
TripCase and United Airlines_[[...]](http://blog.uber.com/api)"

## Getting started
### Get your API key
All calls to the Uber API require an user or server token. Any registered [Uber user](https://m.uber.com/sign-up?client_id=CW8huQUPMiC6Ld6gjHZkShrjRkuK4M7g) can obtain an access 
token by first creating an application at [developer.uber.com/](https://developer.uber.com/apps/)

### Composer package 
Use composer to install this UberPHP package.

```
{
    "require": {
        "basvandorst/UberPHP": "1.0.0"
    }
}
```


### UberPHP usage
#### Example without user authorisation
```php
<?php 
include 'vendor/autoload.php';

use Uber\API\Client;

try {
    $adapter = new Pest('https://api.uber.com/');
    
    $client = new Client($adapter, $token, false); // define SERVER token here
    $client->setLocale('nl_NL');
    
    $products = $client->products(38.9059540,-77.0419260);
    print_r($products);
    
    $estimatesPrice = $client->estimatesPrice(38.9059540,-77.0419260,37.9059540,-76.0419260);
    print_r($estimatesPrice);
    
    $estimatesTime = $client->estimatesTime(38.9059540,-77.0419260,37.9059540,-76.0419260);
    print_r($estimatesTime);
    
} catch(Exception $e) {
    print $e->getMessage();
}
```

#### Example with OAuth user authorisation
##### First, authorisation and authentication
```php
<?php 
include 'vendor/autoload.php';

use Uber\API\OAuth;

try {
    $options = array(
        'clientId'     => 12345, 
        'clientSecret' => 'CLIENT_SECRET',
        'redirectUri'  => 'CALLBACK_URI'
    );
    $oauth = new OAuth($options);
    
    if (!isset($_GET['code'])) {
        print '<a href="'.$oauth->getAuthorizationUrl().'">connect</a>';
    } else {
        $token = $oauth->getAccessToken('authorization_code', array(
            'code' => $_GET['code']
        ));
        print $token;
    }
} catch(\Exception $e) {
    print $e->getMessage();
}
```
##### Then, call the API method!
```php
<?php 
include 'vendor/autoload.php';

use Uber\API\Client;

try {
    $adapter = new Pest('https://api.uber.com/');
    $client = new Client($adapter, $token, true); // define USER token here
    
    $profile = $client->userProfile(false);
    print_r($profile);
    
    $activities = $client->userActivity();
    print_r($activities);
    
} catch(Exception $e) {
    print $e->getMessage();
}
```

## Accepted methods
### OAuth user authorisation required
```php
$client->userProfile();
$client->userActivity($lite = false);
```

### Only server authorisation required
```php
$client->products($latitude, $longitude);
$client->promotions($start_latitude, $start_longitude, $end_latitude, $end_longitude); (not working)
$client->estimatesPrice($start_latitude, $start_longitude, $end_latitude, $end_longitude)
$client->estimatesTime($start_latitude, $start_longitude, $end_latitude, $end_longitude)
```

## About UberPHP
### Used libraries
- [Uber API](https://developer.uber.com/)
- [thephpleague/oauth2-client](https://github.com/thephpleague/oauth2-client/)
- [educoder/pest](https://github.com/educoder/pest)

### Development
The UberPHP library was created by Bas van Dorst, [software engineer](https://www.linkedin.com/in/basvandorst).

### Contributing
All issues and pull requests should be filled on the basvandorst/UberPHP repository.

### License
The UberPHP library is open-source software licensed under MIT license.

