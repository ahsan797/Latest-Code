<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
get_template_part(
    'sections/globals/globalHeadingContentModule', 
    null,
    array(
        'uniqueSectionClass' => $uniqueSectionClass,
        'section' => $section,
        'theme' => 'dark'
    )
);

?>