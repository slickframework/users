<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\I18n\TranslateMethods;
use Slick\Mvc\Controller;
use Slick\Mvc\Http\FlashMessagesMethods;

/**
 * Upload controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Upload extends Controller
{

    /**
     * Needed to set the flash messages
     */
    use FlashMessagesMethods;

    /**
     * Used in messages translation
     */
    use TranslateMethods;

    /**
     * Handles the picture upgrade form submission
     */
    public function handle()
    {
        $this->addSuccessMessage(
            $this->translate(
                "Your picture was successfully updated."
            )
        );
        $this->redirect($this->getRequest()->getHeaderLine('referer'));
    }
}
