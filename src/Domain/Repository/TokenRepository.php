<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain\Repository;

use Slick\Database\Sql;
use Slick\Orm\Repository\EntityRepository;
use Slick\Users\Domain\Token;

/**
 * Token Repository
 *
 * @package Slick\Users\Domain\Repository
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenRepository extends EntityRepository implements TokenRepositoryInterface
{

    /**
     * Get a token by its token
     *
     * @param string $publicToken
     *
     * @return Token|null
     */
    public function getToken($publicToken)
    {
        $parts = explode(':', $publicToken);
        list($selector, $plainTextToken) = $parts;
        /** @var Token $token */
        $token = $this->find()
            ->where(['selector = :key' => [':key' => $selector]])
            ->order('tokens.ttl DESC')
            ->first();
        return $token ? $token->validate($plainTextToken) : null;
    }

    /**
     * Deletes all the tokens with the same account of the provided token
     *
     * @param Token $token
     *
     * @return int Affected rows
     */
    public function deleteAccountTokens(Token $token)
    {
        return Sql::createSql($this->getAdapter())
            ->delete('tokens')
            ->where(['account_id => :id' => [':id' => $token->account->id]])
            ->execute();
    }
}
