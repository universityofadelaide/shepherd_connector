<?php

namespace Drupal\shepherd_connector\Service;

use Drupal\Core\Site\Settings;

/**
 * Provides a mechanism for communicating with Shepherd.
 *
 * @package Drupal\shepherd_connector\Service
 */
class Connector {

  /**
   * Returns the url for Shepherd configured in settings.php.
   *
   * @return string
   *   The url for Shepherd, or NULL if not set.
   */
  public function getUrl() {
    return Settings::get('shepherd_url');
  }

  /**
   * Returns the token used to auth with Shepherd configured in settings.php.
   *
   * @return string
   *   The token for Shepherd, or NULL if not set.
   */
  public function getToken() {
    return Settings::get('shepherd_token');
  }

  /**
   * Returns the Shepherd site id number configured in settings.php.
   *
   * @return int
   *   The site id for this site according to Shepherd, or NULL if not set.
   */
  public function getSiteId() {
    return Settings::get('site_id');
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
    // @todo Actually make this query Shepherd for roles.
    return ['administrator'];
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