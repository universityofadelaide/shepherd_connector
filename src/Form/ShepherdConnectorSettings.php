<?php

namespace Drupal\shepherd_connector\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A form to set config for the Shepherd connector.
 */
class ShepherdConnectorSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'shepherd_connector_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('shepherd_connector.settings');
    $shepherd_connector = \Drupal::service('shepherd_connector.connector');

    $form['url'] = [
      '#type' => 'markup',
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      '#markup' => t(
        'This site has the id: :id, is connected to Shepherd running at: <a href=:url>:url</a>, using the token: :token.',
        [
          ':id' => $shepherd_connector->getSiteId(),
          ':url' => $shepherd_connector->getUrl(),
          ':token' => $shepherd_connector->getToken(),
        ]
      ),
    ];
    $form['role_sync'] = [
      '#type' => 'details',
      '#title' => $this->t('Role sync'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];
    $form['role_sync']['enabled'] = [
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#description' => t('Synchronise user roles with Shepherd.'),
      '#default_value' => $config->get('role_sync.enabled'),
    ];
    $form['role_sync']['controlled_roles'] = [
      '#type' => 'textarea',
      '#title' => t('Controlled Roles'),
      '#description' => t('Comma-separated list of roles to synchronise with Shepherd. Only roles that appear in this list will be affected.'),
      '#default_value' => $config->get('role_sync.controlled_roles'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('shepherd_connector.settings');
    $config->delete();

    $values = $form_state->getValues();
    $config->set('role_sync.enabled', $values['role_sync']['enabled']);
    $config->set('role_sync.controlled_roles', $values['role_sync']['controlled_roles']);
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['shepherd_connector.settings'];
  }

}
