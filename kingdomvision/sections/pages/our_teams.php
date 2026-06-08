<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$sub_heading = $section['sub_heading'] ?? '';
$description = $section['description'] ?? [];
$team_members = $section['team_members'] ?? '';

// Theme Specialist
$teamMembersOption = get_field('team_members' , 'option');

echo '<section class="our-teams full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" aria-label="Team Members"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        if($sub_heading){
            echo '<div class="subHeading ' . ($sub_heading ? 'main_child' : '') . '">';
                echo '<span>'.esc_html($sub_heading).'</span>';
            echo '</div>'; #subHeading
        }
        echo HeadingFromSection($section);
        if($description){
            echo WysiwygReadMoreLess($description , 'mainDesc');
        }

        if($team_members && $teamMembersOption){
            $countMember = count($team_members);
            echo '<div class="teamWrapper '.($countMember > 1 ? ' mobSlider' : '').' '.($countMember > 3 ? 'sliderActive' : '').' " aria-label="Team Members">';
                foreach($team_members as $key => $mem){
                    $selected_name = $mem['select_member'];
                    echo getMemberFromSpecialists($selected_name , $teamMembersOption , '' , true , true);
                }
            echo '</div>'; #teamWrapper 
            if( $countMember > 1 ){
                echo '<div class="ourTeamsArrows '.($countMember > 3 ? ' sliderActive' : '').'">';
                    echo slickSliderArrows($uniqueSectionClass);
                echo '</div>'; #ourTeamsArrows
            }           
        }

    echo '</div>'; #container
echo '</section>'; #our-teams

?>
