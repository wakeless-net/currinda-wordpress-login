<?php

/**
 * The provider that connects to the API
 * and perform API-specific methods.
 *
 * @link       http://currinda.com
 * @since      1.0.0
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary perform authentication.
 *
 * @since      1.0.0
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/includes
 * @author     Currinda
 */
class Currinda_Auth_Provider {

    /**
     * Base URL of the API
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * API version
     *
     * @var string
     */
    protected $apiVersion = 'v2';

    /**
     * Initializes provider
     *
     * @param boolean $secured
     * 
     * @return void
     */
    public function __construct($secured) {

        $this->baseUrl = (($secured) ? 'https' : 'http') . '://' . get_option('currinda_auth_domain');
    }

    /**
     * Acquire access token
     *
     * @return void
     */
    protected function getAccessToken() {

        $response = wp_remote_post($this->baseUrl . '/' . 'api/' . $this->apiVersion . '/' . 'organisation/' . get_option('currinda_auth_org_id') . '/token', array(
            'body' => array(
                'grant_type'    =>  'client_credentials',
                'client_id'     =>  get_option('currinda_auth_app_id'),
                'client_secret' =>  get_option('currinda_auth_app_secret'),
                'scope'         =>  'user',
            )
        ) );

        if ( !is_wp_error($response) && $response['response']['code'] == 200 ) {
            return json_decode( wp_remote_retrieve_body($response) );
        }

        if ( $response['response']['code'] == 500 ) {
            return new WP_Error( 500, 'Something went wrong. Please try again' );
        }

        return new WP_Error( 400, 'Invalid API credentials' );
    }

    public function authenticate( $email, $password, $org ) {

        if ( !is_wp_error($token = $this->getAccessToken()) ) {

            $response = wp_remote_post($this->baseUrl . '/' . 'api/' . $this->apiVersion . '/' . 'organisation/' . get_option('currinda_auth_org_id') . '/authenticate', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token->access_token,
                    'Content-Type' => 'application/json',
                ),
                'body' => array(
                    'email'     =>  $email,
                    'password'  =>  $password,
                    'org'       =>  $org
                )
            ) );

            $body = json_decode( wp_remote_retrieve_body($response) );

            if ( !is_wp_error($response) && $response['response']['code'] == 200 ) {
                return $body->membership;
            }

            if ( $response['response']['code'] == 500 ) {
                return new WP_Error( 500, 'Something went wrong. Please try again' );
            }

            return new WP_Error( 400, $body->message );
        }

        return $token;
    }
}