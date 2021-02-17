<?php

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\InvalidArgumentException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class CurrindaProvider extends AbstractProvider
{

  public $urlAuthorize = "";
  public $urlAccessToken = "";
  public $urlResourceOwnerDetails = "";

  public function __construct(array $options = [], array $collaborators = [])
  {
    $this->assertRequiredOptions($options);

    $possible   = $this->getConfigurableOptions();
    $configured = array_intersect_key($options, array_flip($possible));

    foreach ($configured as $key => $value) {
      $this->$key = $value;
    }

    // Remove all options that are only used locally
    $options = array_diff_key($options, $configured);

    parent::__construct($options, $collaborators);
  }


  /**
   * Returns all options that can be configured.
   *
   * @return array
   */
  protected function getConfigurableOptions()
  {
    return array_merge($this->getRequiredOptions(), [
      'accessTokenMethod',
      'accessTokenResourceOwnerId',
      'scopeSeparator',
      'responseError',
      'responseCode',
      'responseResourceOwnerId',
      'scopes',
    ]);
  }

  /**
   * Returns all options that are required.
   *
   * @return array
   */
  protected function getRequiredOptions()
  {
    return [
      'urlAuthorize',
      'urlAccessToken',
      'urlResourceOwnerDetails',
    ];
  }


  /**
   * Verifies that all required options have been passed.
   *
   * @param  array $options
   * @return void
   * @throws InvalidArgumentException
   */
  private function assertRequiredOptions(array $options)
  {
    $missing = array_diff_key(array_flip($this->getRequiredOptions()), $options);

    if (!empty($missing)) {
      throw new InvalidArgumentException(
        'Required options not defined: ' . implode(', ', array_keys($missing))
      );
    }
  }

  public function urlAuthorize()
  {
    return $this->urlAuthorize;
  }

  public function urlAccessToken()
  {
    return $this->urlAccessToken;
  }

  public function urlUserDetails(AccessToken $token)
  {
    return $this->urlResourceOwnerDetails . "?access_token=$token";
  }

  public function userDetails($response, AccessToken $token)
  {
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

  public function getBaseAuthorizationUrl()
  {
    return $this->urlAuthorize;
  }
  public function getBaseAccessTokenUrl(array $params)
  {
    return $this->urlAccessToken;
  }
  public function getResourceOwnerDetailsUrl(AccessToken $token)
  {
    return $this->urlResourceOwnerDetails;
  }
  protected function getDefaultScopes()
  {
  }
  protected function checkResponse(ResponseInterface $response, $data)
  {
    return $response;
  }
  protected function createResourceOwner(array $response, AccessToken $token)
  {
    return $response;
  }
}
