<?php

$uniqueSectionClass = 'section-'.$currenSectionIndex;
get_template_part(
    'sections/globals/globalExpertAdvice', 
    null,
    array(
        'uniqueSectionClass' => $uniqueSectionClass,
        'section' => $section
    )
);

?>