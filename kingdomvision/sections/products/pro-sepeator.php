<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
get_template_part(
    'sections/globals/globalSepeator', 
    null,
    array(
        'uniqueSectionClass' => $uniqueSectionClass,
        'section' => $section
    )
);


?>