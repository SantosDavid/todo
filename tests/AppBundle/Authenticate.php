<?php

namespace Tests\AppBundle;

trait Authenticate
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
}
