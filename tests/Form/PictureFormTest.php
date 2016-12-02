<?php

/**
 * This file is part of slick/users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Form;

use Slick\Http\PhpEnvironment\Request;
use Slick\Users\Form\UsersForms;
use Slick\Users\Tests\TestCase;

/**
 * Picture Form Test Case
 *
 * @package Slick\Users\Tests\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PictureFormTest extends TestCase
{

    /**
     * Simply use the form
     */
    public function testGetFile()
    {
        $back = $_FILES;
        $post = $_POST;
        $_FILES = [
            'avatar' => [
                'size' => 15476,
                'type' => 'image/jpeg',
                'error' => UPLOAD_ERR_OK,
                'tmp_name' => '/tmp/phpn3FmFr',
                'name' => 'avatar.jpg'
            ]
        ];
        $_POST['form-id'] = 'picture-form';
        $request = (new Request())
            ->withParsedBody($_POST)
            ->withMethod(Request::METHOD_POST)
            ->withHeader('content-type', 'multipart\/form-data');
        $form = UsersForms::getPictureForm();
        $form->setRequest($request)->wasSubmitted();
        $file = $form->getUploadedPicture();
        $this->assertEquals('avatar.jpg', $file->getClientFilename());
        $_FILES = $back;
        $_POST = $post;
    }
}
