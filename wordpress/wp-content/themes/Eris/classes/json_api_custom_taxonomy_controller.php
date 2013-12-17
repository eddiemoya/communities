<?php
// most of the functions here are rewrite of json-api functions
class JSON_API_Custom_Taxonomy_Controller {

    public function get_recent_posts() {
        global $json_api;

        $posts = $json_api->introspector->get_posts();
        foreach ($posts as $jpost) {
            $this->add_taxonomies( $jpost );
        }
        return $this->posts_result($posts);
    }

    protected function posts_result($posts) {
        global $wp_query;
        return array(
            'count' => count($posts),
            'count_total' => (int) $wp_query->found_posts,
            'pages' => $wp_query->max_num_pages,
            'posts' => $posts
        );
    }

    protected function add_taxonomies( $post ) {
        $taxonomies = get_object_taxonomies( $post->type );
        foreach ($taxonomies as $tax) {
            $post->$tax = array();
            $terms = wp_get_object_terms( $post->id, $tax );
            foreach ( $terms as $term ) {
                $post->{$tax}[] = $term->name;
            }
        }
        return true;
    }

    public function get_taxonomy_posts() {
        global $json_api;
        $taxonomy = $this->get_current_taxonomy();
        if (!$taxonomy) {
            $json_api->error("Not found.");
        }
        $term = $this->get_current_term( $taxonomy );
       
        $posts =  $json_api->introspector->get_posts(array('tax_query' => array(
																				array(
																					'taxonomy' => $taxonomy,
																					'field' => 'slug',
																					'terms' => $term,
																					'include_children' => $this->get_return_children()
																				))
														));
        foreach ($posts as $jpost) {
            $this->add_taxonomies( $jpost );
        }
        return $this->posts_object_result($posts, $taxonomy, $term);
    }

    protected function get_current_taxonomy() {
        global $json_api;
        $taxonomy  = $json_api->query->get('taxonomy');
        if ( $taxonomy ) {
            return $taxonomy;
        } else {
            $json_api->error("Include 'taxonomy' var in your request.");
        }
        return null;
    }
	
    protected function get_return_children() {
    	global $json_api;
    	
    	 $children = $json_api->query->get('children');
    	 
    	 return ($children == 'true') ? true : false;
    }
    
    protected function get_current_term( $taxonomy=null ) {
        global $json_api;
        extract($json_api->query->get(array('id', 'slug', 'term_id', 'term_slug')));
        if ($id || $term_id) {
            if (!$id) {
                $id = $term_id;
            }
            return $this->get_term_by_id($id, $taxonomy);
        } else if ($slug || $term_slug) {
            if (!$slug) {
                $slug = $term_slug;
            }
            return $this->get_term_by_slug($slug, $taxonomy);
        } else {
            $json_api->error("Include 'id' or 'slug' var for specifying term in your request.");
        }
        return null;
    }

    protected function get_term_by_id($term_id, $taxonomy) {
        $term = get_term_by('id', $term_id, $taxonomy);
        if ( !$term ) return null;
        return new JSON_API_Term( $term );
    }

    protected function get_term_by_slug($term_slug, $taxonomy) {
        $term = get_term_by('slug', $term_slug, $taxonomy);
        if ( !$term ) return null;
        return new JSON_API_Term( $term );
    }

    protected function posts_object_result($posts, $taxonomy, $term) {
        global $wp_query;
        return array(
          'count' => count($posts),
          'pages' => (int) $wp_query->max_num_pages,
          'taxonomy' => $taxonomy,
          'term' => $term,
          'posts' => $posts
        );
    }

}

// Generic rewrite of JSON_API_Tag class to represent any term of any type of taxonomy in WP
class JSON_API_Term {

  var $id;          // Integer
  var $slug;        // String
  var $title;       // String
  var $description; // String

  function JSON_API_Term($term = null) {
    if ($term) {
      $this->import_wp_object($term);
    }
  }

  function import_wp_object($term) {
    $this->id = (int) $term->term_id;
    $this->slug = $term->slug;
    $this->title = $term->name;
    $this->description = $term->description;
    $this->post_count = (int) $term->count;
  }

}