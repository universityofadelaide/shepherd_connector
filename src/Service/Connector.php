<?php

namespace Drupal\shepherd_connector\Service;

use Drupal\Core\Site\Settings;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Provides a mechanism for communicating with Shepherd.
 *
 * @package Drupal\shepherd_connector\Service
 */
class Connector {

  /**
   * The settings instance.
   *
   * @var \Drupal\Core\Site\Settings
   *   The settings instance.
   */
  protected $settings;

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs a new Connector object.
   *
   * @param \Drupal\Core\Site\Settings $settings
   *   The settings instance.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   A Guzzle client object.
   */
  public function __construct(Settings $settings, ClientInterface $http_client) {
    $this->settings = $settings;
    $this->httpClient = $http_client;
  }

  /**
   * Returns the url for Shepherd configured in settings.php.
   *
   * @return string
   *   The url for Shepherd, or NULL if not set.
   */
  public function getUrl() {
    return $this->settings->get('shepherd_url');
  }

  /**
   * Returns the token used to auth with Shepherd configured in settings.php.
   *
   * @return string
   *   The token for Shepherd, or NULL if not set.
   */
  public function getToken() {
    return $this->settings->get('shepherd_token');
  }

  /**
   * Returns the Shepherd site id number configured in settings.php.
   *
   * @return int
   *   The site id for this site according to Shepherd, or NULL if not set.
   */
  public function getSiteId() {
    return $this->settings->get('shepherd_site_id');
  }

  /**
   * Queries Shepherd for the roles that a user has been assigned for this site.
   *
   * @param string $username
   *   The username of the user to look up in Shepherd.
   *
   * @return array
   *   An array of role machine names that (should) correspond to local Drupal
   *   roles.
   */
  public function getRolesForUser(string $username) {
    try {
      $site_user_roles_url = $this->getUrl() . '/api/site-user-roles/' . $username . '/' . $this->getSiteId();
      $options = [];
      if ($this->getToken()) {
        $options = [
          'headers' => [
            'Authorization' => 'Bearer ' . $this->getToken(),
          ],
        ];
      }
      $response = $this->httpClient->request('GET', $site_user_roles_url, $options);
      return json_decode($response->getBody(), TRUE);
    }
    catch (GuzzleException $exception) {
      return [];
    }
  }

  /**
   * Submits a status report to Shepherd.
   *
   * @param array $report
   *   An list of key/value pairs to submit to Shepherd as a report.
   */
  public function submitReport(array $report) {
    // @todo Actually submit report to Shepherd.
  }

}