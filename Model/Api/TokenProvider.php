<?php

namespace Macopedia\Allegro\Model\Api;

use Macopedia\Allegro\Api\Data\TokenInterface;
use Macopedia\Allegro\Logger\Logger;

/**
 * Class to get current access token received from Allegro APO
 */
class TokenProvider
{
    /** @var Auth */
    private $auth;

    /** @var Credentials */
    private $credentials;

    /** @var Logger */
    private $logger;

    /**
     * @param Auth $auth
     * @param Credentials $credentials
     * @param Logger $logger
     */
    public function __construct(
        Auth $auth,
        Credentials $credentials,
        Logger $logger
    ) {
        $this->auth = $auth;
        $this->credentials = $credentials;
        $this->logger = $logger;
    }

    /**
     * @return TokenInterface
     * @throws ClientException
     * @throws \Exception
     */
    public function getCurrent()
    {
        $token = $this->credentials->getToken();

        if ($token->isExpired()) {
            $token = $this->refreshToken($token);
        }

        return $token;
    }

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     * @throws \Exception
     */
    private function refreshToken(TokenInterface $token)
    {
        $token = $this->auth->refreshToken($token);
        $this->credentials->saveToken($token);
        return $token;
    }
}
