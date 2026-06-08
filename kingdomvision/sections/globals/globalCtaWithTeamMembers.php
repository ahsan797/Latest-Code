<?php

// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';


$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$description = $section['description'] ?: '';
$call_label = $section['call_label'] ?: '';
$numbers = $section['numbers'] ?: false;
$button = $section['button'] ?: '';
$au_number = get_field('au_number' , 'option') ?: '';
$gb_number = get_field('gb_number' , 'option') ?: '';
$us_number = get_field('us_number' , 'option') ?: '';

$select_team_members = $section['select_team_members'] ?: '';
// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option');

$currency = cf_curr_currency();


// role="CTA With Team Member"
echo '<section class="cta-with-team full-section global  '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
     aria-label="CTA With Team Member Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo '<div class="left-area">';
            echo HeadingFromSection($section);
            if($description){
                echo WysiwygReadMoreLess($description ,  'desc-Wrapper');
            }
            if($call_label || $numbers || $button){
                echo '<div class="call-btn">';
                    if($call_label || $numbers){
                        echo '<div class="call">'; 
                            if($call_label){
                                echo '<h3>'.$call_label.'</h3>';
                            }
                            if($numbers){
                                if($au_number || $gb_number || $us_number){
                                    echo '<div class="callWrap numbers-switcher">';
                                        if ($currency === 'USD' && !empty($us_number)) {
                                            $us_number = preg_replace('/\s+/', '', $us_number);
                                            printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));
                                        } elseif ($currency === 'AUD' && !empty($au_number)) {
                                            $au_number = preg_replace('/\s+/', '', $au_number);
                                            printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($au_number), esc_html($au_number));
                                        } elseif ($currency === 'GBP' && !empty($gb_number)) {
                                            $gb_number = preg_replace('/\s+/', '', $gb_number);
                                            printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($gb_number), esc_html($gb_number));
                                        } else {
                                            $us_number = preg_replace('/\s+/', '', $us_number);
                                            printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));            
                                        }
                                    echo '</div>'; #callWrap
                                }
                            }
                        echo '</div>'; #call
                    }
                    if($button){
                        echo dynamicBtnFromSection($section);
                    }
                echo '</div>'; #call-btn
            }
        echo '</div>'; #left-area
        echo '<div class="right-area">';
            if($select_team_members && $teamMembersOption){
                echo '<div class="teamWrapper" aria-label="Team Members">';
                    foreach($select_team_members as $key => $mem){
                        $selected_name = $mem['select_member'];
                        $specificLink = $mem['link'];
                        $member_data = null;

                        // pre($specificLink['url'] );

                        // Find matching repeater row by name
                        foreach ($teamMembersOption as $team_member_row) {
                            if ($team_member_row['name'] === $selected_name) {
                                $member_data = $team_member_row;
                                break;
                            }
                        }
                        if ($member_data) {
                            $page_link = $member_data['page_link'];
                            $profile_image = $member_data['profile_image'];
                            $name = $member_data['name'];
                            $designation = $member_data['designation'];
                            
                            echo '<div class="teamItem" aria-labelledby="member-' . esc_attr($key) . '-name">';
                                if($specificLink){
                                    $specificLinkUrl = $specificLink['url'];
                                    echo '<a href="'.esc_url($specificLinkUrl).'" class="memberLink" aria-label="'.$name.'"></a>';
                                }else{
                                    echo '<a href="'.esc_url($page_link).'" class="memberLink" aria-label="'.$name.'"></a>';
                                }
                                if($profile_image){
                                    echo '<div class="teamImg">';
                                        echo wp_get_attachment_image($profile_image, 'full', false, ['loading' => 'eager']);
                                    echo '</div>'; #teamImg
                                }
                                if($name || $designation){
                                    echo '<div class="teamTitle">';
                                        if($name){
                                            echo '<span>'.esc_html($name).'</span>';
                                        }
                                        if($designation){
                                            echo '<p>'.esc_html($designation).'</p>';
                                        }
                                        if (!empty($specificLink['url'])) {
                                            $specificLinkUrl = $specificLink['url'];
                                            $specificLinkTitle = $specificLink['title'];
                                            $specificLinkTarget = $specificLink['target'] ?? '_self';
                                            echo '<a href="' . esc_url($specificLinkUrl) . '" target="'.esc_attr($specificLinkTarget).'" class="btn" role="button" aria-label="View Profile">'.esc_attr($specificLinkTitle).'</a>';
                                        }else{
                                            if($page_link){
                                                echo '<a href="' . esc_url($page_link) . '" target="_self" class="btn" role="button" aria-label="View Profile">View Profile</a>';
                                            }
                                        }
                                    echo '</div>'; #teamTitle
                                }
                            echo '</div>'; #teamItem
                        }

                    }
                echo '</div>'; #teamWrapper
                echo slickSliderArrows($uniqueSectionClass);
            }
        echo '</div>'; #right-area
    echo '</div>'; #container
echo '</section>'; #cta-with-team

?>