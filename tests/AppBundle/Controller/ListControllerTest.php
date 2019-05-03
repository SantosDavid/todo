<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => '123456',
        ]);

        $crawler = $client->request('GET', '/lists/');

        $this->assertContains(
            'Lista de tarefas',
            $client->getResponse()->getContent()
        );
    }
}