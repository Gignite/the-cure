<?php
namespace TheCure\Optimisers;

use TheCure\Container;

use TheCure\Collections\ModelCollection;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Exceptions\IncompatibleMapperException;

use TheCure\Mappers\FindInMapper;

use TheCure\Relationships\Relationship;

class EagerLoader {
	
	/**
	 * 
	 *     $eagerLoading->load($collection, array('venue', 'friends'));
	 * 
	 * @param  Collection $collection [description]
	 * @param  array      $fields     [description]
	 * @return [type]                 [description]
	 */
	public function loadRelations(
		Container $container,
		ModelCollection $collection,
		array $relations = array())
	{
		$accessor = new TransferObjectAccessor;
		$ids = array();

		foreach ($collection as $_model)
		{
			$attributes = $_model::attributes();
			$object = $accessor->get($_model);

			if (empty($relations))
			{
				foreach ($attributes->asArray() as $_relation)
				{
					if ($_relation instanceOf Relationship)
					{
						$relations[] = $_relation;
					}
				}
			}

			foreach ($relations as $_relation)
			{
				if ( ! isset($ids[$_relation->name()]))
				{
					$ids[$_relation->name()] = array(
						'relation' => $_relation,
						'ids'      => array(),
					);
				}

				$relationID = $object->{$_relation->name()};

				if (is_array($relationID))
				{
					$ids[$_relation->name()]['ids'] = array_merge(
						$ids[$_relation->name()]['ids'],
						$relationID);
				}
				else
				{
					$ids[$_relation->name()]['ids'][] = $relationID;
				}
			}
		}

		foreach ($ids as $_data)
		{
			$mapper = $_data['relation']->mapper($container);

			// if ( ! $mapper instanceOf FindInMapper)
			// {
			// 	$class = get_class($mapper);
			// 	throw new IncompatibleMapperException(
			// 		"{$class} not instance of FindInMapper");
			// }
			// 
			$mongoIDs = array();

			foreach ($_data['ids'] as $_id)
			{
				$mongoIDs[] = new \MongoID($_id);
			}

			iterator_to_array(
				$mapper->find(array('_id' => array('$in' => $mongoIDs))));
		}
	}

}