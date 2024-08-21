<?php

namespace Drupal\lb_section_extras\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Storm layout builder settings for this site.
 */
class StormPageBuilderStyleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lb_section_extras.settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['lb_section_extras.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('lb_section_extras.settings');

    $form['background'] = [
      '#type' => 'details',
      '#title' => $this->t('Background'),
      '#open' => TRUE,
    ];
    $form['background']['background_colors'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Color palette'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the perfixed period CSS selector), and <em>label</em> is the human readable name of the background.</p>'),
      '#default_value' => $config->get('background_colors'),
    ];

    $form['padding'] = [
      '#type' => 'details',
      '#title' => $this->t('Padding'),
      '#open' => TRUE,
    ];
    $form['padding']['markup'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the perfixed period CSS selector), and <em>label</em> is the human readable name.') . '</p>',
    ];
    $form['padding']['padding_top'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Padding top'),
      '#default_value' => $config->get('padding_top'),
    ];
    $form['padding']['padding_bottom'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Padding bottom'),
      '#default_value' => $config->get('padding_bottom'),
    ];
    $form['padding']['padding_left'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Padding left'),
      '#default_value' => $config->get('padding_left'),
    ];
    $form['padding']['padding_right'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Padding right'),
      '#default_value' => $config->get('padding_right'),
    ];

    $form['spacing'] = [
      '#type' => 'details',
      '#title' => $this->t('Spacing'),
      '#open' => TRUE,
    ];
    $form['spacing']['markup'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the perfixed period CSS selector), and <em>label</em> is the human readable name.') . '</p>',
    ];
    $form['spacing']['spacing_top'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Spacing top'),
      '#default_value' => $config->get('spacing_top'),
    ];
    $form['spacing']['spacing_bottom'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Spacing bottom'),
      '#default_value' => $config->get('spacing_bottom'),
    ];
    $form['spacing']['spacing_left'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Spacing left'),
      '#default_value' => $config->get('spacing_left'),
    ];
    $form['spacing']['spacing_right'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Spacing right'),
      '#default_value' => $config->get('spacing_right'),
    ];

    $form['theme'] = [
      '#type' => 'details',
      '#title' => $this->t('Theme'),
      '#open' => TRUE,
    ];
    $form['theme']['markup'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('Enter the classes which will allow site builders to select from a list of styles to apply to layout builder sections.') . '</p>
      <p>' . $this->t('Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the perfixed period CSS selector), and <em>label</em> is the human readable name.') . '</p>',
    ];
    $form['theme']['styles'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('styles'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $ignore = [
      'submit',
      'form_build_id',
      'form_token',
      'form_id',
      'op',
    ];
    $configuration = $this->config('lb_section_extras.settings');
    foreach ($form_state->getValues() as $key => $value) {
      if (!in_array($key, $ignore)) {
        if (!is_array($value)) {
          $configuration->set($key, trim($value));
        }
        else {
          $configuration->set($key, $value);
        }
      }
    }
    $configuration->save();

    parent::submitForm($form, $form_state);
  }

}
