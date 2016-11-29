<?php

/**
 * This file is part of slick/users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Psr\Http\Message\UploadedFileInterface;

/**
 * Picture Updater Interface
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface PictureUpdaterInterface
{

    /**
     * Change the profile picture of current logged in account
     *
     * @param UploadedFileInterface $file
     *
     * @return $this|self|PictureUpdaterInterface
     */
    public function setFile(UploadedFileInterface $file);
}
