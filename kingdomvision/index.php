<?php get_header();
 /* 
 * The default page template
 */
?>

<div class="content-wrapper full-section">
<div class="container">
  <div class="gdl-page-item">
    <div class="blog-content-area">
			<?php
            if (have_posts()) {
                while (have_posts()) {
            the_post(); ?>
            <?php get_template_part( 'blog', 'loop' ); ?>
            <?php }
            }
            ?>
<!--         <div class="page-navi"><?php //wp_pagenavi(); ?></div> -->
    </div>
  <div class="sidebar-content-area">
    <?php dynamic_sidebar('Blog'); ?>
  </div>
</div>
</div><!-- .container -->

<?php get_footer(); ?>