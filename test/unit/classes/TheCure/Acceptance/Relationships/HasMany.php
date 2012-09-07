<?php
namespace TheCure\Acceptance\Relationships;
/**
 * Test the attributes container
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Acceptance
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  acceptance
 * @group  relationships
 * @group  relationships.hasmany
 * @group  mappers
 * @group  mappers.mongo
 */
use TheCure\Acceptance\Acceptance;
use TheCure\Container;

class HasMany extends Acceptance {

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$thread = $container->mapper('Forum\Thread')->model();
		$thread->title('Welcome thread');
		$thread->message('<p>Welcome to the forum!</p>');

		$post = $container->mapper('Forum\Post')->model();
		$post->message('<p>What a great welcome this is :D</p>');
		
		$thread->addPosts($post);

		$container->mapper('Forum\Thread')->save($thread);

		// Test Contains
		$this->assertTrue($thread->containsPosts($post));

		// Test OneToMany
		$this->assertSame($post, $thread->posts()->current());

		// // Test BelongsToOne
		$this->assertSame($thread, $post->thread());

		// Test removing
		$thread->removePosts($post);
		$container->mapper('Forum\Thread')->save($thread);

		$this->assertSame(0, $thread->posts()->count());
		$this->assertFalse($post->thread());
	}

}

