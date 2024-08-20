<?php

namespace Drupal\lb_section_extras\Plugin\Layout;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to provides extra configurations for layouts.
 */
class StormLayout extends LayoutDefault implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new class instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['section_background']['image'] = [
      '#type' => 'details',
      '#title' => $this->t('Background Image'),
      '#weight' => 3,
    ];

    $form['section_background']['image']['background_media'] = [
      '#type' => 'media_library',
      '#allowed_bundles' => ['image'],
      '#title' => $this->t('Image'),
      '#default_value' => $this->configuration['section_background']['background_media'] ?? NULL,
      '#description' => $this->t('Upload or select a background image.'),
    ];

    $form['section_background']['image']['background_position'] = [
      '#type' => 'select',
      '#title' => $this->t('Background image position'),
      '#options' => [
        'left top' => $this->t('Left top'),
        'left center' => $this->t('Left center'),
        'left bottom' => $this->t('Left bottom'),
        'center top' => $this->t('Center top'),
        'center center' => $this->t('Center center'),
        'center bottom' => $this->t('Center bottom'),
        'right top' => $this->t('Right top'),
        'right center' => $this->t('Right center'),
        'right bottom' => $this->t('Right bottom'),
      ],
      '#default_value' => $this->configuration['section_background']['background_position'] ?? 'center center',
    ];

    $form['section_background']['image']['background_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Background image size'),
      '#options' => [
        'cover' => $this->t('Cover'),
        'contain' => $this->t('Contain'),
      ],
      '#default_value' => $this->configuration['section_background']['background_size'] ?? 'cover',
    ];

    $form['section_background']['image']['background_repeat'] = [
      '#type' => 'select',
      '#title' => $this->t('Background image repeat'),
      '#options' => [
        'repeat' => $this->t('Repeat'),
        'no-repeat' => $this->t('No repeat'),
        'repeat-x' => $this->t('Repeat X'),
        'repeat-y' => $this->t('Repeat Y'),
      ],
      '#default_value' => $this->configuration['section_background']['background_repeat'] ?? 'no-repeat',
    ];

    $form['section_background']['image']['background_attachment'] = [
      '#type' => 'select',
      '#title' => $this->t('Background attachment'),
      '#options' => [
        'fixed' => $this->t('Fixed'),
        'scroll' => $this->t('Scroll'),
      ],
      '#default_value' => $this->configuration['section_background']['background_attachment'] ?? 'scroll',
    ];

    $form['section_padding'] = [
      '#type' => 'details',
      '#title' => $this->t('Padding'),
      '#weight' => 3,
    ];

    $padding = [
      'padding_top' => $this->t('Top padding'),
      'padding_bottom' => $this->t('Bottom padding'),
      'padding_left' => $this->t('Left padding'),
      'padding_right' => $this->t('Right padding'),
    ];

    foreach ($padding as $key => $value) {
      $form['section_padding'][$key] = [
        '#type' => 'select',
        '#title' => $value,
        '#options' => [
          'none' => $this->t('None'),
        ],
        '#empty_value' => 'none',
        '#default_value' => $this->configuration['section_padding'][$key] ?? 'none',
      ];
      $form['section_padding'][$key]['#options'] = $this->getOptions($key);
    }

    $form['section_spacing'] = [
      '#type' => 'details',
      '#title' => $this->t('Spacing'),
      '#weight' => 3,
    ];

    $spacing = [
      'spacing_top' => $this->t('Top spacing'),
      'spacing_bottom' => $this->t('Bottom spacing'),
      'spacing_left' => $this->t('Left spacing'),
      'spacing_right' => $this->t('Right spacing'),
    ];

    foreach ($spacing as $key => $value) {
      $form['section_spacing'][$key] = [
        '#type' => 'select',
        '#title' => $value,
        '#options' => [
          'none' => $this->t('None'),
        ],
        '#empty_value' => 'none',
        '#default_value' => $this->configuration['section_spacing'][$key] ?? 'none',
      ];
      $form['section_spacing'][$key]['#options'] = $this->getOptions($key);
    }

    $form['section_theme'] = [
      '#type' => 'details',
      '#title' => $this->t('Theme'),
      '#weight' => 3,
    ];

    $form['section_theme']['styles'] = [
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#options' => $this->getOptions('styles'),
      '#default_value' => $this->configuration['section_theme']['styles'] ?? [],
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * Helper method to get padding options.
   */
  private function getOptions($key) {
    $config = $this->configFactory->get('lb_section_extras.settings')->get($key);
    return $this->getConfigValues($config);
  }

  /**
   * Build the config values array from config object.
   */
  private function getConfigValues($config) {
    foreach (explode("\r\n", $config) as $color) {
      $color = trim($color);
      if (!empty($color)) {
        [$class, $label] = explode('|', $color);
        $options[$class] = $label;
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    foreach ($form_state->getValues() as $key => $config) {
      $this->configuration[$key] = $config;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['#attributes']['class'][] = [
      'layout',
    ];

    return $build;
  }

}
