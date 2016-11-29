<?php

/**
 * This file is part of Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Psr\Http\Message\UploadedFileInterface;
use Slick\Users\Controller\Upload;
use Slick\Users\Form\PictureFormInterface;
use Slick\Users\Service\Account\PictureUpdaterInterface;

/**
 * Upload Test Case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UploadTest extends ControllerTestCase
{

    /**
     * @var Upload
     */
    protected $controller;

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Upload();
        parent::setUp();
    }

    /**
     * Should get the picture service from the dependency container
     * @test
     */
    public function testGetPictureService()
    {
        $service = \Phake::mock(PictureUpdaterInterface::class);
        $dep = ['pictureUpdater' => $service];
        $this->controller->setContainer($this->getContainerMock($dep));
        $this->assertSame($service, $this->controller->getPictureService());
    }

    /**
     * Should use the factory to create a picture form
     * @test
     */
    public function testGetPictureForm()
    {
        $form = $this->controller->getForm();
        $this->assertInstanceOf(PictureFormInterface::class, $form);
    }

    /**
     * Should get the picture service and set the file form the form
     * @test
     */
    public function testHandle()
    {
        $form = \Phake::mock(PictureFormInterface::class);
        $file = \Phake::mock(UploadedFileInterface::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        \Phake::when($form)->getUploadedPicture()->thenReturn($file);
        $service = \Phake::mock(PictureUpdaterInterface::class);
        $this->controller
            ->setForm($form)
            ->setPictureService($service)
            ->handle();
        \Phake::verify($service)->setFile($file);
    }
}
