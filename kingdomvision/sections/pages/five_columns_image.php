<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$sub_heading = $section['sub_heading'] ?: '';
$design_type = $section['design_type'] ?: '';
$process_steps = $section['process_steps'] ?: '';
$team_members = $section['team_members'] ?: '';
$cta_button = $section['cta_button'] ?: '';

$b_width = $section['border_width'] ?: '';
$b_color = $section['border_color'] ?: '';
$b_style = $section['border_style'] ?: '';

// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option');


echo '<section class="five-columns-image full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="5 Columns Image - '.esc_attr($design_type).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);

        if($sub_heading){
            echo '<div class="subHeading">';
            	echo WysiwygReadMoreLess($sub_heading);
            echo '</div>'; #subHeading
        }

        if($design_type == 'teamData'){
            if($team_members && $teamMembersOption){
                $memberCounts = count($team_members);
                echo '<div class="teamWrapper '.($memberCounts > 4 ? 'activeSlider' : '').'" aria-label="Team Members">';
                    foreach($team_members as $key => $mem){
                        $selected_name = $mem['select_member'];
                        $member_data = null;

                        // Find matching repeater row by name
                        foreach ($teamMembersOption as $team_member_row) {
                            if ($team_member_row['name'] === $selected_name) {
                                $member_data = $team_member_row;
                                break;
                            }
                        }
                        if ($member_data) {
                            $profile_image = $member_data['profile_image'];
                            $name = $member_data['name'];
                            $designation = $member_data['designation'];
                            $profile_description = $member_data['profile_description'];
                            $page_link = $member_data['page_link'];

                            echo '<div class="teamItem" aria-labelledby="member-' . esc_attr($key) . '-name">';
                                if($page_link){
                                    echo '<a href="'.esc_url($page_link).'" class="fullAnchor" aria-label="'.$name.'"></a>';
                                }
                                if($profile_image){
                                    echo '<div class="teamImg">';
                                        echo wp_get_attachment_image( $profile_image,'full', false,
                                            [
                                                'loading' => 'eager',
                                                'style'   => 'border-style:'.$b_style.'; border-width:'.$b_width.'px; border-color:'.$b_color.';'
                                            ]
                                        );
                                    echo '</div>'; #teamImg
                                }
                                if($profile_description){
                                    echo '<div class="teamDesc">';
                                        echo mb_strimwidth($profile_description, 0, 90, '...');
                                    echo '</div>'; #teamDesc
                                }
                                if($name || $designation){
                                    echo '<div class="teamTitle">';
                                        if($name){
                                            echo '<span>'.esc_html($name).'</span>';
                                        }
                                        if($designation){
                                            echo '<p>'.esc_html($designation).'</p>';
                                        }
                                    echo '</div>'; #teamTitle
                                }
                            echo '</div>'; #teamItem
                        }

                    }
                echo '</div>'; #teamWrapper
                if($memberCounts > 1){
                    echo '<div class="fiveColArrows '.($memberCounts > 4 ? 'activeSlider' : '').'">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>';
                }
            }
        }else{
            if($process_steps){
                $counts = count($process_steps);
                echo '<div class="processSteps '.($counts > 5 ? 'activeSlider' : '').'" aria-label="Process Steps">';
                    $number = 1;
                    foreach($process_steps as $key => $step){
                        $step_title = $step['step_title'];
                        $step_description = $step['step_description'];

                        echo '<div class="processItem" >';
                            if($step_title){
                                echo '<div class="processCircle" style="border-style:'.$b_style.'; border-width:'.$b_width.'px; border-color:'.$b_color.';">';
                                    echo '<div class="stepNumber" aria-label="Step ' . esc_attr($number) . '">';
                                        echo '<p>'.$number.'</p>';
                                    echo '</div>';
                                    if($step_title){
                                        echo '<div class="stepTitle">';
                                            echo '<span>'.esc_html($step_title).'</span>';
                                        echo '</div>'; #stepTitle
                                    }
                                echo '</div>'; #processCircle
                            }
                            if($step_description){
                                echo '<div class="processDesc">';
                                    echo wpautop($step_description);
                                echo '</div>'; #processDesc
                            }
                        echo '</div>'; #processItem
                        $number++;
                    }
                echo '</div>'; #processSteps
                if($counts > 1){
                    echo '<div class="fiveColArrows '.($counts > 5 ? 'activeSlider' : '').'">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>';
                }
            }
        }
        
        if($cta_button){

            $link_url = $cta_button['url'];
            $link_title = $cta_button['title'];
            $link_target = $cta_button['target'] ? $cta_button['target'] : '_self';

            echo '<div class="ctaButton">';
                echo '<a href="'.esc_url($link_url).'" target="'.esc_attr( $link_target ).'" class="btn" role="button" aria-label="' . esc_attr($link_title) . '">'.esc_html($link_title).'</a>';
            echo '</div>'; #ctaButton
        }

    echo '</div>'; #container
echo '</section>'; #five-columns-image

?>