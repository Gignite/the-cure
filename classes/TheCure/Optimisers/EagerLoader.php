<?php
namespace TheCure\Optimisers;

use TheCure\Container;

use TheCure\Collections\ModelCollection;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Exceptions\IncompatibleMapperException;

use TheCure\Mappers\FindInMapper;

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
				foreach ($attributes as $_relation)
				{
					if ($_relation instanceOf Relationship)
					{
						$relations[] = $_relation;
					}
				}
			}

			foreach ($relations as $_relation)
			{
				$relation = $attributes->get($_relation);
				$ids[] = array($relation, $object->{$_relation});
			}
		}

		foreach ($ids as $_data)
		{
			list($relation, $_ids) = $_data;

			$mapper = $relation->mapper($container);

			// if ( ! $mapper instanceOf FindInMapper)
			// {
			// 	$class = get_class($mapper);
			// 	throw new IncompatibleMapperException(
			// 		"{$class} not instance of FindInMapper");
			// }

			$mapper->find(array('_id' => array('$in' => $_ids)));
		}
	}

}