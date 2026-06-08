<?php 
get_header();

echo '<main class="content-wrapper full-section blogSingle">';
   if(have_posts()){
      while(have_posts()){
         the_post();

         $select_author = get_field('select_member');
         $authors_image_border = get_field('authors_image_border');
         $authors_image_border_color = get_field('authors_image_border_color');
         $date = get_field('date');
         $jumplinks = get_field('jumplinks');
         $jumplink_layout = get_field('jumplink_layout');
         $jumplink_title = get_field('jumplink_title');
         $blog_builder = get_field('blog_builder');

         $custom_breadcrumb = get_field('custom_breadcrumb');
         $teamMembersOption = get_field('team_members' , 'option');

         get_template_part('topBannerUTC');

         if($custom_breadcrumb){
            echo '<section class="breadcrumb-wrapper full-section"
               role="Breadcrumb" 
               aria-label="Breadcrumb">';
               echo '<div class="container">'; 
                  echo do_shortcode('[cst-breadcrumbs]');
               echo '</div>';
            echo '</section>';
         }

         if($select_author){
            echo '<section class="authorDetails full-section"
               role="AuthorDetails" 
               aria-label="AuthorDetails">';
               echo '<div class="container">';                   
                  echo '<div class="borderCover">';
                     echo '<div class="authorWrapper">';
                        if($select_author && $teamMembersOption ){
                           $selected_name = $select_author;
                           $member_data = null;
                           foreach ($teamMembersOption as $team_member_row) {
                              if ($team_member_row['name'] === $selected_name) {
                                    $member_data = $team_member_row;
                                    break;
                              }
                           }
                           if($member_data || $authorName){
                              $profile_image = $member_data['profile_image'] ?: '';
                              $name = $member_data['name'] ?: '';
                              echo '<div class="authorImg">';
                                 echo '<div class="teamImg">';
                                    echo wp_get_attachment_image($profile_image, 'full', false, 
                                       [ 
                                          'loading' => 'eager',
                                          'style' => 'border: '.esc_attr($authors_image_border).'px solid '.esc_attr($authors_image_border_color).''
                                       ]
                                    );
                                 echo '</div>'; // teamImg
                              echo '</div>'; #authorImg
                              echo '<div class="authorData">';
                                 echo '<p class="written">Written by</p>';
                                 if ($name) {
                                    echo '<span>' . esc_html($name) . '</span>';
                                 }
                                 if($date){
                                    echo '<p class="date">'.esc_html($date).'</p>';
                                 }
                              echo '</div>'; #authorData
                           }
                        }
                     echo '</div>'; #authorWrapper
                     echo '<div class="storyWrapper">';
                        echo '<div class="storyToggle">';
                           echo '<span class="text">Share</span>';
                              echo '<span class="arrow"><svg width="16" height="25" viewBox="0 0 16 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 24.1673V22.7569H15.5V24.1673H0.5ZM7.295 18.3982V3.46524L1.88042 8.89815L0.907917 7.92565L8 0.833984L15.0921 7.92565L14.1196 8.89815L8.705 3.46524V18.3982H7.295Z" fill="%23393732"/></svg></span>';
                        echo '</div>'; #storyToggle
                        echo '<div class="storyShare" style="display: none;">';
                           $facebook = get_field('facebook', 'option');
                           $twitter = get_field('twitter', 'option');
                           $linkedin = get_field('linkedin', 'option');
                           $google_plus = get_field('google_plus', 'option');
                           $mail = get_field('mail', 'option');
                           echo '<ul>';
                              if($facebook){
                              echo '<li><a href="//www.facebook.com/sharer/sharer.php?u='.get_the_permalink().'" target="_blank"><i class="fa-brands fa-square-facebook"></i></a></li>';
                              }
                              if($twitter){
                              echo '<li><a href="//twitter.com/intent/tweet?text='.get_the_title().'&amp;url='.get_the_permalink().'" target="_blank"><i class="fa-brands fa-square-twitter"></i></a></li>';
                              }
                              if($linkedin){
                              echo '<li><a href="https://www.linkedin.com/sharing/share-offsite/?url='.get_the_permalink().'" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>';
                              }
                              if($mail){
                              echo '<li><a href="mailto:?subject='.get_the_title().'&amp;body='.get_the_permalink().'"><i class="fa-solid fa-envelope"></i></a></li>';
                              }
                           echo '</ul>';
                           echo '<span class="closeStory">';
                              echo '<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.95715 16.5796L0.920898 15.5433L7.96382 8.50039L0.920898 1.49914L1.95715 0.462891L9.00007 7.50581L16.0013 0.462891L17.0376 1.49914L9.99465 8.50039L17.0376 15.5433L16.0013 16.5796L9.00007 9.53664L1.95715 16.5796Z" fill="#393732"></path></svg>';
                           echo '</span>'; #closeStory
                        echo '</div>'; #storyShare
                     echo '</div>'; #storyWrapper
                  echo '</div>'; #borderCover
               echo '</div>'; #container
            echo '</section>'; #authorDetails
         }

         $defualtPostEditor = apply_filters('the_content', get_the_content());
         if($defualtPostEditor){
            echo '<section class="introContent full-section">';
               echo '<div class="container">';
                  echo '<div class="defualtPostEditor">';
                     echo $defualtPostEditor;
                  echo '</div>'; #efualtPostEditor
               echo '</div>';
            echo '</section>';
         }

         if ($jumplinks){
              echo jumplinksCode($jumplinks, $jumplink_layout, $jumplink_title);
         }

         if($blog_builder){
            echo '<div class="blogBuilder full-section">';
               if (!empty($blog_builder) && is_array($blog_builder)) {
                  $sectionIndex = 0;
                  foreach ($blog_builder as $pageIndex => $section){
                     $sectionIndex++;
                     $layout = $section['acf_fc_layout'];
                     $pageIndex = $layout;
                     $template = locate_template("sections/blogs/{$layout}.php");

                     if ($template) {
                           $currenSectionIndex = $sectionIndex;
                           include $template;
                     }

                  }

               }else{
                  echo '<div class="container" style="text-align:center;">';
                     the_content();
                  echo '</div>';
               }
            echo '</div>'; #blogBuilder
         }

      }
   }
echo '</main>'; #content-wrapper

get_footer(); ?>