<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected Generator $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
    }
}
