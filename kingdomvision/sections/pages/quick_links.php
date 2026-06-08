<?php
// Jump Links Section
$jumplink_layout = $section['jumplink_layout'] ?? '';
$jumplink_title = $section['jumplink_title'] ?? '';
$jumplinks   = $section['jumplinks'] ?? '';


if ($jumplinks){
    echo jumplinksCode($jumplinks, $jumplink_layout, $jumplink_title);
}

?>
