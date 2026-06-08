<?php get_header(); 

echo '<div class="content-wrapper full-section searchPage">';

    $s = get_search_query();
    $args = array(
        's' =>$s
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
        echo '<section class="searchWrapper full-section dark" role="region" aria-label="Search" >';
            echo '<div class="container ">';
                echo '<div class="Searchblogs">';
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        $post = get_the_ID();
                        // echo blogStructureFromRepeator(get_the_ID(), 'Read More');
                        echo blogStructureFromRepeator( rep: $post, button_text: 'Read More' );
                    }
                echo '</div>'; #blogWrapper
            echo '</div>'; #container
        echo '</section>'; #searchWrapper
    }else{
        echo '<div class="container" style="text-align:center;">';
            echo '<h2 style="margin:20px 0;display:inline-block;">Nothing Found</h2>';
            echo '<p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>';
        echo '</div>';
    }

echo '</div>';

get_footer(); ?>