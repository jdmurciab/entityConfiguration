<?php
namespace Drupal\advertising\Form;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Class AdvertisingEntityForm.
 */
class AdvertisingEntityForm extends EntityForm {
  /**
   * @var $entity_type Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity_type;
  /**
   * @var $connection Drupal\Core\Database\Connection
   */
  protected $connection;
  /**
   * Class construct
   * 
   * @param $entity_type Drupal\Core\Entity\EntityTypeManager
   *  The entity type manager
   * 
   * @param $connection Drupal\Core\Database\Connection
   *  The connection to database
   */
  public function __construct(EntityTypeManager $entity_type, Connection $connection) {
    $this->entity_type = $entity_type;
    $this->connection = $connection;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('database')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $advertising_entity = $this->entity;
    // Disable caching for the form
    $form['#cache'] = ['max-age' => 0];
    
    // Do not flatten nested form fields
    $form['#tree'] = TRUE;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $advertising_entity->label(),
      '#description' => $this->t("Name for the Advertising entity."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $advertising_entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\advertising\Entity\AdvertisingEntity::load',
      ],
      '#disabled' => !$advertising_entity->isNew(),
    ];
/*     $form['url_ad'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Url'),
      '#default_value' => $advertising_entity->url_ad,
      '#description' => $this->t('The Url of AD'),
      '#required' => TRUE,
    ];
    $form['id_ad'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ID'),
      '#maxlength' => 255,
      '#default_value' => $advertising_entity->id_ad,
      '#description' => $this->t('The unique id of each AD'),
      '#required' => TRUE,
    ]; */
    $data_taxonomy = $this->taxonomy_vocabulary_get_names();
    $data_content_type = $this->content_type_get_names();
    $form['place'] = [
      '#type' => 'select',
      '#title' => $this->t('Default Place'),
      '#default_value' => $advertising_entity->getPlace(),
      '#description' => $this->t('The place where the ad will be displayed'),
      '#options' => [
        'Taxonomies' => $data_taxonomy,
        'Content Types' => $data_content_type,
      ],
      '#required' => TRUE,
    ];
    $form['breakpoints'] = [
      '#type' => 'fieldset',
      '#tree' => TRUE,
      '#description' => '',
      '#title' => $this->t('Breakpoints'),
      '#prefix' => '<div id="breakpoint-wrapper">',
      '#suffix' => '</div>',
    ];
    $width = $advertising_entity->getBreakpoints();
    if(!empty($width)) {
      $form_state->set('field_deltas', range(0,count($width['form']) - 1));
    }
    if ($form_state->get('field_deltas') == '') {
      $form_state->set('field_deltas', range(0,1));
    }
    
    $field_count = $form_state->get('field_deltas');
  
  
    foreach ($field_count as $delta) {
      $form['breakpoints']['form'][$delta] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Options'),
        '#tree' => TRUE,
      ];
  
      $form['breakpoints']['form'][$delta]['width'] = [
        '#type' => 'number',
        '#title' => 'Width', 
        '#min' => 1,
        '#required' => TRUE,
        '#default_value' => $width['form'][$delta]['width'],
        '#description' => $this->t('The width in px.'),
      ];
  
      $form['breakpoints']['form'][$delta]['height'] = [
        '#type' => 'number',
        '#title' => 'height', 
        '#min' => 1,
        '#required' => TRUE,
        '#default_value' => $width['form'][$delta]['height'],
        '#description' => $this->t('The height in px.'),
      ];
  
      $form['breakpoints']['form'][$delta]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::addMoreRemove'],
        '#ajax' => [
          'callback' => '::addMoreRemoveCallback',
          'wrapper' => 'breakpoint-wrapper',
        ],
        '#name' => 'remove_name_' . $delta,
      ];
    }
    $form['breakpoints']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#submit' => ['::AddMoreAddOne'],
      '#ajax' => [
        'callback' => '::AddMoreAddOneCallback',
        'wrapper' => 'breakpoint-wrapper',
      ],
    ];
    return $form;
  }
  /**
   * function to add one field of breakpoint.
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
	public function addMoreRemove(array &$form, FormStateInterface $form_state) {
		// Get the triggering item
    $delta_remove = $form_state->getTriggeringElement()['#parents'][2];
    
    // Store our form state
    $field_deltas_array = $form_state->get('field_deltas');
    
    // Find the key of the item we need to remove
    $key_to_remove = array_search($delta_remove, $field_deltas_array);
    
    // Remove our triggered element
    unset($field_deltas_array[$key_to_remove]);
    
    // Rebuild the field deltas values
    $form_state->set('field_deltas', $field_deltas_array);
    
    // Rebuild the form
    $form_state->setRebuild();
    return $this->messenger()->addMessage($this->t('The BreakPoint has been remove'), 'warning');
	}
  /**
   * ajax callback to add the new field to the render form.
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function addMoreRemoveCallback(array &$form, FormStateInterface $form_state) {
		return $form['breakpoints'];
	}
  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
	public function AddMoreAddOne(array &$form, FormStateInterface $form_state) {
    // Store our form state
    $field_deltas_array = $form_state->get('field_deltas');
    
    // check to see if there is more than one item in our array
    if (count($field_deltas_array) > 0) {
      // Add a new element to our array and set it to our highest value plus one
      $field_deltas_array[] = max($field_deltas_array) + 1;
    }
    else {
      // Set the new array element to 0
      $field_deltas_array[] = 0;
    }
  
    // Rebuild the field deltas values
    $form_state->set('field_deltas', $field_deltas_array);
  
    // Rebuild the form
    $form_state->setRebuild();
    
  }
  
  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  function AddMoreAddOneCallback(array &$form, FormStateInterface $form_state) {
    return $form['breakpoints'];
  }
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    
    $advertising_entity = $this->entity;
    $value_breakpoints= $form_state->getValue('breakpoints', 'form');
    $advertising_entity->setBreakpoints($value_breakpoints);
    $status = $advertising_entity->save();
    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Advertising entity.', [
          '%label' => $advertising_entity->label(),
        ]));
        break;
      default:
        $this->messenger()->addMessage($this->t('Saved the %label Advertising entity.', [
          '%label' => $advertising_entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($advertising_entity->toUrl('collection'));
  }
  /**
   * Get names for all taxonomy vocabularies.
   * 
   * @return array array A list of existing vocabulary IDs.
   */
  public function taxonomy_vocabulary_get_names() {
    $names =& drupal_static(__FUNCTION__);
    if (!isset($names)) {
      $names = [];
      $config_names = \Drupal::configFactory()
        ->listAll('taxonomy.vocabulary.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('taxonomy.vocabulary.'));
        $names[$id] = entity_load('taxonomy_vocabulary', $id)->label();
      }
    }
    return $names;
  }
  /**
   * Get names for all content types.
   * 
   * @return array array A list of existing content types IDs.
   */
  public function content_type_get_names() {
    $names =& drupal_static(__FUNCTION__);
    if (!isset($names)) {
      $names = [];
      $config_names = \Drupal::configFactory()
        ->listAll('node_type.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('node_type.'));
        $names[$id] = entity_load('node_type', $id)->label();
        
      }
    }
    return $names;
  }
}