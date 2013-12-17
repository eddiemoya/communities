<?php
/*
 * XML Sitemap Template
 */

/*
/--------------------------------------------------------------------\
|                                                                    |
| License: GPL                                                       |
|                                                                    |
| Simple Multisite Sitemaps                                         |
| Copyright (C) 2012, Jan Brinkmann                                  |
| http://the-luckyduck.de                                            |
|                                                                    |
| This program is free software; you can redistribute it and/or      |
| modify it under the terms of the GNU General Public License        |
| as published by the Free Software Foundation; either version 2     |
| of the License, or (at your option) any later version.             |
|                                                                    |
| This program is distributed in the hope that it will be useful,    |
| but WITHOUT ANY WARRANTY; without even the implied warranty of     |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
| GNU General Public License for more details.                       |
|                                                                    |
| You should have received a copy of the GNU General Public License  |
| along with this program; if not, write to the                      |
| Free Software Foundation, Inc.                                     |
| 51 Franklin Street, Fifth Floor                                    |
| Boston, MA  02110-1301, USA                                        |   
|                                                                    |
\--------------------------------------------------------------------/
*/
global $blog_id;
if($_GET["refresh"] == true) :
	ob_start();
	echo '<?xml version="1.0" encoding="'.get_option( 'blog_charset' ).'"?'.'>'; 
?>

	<urlset	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
		    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<?php 
	$alinks = array(
		'post'     => site_url('posts'),
		'question' => get_post_type_archive_link('question'),
		'guide'    => get_post_type_archive_link('guide')
	);
	$terms = array(
		"question" => get_terms_by_post_type('category', 'question'),
		"post"     => get_terms_by_post_type('category', 'post'),
		"guide"    => get_terms_by_post_type('category', 'guide'),
	);
	$labels = array (
		'question' => "Q&A's",
		'post'     => 'Blog Posts',
		'guide'    => 'Guides'
	);

	$query_args = array(
		'post_type'   => array( 'post', 'page', 'guide', 'question' ),
		'post_status' => 'publish',
		'orderby'     => 'date',
		'showposts'		=> -1
	);

	query_posts( $query_args );

	foreach(get_categories() as $category) :
		$url = get_category_link($category->term_id);
?>
		<url>
			<loc><?php echo $url; ?></loc>
			<changefreq>weekly</changefreq> 
			<priority>0.6</priority>
		</url>
<?php
	endforeach;

	foreach($labels as $post_type => $label) :
		foreach($terms[$post_type] as $term) :
			$url = esc_url( get_category_link($term->term_id) ).$post_type;
?>
				<url>
					<loc><?php echo $url; ?></loc>
					<changefreq>weekly</changefreq> 
					<priority>0.6</priority>
				</url>
<?php
		endforeach;
	endforeach;

	if ( have_posts()) : 
		while (have_posts() ) : the_post();
?>
				<url>
					<loc><?php echo (function_exists("get_blog_permalink") && get_blog_permalink( $blog_id, $post->ID )) ? get_blog_permalink( $blog_id, $post->ID ) : get_permalink($post->ID); ?></loc> 
					<lastmod><?php echo mysql2date( 'Y-m-d\TH:i:s+00:00', get_post_modified_time('Y-m-d H:i:s', true), false ); ?></lastmod> 
					<changefreq>weekly</changefreq> 
					<priority>0.6</priority>
				</url>
<?php 
		endwhile; 
	endif; 
?>
	</urlset>
<?php
	$sitemap = ob_get_clean();
	ob_end_clean();
	$uploads = wp_upload_dir();
	file_put_contents("{$uploads['basedir']}/{$blog_id}sitemap.xml", $sitemap);
else:
	header( 'HTTP/1.0 200 OK' );
	header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );
	$uploads = wp_upload_dir();
	echo file_get_contents("{$uploads['basedir']}/{$blog_id}sitemap.xml");
endif;
