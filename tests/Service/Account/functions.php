<?php

namespace Slick\Users\Service\Account;

use Slick\Users\Tests\Service\Account\CookieTokenStorageServiceTest;

function session_regenerate_id($delete)
{
    \PHPUnit_Framework_Assert::assertTrue($delete);
}

function setcookie($name, $value, $expire, $path)
{
    $data = [
        'name' => $name,
        'value' => $value,
        'expire' => $expire,
        'path' => $path
    ];
    CookieTokenStorageServiceTest::$cookieData = $data;
}