<?php
namespace Drupal\mautic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "mautic_mautic_block",
 *   admin_label = @Translation("Mautic block")
 * )
 */
class MauticBlock extends BlockBase {
  public function build() {
    $config = \Drupal::config('mautic.settings');
    $mautic_base = $config->get('mautic_base');
    $current_url = $this->getCurrentURL();
    $attrs = array();
    $attrs['title'] = $this->resolveTitle();
    $attrs['language'] = $this->resolveLanguage();
    $attrs['referrer'] = ((isset($_SERVER['HTTP_REFERER'])
      )?$_SERVER['HTTP_REFERER']:$current_url);
    $attrs['url'] = $current_url;
    $enc_attrs = urlencode(base64_encode(serialize($attrs)));
    $mautic_load_form_js = $config->get('mautic_load_form_js');
    return array(
      '#theme' => 'mautic',
      '#mautic_base' => $mautic_base,
      '#enc_attr' => $enc_attrs,
      '#mautic_load_form_js' => $mautic_load_form_js
    );
  }
  private function resolveTitle() {
    $request = \Drupal::request();
    $route_match = \Drupal::routeMatch();
    $title = \Drupal::service('title_resolver')->getTitle($request,
      $route_match->getRouteObject());
    if (is_array($title)) {
      return $title['#markup'];
    }
    return $title;
  }
  private function resolveLanguage() {
    return \Drupal::languageManager()->getCurrentLanguage()->getId();
  }
  private function getCurrentURL() {
    $request = \Drupal::request();
    return $request->getSchemeAndHttpHost() . $request->getRequestUri();
  }
}