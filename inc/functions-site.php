<?php
include_once ('taxonomy-departments.php');
//include_once ('add_new_users.php');

function exclude_category($query) {
    if ( $query->is_home() ) {
				// remove Benefits 2015 to 2016
        $query->set( 'cat', '-295' );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'exclude_category' );

?>