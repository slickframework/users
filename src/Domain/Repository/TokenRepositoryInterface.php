<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain\Repository;

use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Token;

/**
 * Token Repository Interface
 *
 * @package Slick\Users\Domain\Repository
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface TokenRepositoryInterface extends RepositoryInterface
{

    /**
     * Get a token by its token
     *
     * @param string $publicToken
     *
     * @return Token|null
     */
    public function getToken($publicToken);

    /**
     * Deletes all the tokens with the same account of the provided token
     *
     * @param Token $token
     *
     * @return int Affected rows
     */
    public function deleteAccountTokens(Token $token);
}
