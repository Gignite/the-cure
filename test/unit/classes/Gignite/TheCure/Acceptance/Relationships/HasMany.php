<?php
namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.onetomany
 */

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Models\Forum;

class HasMany extends \PHPUnit_Framework_TestCase {

	public function provideContainers()
	{
		return array(
			array(new Container('Mock')),
			array(new Container('Mongo')),
		);
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$thread = new Forum\Thread;
		$thread->__container($container);
		$thread->title('Welcome thread');
		$thread->message('<p>Welcome to the forum!</p>');

		$post = new Forum\Post;
		$post->__container($container);
		$post->message('<p>What a great welcome this is :D</p>');
		
		$thread->add_posts($post);

		$container->mapper('Forum\Thread')->save($thread);

		// Test OneToMany
		$this->assertSame($post, $thread->posts()->current());

		// // Test BelongsToOne
		$this->assertSame($thread, $post->thread());
	}

}

