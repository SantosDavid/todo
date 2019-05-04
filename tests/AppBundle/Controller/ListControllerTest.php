<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => '123456',
        ]);
    }

    public function testIndex()
    {
        $this->client->request('GET', '/lists/');

        $this->assertContains(
            'Lista de tarefas',
            $this->client->getResponse()->getContent()
        );

        $this->assertContains(
            'listar',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/lists/create');

        $form = $crawler->selectButton('save')->form();

        $this->client->submit($form, ['name' => 'New list', 'description' => 'list create to test']);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}