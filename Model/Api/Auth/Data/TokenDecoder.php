<?php

namespace Macopedia\Allegro\Model\Api\Auth\Data;

use Macopedia\Allegro\Api\Data\TokenInterface;

class TokenDecoder
{

    /**
     * @param TokenInterface $token
     * @return mixed
     * @throws TokenDecoderException
     */
    public function getSellerId(TokenInterface $token)
    {
        $body = $this->decode($token);
        if (!isset($body['user_name'])) {
            throw new TokenDecoderException(__('Invalid token string. User name is missing'));
        }

        return $body['user_name'];
    }

    /**
     * @param TokenInterface $token
     * @return array
     * @throws TokenDecoderException
     */
    public function decode(TokenInterface $token)
    {
        $tokenParts = explode('.', $token->getAccessToken());
        if (!isset($tokenParts[1])) {
            throw new TokenDecoderException(__('Invalid token string'));
        }

        $json = base64_decode($tokenParts[1]);
        if ($json === false) {
            throw new TokenDecoderException(__('Invalid token string'));
        }

        $body = (array) json_decode($json);
        if ($body === null) {
            throw new TokenDecoderException(__('Invalid token string'));
        }

        return $body;
    }
}
