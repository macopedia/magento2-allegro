<?php

namespace Macopedia\Allegro\Api\Data;

interface TokenInterface
{
    /**
     * @param string $accessToken
     * @return void
     */
    public function setAccessToken(string $accessToken);

    /**
     * @param string $refreshToken
     * @return void
     */
    public function setRefreshToken(string $refreshToken);

    /**
     * @param int $expirationTime
     * @return Token
     */
    public function setExpirationTime(int $expirationTime);

    /**
     * @return string
     */
    public function getAccessToken();

    /**
     * @return string
     */
    public function getRefreshToken();

    /**
     * @return int
     */
    public function getExpirationTime();

    /**
     * @return bool
     * @throws \Exception
     */
    public function isExpired();
}
