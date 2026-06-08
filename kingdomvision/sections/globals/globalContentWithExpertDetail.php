<?php
// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';


$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id'] ?: '');

$background_color = $section['background_color'] ?: '#fff';
$heading_color = $section['heading_color'] ?: '';


$content = $section['content'] ?: '';
$expert_title = $section['expert_title'] ?: '';
$expert_content = $section['expert_content'] ?: '';
$button = $section['button'] ?: '';


$select_member = $section['select_member'] ?: '';
// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option') ?: [];


echo '<section class="content-with-expert-detail full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Content With Expert Detail - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    echo '<div class="container">';
        echo '<div class="flexWrap">';
            echo '<div class="leftSide">';    
                echo HeadingFromSection($section);
                if($content ){
                    echo WysiwygReadMoreLess($content);
                }
            echo '</div>'; //leftSide
            echo '<div class="rightSide" '.( $heading_color ? 'style=" border-color: '.$heading_color.'; "' : '').'>';
                if($expert_title){
                    echo '<h3 
                        '.(
                            $heading_color || $background_color ? 
                            'style=" '.( $background_color ? 'background: '.$background_color.';' : '' ).' '.($heading_color ? 'color: '.$heading_color.';' : '' ).' "' 
                            : '').' >'.$expert_title.'</h3>';
                }
                if($expert_content ){
                    echo WysiwygReadMoreLess($expert_content, 'expertContent');
                }
                echo dynamicBtnFromSection($section);
                if($select_member){
                    echo '<div class="expertMember" role="group" aria-label="Expert Profile" '.( $background_color ? 'style=" background: '.$background_color.'; "' : '').' >';
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
                                    echo '<span aria-label="Expert Name" '.( $heading_color ? 'style=" color: '.$heading_color.'; "' : '').'>' . esc_html($name) . '</span>';
                                }
                                if($designation){
                                    echo '<p aria-label="Expert Designation">'.esc_html($designation).'</p>';
                                }
                                echo '</div>'; #authorData
                            }
                        }
                    echo '</div>'; #expertMember
                }
            echo '</div>'; //rightSide
        echo '</div>'; //flexWrap
    echo '</div>'; //container
echo '</section>'; #content-with-expert-detail

?>