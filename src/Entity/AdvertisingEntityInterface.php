<?php

namespace Drupal\advertising\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Advertising entity entities.
 */
interface AdvertisingEntityInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.

  public function getSelect();
/**
* Set the default place to put an AD.
*
* @param string $select
* The place to set.
*
* @return string
*/
public function setSelect($select);
/**
* Get the breakpoints.
*
* @return string
*/
public function getBreakpoints();
/**
* Set the default breakpoints.
*
* @param string $breakpoints
* The breakpoints to set.
*
* @return string
*/
public function setBreakpoints($breakpoints);

}
