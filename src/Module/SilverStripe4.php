<?php

namespace Codeception\Module;

use Codeception\Lib\Framework;
use Codeception\Lib\Interfaces\ActiveRecord;
use Codeception\TestInterface;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\TestKernel;
use SilverStripe\ORM\DataObject;

class SilverStripe4 extends Framework implements ActiveRecord
{

    /**
     * @var TestKernel
     */
    protected $kernel;

    /**
     * @var TestKernel[]
     */
    protected $kernelStack = [];

    protected function nest()
    {
        if ($this->kernel) {
            $this->kernel = $this->kernel->nest();
            $this->kernelStack[] = $this->kernel;
        } else {
            $this->debug('Nothing to nest');
        }
        Injector::nest();
        Config::nest();
    }

    protected function unnest()
    {
        if (!empty($this->kernelStack)) {
            $this->kernel = array_pop($this->kernelStack);
            $this->kernel->activate();
        } else {
            $this->debug('Nothing to unnest');
        }
        Injector::unnest();
        Config::unnest();
    }

    public function _initialize()
    {
        parent::_initialize();
        require_once FRAMEWORK_PATH . '/tests/bootstrap/environment.php';
        $kernel = new TestKernel(BASE_PATH);
        $kernel->boot();
        $this->kernel = $kernel;
    }

    public function _beforeSuite($settings = [])
    {
        parent::_beforeSuite($settings);
        $this->nest();
    }

    public function _afterSuite()
    {
        parent::_afterSuite();
        $this->unnest();
    }

    public function _before(TestInterface $test)
    {
        parent::_before($test);
        $this->nest();
    }

    public function _after(TestInterface $test)
    {
        parent::_after($test);
        $this->unnest();
    }

    public function haveRecord($model, $attributes = [])
    {
        /** @var DataObject $obj */
        $obj = $model::create();
        $obj->update($attributes);
        $obj->write();
        return $obj;
    }

    public function seeRecord($model, $attributes = [])
    {
        if (!$model::get()->filter($attributes)->exists()) {
            $this->fail("Could not find $model with " . json_encode($attributes));
        }
    }

    public function dontSeeRecord($model, $attributes = [])
    {
        if ($model::get()->filter($attributes)->exists()) {
            $this->fail("Unexpectedly found matching $model with " . json_encode($attributes));
        }
    }

    public function grabRecord($model, $attributes = [])
    {
        $datalist = $model::get()->filter($attributes);
        if (!$datalist->exists()) {
            $this->fail("Could not find $model with " . json_encode($attributes));
        }

        return $datalist->first();
    }

}