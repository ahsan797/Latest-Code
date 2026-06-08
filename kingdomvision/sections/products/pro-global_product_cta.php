<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
get_template_part(
    'sections/globals/globalProductCta', 
    null,
    array(
        'section_id' => $section_id,
        'uniqueSectionClass' => $uniqueSectionClass
    )
);

?>