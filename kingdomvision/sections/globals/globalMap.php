<?php
// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';


$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';
$map_location = $section['map_location'] ?? '';

$unique_map_id = 'map_' . uniqid();

echo '<section class="map full-section global '.esc_attr($uniqueSectionClass).'" role="Map" aria-label="Map"
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    if($map_location){

        $map_arr = [];
        foreach ($map_location as $l) {
            $map_arr[] = [$l['title'], (float) $l['latitude'], (float) $l['longitude']];
        }

        echo '<div class="mapWrapper">'; 
            echo '<div id="'.esc_attr($unique_map_id).'" style="height: 500px;"></div>';
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
    
echo '</section>'; #map

?>
<script>
(function () {
    var map_marker = <?php echo isset($map_arr) ? json_encode($map_arr) : '[]'; ?>;
    var map_id = "<?php echo $unique_map_id; ?>";

    function initialize() {
        console.log(map_marker);

        if (typeof map_marker === "undefined" || !Array.isArray(map_marker) || map_marker.length === 0) {
            console.error("map_marker is not defined or empty!");
            return;
        }

        var center = {
            lat: map_marker[0][1] || 0,
            lng: map_marker[map_marker.length - 1][2] || 0
        };

        var map = new google.maps.Map(document.getElementById(map_id), {
            disableDefaultUI: true,
            center: center,
            gestureHandling: "auto",
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapId: "42daae7529408ece"
        });

        // Safe Zoom Buttons
        const container = document.getElementById(map_id).closest('.mapWrapper');
        const zoomInBtn = container.querySelector(".ZoomIn");
        const zoomOutBtn = container.querySelector(".ZoomOut");
        zoomInBtn.addEventListener("click", function () {
            console.log('Zoom In clicked');
            map.setZoom(map.getZoom() + 1);
        });
        zoomOutBtn.addEventListener("click", function () {
            console.log('Zoom Out clicked');
            map.setZoom(map.getZoom() - 1);
        });

        function setMarkers(map) {
            // if (map_marker.length > 1) {
            //     map_marker.push(map_marker[0]);
            // }

            var flightPlanCoordinates = [];
            var bounds = new google.maps.LatLngBounds();

            for (var i = 0; i < map_marker.length; i++) {
                var marker = map_marker[i];
                var myLatLng = new google.maps.LatLng(marker[1], marker[2]);

                let iconImg = document.createElement("img");
                iconImg.src = '<?php echo THEME_URL . "/images/mapointer/mapPointerIcon.png"; ?>';

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

            if (map_marker.length === 1) {
                map.setCenter(bounds.getCenter());
                map.setZoom(16);
            } else {
                map.fitBounds(bounds);
            }
        }

        setMarkers(map);
    }

    window.addEventListener("load", function() {
        if (typeof google !== "undefined" && google.maps) {
            initialize();
        } else {
            console.error("Google Maps not loaded yet.");
        }
    });
})();
</script>