<?php
/**
 * Main plugin file.
 *
 * @package PostSocialLinks
 */

namespace PostSocialLinks;

/*
 * Plugin Name: Post Social Links
 * Plugin URI: https://github.com/kienstra/post-social-links
 * Description: Adds social sharing links after every post.
 * Version: 0.1.0
 * Author: Ryan Kienstra
 * Author URI: https://github.com/kienstra
 * License: GPL2+
*/

add_filter( 'the_content', __NAMESPACE__ . '\add_social_links_after_each_post' );

function add_social_links_after_each_post( $content ) {
	$social_links = [
		[
			'blockName' => 'core/social-link',
			'attrs'     => [
				'url'     => sprintf(
					'http://twitter.com/intent/tweet?text=%1$s+%2$s',
					rawurlencode( get_the_title() ),
					rawurlencode( get_the_permalink() ),
				),
				'service' => 'twitter',
			],
		],
		[
			'blockName' => 'core/social-link',
			'attrs'     => [
				'url'     => add_query_arg(
					[
						'url'   => rawurlencode( get_the_permalink() ),
						'title' => rawurlencode( get_the_title() ),
					],
					'https://reddit.com/submit'
				),
				'service' => 'reddit',
			],
		],
		[
			'blockName' => 'core/social-link',
			'attrs'     => [
				'url'     => add_query_arg(
					[
						'subject' => esc_attr(
							sprintf(
								/* translators: %1$s: the title of the post */
								__( 'Here is a post I found: %1$s', 'post-social-links' ),
								get_the_title()
							),
						),
						'body'    => rawurlencode( get_the_permalink() ),
					],
					'mailto:'
				),
				'service' => 'mail',
			],
		],
	];

	return $content . render_block(
		[
			'blockName'    => 'core/social-links',
			'innerBlocks'  => $social_links,
			'innerHTML'    => '<ul class="wp-block-social-links"></ul>',
			'innerContent' => [
				'<ul class="wp-block-social-links">',
				...array_fill( 0, count( $social_links ), null ),
				'</ul>',
			],
		]
	);
}
