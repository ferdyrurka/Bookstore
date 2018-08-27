<?php

namespace App\Tests\Service;

use App\Service\UploadFile;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadFileTest
 * @package App\Tests\Service
 */
class UploadFileTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testUpload(): void
    {
        $uploadedFile = Mockery::mock(UploadedFile::class);
        $uploadedFile->shouldReceive('guessExtension')->once()->andReturn('.jpg');
        $uploadedFile->shouldReceive('getClientOriginalName')->once()->andReturn('test_name_file');
        $uploadedFile->shouldReceive('move')->once();

        $uploadFile = new UploadFile('test');
        $uploadFile->upload($uploadedFile);
    }
}
