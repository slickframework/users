<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class BrowserContext extends MessagesContext
{

    /**
     * @When I restart the browser
     */
    public function restartBrowser()
    {
        $rememberMe = $this->getSession()->getCookie('users-rmm');
        $this->getSession()->restart();
        $this->visitPath('/');
        $this->getSession()->setCookie('users-rmm', $rememberMe);
    }
}
