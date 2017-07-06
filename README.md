# Calculator module
[![Build Status](https://scrutinizer-ci.com/g/WildPHP/module-calculator/badges/build.png?b=master)](https://scrutinizer-ci.com/g/WildPHP/module-calculator/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WildPHP/module-calculator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/WildPHP/module-calculator/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wildphp/module-calculator/v/stable)](https://packagist.org/packages/wildphp/module-calculator)
[![Latest Unstable Version](https://poser.pugx.org/wildphp/module-calculator/v/unstable)](https://packagist.org/packages/wildphp/module-calculator)
[![Total Downloads](https://poser.pugx.org/wildphp/module-calculator/downloads)](https://packagist.org/packages/wildphp/module-calculator)

Calculator module for WildPHP, allowing calculation of expressions from IRC and Telegram.

## System Requirements
If your setup can run the main bot, it can run this module as well.

## Installation
To install this module, we will use `composer`:

```composer require wildphp/module-calculator```

That will install all required files for the module. In order to activate the module, add the following line to your modules array in `config.neon`:

    - WildPHP\Modules\Calculator\Calculator

The bot will run the module the next time it is started.

## Usage
Pass any mathematical expression to the `calc` command.

## License
This module is licensed under the MIT license. Please see `LICENSE` to read it.
