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
 *     "url" = "url",
 *     "select" = "select",
 *     "height" = "height"
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
    protected $url;

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
    protected $height;

}
