<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Psr\Http\Message\UploadedFileInterface;
use Slick\Mvc\Form\EntityForm;

/**
 * Picture Form
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PictureForm extends EntityForm implements PictureFormInterface
{

    /**
     * Get the uploaded picture file
     *
     * @return UploadedFileInterface|null
     */
    public function getUploadedPicture()
    {
        $data = $this->getData();
        $file = null;
        if (array_key_exists('avatar', $data)) {
            $file = $data['avatar'];
        }
        return $file;
    }
}
