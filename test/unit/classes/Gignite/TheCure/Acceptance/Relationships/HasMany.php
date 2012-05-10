<?php
namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.onetomany
 * @group  mappers.mongo
 */

use Gignite\TheCure\Acceptance\Acceptance;
use Gignite\TheCure\Container;

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
		
		$thread->add_posts($post);

		$container->mapper('Forum\Thread')->save($thread);

		// Test OneToMany
		$this->assertSame($post, $thread->posts()->current());

		// // Test BelongsToOne
		$this->assertSame($thread, $post->thread());

		// Test removing
		$thread->remove_posts($post);
		$container->mapper('Forum\Thread')->save($thread);

		$this->assertSame(0, $thread->posts()->count());
		$this->assertNull($post->thread());
	}

}

