<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
get_template_part(
    'sections/globals/globalTrendingBlogs', 
    null,
    array(
        'uniqueSectionClass' => $uniqueSectionClass,
        'section' => $section,
        'pageIndex' => $pageIndex,
        'theme' => 'dark'
    )
);

?>