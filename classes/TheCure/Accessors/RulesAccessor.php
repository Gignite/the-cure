<?php

namespace TheCure\Accessors;

use TheCure\Lists\AttributeList;

class RulesAccessor {

	/**
	 * Filter fields from the AttributeList by filter
	 *
	 * @param AttributeList $fields
	 * @param array         $filter
	 * return AttributeList
	 */
	protected function filter_fields(AttributeList $fields, array $filter)
	{
		$filtered = array();

		foreach ($filter as $_field)
		{
			if ($field = $fields->get($_field))
			{
				$filtered[] = $field;
			}
		}

		return $filtered;
	}

	/**
	 * Get an array of field names as keys and rules array as values
	 *
	 * @example
	 *
	 *		array(
	 *			'ticket_url' => array(
	 *				array('not_empty'),
	 *				array('url'),
	 *			'date' => array(
	 *				array('not_empty'),
	 *				array('date'),
	 *		)
	 *
	 * @param  Model  $model
	 * @param  array  $filter
	 * @return array
	 */
	public function get(AttributeList $attributes, array $filter)
	{
		$fields_rules = array();

		$fields = $this->filter_fields($attributes, $filter);

		foreach ($fields as $_field)
		{
			if ($rules = $_field->rules())
			{
				$fields_rules[$_field->name()] = $rules;
			}
		}

		return $fields_rules;
	}

}
