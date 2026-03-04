// Inicializador único do mapa (backend e frontend chamam isto)
window.initCopMap = function (opts) {
    const elId = opts.elId || 'map';

    // ====== MODO IMAGEM (planta) ======
    if (opts.mode === 'image') {
        const IMG_W = Number(opts.imageWidth);
        const IMG_H = Number(opts.imageHeight);
        const imageUrl = opts.imageUrl;

        const map = L.map(elId, {
            crs: L.CRS.Simple,
            minZoom: opts.minZoom ?? -2,
            maxZoom: opts.maxZoom ?? 4,
            zoomControl: true,
        });

        const bounds = [[0, 0], [IMG_H, IMG_W]];
        L.imageOverlay(imageUrl, bounds).addTo(map);
        map.fitBounds(bounds, { animate: false });
        map.setMaxBounds(bounds);

        // AdminLTE/layout: garantir render correto
        setTimeout(() => map.invalidateSize(true), 200);
        window.addEventListener('resize', () => map.invalidateSize(true));

        if (opts.showCoordsOnClick) {
            map.on('click', (e) => {
                const x = Math.round(e.latlng.lng);
                const y = Math.round(e.latlng.lat);
                L.popup()
                    .setLatLng(e.latlng)
                    .setContent(`<b>x</b>: ${x} &nbsp; <b>y</b>: ${y}`)
                    .openOn(map);
            });
        }

        const layers = {
            features: L.layerGroup().addTo(map),
        };

        return { map, layers, reload: async () => {} };
    }

    // ====== MODO OSM (lat/lng) ======
    const center = opts.center || [38.65, -9.10];
    const zoom = opts.zoom || 16;

    const map = L.map(elId).setView(center, zoom);

    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const layers = { features: L.layerGroup().addTo(map) };

    async function loadFeatures() {
        if (!opts.featuresIndexUrl) return;
        const res = await fetch(opts.featuresIndexUrl, { credentials: 'same-origin' });
        const data = await res.json();
        layers.features.clearLayers();
        data.forEach(f => L.geoJSON(f.geojson).addTo(layers.features));
    }

    loadFeatures();
    return { map, layers, reload: loadFeatures };
};