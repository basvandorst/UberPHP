<?php
namespace Uber\API;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken as AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider as AbstractProvider;

/**
 * Uber OAuth
 * The Uber implementation of the OAuth client
 * 
 * @see: https://github.com/thephpleague/oauth2-client
 * @author Bas van Dorst
 */
class OAuth extends AbstractProvider
{
    public $scopeSeparator = ' ';
    public $scopes = array('write');
    public $responseType = 'json';
    
    /**
     * @see AbstractProvider::__construct
     * @param array $options
     */
    public function __construct($options)
    {
        parent::__construct($options);
        $this->headers = array(
            'Authorization' => 'Bearer'
        );
    }
    
    /**
     * @see AbstractProvider::urlAuthorize
     */
    public function urlAuthorize()
    {
        return 'https://login.uber.com/oauth/authorize';
    }

    /**
     * @see AbstractProvider::urlAuthorize
     */
    public function urlAccessToken()
    {
        return 'https://login.uber.com/oauth/token';
    }

    /**
     * @see AbstractProvider::urlUserDetails
     */
    public function urlUserDetails(AccessToken $token)
    {
        return 'https://api.uber.com/v1/me?access_token='.$token;
    }
    
    /**
     * @see AbstractProvider::userDetails
     */
    public function userDetails($response, AccessToken $token)
    {
        $user = new User;

        $user->exchangeArray(array(
            'uid' => $response->uuid,
            'name' => implode(" ", array($response->first_name, $response->last_name)),
            'firstName' => $response->first_name,
            'lastName' => $response->last_name,
            'email' => $response->email,
            'imageUrl' => $response->picture,
        ));

        return $user;
    }

    /**
     * @see AbstractProvider::userUid
     */
    public function userUid($response, AccessToken $token)
    {
        return $response->uuid;
    }

    /**
     * @see AbstractProvider::userUid
     */
    public function userEmail($response, AccessToken $token)
    {
        return isset($response->email) && $response->email ? $response->email : null;
    }

    /**
     * @see AbstractProvider::userScreenName
     */
    public function userScreenName($response, AccessToken $token)
    {
        return implode(" ", array($response->first_name, $response->last_name));
    }
}