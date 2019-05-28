<?php

namespace Tests\AppBundle\Unit\Service\General;

use AppBundle\Service\General\FileUploaderService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends TestCase
{
    private $service;

    private $target = '/tmp/';

    public function setUp()
    {
        parent::setUp();

        $this->service = new FileUploaderService($this->target);
    }

    public function testDirectory()
    {
        $this->assertEquals(
            $this->target,
            $this->service->getTargetDirectory()
        );
    }

    public function testUpload()
    {
        $stub = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stub->expects($this->once())
            ->method('move')
            ->willReturn(true);

        $stub->expects($this->once())
            ->method('guessExtension')
            ->willReturn('jpg');

        $result = $this->service->upload($stub);

        $this->assertRegExp("/.+.jpg$/", $result);
    }
}