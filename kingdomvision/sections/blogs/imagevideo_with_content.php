<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;
$bg = backgroundFromSection($section); //Fixed For All Section

$imagevideo = $section['imagevideo'] ?? [];
$wysiwyg_editor = $section['wysiwyg_editor'] ?? '';
$section_id = preg_replace('/\s+/', '', $section['section_id'] ?? '');

echo '<section class="imgVideoContent full-section dark '.esc_attr($uniqueSectionClass).'"
    role="region" 
    aria-label="Image or Video Content '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').'>';
    echo '<div class="container">';
        echo '<div class="postMaxContent">';
            echo HeadingFromSection($section);
            if($imagevideo){
                $counter = count($imagevideo);
                $class = '';
                if($counter == 1){
                    $class = 'item1';
                }elseif($counter == 2){
                    $class = 'item2';
                }elseif($counter == 3){
                    $class = 'item3';
                }elseif($counter == 4){
                    $class = 'item4';
                }
                echo '<div class="imgVideoWrapper '.esc_attr($class).'">';
                    foreach($imagevideo as $key => $iv){
                        $select_imagevideo = $iv['select_imagevideo'] ?: '';
                        $image = $iv['image'] ?: '';
						$caption = $iv['image_caption'] ?: '';

                        echo '<div class="ivItem">';
                            if($select_imagevideo == 'video'){
                                echo ywVideoFromRepeator($iv);
                            }else{
                                echo '<div class="imgWrap">';
                                    echo '<div class="imgWrapInner">';
                                        if($caption){
                                            echo '<span class="img_caption">'.$caption.'</span>';
                                        }
                                        echo getFocalImage($image, 'image', $pageIndex, $key);
                                    echo '</div>';
                                echo '</div>';
                            }
                        echo '</div>'; #ivItem

                    }
                echo '</div>'; #imgVideoWrapper
            }
            if($wysiwyg_editor){
                echo WysiwygReadMoreLess($wysiwyg_editor ,  'wysiwygEditor');
            }
            echo dynamicBtnFromSection($section);
        echo '</div>'; #postMaxContent
    echo '</div>'; #container
echo '</section>'; #imgVideoContent


?>