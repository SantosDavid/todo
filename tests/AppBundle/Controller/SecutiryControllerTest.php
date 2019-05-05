<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\ResetDatabase;

class SecutiryControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testAccessWithoutIsLogged()
    {
        $client = static::createClient([]);

        $client->request('GET', '/lists/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => '123456',
        ]);

        $client->request('GET', '/lists/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}