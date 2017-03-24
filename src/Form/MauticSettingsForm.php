<?php
namespace Drupal\mautic\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MauticSettingsForm extends ConfigFormBase {
  public function getFormId() {
    return 'mautic_settings_form';
  }
  protected function getEditableConfigNames() {
    return array('mautic.settings');
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mautic.settings');
    $form['mautic_base_url'] = array(
      '#type' => 'url',
      '#title' => $this->t('Mautic Base URL'),
      '#default_value' => $config->get('mautic_base'),
      '#required' => true
    );
    $form['mautic_load_form_js'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use Mautic forms'),
      '#default_value' => $config->get('mautic_load_form_js'),
      '#required' => false
    );
    return parent::buildForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mautic.settings')->set('mautic_base',
      $form_state->getValue('mautic_base_url'))->set(
      'mautic_load_form_js', (boolean)($form_state->getValue(
      'mautic_load_form_js', false)))->save();
    parent::submitForm($form, $form_state);
  }
}