<?php
/* This file takes the AJAX request and processes it to build the results elements. It uses a simple search that gets the title and permalink of a post, and displays it within a list. */

/* If there are no results for a query, the dialog will tell you. At the bottom are two functions that let you show this search tool to both logged in and logged out users. */

function live_search() {
    $search_term = $_POST['search'];
    $query = new WP_Query(array(
        's' => $search_term,
        'posts_per_page' => 5,
    ));

    if ($query->have_posts()) {
        echo '<ol>';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        wp_reset_postdata();
        echo '</ol>';
    } else {
        echo 'No results found.';
    }

    wp_die();
}

add_action('wp_ajax_live_search', 'live_search');
add_action('wp_ajax_nopriv_live_search', 'live_search');
?>
