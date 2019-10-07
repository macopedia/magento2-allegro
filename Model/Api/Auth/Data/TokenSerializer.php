<?php

namespace Macopedia\Allegro\Model\Api\Auth\Data;

use Macopedia\Allegro\Api\Data\TokenInterface;
use Macopedia\Allegro\Api\Data\TokenInterfaceFactory;
use Macopedia\Allegro\Model\Api\Auth\Data\TokenSerializer\TokenSerializerException;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\Serializer\Json;

class TokenSerializer
{

    /** @var TokenInterfaceFactory */
    private $tokenFactory;

    /** @var EncryptorInterface */
    private $encryptor;

    /** @var Json */
    private $json;

    /**
     * TokenSerializer constructor.
     * @param TokenInterfaceFactory $tokenFactory
     * @param EncryptorInterface $encryptor
     * @param Json $json
     */
    public function __construct(
        TokenInterfaceFactory $tokenFactory,
        EncryptorInterface $encryptor,
        Json $json
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->encryptor = $encryptor;
        $this->json = $json;
    }

    /**
     * @param TokenInterface $token
     * @return string
     */
    public function encode(TokenInterface $token)
    {
        return $this->encryptor->encrypt(
            $this->json->serialize([
                'access_token' => $token->getAccessToken(),
                'refresh_token' => $token->getRefreshToken(),
                'expiration_time' => $token->getExpirationTime(),
            ])
        );
    }

    /**
     * @param string $tokenString
     * @return TokenInterface
     * @throws \Macopedia\Allegro\Model\Api\Auth\Data\TokenSerializer\TokenSerializerException
     */
    public function decode(string $tokenString)
    {
        $data = (array)$this->json->unserialize(
            $this->encryptor->decrypt($tokenString)
        );

        if (!(isset($data['access_token']) && isset($data['refresh_token']) && isset($data['expiration_time']))) {
            throw new TokenSerializerException('Invalid serialized token string');
        }

        $token = $this->tokenFactory->create();
        $token->setAccessToken($data['access_token']);
        $token->setRefreshToken($data['refresh_token']);
        $token->setExpirationTime($data['expiration_time']);

        return $token;
    }
}
