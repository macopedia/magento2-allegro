<?php

namespace Macopedia\Allegro\Model\Api;

use Macopedia\Allegro\Model\Api\Auth\Data\Token;
use Macopedia\Allegro\Model\Api\Auth\Data\TokenSerializer;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Credentials data class
 */
class Credentials
{
    const API_KEY_CONFIG_KEY = 'allegro/credentials/api_key';
    const CLIENT_ID_CONFIG_KEY = 'allegro/credentials/client_id';
    const CLIENT_SECRET_CONFIG_KEY = 'allegro/credentials/client_secret';
    const TOKEN_DATA_CONFIG_KEY = 'allegro/credentials/token_data';
    const SANDBOX_CONFIG_KEY = 'allegro/general/sandbox';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var WriterInterface */
    private $configWriter;

    /** @var TypeListInterface */
    private $cacheTypeList;

    /** @var TokenSerializer */
    private $tokenSerializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param TokenSerializer $tokenSerializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        TypeListInterface $cacheTypeList,
        TokenSerializer $tokenSerializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
        $this->tokenSerializer = $tokenSerializer;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->scopeConfig->getValue(self::API_KEY_CONFIG_KEY);
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->scopeConfig->getValue(self::CLIENT_ID_CONFIG_KEY);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->scopeConfig->getValue(self::CLIENT_SECRET_CONFIG_KEY);
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return (bool)$this->scopeConfig->getValue(self::SANDBOX_CONFIG_KEY);
    }

    /**
     * @param Token $token
     */
    public function saveToken(Token $token)
    {
        $this->configWriter->save(
            self::TOKEN_DATA_CONFIG_KEY,
            $this->tokenSerializer->encode($token)
        );
        $this->cacheTypeList->cleanType('config');
    }

    /**
     * @return bool|mixed
     * @throws ClientException
     */
    public function getToken()
    {
        $tokenString = $this->scopeConfig->getValue(self::TOKEN_DATA_CONFIG_KEY);
        if (!$tokenString) {
            throw new ClientException(__('Allegro account is not connected. Connect to Allegro account and try again'));
        }

        try {
            return $this->tokenSerializer->decode($tokenString);
        } catch (TokenSerializer\TokenSerializerException $e) {
            throw new ClientException(__('Something went wrong while decoding Allegro Api token'));
        }
    }
}
