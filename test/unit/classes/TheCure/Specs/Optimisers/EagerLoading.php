<?php
namespace TheCure\Specs;
/**
 * Test has many relationship
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
 * @group  relationships.hasmany
 */
use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Models;

use TheCure\Container;

use TheCure\Relationships\HasManyRelationship;

use TheCure\Maps\IdentityMap;

use TheCure\Optimisers\EagerLoader;

class EagerLoading extends \PHPUnit_Framework_TestCase {

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldEagerLoad()
	{
		$container = $this->container();

		$thread = $container->mapper('Forum\Thread')->model();
		$thread->title('Welcome thread');
		$thread->message('<p>Welcome to the forum!</p>');

		$post = $container->mapper('Forum\Post')->model();
		$post->message('<p>What a great welcome this is :D</p>');
		
		$thread->addPosts($post);

		$container->mapper('Forum\Thread')->save($thread);

		$identityMap = new IdentityMap;
		$container->mapper('Forum\Thread')->identities($identityMap);
		$accessor = new TransferObjectAccessor;
		$postId = $accessor->get($post)->_id;

		$eagerLoader = new EagerLoader;
		$threads = $container->mapper('Forum\Thread')->find();
		$eagerLoader->loadRelations($container, $threads, array('posts'));
		$this->assertNotNull($identityMap->get('Forum\Thread', $postId));

		$identityMap = new IdentityMap;
		$container->mapper('Forum\Thread')->identities($identityMap);
		$accessor = new TransferObjectAccessor;
		$postId = $accessor->get($post)->_id;

		$eagerLoader = new EagerLoader;
		$threads = $container->mapper('Forum\Thread')->find();
		$eagerLoader->loadRelations($container, $threads);
		$this->assertNotNull($identityMap->get('Forum\Thread', $postId));
	}

}

