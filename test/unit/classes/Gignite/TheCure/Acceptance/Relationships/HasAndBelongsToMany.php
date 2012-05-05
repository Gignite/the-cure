<?php
namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.manytomany
 */

use Gignite\TheCure\Acceptance\Acceptance;
use Gignite\TheCure\Mapper\Container;

class HasAndBelongsToMany extends Acceptance {

	protected function createPost($container, $message)
	{
		$post = $container->mapper('Forum\Post')->model();
		$post->message($message);
		return $post;
	}

	protected function createTag($container, $name)
	{
		$tag = $container->mapper('Forum\Tag')->model();
		$tag->name($name);
		return $tag;
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$firstPost = $this->createPost(
			$container,
			'<p>What a great welcome this is :D</p>');

		$secondPost = $this->createPost(
			$container,
			'<p>Cool stuff</p>');

		$coolTag = $this->createTag($container, 'cool');
		$irrelevantTag = $this->createTag($container, 'irrelevant');
		
		// Adding to HasMany relationship
		$firstPost->add_tags($coolTag);

		// And some more
		$secondPost->add_tags($coolTag);
		$secondPost->add_tags($irrelevantTag);

		$container->mapper('Forum\Post')->save($firstPost);
		$container->mapper('Forum\Post')->save($secondPost);

		// Getting HasMany relationship
		$this->assertSame(1, $firstPost->tags()->count());
		$this->assertSame(2, $secondPost->tags()->count());

		// Getting BelongsToMany relationship
		$this->assertSame(2, $coolTag->posts()->count());
		$this->assertSame(1, $irrelevantTag->posts()->count());
	}

}