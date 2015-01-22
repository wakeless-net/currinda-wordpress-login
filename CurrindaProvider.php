<?php

namespace League\OAuth2\Client\Provider;

use \League\OAuth2\Client\Token\AccessToken;

class CurrindaProvider extends AbstractProvider {
  var $url_authorize= "" , $url_access_token="", $url_user_details="";
    public function urlAuthorize()
    {
      return $this->url_authorize;
    }

    public function urlAccessToken()
    {
      return $this->url_access_token;
    }

    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token)
    {
      return $this->url_user_details."?access_token=$token";
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)  {
      return $response;
    }

    public function getUserDetails(AccessToken $token)
    {
        $response = $this->fetchUserDetails($token);

        return $this->userDetails(json_decode($response), $token);
    }

    public function getUserUid(AccessToken $token)
    {
        $response = $this->fetchUserDetails($token, true);

        return $this->userUid(json_decode($response), $token);
    }

    public function getUserEmail(AccessToken $token)
    {
        $response = $this->fetchUserDetails($token, true);

        return $this->userEmail(json_decode($response), $token);
    }

    public function getUserScreenName(AccessToken $token)
    {
        $response = $this->fetchUserDetails($token, true);

        return $this->userScreenName(json_decode($response), $token);
    }
    
}
