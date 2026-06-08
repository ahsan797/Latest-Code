<?php

$page_id = get_queried_object_id();

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$button_text = $section['button_text'] ?? '';
$button_url = $section['button_url'] ?? '';
$calenders = $section['calenders'] ?? [];

$product = get_field('product' , $page_id) ?? [];
$duration = $product['duration'] ?? '';

echo '<section class="utc-departure-dates-updated full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
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
                                    foreach($months_event as $mkey => $monthEvent){
                                        $month_name = $monthEvent['month_name'];
                                        $dates_events = $monthEvent['dates_events'];
                                        
                                        echo '<div class="mdWrapper repeaterArrows">';
                                            echo '<div class="monthsNameWrap '.($dates_events ? 'haveData' : '').'">';
                                                echo '<span class="iconWrap">'.esc_html($month_name).' '.esc_html($years).'</span>';
                                                echo '<span class="lineWrap"></span>';
                                                echo '<span class="arrowWrap"></span>';
                                            echo '</div>'; #monthsNameWrap

                                            echo '<div class="departureAcc">';
                                                if($dates_events){
                                                    echo '<div class="eventWrap  '.( count($dates_events) > 3 ? 'activeSlider' : '' ).'">';
                                                        foreach ($dates_events as $key => $dateEvent) {
                                                            $date = $dateEvent['date'];
                                                            $event_name = $dateEvent['event_name'] ?: $duration;

                                                            echo '<div class="eventItem">';
                                                                echo '<div class="dataWrap">';
                                                                    if($date){
                                                                        $dateFormat = DateTime::createFromFormat('Ymd', $date);
                                                                        $timestamp = strtotime($date);

                                                                        // Extract separate components
                                                                        $day   = $dateFormat->format('d'); // e.g., 05
                                                                        $month = $dateFormat->format('M'); // e.g., May (use 'M' for 3-letter abbreviation)
                                                                        $year  = $dateFormat->format('Y'); // e.g., 2026

                                                                        // echo $day, $month, $year;
                                                                        echo '<div class="eventDate">';
                                                                            echo '<span class="sameSize">'.date('D', $timestamp).'</span>';
                                                                            echo '<span>'.$day.'</span>';
                                                                            echo '<span class="sameSize">'.$month.' '.$year.'</span>';
                                                                        echo '</div>'; #eventDate
                                                                    }
                                                                    echo '<div class="eventName">';
                                                                        echo '<p>'.esc_html($event_name).'</p>';
                                                                    echo '</div>'; #eventName
                                                                echo '</div>'; #dataWrap
                                                                echo '<div class="btnWrap">';
                                                                    echo '<a href="'.($button_url ? $button_url : 'javascript:;' ).'" class="csbtn enquiryNow" 
                                                                            data-departureDate="'.$day.' '.$month.' '.$year.'"
                                                                            data-departureEvent="'.esc_attr($event_name).'" 
                                                                            data-postID="'.esc_attr(get_the_ID()).'" 
                                                                            data-postTitle="'.esc_attr(get_the_title()).'"
                                                                            role="button" 
                                                                            aria-label="'.($button_text ? $button_text : 'Request A Quote' ).'">'.($button_text ? $button_text : 'Request A Quote' ).' <span><img src="'.THEME_URL.'/images/anchor-arrow.svg" alt="arrowIcon"/></span></a>';
                                                                echo '</div>'; #btnWrap
                                                            echo '</div>'; #eventItem

                                                        }
                                                    echo '</div>'; #eventWrap
                                                }
                                                if( is_array($dates_events) && count($dates_events) > 1 ){
                                                    echo '<div class="deArrow '.(count($dates_events) > 3 ? 'activeSlider' : '').'">';
                                                        echo slickSliderArrows('repeaterArrows');
                                                    echo '</div>';
                                                }
                                            echo '</div>'; #departureAcc
                                        echo '</div>'; #mdWrapper
                                    }
                            }
                        echo '</div>'; #yearsTabItem

                    }
                echo '</div>'; #yearsTabContent

            echo '</div>'; #departureWrapper
        }
    echo '</div>'; #container

echo '</section>'; #utc-departure-dates

?>

