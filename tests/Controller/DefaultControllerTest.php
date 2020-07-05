<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        # * Instead:
        # 1. Remove it now: composer remove --dev phpunit/phpunit
        # 2. Use Symfony's bridge: composer require --dev phpunit

        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
