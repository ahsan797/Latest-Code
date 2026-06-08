<?php
// Unique Section Class
$uniqueSectionClass = 'section-' . $currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$main_child = $section['main_child_heading'] ?: '';
$introductory_text = $section['introductory_text'] ?: '';
$operated_by = $section['operated_by'] ?: '';
$awards_repeater = $section['awards_repeater'] ?: '';
$sub_heading = $section['sub_heading'] ?: '';
$info_listings = $section['info_listings'] ?: '';


echo '<section class="welcome-module full-section ' . esc_attr($section_text_color) . ' ' . esc_attr($uniqueSectionClass) . '" role="Welcome" aria-label="Welcome Section"
    ' . ($bg ? $bg : '') . '
    ' . ($section_id ? 'id="' . esc_attr($section_id) . '"' : '') . ' >';

echo '<div class="container">';
if ($main_child) {
    echo '<div class="subHeading ' . ($main_child ? 'main_child' : '') . '">';
    echo '<span>' . esc_html($main_child) . '</span>';
    echo '</div>'; #subHeading
}
echo HeadingFromSection($section);
if ($introductory_text) {
    echo WysiwygReadMoreLess($introductory_text,  'introductoryText');
}
if ($operated_by) {
    echo '<div class="opHeading">';
    echo '<span>' . esc_html($operated_by) . '</span>';
    echo '</div>'; #opHeading
}
if ($awards_repeater) {
    $countsAward = count($awards_repeater);
    echo '<div class="awardsRepeater ' . ($countsAward > 1 ? 'activeSlider' : '') . '" role="list" aria-label="Awards and Recognitions">';
    foreach ($awards_repeater as $key => $rep) {
        $award_logo = $rep['award_logo'];
        $award_url = $rep['award_url'];
        echo '<div class="awardItems" role="listitem">';
        if ($award_url) {
            echo '<a href="' . esc_url($award_url) . '" target="_blank" class="coverAnchor" aria-label="View award ' . ($key + 1) . ' details"></a>';
        }
        echo getFocalImage($award_logo, 'award_logo', $pageIndex, $key);
        echo '</div>'; #awardItems
    }
    echo '</div>'; #awardsRepeater
}
if ($sub_heading) {
    echo '<div class="opSubHeading">';
    echo '<span>' . esc_html($sub_heading) . '</span>';
    echo '</div>'; #opSubHeading
}

if ($info_listings) {
    echo '<div class="infoWrapper">';
    $post_id = get_queried_object_id();
    $product   = get_field('product', $post_id);
    $prodRegularPrice = !empty($product['regular_price']) ? $product['regular_price'] : '';
    $prodSalePrice = !empty($product['sale_price']) ? $product['sale_price'] : '';

    foreach ($info_listings as $key => $info) {
        $option = $info['option'];
        $title = $info['title'];
        $text = $info['text'];

        if ($option == 'price') {
            echo '<div class="infoItem '.esc_attr($option == 'price' ? 'perPerson' : '').' '.esc_attr($option).'">';
            echo '<span class="title">' . esc_html($title) . '</span>';
                echo regularSalePrice( regularPrice : $prodRegularPrice, salePrice : $prodSalePrice);
            echo '</div>'; #infoItem
        } else {
            echo '<div class="infoItem">';
            echo '<span class="title">' . esc_html($title) . '</span>';
            echo '<p>' . esc_html($text) . '</p>';
            echo '</div>'; #infoItem
        }
    }
    echo '</div>'; #infoWrapper
}

echo '</div>'; #container

echo '</section>';
