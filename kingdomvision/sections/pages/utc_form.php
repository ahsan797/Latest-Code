<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$form_shortcode = $section['form_shortcode'] ?? '';
$expert_title = $section['expert_title'] ?? '';
$select_member = $section['select_member'] ?? '';
$expert_description = $section['expert_description'] ?? '';
$opening_times = $section['opening_times'] ?? '';

// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option') ?? [];

$au_number = get_field('au_number' , 'option');
$gb_number = get_field('gb_number' , 'option');
$us_number = get_field('us_number' , 'option');
$currency = cf_curr_currency();

// Cookies Variable
// var_dump($_GET);
// $usePostTitle = $_GET['ajaxPostTitle'] ?? '';
// $useEnquiryPostId = $_GET['ajaxPostId'] ?? '';
// $useAuthorImg = $_GET['ajaxAuthorImg'] ?? ($_COOKIE['authorImg'] ?? '');

// echo $useAuthorImg;


echo '<section class="utc-form full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" role="region" aria-label="Utc Form Section  - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        
        echo '<div class="leftSide">';
            echo HeadingFromSection($section);
            if($form_shortcode){
                echo '<div class="formShortcode">';
                    echo do_shortcode($form_shortcode);
                echo '</div>'; #formShortcode
            }
        echo '</div>'; #leftSide
        echo '<div class="rightSide">';
            if($expert_title){
                echo '<span class="expertTitle">'.$expert_title.'</span>';
            }
            if($expert_description){
                echo WysiwygReadMoreLess($expert_description);
            }
            if($select_member != 'null' && $teamMembersOption ){
                echo '<div class="expertMember" role="group" aria-label="Expert Profile">';
                    $selected_name = $select_member;
                    $member_data = null;
                    foreach ($teamMembersOption as $team_member_row) {
                        if ($team_member_row['name'] === $selected_name) {
                            $member_data = $team_member_row;
                            break;
                        }
                    }
                    // if($member_data){
                        $profile_image = $member_data['profile_image'] ?: '';
                        $name = $member_data['name'] ?: '';
                        $designation = $member_data['designation'] ?: '';
                        echo '<div class="authorImg">';
                            echo '<div class="teamImg">';
                                if(empty($selected_name)){
                                    echo '<img src="'.esc_url(wp_get_attachment_url(mobDefaultImageID())).'" alt="" class="getCookiesImg"/>';
                                        // echo wp_get_attachment_image(mobDefaultImageID(), 'full', false, ['loading' => 'eager' , 'class' => 'getCookiesImg']);
                                    // if($_COOKIE['authorImg']){
                                    // }
                                    //     echo '<img src="" alt="" class="getCookiesImg"/>';
                                    // else{
                                    // } 
                                }else{
                                    echo wp_get_attachment_image($profile_image, 'full', false, ['loading' => 'eager']);
                                }

                                // if($useAuthorImg){
                                //     echo '<img src="'.esc_url($useAuthorImg).'" alt="'.esc_attr($usePostTitle).'"/>';
                                // }
                            echo '</div>'; // teamImg
                        echo '</div>'; #authorImg
                    // }
                        echo '<div class="authorData">';
                            if($au_number || $gb_number || $us_number){
                                echo '<div class="callWrap numbers-switcher">';
                                    echo '<span>Call: </span>';
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
                            if($opening_times){
                                echo '<div class="openingTimes">';
                                    echo '<span>Opening Times: </span>';
                                    echo '<p>'.esc_attr($opening_times).'</p>';
                                echo '</div>'; #openingTimes
                            }
                        echo '</div>'; #authorData
                echo '</div>'; #expertMember
            }
        echo '</div>'; #rightSide

        

    echo '</div>'; #container

echo '</section>'; #utc-form

?>
