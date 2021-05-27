<?php

namespace Macopedia\Allegro\Model\Api;

use Macopedia\Allegro\Api\Data\TokenInterface;

/**
 * Class to get current access token received from Allegro API
 */
class TokenProvider
{
    /** @var Auth */
    private $auth;

    /** @var Credentials */
    private $credentials;

    /**
     * @param Auth $auth
     * @param Credentials $credentials
     */
    public function __construct(
        Auth $auth,
        Credentials $credentials
    ) {
        $this->auth = $auth;
        $this->credentials = $credentials;
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
     * @return void
     * @throws ClientException
     * @throws \Exception
     */
    public function forceRefreshToken()
    {
        $this->credentials->saveToken(
            $this->auth->refreshToken(
                $this->credentials->getToken()
            )
        );
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
