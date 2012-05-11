<?php
namespace Gignite\TheCure\Specs;

/**
 * @group  specs
 * @group  relationships
 * @group  relationships.belongstoone
 */

use Gignite\TheCure\Object;
use Gignite\TheCure\Models;
use Gignite\TheCure\Container;
use Gignite\TheCure\Relationships\BelongsToOne;

class RelationshipBelongsToOne extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper_suffix' => 'User',
			'model_suffix'  => 'Admin',
			'foreign'       => 'best_friend',
		);
		return new BelongsToOne('best_friender', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindARelatedModel()
	{
		$container = $this->container();
		$model = new Models\User\Admin;
		$model->__object(new Object(array(
			'best_friend' => 0,
		)));
		$container->mapper('User')->save($model);
		$collection = $this->relationship()->find($container, $model);
		$this->assertInstanceOf('Gignite\TheCure\Models\User\Admin', $collection);
	}

}

