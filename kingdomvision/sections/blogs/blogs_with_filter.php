<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
get_template_part(
    'sections/globals/globalBlogsWithFilter', 
    null,
    array(
        'uniqueSectionClass' => $uniqueSectionClass,
        'section' => $section,
        'theme' => 'dark'
    )
);

?>