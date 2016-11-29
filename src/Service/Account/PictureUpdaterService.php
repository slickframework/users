<?php

/**
 * This file is part of Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Psr\Http\Message\UploadedFileInterface;

/**
 * Picture Updater Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PictureUpdaterService extends AccountService implements
    PictureUpdaterInterface
{

    /**
     * Change the profile picture of current logged in account
     *
     * @param UploadedFileInterface $file
     *
     * @return $this|self|PictureUpdaterInterface
     */
    public function setFile(UploadedFileInterface $file)
    {
        // TODO: Implement setFile() method.
    }
}