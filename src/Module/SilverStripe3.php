<?php

namespace Codeception\Module;

use Codeception\Lib\Framework;
use Codeception\Lib\Connector\SilverStripe3 as Client;
use Codeception\TestInterface;

class SilverStripe3 extends Framework {

    public function _initialize()
    {
        parent::_initialize();
        require_once 'framework/tests/bootstrap.php';
    }

    public function _before(TestInterface $test)
    {
        if (!$this->client) {
            $this->client = new Client();
        }
        parent::_before($test);
    }

}
