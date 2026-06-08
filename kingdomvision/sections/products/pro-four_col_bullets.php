<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$select_columns = $section['select_columns'] ?? '';
$text_alignment = $section['text_alignment'] ?? '';
$one_column_bullets = $section['one_column_bullets'] ?? [];
$two_column_bullets = $section['two_column_bullets'] ?? '';
$three_column_bullets = $section['three_column_bullets'] ?? '';
$four_column_bullets = $section['four_column_bullets'] ?? '';


echo '<section class="bullets-block full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Bullets - '.esc_attr($select_columns).'" aria-label="Bullets - '.esc_attr($select_columns).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);

        if(!function_exists('bullets')){
            function bullets($bullets, $skipEmpty = false){
                if($bullets){
                    echo '<ul class="bulletWrapper">';
                        $index = 1;
                        foreach($bullets as $key => $bullet){
                            $label = $bullet['label'];
                            $option = $bullet['option'];

                            if($skipEmpty && $label === ''){
                                continue; // skip empty li
                            }

                            echo '<li class="'.esc_attr($option == 'title' ? 'title' : 'text').'">';
                                if($option == 'title'){
                                    echo '<span>' . esc_html($label) . '</span>';
                                }else{
                                    echo '<p>'.esc_html($label).'</p>';
                                }
                            echo '</li>';
                            $index++;
                        }
                    echo '</ul>'; #select_columns
                }
            }
        }

        if($select_columns == 'four'){
            echo '<div class="columnsWrapper onlyDesktop '.esc_attr($select_columns).'">';
                echo '<div class="col">';
                    bullets($four_column_bullets['column_one']);
                echo '</div>';
                echo '<div class="col">';
                    bullets($four_column_bullets['column_two']);
                echo '</div>';
                echo '<div class="col">';
                    bullets($four_column_bullets['column_three']);
                echo '</div>';
                echo '<div class="col">';
                    bullets($four_column_bullets['column_four']);
                echo '</div>';
            echo '</div>'; #columnsWrapper

            echo '<div class="columnsWrapper onlyMobile '.esc_attr($select_columns).'" style="display: none;">';
                echo '<div class="col">';
                    $fourColBullets = array_merge(
                        $four_column_bullets['column_one'] ?? [],
                        $four_column_bullets['column_two'] ?? [],
                        $four_column_bullets['column_three'] ?? [],
                        $four_column_bullets['column_four'] ?? []
                    );
                    bullets($fourColBullets, true);

                echo '</div>'; #col
            echo '</div>'; #columnsWrapper/onlyMobile

        }elseif($select_columns == 'three'){
            echo '<div class="columnsWrapper onlyDesktop '.esc_attr($select_columns).'">';
                echo '<div class="col">';
                    bullets($three_column_bullets['column_one']);
                echo '</div>';
                echo '<div class="col">';
                    bullets($three_column_bullets['column_two']);
                echo '</div>';
                echo '<div class="col">';
                    bullets($three_column_bullets['column_three']);
                echo '</div>';
            echo '</div>'; #columnsWrapper

            echo '<div class="columnsWrapper onlyMobile '.esc_attr($select_columns).'" style="display: none;">';
                echo '<div class="col">';
                    $threeColBullets = array_merge(
                        $three_column_bullets['column_one'] ?? [],
                        $three_column_bullets['column_two'] ?? [],
                        $three_column_bullets['column_three'] ?? [],
                    );
                    bullets($threeColBullets, true);

                echo '</div>'; #col
            echo '</div>'; #columnsWrapper/onlyMobile

        }elseif ($select_columns == 'two') {
            echo '<div class="columnsWrapper onlyDesktop '.esc_attr($select_columns).'">';
                echo '<div class="col">';
                    bullets($two_column_bullets['column_one']);
                echo '</div>';
                echo '<div class="col">';
                    bullets($two_column_bullets['column_two']);
                echo '</div>';
            echo '</div>'; #columnsWrapper       

            echo '<div class="columnsWrapper onlyMobile '.esc_attr($select_columns).'" style="display: none;">';
                echo '<div class="col">';
                    $twoColBullets = array_merge(
                        $two_column_bullets['column_one'] ?? [],
                        $two_column_bullets['column_two'] ?? [],
                    );     
                    bullets($twoColBullets, true);
                echo '</div>'; #col
            echo '</div>'; #columnsWrapper/onlyMobile

        }else{
            echo '<div class="columnsWrapper '.esc_attr($select_columns).' ">';
                echo '<div class="col '.esc_attr($text_alignment).'">';
                    bullets($one_column_bullets, true);
                echo '</div>';
            echo '</div>'; #columnsWrapper
        }

    echo '</div>'; #container
echo '</section>'; #faqs

?>