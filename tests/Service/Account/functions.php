<?php

namespace Slick\Users\Service\Account;

function session_regenerate_id($delete)
{
    \PHPUnit_Framework_Assert::assertTrue($delete);
}