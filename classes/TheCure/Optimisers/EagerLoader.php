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
		array $fields)
	{
		$accessor = new TransferObjectAccessor;
		$ids = array();

		foreach ($collection as $_model)
		{
			$object = $accessor->get($_model);

			foreach ($fields as $_field)
			{
				$relation = $_model::attributes()->get($_field);
				$ids[] = array($relation, $object->{$_field});
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

			$mapper->find(array('_id' => $_ids));
		}
	}

}