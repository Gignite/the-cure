<?php
namespace TheCure\Optimisers;

use TheCure\Container;

use TheCure\Lists\AttributeList;

use TheCure\Collections\ModelCollection;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Exceptions\IncompatibleMapperException;

use TheCure\Mappers\FindInMapper;
use TheCure\Mappers\MongoMapper;

use TheCure\Relationships\Relationship;

class EagerLoader {

	private function getRelationshipsFromAttributeList(AttributeList $attributes)
	{
		$relationships = array();

		foreach ($attributes->asArray() as $_relationship)
		{
			if ($_relationship instanceOf Relationship)
			{
				$relationships[] = $_relationship;
			}
		}

		return $relationships;
	}

	private function extractAttributes(AttributeList $attributes, $names)
	{
		$extractedAttributes = array();

		foreach ($names as $_k => $_relation)
		{
			$extractedAttributes[$_k] = $attributes->get($_relation);
		}

		return $extractedAttributes;
	}

	private function collectIDsFromRelationship($object, $relationship)
	{
		$ids = $object->{$relationship->alias()};

		if ( ! is_array($ids))
		{
			$ids = array($ids);
		}

		return $ids;
	}

	private function appendRelationAndIDs(
		Relationship $relationship,
		array $ids,
		array $newIDs)
	{
		$relationshipName = $relationship->alias();

		if ( ! isset($ids[$relationshipName]))
		{
			$ids[$relationshipName] = array(
				'relation' => $relationship,
				'ids'      => array(),
			);
		}

		$ids[$relationshipName]['ids'] = array_merge(
			$ids[$relationshipName]['ids'],
			$newIDs);

		return $ids;
	}

	private function getRelationshipsAndIDsFromCollection(
		\TheCure\Collections\Collection $collection)
	{
		$attributes = array();
		$accessor = new TransferObjectAccessor;
		$ids = array();

		foreach ($collection as $_model)
		{
			$class = get_class($_model);

			if ( ! isset($attributes[$class]))
			{
				$attributes[$class] = $_model::attributes();
			}

			$attr = $attributes[$class];
			$object = $accessor->get($_model);
			$relations = array();

			if (empty($relationNames))
			{
				$relationships = $this->getRelationshipsFromAttributeList(
					$attr);
			}
			else
			{
				$relationships = $this->extractAttributes(
					$attr,
					$relationNames);
			}

			foreach ($relationships as $_relation)
			{
				$ids = $this->appendRelationAndIDs(
					$_relation,
					$ids,
					$this->collectIDsFromRelationship($object, $_relation));
			}
		}

		return $ids;
	}
	
	/**
	 * 
	 *     $mapOfCollections = $eagerLoader->loadRelations(
	 *         $container,
	 *         $collection,
	 *         array('venue', 'friends'));
	 *
	 *     foreach ($mapOfCollections as $_collection)
	 *     {
	 *         $eagerLoader->loadRelations($container, $_collection, array('location'));
	 *     }
	 * 
	 * @param   Container
	 * @param   ModelCollection
	 * @param   array     
	 * @return  map of ModelCollections
	 */
	public function loadRelations(
		Container $container,
		$collection,
		array $relationNames = array())
	{

		if (is_array($collection))
		{
			$collection = new \TheCure\Collections\Collection($collection);
		}
		
		$ids = $this->getRelationshipsAndIDsFromCollection($collection);

		$eagerLoaded = array();

		foreach ($ids as $_data)
		{
			$relation = $_data['relation'];
			$mapper = $relation->mapper($container);

			if ($mapper instanceOf MongoMapper)
			{
				foreach ($_data['ids'] as $_k => $_id)
				{
					$_data['ids'][$_k] = new \MongoID($_id);
				}
			}

			$eagerLoaded[$relation->name()] =
				$mapper->find(
					array('_id' => array('$in' => $_data['ids'])),
					$relation->model());

			iterator_to_array($eagerLoaded[$relation->name()]);
		}

		return $eagerLoaded;
	}

}