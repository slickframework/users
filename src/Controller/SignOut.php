<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Mvc\Controller;

class SignOut extends Controller
{

    public function handle()
    {
        session_regenerate_id(true);
        $_SESSION = [];
        $this->redirect('home');
    }
}