### PHP Telegram CLI 
a wrapper class for working with [tg-cli](https://github.com/vysheng/tg/) inspired by [zyberspace/php-telegram-cli-client](https://github.com/zyberspace/php-telegram-cli-client)

## install 

create a `composer.json` and put the following command in it : 
```
{
    "require": {
        "smoqadam/telegramcli": "@dev"
    }
}
```

then run `$ composer install`

## How to use
first start tg-cli :

`$ ./bin/telegram-cli --json -dWS /tmp/t.sck &`

then in your php file : 

```php
<?php

require 'vendor/autoload.php';
$t = new Smoqadam\TelegramCli('unix:///tmp/t.sck');
$contacts = $t->contact_list();
//print_r($contacts);
echo $t->post($contacts[0]['print_name'],'Hello');
```

The methods are exactly the same name as the original methods in tg-cli. For exapmple if you want to add new contact in tg-cli you must to use `add_contact <phone> <name> <family>` structure. In php-telegram-cli you must use the following command : 

```php 
$t->add_contact('phone','name','family');
```

## Contributing 

This is a bery simple class for using tg-cli. If you need some useful features, you can fork it. Please let me know if you find bugs or any problems by openning new issue.

