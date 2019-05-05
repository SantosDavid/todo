<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\Authenticate;
use Tests\AppBundle\ResetDatabase;

class PostControllerTest extends WebTestCase
{
    use Authenticate;
    use ResetDatabase;

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

    public function testCreateWithDuplicatedName()
    {
        $crawler = $this->client->request('GET', '/lists/create');

        $form = $crawler->selectButton('save')->form();

        $this->client->submit($form, ['name' => 'New list', 'description' => 'list create to test']);
        $this->client->submit($form, ['name' => 'New list', 'description' => 'list create to test']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}