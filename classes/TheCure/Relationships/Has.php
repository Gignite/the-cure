<?php
/**
 * A relationship between two models
 *
 * @package     TheCure
 * @category    Attribute
 * @category    Relationships
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\Relation;

abstract class Has extends Relationship implements Relation\Find {}