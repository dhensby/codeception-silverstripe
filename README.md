# Codeception SilverStripe Framework Module

This module links [Codeception](https://github.com/codeception) to the [SilverStripe Framework](https://github.com/silverstripe).

This allows you to use Codeception to run Functional tests off of SilverStripe.

## Installation

`composer require dhensby/codeception-silverstripe --dev`

## Usage

Update your `functional.suite.yml` file so it looks like this:

```yml
# Codeception Test Suite Configuration
#
# Suite for functional tests
# Emulate web requests and make application process them
# Include one of framework modules (Symfony2, Yii2, Laravel5) to use it
# Remove this suite if you don't use frameworks

actor: FunctionalTester
modules:
    enabled:
        # add a framework module here
        - SilverStripe
        - \Helper\Functional
```

and you're done... Happy testing!