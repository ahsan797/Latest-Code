<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$content_direction = $section['content_direction'] ?: '';
$imagecontent_lists = $section['imagecontent_lists'] ?: '';


echo '<section class="image-grid-content-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Image Grid Content Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        if($imagecontent_lists){
            echo '<div class="imgContWrapper '.esc_attr($content_direction).'" >';
                $itemIndex = 0;
                foreach($imagecontent_lists as $key => $lists){
                    $imagecontent = $lists['imagecontent'];
                    $image = $lists['image'];
                    $content = $lists['content'];

                    $itemIndex++;

                    if($imagecontent == 'cont'){
                        echo '<div class="imgContItem item-'.esc_attr($itemIndex).'" >';
                            $heading_tags = $content['heading_tags'];
                            $heading_text = $content['heading_text'];
                            $heading_color = $content['heading_color'];
                            $sub_title = $content['sub_title'];
                            $wysiwyg_editor = $content['wysiwyg_editor'];
                            $button = $content['button'];

                            if($heading_text){
                                if($heading_tags == 'h1'){
                                    echo '<h1 class="heading" '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'>'.esc_html($heading_text).'</h1>';
                                }elseif($heading_tags == 'h3'){
                                    echo '<h3 class="heading" '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'>'.esc_html($heading_text).'</h3>';
                                }elseif($heading_tags == 'h4'){
                                    echo '<h4 class="heading" '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'>'.esc_html($heading_text).'</h4>';
                                }elseif($heading_tags == 'h5'){
                                    echo '<h5 class="heading" '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'>'.esc_html($heading_text).'</h5>';
                                }elseif($heading_tags == 'h6'){
                                    echo '<h6 class="heading" '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'>'.esc_html($heading_text).'</h6>';
                                }else{
                                    echo '<h2 class="heading" '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'>'.esc_html($heading_text).'</h2>';
                                }
                            }

                            if($sub_title){
                                echo '<p class="subTitle">'.esc_html($sub_title).'</p>';
                            }

                            if($wysiwyg_editor){
                                echo WysiwygReadMoreLess($wysiwyg_editor ,  'contWrapper');
                            }

                            if($button){
                                $link_url = $button['url'];
                                $link_title = $button['title'];
                                $link_target = $button['target'] ? $button['target'] : '_self';
                                
                                echo  '<a href="'.esc_url($link_url).'" target="'.esc_attr( $link_target ).'" class="btn" role="button" aria-label="' . esc_attr($link_title) . '">'.esc_html($link_title).'</a>';
                                
                            }

                        echo '</div>'; #imgContItem
                    }else{
                        echo '<div class="imgContItem item-'.esc_attr($itemIndex).'">';
                            echo getFocalImage($image, 'image', $pageIndex, $key);
                        echo '</div>'; #imgContItem
                    }
                }
            echo '</div>'; #imgContWrapper
        }
    echo '</div>'; #container
echo '</section>'; #itineraries-module

?>
