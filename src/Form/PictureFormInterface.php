<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Psr\Http\Message\UploadedFileInterface;
use Slick\Form\FormInterface;

/**
 * Picture Form Interface
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface PictureFormInterface extends FormInterface
{

    /**
     * Get the uploaded picture file
     *
     * @return UploadedFileInterface|null
     */
    public function getUploadedPicture();
}
