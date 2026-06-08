<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$calenders = $section['calenders'] ?? [];

echo '<section class="utc-departure-dates full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Departure Dates - '.esc_attr($uniqueSectionClass).'" aria-label="Departure Dates - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($calenders){
            echo '<div class="departureWrapper">';
                echo '<div class="yearsTabs">';
                    foreach($calenders as $key => $year){
                        $years = $year['years'];
                        $months_event = $year['months_event'];

                        if($months_event){
                            echo '<div class="yearsItem">';
                                echo '<a href="#years'.$key.'">'.esc_html($years).'</a>';
                            echo '</div>'; #yearsItem
                        }

                    }
                echo '</div>'; #yearsTabs
                echo '<div class="yearsTabContent">';
                    foreach($calenders as $key => $cal){
                        $years = $cal['years'];
                        $months_event = $cal['months_event'];

                        echo '<div class="yearsTabItem" id="years'.$key.'">';
                            
                            if($months_event){
                                echo '<div class="monthsTabs">';
                                    echo '<div class="FixedItem ">';
                                       echo '<span class="fixedyearName">'.esc_html($years).'</span>';
                                    echo '</div>'; #monthsItem
                                    foreach($months_event as $mkey => $monthEvent){
                                        $month_name = $monthEvent['month_name'];
                                        $dates_events = $monthEvent['dates_events'];
                                        
                                        echo '<div class="monthsItem '.($dates_events ? 'haveData' : '').'">';
                                            echo '<a href="javacript:;" data-months="months'.$mkey.'">'.esc_html($month_name).'</a>';
                                        echo '</div>'; #monthsItem

                                    }
                                echo '</div>'; #monthsTabs
                                echo '<div class="monthsTabContent">';                                
                                    foreach($months_event as $mkey => $monthEvent){
                                        $dates_events = $monthEvent['dates_events'];

                                        echo '<div class="monthsTabItem repeaterArrows" data-month-item="months'.$mkey.'">';
                                            if($dates_events){
                                                $DECounts = count($dates_events);
                                                echo '<div class="dateEventWrapper '.($DECounts > 4 ? 'activeSlider' : '').'">';
                                                    foreach($dates_events as  $dateEvents){
                                                        $date = $dateEvents['date'];
                                                        $events_name = $dateEvents['events_name'];
                                                        $button = $dateEvents['button'];

                                                        echo '<div class="dateEventItem">';
                                                            if($date){
                                                                echo '<span class="date">'.esc_html($date).'</span>';
                                                            }
                                                            if($events_name){
                                                                echo '<ul>';
                                                                    foreach($events_name as $eName){
                                                                        $event_name = $eName['event_name'];

                                                                        echo '<li>'.esc_html($event_name).'</li>';
                                                                    }
                                                                echo '</ul>';
                                                            }
                                                            if (!empty($button['url']) && !empty($button['title'])) {
                                                                $link_url = $button['url'];
                                                                $link_title = $button['title'];
                                                                $link_target = $button['target'] ? $button['target'] : '_self';
                                                                echo '<a href="'.esc_url($link_url).'" target="'.esc_attr( $link_target ).'" class="btn enquiryNow" data-departureDate="'.esc_html($date).' " data-postTitle="'.esc_attr(get_the_title()).'" data-postID="'.esc_attr(get_the_ID()).'" role="button" aria-label="' . esc_attr($link_title) . '">'.esc_html($link_title).'</a>';
                                                            }
                                                        echo '</div>'; #dateEventItem

                                                    }
                                                echo '</div>'; #dateEventWrapper
                                                if( $DECounts > 1 ){
                                                    echo '<div class="deArrow '.($DECounts > 4 ? 'activeSlider' : '').'">';
                                                        echo slickSliderArrows('repeaterArrows');
                                                    echo '</div>';
                                                }
                                            }else{
                                                echo '<p class="noFound">No Date Found!</p>';
                                            }
                                        echo '</div>'; #monthsTabItem

                                    }                                
                                echo '</div>'; #monthsTabContent
                            }
                        echo '</div>'; #yearsTabItem

                    }
                echo '</div>'; #yearsTabContent

            echo '</div>'; #departureWrapper
        }
    echo '</div>'; #container

echo '</section>'; #utc-departure-dates

?>

