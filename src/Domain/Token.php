<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain {

    use Slick\Orm\Annotations as Orm;
    use Slick\Orm\Entity;
    use Slick\Users\Shared\DataType\DateTime;

    /**
     * Token
     *
     * @package Slick\Users\Domain
     * @author  Filipe Silva <silvam.filipe@gmail.com>
     *
     * @property int $id      Entity primary key
     * @property Account $account Account this token belongs to
     * @property string $token   The token itself
     * @property DateTime $ttl     The datetime this until when this token is valid
     * @property string $action  The action this token is used for
     *
     * @property-write string $code
     *
     * @adapter slickUsers
     * @repository Slick\Users\Domain\Repository\TokenRepository
     */
    class Token extends Entity
    {

        /**
         * Token length
         */
        const LENGTH = 60;

        /**#@+
         * Token known actions
         */
        const ACTION_RECOVER = 'recover';
        const ACTION_CONFIRM = 'confirm';
        /**#@- */

        /**
         * @readwrite
         * @Orm\Column type=integer, primaryKey, autoIncrement
         * @var integer
         */
        protected $id;

        /**
         * @readwrite
         * @Orm\Column type=text, length=13
         * @var string
         */
        protected $selector;

        /**
         * @readwrite
         * @Orm\Column type=text, length=60
         * @var string
         * @display
         */
        protected $token;

        /**
         * @readwrite
         * @Orm\BelongsTo Slick\Users\Domain\Account
         * @var Account
         */
        protected $account;

        /**
         * @readwrite
         * @Orm\Column type=text, size=tiny
         * @var string
         */
        protected $action = self::ACTION_CONFIRM;

        /**
         * @readwrite
         * @Orm\Column type=datetime
         * @var DateTime
         */
        protected $ttl;

        /**
         * @var string
         */
        protected $tokenString;

        /**
         * @write
         * @var string
         */
        protected $code;

        /**
         * @var bool
         */
        protected $valid = false;

        /**
         * Returns entity ID
         *
         * This is usually the primary key or a UUID
         *
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Sets entity ID
         *
         * @param mixed $entityId Primary key or a UUID
         *
         * @return Token
         */
        public function setId($entityId)
        {
            $this->id = $entityId;
            return $this;
        }

        /**
         * Get token value.
         *
         * If its not set yet it will generate a new one.
         *
         * @return string
         */
        public function getToken()
        {
            if (!$this->token) {
                $this->token = hash('sha256', $this->getCode());
            }
            return $this->token;
        }

        /**
         * Get generated code
         *
         * @return string
         */
        protected function getCode()
        {
            if (!$this->code) {
                $this->code = bin2hex(random_bytes(self::LENGTH));
            }
            return $this->code;
        }

        /**
         * Generate the unique ID for this token
         *
         * @return string
         */
        protected function getSelector()
        {
            if (!$this->selector) {
                $this->selector = uniqid();
            }
            return $this->selector;
        }

        /**
         * Get the time to live datetime
         *
         * After this date the token is invalid
         *
         * @return DateTime
         */
        public function getTtl()
        {
            if (!$this->ttl) {
                $this->setTtl("+24 hours");
            }
            return $this->ttl;
        }

        /**
         * Set the time to live datetime
         *
         * @param DateTime|string|int $ttl
         *
         * @return Token
         */
        public function setTtl($ttl)
        {
            $this->ttl = new DateTime($ttl);
            return $this;
        }

        public function validate($token)
        {
            $hash = hash('sha256', $token);
            $this->valid = hash_equals($this->token, $hash);
            return $this;
        }

        /**
         * Check if this token is valid
         *
         * @return bool
         */
        public function isValid()
        {
            return $this->valid;
        }

        /**
         * Check if this token has expired
         *
         * @return bool
         */
        public function hasExpired()
        {
            $now = new DateTime();
            return $this->getTtl() < $now;
        }

        /**
         * Returns the string you should be using in cookies or e-mails
         *
         * @return string
         */
        public function getPublicToken()
        {
            return "{$this->getSelector()}:{$this->getCode()}";
        }

        public function __toString()
        {
            return $this->getPublicToken();
        }
    }

    if (!function_exists('hash_equals')) {
        function hash_equals($str1, $str2)
        {
            if (strlen($str1) != strlen($str2)) {
                return false;
            } else {
                $res = $str1 ^ $str2;
                $ret = 0;
                for ($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
                return !$ret;
            }
        }
    }
}