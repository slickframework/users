<?php

/**
 * This file is part of Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Form;

use Slick\Users\Form\PictureForm;
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
        $_FILES = [
            'avatar' => [
                'size' => 15476,
                'type' => 'image/jpeg',
                'error' => UPLOAD_ERR_OK,
                'tmp_name' => '/tmp/phpn3FmFr',
                'name' => 'avatar.jpg'
            ]
        ];
        $form = UsersForms::getPictureForm();
        $form->get('avatar')->setValue($_FILES['avatar']);
        $file = $form->getUploadedPicture();
        $this->assertEmpty('avatar.jpg', $file->getClientFilename());
        $_FILES = $back;
    }
}
