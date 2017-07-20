<?php

namespace Codeception\Module;

use Codeception\Lib\Framework;
use Codeception\Lib\Interfaces\ActiveRecord;
use SilverStripe\Dev\TestKernel;
use SilverStripe\ORM\DataObject;

class SilverStripe4 extends Framework implements ActiveRecord
{

    /**
     * @var TestKernel
     */
    protected $kernel;

    public function _beforeSuite($settings = [])
    {
        parent::_beforeSuite($settings);
        $this->kernel = new TestKernel(BASE_PATH);
        $this->kernel->boot();
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