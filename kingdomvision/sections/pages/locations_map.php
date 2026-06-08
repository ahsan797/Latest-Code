<?php
// Unique Section Class (for styling only)
$uniqueSectionClass = 'section-' . $currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section);
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
$heading = HeadingFromSection($section);

// ACF Repeater
$locations = $section['location'] ?? [];

echo '<section class="locations-map full-section ' . esc_attr($section_text_color) . ' ' . esc_attr($uniqueSectionClass) . '" 
    role="Locations Map"
    aria-label="Locations Map"
    ' . ($bg ?: '') . '
    ' . ($section_id ? 'id="' . esc_attr($section_id) . '"' : '') . ' >';

echo '<div class="container">';
echo HeadingFromSection($section);

if (!empty($locations)) {
    echo '<div class="map-list-wrapper">';

    // GOOGLE MAP
    echo '<div id="google-map" class="google-map"></div>';

    // RIGHT SIDE LIST
    echo '<div class="map-location-list">';
    $newLocations = []; // ← collect clean data for JS

    foreach ($locations as $i => $loc) {

        // Handle ACF image field (ID or array)
        $img = $loc['image'] ?? '';

        if (is_numeric($img)) {
            $img_url = wp_get_attachment_image_url($img, 'medium');
        } elseif (is_array($img) && isset($img['url'])) {
            $img_url = $img['url'];
        } else {
            $img_url = '';
        }

        $title   = $loc['title'] ?? '';
        $address = $loc['address'] ?? '';
        $lat     = $loc['latitude'] ?? '';
        $lng     = $loc['longitude'] ?? '';

        // OUTPUT for right-side list (unchanged)
        echo '<div class="location-item" data-index="' . $i . '">';
            if ($img_url) echo '<img src="' . esc_url($img_url) . '" class="loc-thumb" alt="UnforgettableCroatia">';
        echo '<div class="locationInfo">';
        echo '<h4>' . esc_html($title) . '</h4>';
        echo '<p>' . esc_html($address) . '</p>';
        echo '</div>'; #locationInfo
        echo '</div>';

        // STORE clean data for JS map
        $newLocations[] = [
            'title'   => $title,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lng,
            'img_url' => $img_url, // ← Add image for Google Maps popup
        ];
    }
    echo '</div>';

    echo '</div>'; // map-list-wrapper
}

echo '</div>'; // container
echo '</section>';

// Pass ACF data to JS (global variable)
echo '<script>
    var mapData = ' . json_encode($newLocations) . ';
</script>';
?>

<script>
    var activeMarkerIndex = null;

    // ORANGE default marker
    var defaultIcon = {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 11,
        fillColor: "#C65A34",
        fillOpacity: 1,
        strokeWeight: 2,
        strokeColor: "#ffffff"
    };

    // BLUE active marker
    var activeIcon = {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 12,
        fillColor: "#0A2342",
        fillOpacity: 1,
        strokeWeight: 3,
        strokeColor: "#ffffff"
    };

    function initLocMap() {
        const data = mapData;
        if (!data || data.length === 0) return;

        const mapEl = document.getElementById("google-map");

        const first = {
            lat: parseFloat(data[0].latitude),
            lng: parseFloat(data[0].longitude),
        };

        const map = new google.maps.Map(mapEl, {
            zoom: 12,
            center: first
        });

        // 🔥 MOVE ICON DEFINITIONS HERE (AFTER GOOGLE MAPS LOADS)
        const defaultIcon = {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 11,
            fillColor: "#C65A34",
            fillOpacity: 1,
            strokeWeight: 2,
            strokeColor: "#ffffff"
        };

        const activeIcon = {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 12,
            fillColor: "#0A2342",
            fillOpacity: 1,
            strokeWeight: 3,
            strokeColor: "#ffffff"
        };

        const markers = [];
        const infoWindows = [];

        data.forEach((loc, index) => {
            let pos = {
                lat: parseFloat(loc.latitude),
                lng: parseFloat(loc.longitude),
            };

            let marker = new google.maps.Marker({
                position: pos,
                map,
                icon: defaultIcon,
                title: loc.title
            });

            markers.push(marker);

            let info = new google.maps.InfoWindow({
                content: `
                <div class="map-info">
                    ${loc.img_url ? `<img src="${loc.img_url}" class="map-info-img" />` : ''}
                    <div class="map-data">
                        <div class='title'>${loc.title}</div>
                        <div class='address'>${loc.address}</div>
                    </div>
                </div>`
            });

            infoWindows.push(info);

            marker.addListener("click", function() {
                setActiveMarker(index, map, markers, infoWindows, defaultIcon, activeIcon);
            });
        });

        document.querySelectorAll(".location-item").forEach(item => {
            item.addEventListener("click", function() {
                const i = this.dataset.index;
                // 🔥 Remove active class from all items
                document.querySelectorAll(".location-item").forEach(el => {
                    el.classList.remove("active");
                });

                // 🔥 Add active class to the clicked item
                this.classList.add("active");
                setActiveMarker(i, map, markers, infoWindows, defaultIcon, activeIcon);
            });
        });
    }

    function setActiveMarker(index, map, markers, infoWindows, defaultIcon, activeIcon) {
        infoWindows.forEach(iw => iw.close());
        markers.forEach(m => m.setIcon(defaultIcon));

        markers[index].setIcon(activeIcon);
        infoWindows[index].open(map, markers[index]);

        map.panTo(markers[index].getPosition());
        map.setZoom(12.5);
    }
</script>
<!-- Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAU7oE95QhMVyp3yEnXqZRxzm4O3KqPO9A&callback=initLocMap"></script>