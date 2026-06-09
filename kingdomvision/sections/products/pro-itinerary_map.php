<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$img_iframe  = $section['image_or_iframe'];
$left_image  = $section['left_image'];
$map_loca    = $section['map_location'];

$options     = $section['options'];


echo '<section class="two_image_columns glance_updated full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" role="Itinerary Map" aria-label="Itinerary Map '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').'  >';

    echo '<div class="container">';
        echo HeadingFromSection($section);

        echo '<div class="map_cover">';
            echo '<div class="left-image-item">';
                if (!empty($options)) {
                    echo '<ul class="map__points">';
                        foreach ($options as $p) {
                            $day = $p['day'];
                            $points = $p['points'];

                            echo '<li>';
                                echo '<span>' . $day . '</span>';
                                echo '<ul>';
                                if (!empty($points)) {
                                    foreach ($points as $child) {
                                        $parent_point = $child['parent_point'];
                                        $child_points = $child['child_points'];
                                            echo '<li>';
                                                echo '<h3>' . $parent_point . '</h3>';
                                            echo '<ul>';
                                                if (!empty($child_points)) {
                                                    foreach ($child_points as $c) {
                                                        $list = $c['child_list'];
                                                        echo '<li>' . $list . '</li>';
                                                    }
                                                }
                                            echo '</ul>';
                                        echo '</li>';
                                    }
                                }
                                echo '</ul>';
                            echo '</li>';
                        }
                    echo '</ul>'; #map__points
                }
            echo '</div>'; #left-image-item
            echo '<div class="right-image-item">';
                if ($img_iframe == 'img') {
                    echo wp_get_attachment_image($left_image, 'full');
                } else {
                    $map_arr = [];
                    foreach ($map_loca as $l) {
                        $map_arr[] = [$l['title'], (float) $l['latitude'], (float) $l['longitude']];
                    }

                    echo '<div class="mapWrapper">'; 
                        echo '<div id="map_container"></div>';
                        echo '<div class="zoomWrapper">';
                            echo '<div class="zoom ZoomIn">';
                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M13 6.46875C13 6.75 12.75 6.96875 12.5 6.96875H7V12.4688C7 12.75 6.75 13 6.5 13C6.21875 13 6 12.75 6 12.4688V6.96875H0.5C0.21875 6.96875 0 6.75 0 6.5C0 6.21875 0.21875 5.96875 0.5 5.96875H6V0.46875C6 0.21875 6.21875 0 6.5 0C6.75 0 7 0.21875 7 0.46875V5.96875H12.5C12.75 5.96875 13 6.21875 13 6.46875Z" fill="black"/>
                                </svg>';
                            echo '</div>';
                            echo '<div class="zoom ZoomOut">';
                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="1" viewBox="0 0 13 1" fill="none">
                                    <path d="M13 0.5C13 0.78125 12.75 1 12.5 1H0.5C0.21875 1 0 0.78125 0 0.53125C0 0.25 0.21875 0 0.5 0H12.5C12.75 0 13 0.25 13 0.5Z" fill="black"/>
                                </svg>';
                            echo '</div>';
                        echo '</div>'; #zoomWrapper
                    echo '</div>'; #mapWrapper
                }
            echo '</div>'; #right-image-item

        echo '</div>'; #map_cover

    echo '</div>'; #container


echo '</section>'; #glance_updated

?>

<script>
var itineraryMap_marker = <?php echo isset($map_arr) ? wp_json_encode($map_arr) : '[]'; ?>;

function itineraryMapInitialize() {
    console.log(itineraryMap_marker);
    // ✅ Fail-safe check
    if (typeof itineraryMap_marker === "undefined" || !Array.isArray(itineraryMap_marker) || itineraryMap_marker.length === 0) {
        console.error("itineraryMap_marker is not defined or empty!");
        return;
    }

    var center = {
        lat: itineraryMap_marker[0][1] || 0,
        lng: itineraryMap_marker[itineraryMap_marker.length - 1][2] || 0
    };

    var gmapstyle = [
        {elementType: "geometry", stylers: [{color: "#f5f5f5"}]},
        {elementType: "labels.icon", stylers: [{visibility: "off"}]},
        {elementType: "labels.text.fill", stylers: [{color: "#616161"}]},
        {elementType: "labels.text.stroke", stylers: [{color: "#f5f5f5"}]},
        {featureType: "administrative.country", elementType: "geometry.stroke", stylers: [{color: "#3e3e3e"}]},
        {featureType: "administrative.country", elementType: "labels.icon", stylers: [{visibility: "on"}]},
        {featureType: "administrative.land_parcel", elementType: "labels.text.fill", stylers: [{color: "#bdbdbd"}]},
        {featureType: "administrative.locality", stylers: [{visibility: "on"}]},
        {featureType: "poi", stylers: [{visibility: "off"}]},
        {featureType: "poi", elementType: "geometry", stylers: [{color: "#eeeeee"}]},
        {featureType: "poi", elementType: "labels.icon", stylers: [{visibility: "on"}]},
        {featureType: "poi", elementType: "labels.text.fill", stylers: [{color: "#757575"}]},
        {featureType: "road", elementType: "geometry", stylers: [{color: "#ffffff"}]},
        {featureType: "road.arterial", elementType: "labels.text.fill", stylers: [{color: "#757575"}]},
        {featureType: "road.highway", elementType: "geometry", stylers: [{color: "#dadada"}]},
        {featureType: "road.highway", elementType: "labels.text.fill", stylers: [{color: "#616161"}]},
        {featureType: "road.local", elementType: "labels.text.fill", stylers: [{color: "#9e9e9e"}]},
        {featureType: "water", elementType: "geometry", stylers: [{color: "#c9c9c9"}]},
        {featureType: "water", elementType: "geometry.fill", stylers: [{color: "#a7c7d5"}, {lightness: 35}]},
        {featureType: "water", elementType: "labels.text.fill", stylers: [{color: "#9e9e9e"}]}
    ];

    var map = new google.maps.Map(document.getElementById('map_container'), {
        disableDefaultUI: true,
        center: center,
        styles: gmapstyle,
        gestureHandling: "auto",
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapId: "42daae7529408ece"
    });

    // Safe Zoom Buttons
    const zoomInBtn = document.querySelector(".ZoomIn");
    const zoomOutBtn = document.querySelector(".ZoomOut");
    zoomInBtn.addEventListener("click", function () {
        console.log('Zoom In clicked');
        map.setZoom(map.getZoom() + 1);
    });
    zoomOutBtn.addEventListener("click", function () {
        console.log('Zoom Out clicked');
        map.setZoom(map.getZoom() - 1);
    });

    function setMarkers(map) {
        if (itineraryMap_marker.length > 1) {
            itineraryMap_marker.push(itineraryMap_marker[0]);
        }

        var flightPlanCoordinates = [];
        var bounds = new google.maps.LatLngBounds();

        for (var i = 0; i < itineraryMap_marker.length - 1; i++) {
            var marker = itineraryMap_marker[i];
            var myLatLng = new google.maps.LatLng(marker[1], marker[2]);

            let iconImg = document.createElement("img");
            iconImg.src = '<?php echo esc_url(THEME_URL."/images/mapointer/map_pointer"); ?>' + (i + 1) + '.png';

            let eachMarker = new google.maps.marker.AdvancedMarkerElement({
                position: myLatLng,
                map: map,
                content: iconImg,
                title: marker[0],
                gmpClickable: true,
                draggable: false
            });

            bounds.extend(myLatLng);
            flightPlanCoordinates.push({lat: marker[1], lng: marker[2]});
        }

        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: "#BB5B3E",
            strokeOpacity: 0.9,
            strokeWeight: 2,
            zIndex: 2
        });

        flightPath.setMap(map);
        map.setCenter(bounds.getCenter());
        map.fitBounds(bounds);
    }

    setMarkers(map);
}

// ✅ WP Rocket safe init
window.addEventListener("load", function() {
    if (typeof google !== "undefined" && google.maps) {
        itineraryMapInitialize();
    } else {
        console.error("Google Maps not loaded yet.");
    }
});
</script>

