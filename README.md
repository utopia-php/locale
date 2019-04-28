# Utopia Locale

Utopia framework locale library is simple and lite library for managing application translations and localization. This library is aiming to be as simple and easy to learn and use.

Although this library is part of the [Utopia Framework](https://github.com/utopia-php/framework) project it is dependency free and can be used as standalone with any other PHP project or framework.

## Getting Started

```php
<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Utopia\Locale\Locale;

// Init translations
Locale::setLanguage('en-US', ['hello' => 'Hello','world' => 'World']); // Set English
Locale::setLanguage('he-IL', ['hello' => 'שלום',]); // Set Hebrew, Any missing items will fallback to english

// Get translation

echo Locale::getText('hello'); // prints "שלום"
echo Locale::getText('world'); // prints "World"
```

## System Requirements

Utopia Framework requires PHP 7.1 or later. We recommend using the latest PHP version whenever possible.

## Authors

**Eldad Fux**

+ [https://twitter.com/eldadfux](https://twitter.com/eldadfux)
+ [https://github.com/eldadfux](https://github.com/eldadfux)

## Copyright and license

The MIT License (MIT) [http://www.opensource.org/licenses/mit-license.php](http://www.opensource.org/licenses/mit-license.php)