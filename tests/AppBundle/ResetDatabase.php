<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

trait ResetDatabase
{
    private $client;

    public function tearDown()
    {
        parent::tearDown();

        $application = new Application(Self::$kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:drop',
            '--force' => true,
            '--env' => 'test',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
            '--env' => 'test',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);
    }
}
