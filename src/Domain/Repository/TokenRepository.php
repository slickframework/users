<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain\Repository;

use Slick\Orm\Repository\EntityRepository;
use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Token;

/**
 * Token Repository
 *
 * @package Slick\Users\Domain\Repository
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenRepository extends EntityRepository implements RepositoryInterface
{

    /**
     * Get a token by its token
     *
     * @param string $token
     *
     * @return Token|null
     */
    public function getToken($token)
    {
        return $this->find()
            ->where(['token = :tkn' => [':tkn' => $token]])
            ->order('tokens.ttl DESC')
            ->first();
    }
}
