<?php

// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';

$padding_top = $section['padding_top'] ?: '';
$padding_bottom = $section['padding_bottom'] ?: '';
$background_color = $section['background_color'] ?: '';
$width = $section['width'] ?: '';
$color = $section['color'] ?: '';

$style = '';
if($padding_top || $padding_bottom || $background_color || $color){
    $style = 'style="
    '.($padding_top ? 'padding-top: '.esc_attr($padding_top).'px;' : '').'
    '.($padding_bottom ? 'padding-bottom: '.esc_attr($padding_bottom).'px;' : '').'
    '.($background_color ? 'background: '.esc_attr($background_color).';' : '').'"';
}

echo '<section class="separatorBlock full-section '.esc_attr($uniqueSectionClass).' global" '.($style ? $style : '').'>';
    echo '<div class="container" 
    '.($width ? 'style="'.($width ? 'width: '.esc_attr($width).'%;' : '').' height: 1px; background: '.esc_attr($color).';"' : '').'>';
echo '</section>';

?>