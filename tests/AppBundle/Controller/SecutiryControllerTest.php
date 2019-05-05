<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\ResetDatabase;

class SecutiryControllerTest extends WebTestCase
{
    use ResetDatabase;

    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient([]);
    }

    public function testAccessWithoutIsLogged()
    {
        $this->client->request('GET', '/lists/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}