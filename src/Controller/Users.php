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
 * Users controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Users extends Controller
{

    public function edit($id = 0)
    {
        $oId = $this->getRouteAttributes('id');
    }
}