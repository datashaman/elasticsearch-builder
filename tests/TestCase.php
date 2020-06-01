<?php

namespace Datashaman\Elasticsearch\Builder\Tests;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TestCase extends PHPUnit\TestCase
{
    use MockeryPHPUnitIntegration;

    public function setUp(): void
    {
        parent::setUp();
    }
}
