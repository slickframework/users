<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Mvc\Controller;

/**
 * Pages controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Pages extends Controller
{

    /**
     * Handle request for home page
     */
    public function home()
    {

    }

    /**
     * Forbidden page
     */
    public function forbidden()
    {
        $this->response = $this->getResponse()->withStatus(403);
    }
}
