<?php
// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';


$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id'] ?: '');

$sub_title = $section['sub_title'] ?: '';
$content = $section['content'] ?: '';
$button = $section['button'] ?? '';
$select_member = $section['select_member'] ?: '';


// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option') ?: [];

echo '<section class="advice-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Advice Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="expertAdvice">';
        echo '<div class="container">';
            echo HeadingFromSection($section);
            echo '<div class="expertContent">';
                if($sub_title){
                    echo '<span class="subTitle">'.$sub_title.'</span>';
                }
                if($content ){
                    echo WysiwygReadMoreLess($content);
                }
                // if(!empty($button)){
                // }
                    echo dynamicBtnFromSection($section);
            echo '</div>'; //expertContent

            echo '<div class="expertMember" role="group" aria-label="Expert Profile">';
                if($select_member && $teamMembersOption ){
                    $selected_name = $select_member;
                    $member_data = null;
                    foreach ($teamMembersOption as $team_member_row) {
                        if ($team_member_row['name'] === $selected_name) {
                            $member_data = $team_member_row;
                            break;
                        }
                    }
                    if($member_data){
                        $profile_image = $member_data['profile_image'] ?: '';
                        $name = $member_data['name'] ?: '';
                        $designation = $member_data['designation'] ?: '';
                        echo '<div class="authorImg">';
                        echo '<div class="teamImg">';
                            echo wp_get_attachment_image($profile_image, 'full', false, ['loading' => 'eager']);
                        echo '</div>'; // teamImg
                        echo '</div>'; #authorImg
                        echo '<div class="authorData">';
                        if ($name) {
                            echo '<span aria-label="Expert Name">' . esc_html($name) . '</span>';
                        }
                        if($designation){
                            echo '<p aria-label="Expert Designation">'.esc_html($designation).'</p>';
                        }
                        echo '</div>'; #authorData
                    }
                }
            echo '</div>'; #expertMember
        echo '</div>';
    echo '</div>'; //expertAdvice
echo '</section>';

?>