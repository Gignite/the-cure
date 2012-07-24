<?php
namespace TheCure\Specs;
/**
 * Test belongs to one relationship
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  relationships
 * @group  relationships.belongstoone
 */
use TheCure\Container;

use TheCure\TransferObjects\TransferObject;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Models;

use TheCure\Relationships\BelongsToOneRelationship;

class RelationshipBelongsToOne extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper' => 'User',
			'modelSuffix'  => 'Admin',
			'foreign'       => 'best_friend',
		);
		return new BelongsToOneRelationship('best_friender', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindARelatedModel()
	{
		$container = $this->container();
		$model = new Models\User\Admin;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array('best_friend' => 0));
		$container->mapper('User')->save($model);
		$collection = $this->relationship()->find($container, $model);
		$this->assertInstanceOf('TheCure\Models\User\Admin', $collection);
	}

}

