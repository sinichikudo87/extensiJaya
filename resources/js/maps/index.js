import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';

// Ganti icon default ke custom
const DefaultIcon = L.icon({
    iconUrl: '/images/icon.png',       // icon custom
    iconRetinaUrl: '/images/icon.png', // bisa pakai versi retina kalau ada    
    iconSize: [32, 37],   // sesuaikan ukuran icon
    iconAnchor: [16, 37], // titik “nempel” di lokasi
    popupAnchor: [0, -35],
    shadowSize: [41, 41],
});
L.Marker.prototype.options.icon = DefaultIcon;

window.L = L;

// Init Map
const map = L.map('map', {
    center: [-7.5653, 112.2381],
    zoom: 13,
    zoomControl: false,
});

// Tile Layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
}).addTo(map);

const CustomZoom = L.Control.extend({
    options: { position: 'topleft' },
    onAdd: function (map) {
        const container = L.DomUtil.create('div', 'custom-zoom');

        const zoomIn = L.DomUtil.create('a', 'zoom-in', container);
        zoomIn.innerHTML = '+';
        zoomIn.href = '#';
        zoomIn.title = 'Zoom In';
        L.DomEvent.on(zoomIn, 'click', L.DomEvent.stopPropagation)
                  .on(zoomIn, 'click', L.DomEvent.preventDefault)
                  .on(zoomIn, 'click', () => map.zoomIn());

        const zoomOut = L.DomUtil.create('a', 'zoom-out', container);
        zoomOut.innerHTML = '−';
        zoomOut.href = '#';
        zoomOut.title = 'Zoom Out';
        L.DomEvent.on(zoomOut, 'click', L.DomEvent.stopPropagation)
                  .on(zoomOut, 'click', L.DomEvent.preventDefault)
                  .on(zoomOut, 'click', () => map.zoomOut());

        return container;
    },
});

map.addControl(new CustomZoom());

// ---------------------
// Gear / Settings di kanan
// ---------------------
const GearControl = L.Control.extend({
    options: { position: 'topright' },

    onAdd: function(map) {
        const container = L.DomUtil.create('div', 'gear-control relative');

        L.DomEvent.disableClickPropagation(container);

        // Tombol gear
        const gearBtn = L.DomUtil.create('a', 'gear-btn flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-lg cursor-pointer hover:bg-gray-100 transition-transform duration-200 transform hover:scale-110', container);

        // Pakai Font Awesome
        gearBtn.innerHTML = `<i class="fa-solid fa-gear text-gray-700 text-lg"></i>`;



        // Menu dropdown (hidden)
        const menu = L.DomUtil.create('div', 'gear-menu hidden absolute right-0 mt-2 w-40 bg-white shadow rounded-xl overflow-hidden flex flex-col', container);
        menu.innerHTML = `
            <div class="p-3">
                <h3 class="font-semibold mb-2">Basemap</h3>
                <div class="flex flex-col gap-1 mb-3">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="googleStreets" checked class="accent-purple-500">
                        Google Streets
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="googleHybrid" class="accent-purple-500">
                        Google Hybrid
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="googleTerrain" class="accent-purple-500">
                        Google Terrain
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="googleTraffic" class="accent-purple-500">
                        Google Traffic
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="osm" class="accent-purple-500">
                        OSM
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="bingRoad" class="accent-purple-500">
                        Bing Road
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="bingAerial" class="accent-purple-500">
                        Bing Aerial
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="mapboxStreet" class="accent-purple-500">
                        Mapbox Street
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="mapboxSatellite" class="accent-purple-500">
                        Mapbox Satellite
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="esriWorldTopoMap" class="accent-purple-500">
                        ESRI World TopoMap
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="basemap" value="esriWorldImagery" class="accent-purple-500">
                        ESRI World Imagery
                    </label>
                </div>
                <h3 class="font-semibold mb-2">Overlay Layers</h3>
                <div class="flex flex-col gap-1 mb-3">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="overlay" value="openSea" class="accent-purple-500">
                        OpenSea Layer
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="overlay" value="openAIP" class="accent-purple-500">
                        OpenAIP Layer
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="overlay" value="nasagibs" class="accent-purple-500">
                        NASAGIBS Layer
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="overlay" value="openRailway" class="accent-purple-500">
                        Open Railway Layer
                    </label>
                </div>
            </div>
        `;

        // Toggle menu
        L.DomEvent.on(gearBtn, 'click', L.DomEvent.stopPropagation)
                  .on(gearBtn, 'click', () => menu.classList.toggle('hidden'));

        // Klik di luar untuk menutup
        document.addEventListener('click', () => {
            if(!menu.classList.contains('hidden')) menu.classList.add('hidden');
        });

        return container;
    }
});

map.addControl(new GearControl());

// Marker cluster
const markersCluster = L.markerClusterGroup();
map.addLayer(markersCluster);

// Load semua GPS
let markers = {};

async function loadAllGPS() {
    try {
        const res = await fetch("/api/gps/getLastLocation");
        const data = await res.json();

        if (!Array.isArray(data)) return;

        const currentZoom = map.getZoom();

        data.forEach(unit => {
            const latlng = [unit.latitude, unit.longitude];

            const tooltipContent = unit.top_up
                ? `${unit.device_name}<br>IMEI: ${unit.imei}<br>Speed: ${unit.speed} km/h<br>Time: ${unit.reported_at}`
                : `${unit.device_name}<br>Speed: ${unit.speed} km/h`;

            const popupContent = unit.top_up
                ? `<b>${unit.device_name}</b><br>IMEI: ${unit.imei}<br>Speed: ${unit.speed} km/h<br>Time: ${unit.reported_at}`
                : `<b>${unit.device_name}</b><br>Speed: ${unit.speed} km/h`;

            if (!markers[unit.imei]) {
                const marker = L.marker(latlng);

                // Tooltip permanen di atas marker
                marker.bindTooltip(tooltipContent, { 
                    permanent: true, 
                    direction: 'top', 
                    offset: [0, -30], 
                    className: 'gps-tooltip'
                });

                // Event click membuka popup
                // marker.on('click', () => {
                //     marker.bindPopup(popupContent, {
                //         offset: [0, 120], // geser 30px ke bawah
                //         className: 'gps-popup-custom' // opsional untuk styling
                //     }).openPopup();
                // });

                const panel = document.getElementById('marker-panel');

                marker.on('click', () => {
                    panel.innerHTML = `
                        <div class="font-bold mb-2">${unit.device_name}</div>
                        <div>IMEI: ${unit.imei || '-'}</div>
                        <div>Speed: ${unit.speed} km/h</div>
                        <div>Time: ${unit.reported_at || '-'}</div>
                    `;
                    panel.classList.remove('hidden');
                });


                markersCluster.addLayer(marker);
                markers[unit.imei] = marker;

            } else {
                markers[unit.imei].setLatLng(latlng);
                markers[unit.imei].getTooltip().setContent(tooltipContent);

                // Update popup jika sudah ada
                markers[unit.imei].on('click', () => {
                    markers[unit.imei].bindPopup(popupContent).openPopup();
                });
            }
        });

        if (data.length > 0) {
            map.setView([data[0].latitude, data[0].longitude], currentZoom);
        }

    } catch (err) {
        console.error("Gagal ambil GPS:", err);
    }
}

setInterval(loadAllGPS, 5000);
loadAllGPS();
