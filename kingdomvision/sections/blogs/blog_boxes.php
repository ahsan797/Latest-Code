<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
get_template_part(
    'sections/globals/globalBlogBoxes', 
    null,
    array(
        'uniqueSectionClass' => $uniqueSectionClass,
        'section' => $section,
        'theme' => 'dark'
    )
);

?>