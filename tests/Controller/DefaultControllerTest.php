<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\TestCase;

class DefaultControllerTest extends TestCase
{
    public function testIndex(): void
    {
        // * Instead:
        // 1. Remove it now: composer remove --dev phpunit/phpunit
        // 2. Use Symfony's bridge: composer require --dev phpunit

        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testNotFound(): void
    {
        // * Instead:
        // 1. Remove it now: composer remove --dev phpunit/phpunit
        // 2. Use Symfony's bridge: composer require --dev phpunit/phpunit

        $client = static::createClient();
        $client->request('GET', '/not-found');

        $this->assertFalse($client->getResponse()->isSuccessful());
    }
}
