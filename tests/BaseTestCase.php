<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseTestCase extends KernelTestCase
{
    protected function getPrivateContainer()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // gets the special container that allows fetching private services
        $container = self::$container;
        return $container;
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}