<?php

get_header();

echo '<section class="blogs full-section dark" 
    role="Blogs" aria-label="Blogs" style="background: #F2E2C4;">';
    echo '<div class="container">';

        echo '<div class="headingWrapper">';
          echo '<h1 class="heading center" style="color: #bb5b3e; ">';
            foreach((get_the_category()) as $category) { echo $category->cat_name . ' '; }
          echo '</h1>';
        echo '</div>'; #headingWrapper

        if(have_posts()){
          echo '<div class="blogWrapper" role="list">';
          while(have_posts()){
            the_post();
            $post = get_the_ID();
            // echo blogStructureFromRepeator($post);
            echo blogStructureFromRepeator( rep: $post);
          }
          echo '</div>';
        } 
    echo '</div>'; #container
echo '</section>'; #blogs

get_footer();

?>
