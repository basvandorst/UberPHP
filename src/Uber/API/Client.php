<?php
namespace Uber\API;

/**
 * Uber API client (REST Service)
 * 
 * @author Bas van Dorst
 * @package UberPHP
 */
class Client {    
    
    /**
     * Uber API endpoint
     * @var string
     */
    private static $endpoint = 'https://api.uber.com/';
    
    /**
     * REST adapter
     * @var Pest
     */
    protected $adapter;
    
    /**
     * Server or access token
     * @var string
     */
    private $token = null;
    
    /**
     * Authorization via user access token?
     * @var boolean 
     */
    private $user_authorization = false;
    
    /**
     * Locale settings
     * @var string 
     */
    private $locale = 'en_US';
    
    /**
     * Inititate this API client
     * 
     * @param Pest $adapter
     * @param string $token
     * @param boolean $user_authorization
     * 
     */
    public function __construct($adapter, $token, $user_authorization = false) {
        $this->adapter = $adapter;
        $this->token = $token;
        $this->user_authorization = $user_authorization;
    }
    
    /**
     * Set locale
     * 
     * @see: https://developer.uber.com/v1/api-reference/#localization
     * @param string $locale
     */
    public function setLocale($locale) { 
        $this->locale = $locale;
    }
    
    /**
     * Return an array of HTTP headers
     * @return array
     */
    private function getHeaders() {
        $headers = array('Accept-Language: '.$this->locale);
        
        if($this->user_authorization) {
            $headers[] = 'Authorization: Bearer '.$this->token;
        } else {
            $headers[] ='Authorization: Token '.$this->token;
        }
        return $headers;
    }
    
    /**
     * Returns information about the Uber products offered at a given location.
     * 
     * @see https://developer.uber.com/v1/endpoints/#product-types
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function products($latitude, $longitude) {
        $path = '/v1/products';
        $parameters = array(
            'latitude' => $latitude,
            'longitude' => $longitude
        );
        $result = $this->adapter->get($path, $parameters, $this->getHeaders());
        return $this->format($result);
    }
    
    /**
     * @todo this call is not working. First I need to find out how to fix
     * the `Country not found` error
     * 
     * Returns information about the promotion that will be available to a new 
     * user based on their activity's location.
     * 
     * @see https://developer.uber.com/v1/endpoints/#promotions
     * @param float $start_latitude
     * @param float $start_longitude
     * @param float $end_latitude
     * @param float $end_longitude
     * @return array
     */
    public function promotions($start_latitude, $start_longitude, $end_latitude, $end_longitude) {
        $path = '/v1/promotions';
        $parameters = array(
            'start_latitude' => $start_latitude,
            'start_longitude' => $start_longitude,
            'end_latitude' => $end_latitude,
            'end_longitude' => $end_longitude,
        );
        $result = $this->adapter->get($path, $parameters, $this->getHeaders());
        return $this->format($result);      
    }
    
    /**
     * Returns an estimated price range for each product offered at a given 
     * location.
     * 
     * @see https://developer.uber.com/v1/endpoints/#price-estimates
     * @param float $start_latitude
     * @param float $start_longitude
     * @param float $end_latitude
     * @param float $end_longitude
     * @return array
     */
    public function estimatesPrice($start_latitude, $start_longitude, $end_latitude, $end_longitude) {
        $path = '/v1/estimates/price';
        $parameters = array(
            'start_latitude' => $start_latitude,
            'start_longitude' => $start_longitude,
            'end_latitude' => $end_latitude,
            'end_longitude' => $end_longitude,
        );
        $result = $this->adapter->get($path, $parameters, $this->getHeaders());
        return $this->format($result);      
    }
    
    /**
     * Returns ETAs for all products offered at a given location, with the 
     * responses expressed as integers in seconds. 
     * 
     * @see https://developer.uber.com/v1/endpoints/#time-estimates
     * @param float $start_latitude
     * @param float $start_longitude
     * @param float $end_latitude
     * @param float $end_longitude
     * @return array
     */
    public function estimatesTime($start_latitude, $start_longitude, $end_latitude, $end_longitude) {
        $path = '/v1/estimates/time';
        $parameters = array(
            'start_latitude' => $start_latitude,
            'start_longitude' => $start_longitude,
            'end_latitude' => $end_latitude,
            'end_longitude' => $end_longitude,
        );
        $result = $this->adapter->get($path, $parameters, $this->getHeaders());
        return $this->format($result);      
    }
    
    /**
     * Returns information about the Uber user that has authorized with 
     * the application.
     * 
     * @see https://developer.uber.com/v1/endpoints/#user-profile
     * @return array
     */
    public function userProfile() {
        $path = '/v1/me';
        
        $result = $this->adapter->get($path, array(), $this->getHeaders());
        return $this->format($result);
    }
    
    /**
     * Returns (a limited amount of data) about a user's lifetime activity 
     * with Uber.
     * 
     * @see https://developer.uber.com/v1/endpoints/#user-activity-v1
     * @see https://developer.uber.com/v1/endpoints/#user-activity-v1-1
     * @param boolean $lite 
     * @return array
     */
    public function userActivity($lite = false) {
        if($lite) {
            $path = '/v1.1/history';
        } else {
            $path = '/v1/history';
        }
        
        $result = $this->adapter->get($path, array(), $this->getHeaders());
        return $this->format($result);
    }
    
    /**
     * Convert the JSON output to an array
     * @param string $result
     */
    private function format($result) {
        return json_decode($result,true);
    }
}