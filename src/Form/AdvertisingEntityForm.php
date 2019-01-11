<?php

namespace Drupal\advertising\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AdvertisingEntityForm.
 */
class AdvertisingEntityForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $advertising_entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $advertising_entity->label(),
      '#description' => $this->t("Label for the Advertising entity."),
      //'#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $advertising_entity->id(),
      '#required' => FALSE,
      '#machine_name' => [
        'exists' => '\Drupal\advertising\Entity\AdvertisingEntity::load',
      ],
      '#disabled' => !$advertising_entity->isNew(),
    ];

    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('Url'),
      '#default_value' => $advertising_entity->get('url'),
      //'#required' => TRUE,
    ];

    $form['select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select element'),
      //'#required' => TRUE,
      '#default_value' => $advertising_entity->get('select'),
      '#options' => [
        'Home' => $this->t('Home'),
        'Sections' => $this->t('Sections'),
        'Articles' => $this->t('Articles'),
      ],
    ];

      // Gather the number of names in the form already.

    $campo = $form_state->get('campo');

    // We have to ensure that there is at least one name field.

    if ($campo === NULL) {
      $name_field = $form_state->set('campo', 1);
      $campo = 1;
    }

    
    $form['breakpoints'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Forms'),
      '#prefix' => '<div id="breakpoints-wrapper">',
      '#suffix' => '</div>',
      '#tree' => TRUE,
    ];

    for ($i = 0; $i < $campo; $i++) {
      $form['breakpoints']['form'][$i] = [
       '#type' => 'fieldset'
     ];
     $form['breakpoints']['form'][$i]['height'] = [
      '#type' => 'number',
      '#title' => $this->t('height'),
      '#default_value' => $form_state->get('height'),
      
    ];
    $form['breakpoints']['form'][$i]['width'] = [
      '#type' => 'number',
      '#title' => $this->t('width'),
    ];
    $form['breakpoints']['form'][$i]['remove'] = [
      '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'breakpoints-wrapper',
        ],
        '#name' => 'remove_name' . $campo,
      ];
    }

    $form['breakpoints']['actions'] = [
      '#type' => 'actions',
    ];
    $form['breakpoints']['actions']['add_form'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'breakpoints-wrapper',
      ],
    ];
    // If there is more than one name, add the remove button.
    if ($campo > 1) {
      $form['breakpoints']['actions']['remove_form'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove one'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'breakpoints-wrapper',
        ],
      ];
    }

    return $form;
  }


  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['breakpoints'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('campo');
    $add_button = $name_field + 1;
    $form_state->set('campo', $add_button);
    // Since our buildForm() method relies on the value of 'campo' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('campo');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('campo', $remove_button);
    }
    // Since our buildForm() method relies on the value of 'campo' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().

    $form_state->setRebuild();
  }
  
  
  
  public function save(array $form, FormStateInterface $form_state) {
    $advertising_entity = $this->entity;
    $status = $advertising_entity->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Advertising entity.', [
          '%label' => $advertising_entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Advertising entity.', [
          '%label' => $advertising_entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($advertising_entity->toUrl('collection'));
  }
}

