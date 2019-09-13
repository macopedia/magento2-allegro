<?php

namespace Macopedia\Allegro\Model\Api\Auth\Data;

use Macopedia\Allegro\Api\Data\TokenInterface;
use Magento\Framework\DataObject;

/**
 * Token data class
 */
class Token extends DataObject implements TokenInterface
{
    const ACCESS_TOKEN_FIELD_NAME = 'access_token';
    const REFRESH_TOKEN_FIELD_NAME = 'refresh_token';
    const EXPIRATION_TIME_FIELD_NAME = 'expiration_time';

    /**
     * @param string $accessToken
     * @return void
     */
    public function setAccessToken(string $accessToken)
    {
        $this->setData(self::ACCESS_TOKEN_FIELD_NAME, $accessToken);
    }

    /**
     * @param string $refreshToken
     * @return void
     */
    public function setRefreshToken(string $refreshToken)
    {
        $this->setData(self::REFRESH_TOKEN_FIELD_NAME, $refreshToken);
    }

    /**
     * @param int $expirationTime
     * @return void
     */
    public function setExpirationTime(int $expirationTime)
    {
        $this->setData(self::EXPIRATION_TIME_FIELD_NAME, $expirationTime);
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->getData(self::ACCESS_TOKEN_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->getData(self::REFRESH_TOKEN_FIELD_NAME);
    }

    /**
     * @return int
     */
    public function getExpirationTime()
    {
        return $this->getData(self::EXPIRATION_TIME_FIELD_NAME);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isExpired()
    {
        return $this->getExpirationTime() <= (new \DateTime())->getTimestamp();
    }
}
