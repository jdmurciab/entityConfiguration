<?php

namespace Drupal\advertising\Entity;

use Drupal\example\ExampleInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;


/**
 * Defines the Advertising entity entity.
 *
 * @ConfigEntityType(
 *   id = "advertising_entity",
 *   label = @Translation("Advertising entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\advertising\AdvertisingEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\advertising\Form\AdvertisingEntityForm",
 *       "edit" = "Drupal\advertising\Form\AdvertisingEntityForm",
 *       "delete" = "Drupal\advertising\Form\AdvertisingEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\advertising\AdvertisingEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "advertising_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "address" = "address"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/advertising_entity/{advertising_entity}",
 *     "add-form" = "/admin/structure/advertising_entity/add",
 *     "edit-form" = "/admin/structure/advertising_entity/{advertising_entity}/edit",
 *     "delete-form" = "/admin/structure/advertising_entity/{advertising_entity}/delete",
 *     "collection" = "/admin/structure/advertising_entity"
 *   }
 * )
 */
class AdvertisingEntity extends ConfigEntityBase implements AdvertisingEntityInterface {

  /**
   * The Advertising entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Advertising entity label.
   *
   * @var string
   */
  protected $label;

    /**
   * The Advertising entity label.
   *
   * @var string
   */
    protected $address;

    /**
   * The Advertising entity label.
   *
   * @var string
   */
    protected $select;

    /**
   * The Advertising entity label.
   *
   * @var string
   */
    protected $ID;

    /**
   * Set the default place to put an AD.
   *
   * @param string $place
   *   The  select place to set.
   *
   * @return string
   */

  public $breakpoints;
  /**
   * Set the default breakpoints.
   *
   * @param string $breakpoints
   *   The breakpoints to set.
   *
   * @return string
   */
  public function setSelect($select) {
    return $this->set('place', $select);
  }
  /**
   * Get the default select place to put an AD.
   *
   * @return string
   */
  public function getSelect() {
    return $this->get('place');
  }

  /**
   * The Advertising entity breakpoints.
   *
   * @var array
   */
  
  public function setBreakpoints($breakpoints) {
    $serializer = \Drupal::service('serialization.phpserialize');
    $this->breakpoints = $serializer->encode($breakpoints);
  }
  /**
   * Get the breakpoints.
   *
   * @return string
   */
  public function getBreakpoints() {
    $serializer = \Drupal::service('serialization.phpserialize');
    return $serializer->decode($this->breakpoints);
  }
}