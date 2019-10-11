<?php

namespace Macopedia\Allegro\Model\Api;

use GuzzleHttp\Client;
use Macopedia\Allegro\Api\Data\TokenInterface;
use Macopedia\Allegro\Api\Data\TokenInterfaceFactory;
use Macopedia\Allegro\Logger\Logger;
use Magento\Backend\Model\Url;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException as HttpClientException;

/**
 * Class responsible for authentication with Allegro API
 */
class Auth
{
    const OAUTH_URL_CONFIG_KEY = 'allegro/general/authentication_url';
    const SANDBOX_OAUTH_URL_CONFIG_KEY = 'allegro/general/sandbox_authentication_url';

    /** @var Credentials */
    private $credentials;

    /** @var Client */
    private $client;

    /** @var TokenInterfaceFactory */
    private $tokenFactory;

    /** @var Logger */
    private $logger;

    /** @var Url */
    private $url;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var Json */
    private $json;

    /**
     * @param Credentials $credentials
     * @param Client $client
     * @param TokenInterfaceFactory $tokenFactory
     * @param Logger $logger
     * @param Url $url
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Credentials $credentials,
        Client $client,
        TokenInterfaceFactory $tokenFactory,
        Logger $logger,
        Url $url,
        ScopeConfigInterface $scopeConfig,
        Json $json
    ) {
        $this->credentials = $credentials;
        $this->client = $client;
        $this->tokenFactory = $tokenFactory;
        $this->logger = $logger;
        $this->url = $url;
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
    }

    /**
     * @param $authCode
     * @return TokenInterface
     * @throws ClientException
     */
    public function getNewToken($authCode)
    {
        try {
            $response = $this->client->post(
                $this->getOauthUrl() . '/token',
                $this->getNewTokenData($authCode)
            );

        } catch (HttpClientException $e) {
            $this->logger->exception($e, "Error while getting new token: " . $e->getMessage());
            throw new ClientException(__('Error while getting new access token. Received response: "%1"', $e->getResponse()->getBody()), $e);
        }

        return $this->createTokenFromResponse($response);
    }

    /**
     * @param TokenInterface $token
     * @return TokenInterface
     * @throws \Exception
     */
    public function refreshToken(TokenInterface $token)
    {
        try {
            $response = $this->client->post(
                $this->getOauthUrl() . '/token',
                $this->getRefreshTokenData($token)
            );

        } catch (HttpClientException $e) {
            $this->logger->exception($e, "Error while refreshing access token" . $e->getMessage());
            throw new ClientException(__('Error while refreshing access token. Received response: "%1"', $e->getResponse()->getBody()), $e);
        }

        return $this->createTokenFromResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return TokenInterface
     * @throws ClientException
     * @throws \Exception
     */
    private function createTokenFromResponse(ResponseInterface $response): TokenInterface
    {
        $data = $this->json->unserialize(
            $response->getBody()->getContents()
        );

        if (!isset($data['access_token']) || !isset($data['refresh_token']) || !isset($data['expires_in'])) {
            throw new ClientException(
                __('Wrong response structure received')
            );
        }

        /** @var TokenInterface $token */
        $token = $this->tokenFactory->create();
        $token->setAccessToken($data['access_token']);
        $token->setRefreshToken($data['refresh_token']);
        $token->setExpirationTime($this->getExpirationTimestamp($data['expires_in']));

        return $token;
    }

    /**
     * @param $expiresIn
     * @return int
     * @throws \Exception
     */
    private function getExpirationTimestamp($expiresIn)
    {
        $expirationDate = new \DateTime();
        $expirationDate->add(new \DateInterval("PT{$expiresIn}S"));

        return $expirationDate->getTimestamp();
    }

    /**
     * @return string
     */
    public function getAuthUrl()
    {
        $query = [
            'response_type' => 'code',
            'client_id' => $this->credentials->getClientId(),
            'api-key' => $this->credentials->getApiKey(),
            'redirect_uri' => $this->getRedirectUrl(),
        ];

        return $this->getOauthUrl() . '/authorize?' . http_build_query($query);
    }

    /**
     * @param TokenInterface $token
     * @return array
     */
    private function getRefreshTokenData(TokenInterface $token)
    {
        return $this->createData([
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->getRefreshToken(),
            'redirect_uri' => $this->getRedirectUrl(),
        ]);
    }

    /**
     * @param string $authCode
     * @return array
     */
    public function getNewTokenData(string $authCode)
    {
        return $this->createData([
            'grant_type' => 'authorization_code',
            'code' => $authCode,
            'api-key' => $this->credentials->getApiKey(),
            'redirect_uri' => $this->getRedirectUrl(),
        ]);
    }

    /**
     * @param array $query
     * @return array
     */
    public function createData(array $query)
    {
        return [
            'auth' => [
                $this->credentials->getClientId(),
                $this->credentials->getClientSecret(),
            ],
            'query' => $query,
        ];
    }

    /**
     * @return string
     */
    private function getRedirectUrl()
    {
        return $this->url->getUrl(
            'allegro/system/authenticate',
            [
                'key' => false
            ]
        );
    }

    /**
     * @return string
     */
    private function getOauthUrl()
    {
        return $this->credentials->isSandbox() ?
            $this->scopeConfig->getValue(self::SANDBOX_OAUTH_URL_CONFIG_KEY) :
            $this->scopeConfig->getValue(self::OAUTH_URL_CONFIG_KEY);
    }
}
