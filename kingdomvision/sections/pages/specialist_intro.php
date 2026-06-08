<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$select_specialist = $section['select_specialist'] ?? '';
$StaticDesignation = $section['designation'] ?? '';
$specialist_image = $section['specialist_image'] ?? '';
$specialist_description = $section['specialist_description'] ?? [];
$button = $section['button'] ?? '';

$select_member = $section['select_member'] ?? '';
// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option');

echo '<section class="specialist-intro full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'"  aria-label="Team Members"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';

        if($select_specialist == 'dynamic'){
            if($select_member){                  
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
                        $profile_image = $member_data['profile_image'];
                        $name = $member_data['name'];
                        $designation = $member_data['designation'];
                        $profile_description = $member_data['profile_description'];

                        echo '<div class="introWrapper">';
                            if($name){
                                echo '<h1>'.esc_html($name).'</h1>';
                            }
                            if($designation){
                                echo '<div class="designation">';
                                    echo '<span>'.esc_html($designation).'</span>';
                                echo '</div>'; #subHeading
                            }
                        echo '</div>'; #introWrapper
                        echo '<div class="left-right-wrapper">';
                            echo '<div class="leftSide">';
                                if($profile_image){
                                    echo '<div class="specImg">';
                                        echo wp_get_attachment_image( $profile_image,'full', false,
                                            [
                                                'loading' => 'eager',
                                            ]
                                        );
                                    echo '</div>'; #specImg
                                }
                            echo '</div>'; #leftSide
                            echo '<div class="rightSide">';
                               if($profile_description){
                                    echo '<div class="specDesc">';
                                        echo $profile_description;
                                    echo '</div>'; #specDesc
                                }
                                if($button){
                                    echo dynamicBtnFromSection($section);
                                }
                            echo '</div>'; #rightSide
                        echo '</div>'; #left-right-wrapper
                    }
                }
            }

        }else{

            echo '<div class="introWrapper">';
                echo HeadingFromSection($section);
                if($StaticDesignation){
                    echo '<div class="designation">';
                        echo '<span>'.esc_html($StaticDesignation).'</span>';
                    echo '</div>'; #subHeading
                }
            echo '</div>'; #introWrapper
            echo '<div class="left-right-wrapper">';
                echo '<div class="leftSide">';
                    
                    if($specialist_image){
                        echo '<div class="specImg">';
                            echo getFocalImage($specialist_image, 'specialist_image', $pageIndex);
                        echo '</div>'; #specImg
                    }
                echo '</div>'; #leftSide
                echo '<div class="rightSide">';
                    if($specialist_description){
                        echo WysiwygReadMoreLess($specialist_description, 'specDesc');
                    }
                    if($button){
                        echo dynamicBtnFromSection($section);
                    }
                echo '</div>'; #rightSide
            echo '</div>'; #left-right-wrapper
        }

    echo '</div>'; #container
echo '</section>'; #specialist-intro

?>
