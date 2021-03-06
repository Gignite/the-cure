<?php
/**
 * A relationship between two models
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Attribute
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\Relations\FindRelation;

abstract class HasRelationship extends Relationship implements FindRelation {}