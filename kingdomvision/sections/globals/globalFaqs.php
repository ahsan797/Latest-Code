<?php

// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$theme = $args['theme'] ?? '';


$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$faq_columns = $section['faq_columns'] ?: '';
$default_open = $section['default_open'] ?: 0;
$faqs_lists = $section['faqs_lists'] ?: '';


echo '<section class="faqs full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    aria-label="Faqs - '.esc_attr($faq_columns).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);

        if($faqs_lists){
            if($faq_columns == 'two'){
                $total_faqs = count($faqs_lists);
                $half = ceil($total_faqs / 2);
                $first_half = array_slice($faqs_lists, 0, $half);
                $second_half = array_slice($faqs_lists, $half);

                echo '<div class="faqWrapper twoColumn">';
                    echo '<div class="col columnOne">';
                        echo '<div class="faqListing" data-open="'.esc_attr($default_open).'">';
                            foreach ($first_half as $lists) {
                                $question = $lists['question'] ?? '';
                                $answer = $lists['answer'] ?? '';

                                echo '<div class="faqItem" >';
                                    echo '<div class="question"><h3>'.$question.'</h3></div>';
                                    echo WysiwygReadMoreLess($answer ,  'answer');
                                echo '</div>';
                            }
                        echo '</div>'; #faqListing
                    echo '</div>'; #columnOne
                    echo '<div class="col columnTwo">';
                        echo '<div class="faqListing" data-open="'.esc_attr($default_open).'">';
                            foreach ($second_half as $lists) {
                                $question = $lists['question'] ?? '';
                                $answer = $lists['answer'] ?? '' ;

                                echo '<div class="faqItem" >';
                                    echo '<div class="question"><h3>'.$question.'</h3></div>';
                                    echo WysiwygReadMoreLess($answer ,  'answer');
                                echo '</div>';
                            }
                        echo '</div>'; #faqListing
                    echo '</div>'; #columnTwo
                echo '</div>'; #faqWrapper

            }else{
                echo '<div class="faqListing" role="lists" data-open="'.esc_attr($default_open).'">';
                    foreach($faqs_lists as $key => $lists){
                        $question = $lists['question'] ?? '';
                        $answer = $lists['answer'] ?? '';

                        echo '<div class="faqItem" role="listitem" >';
                            echo '<div class="question">';
                                echo '<h3>'.$question.'</h3>';
                            echo '</div>'; #question
                            echo WysiwygReadMoreLess($answer ,  'answer');
                        echo '</div>'; #faqItem

                    }
                echo '</div>'; #faqListing
            }
        }

    echo '</div>'; #container
echo '</section>'; #faqs

?>