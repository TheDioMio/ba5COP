window.initCopMapReadOnly = function (opts) {
    const elId = opts.elId || 'map';

    if (!opts.imageUrl) {
        console.error('initCopMapReadOnly: imageUrl é obrigatório');
        return;
    }

    if (opts.mode !== 'image') {
        throw new Error('Este mapa está preparado apenas para mode=image');
    }

    const IMG_W = Number(opts.imageWidth);
    const IMG_H = Number(opts.imageHeight);

    if (!IMG_W || !IMG_H) {
        console.error('initCopMapReadOnly: imageWidth e imageHeight são obrigatórios');
        return;
    }

    // --- INIT MAP ---
    const map = L.map(elId, {
        crs: L.CRS.Simple,
        minZoom: opts.minZoom ?? -2,
        maxZoom: opts.maxZoom ?? 4,
        zoomControl: false,
        attributionControl: false,
        scrollWheelZoom: opts.scrollWheelZoom ?? false,
    });

    const bounds = [[0, 0], [IMG_H, IMG_W]];
    const center = [IMG_H / 2, IMG_W / 2];

    // --- IMAGE OVERLAY ---
    L.imageOverlay(opts.imageUrl, bounds).addTo(map);

    // --- FIT FUNCTIONS ---
    function fitContain() {
        map.invalidateSize(true);
        map.fitBounds(bounds, { animate: false });
        map.setMaxBounds(bounds);
    }

    function fitCover() {
        map.invalidateSize(true);
        const zoom = map.getBoundsZoom(bounds, true);
        map.setView(center, zoom, { animate: false });
        map.setMaxBounds(bounds);
    }

    // Usa contain por default
    fitContain();

    // Fix inicial (layout ainda não estabilizado)
    setTimeout(fitContain, 200);

    // --- RESIZE HANDLING (CRUCIAL) ---
    let resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            map.invalidateSize(true);
        }, 100);
    });

    // --- OPTIONAL: OBSERVER PARA MUDANÇAS DE LAYOUT (navbar toggle, etc) ---
    const container = document.getElementById(elId);
    if (container) {
        const observer = new ResizeObserver(() => {
            map.invalidateSize(true);
        });
        observer.observe(container);
    }

    // --- LOAD FEATURES (READ ONLY) ---
    if (opts.featureCollection && opts.featureCollection.features) {
        const geoJsonLayer = L.geoJSON(opts.featureCollection, {
            onEachFeature: function (feature, layer) {
                const item = feature.properties || {};

                const popupHtml = `
                    <strong>${item.name ?? 'Sem nome'}</strong><br>
                    Tipo: ${item.location_type ?? '—'}<br>
                    Estado: ${item.status_type ?? '—'}<br>
                    Crítico: ${item.is_critical ? 'Sim' : 'Não'}<br>
                    ${item.notes ? `<br>Notas: ${item.notes}` : ''}
                `;

                layer.bindPopup(popupHtml);
            },

            style: function (feature) {
                const item = feature.properties || {};

                // Cores por estado (ajusta aos teus status_type)
                let color = '#3388ff';

                switch (item.status_type) {
                    case 'CRÍTICO':
                    case 'RED':
                        color = '#ff3b3b';
                        break;
                    case 'ALERTA':
                    case 'YELLOW':
                        color = '#ffb300';
                        break;
                    case 'OK':
                    case 'GREEN':
                        color = '#00c853';
                        break;
                }

                return {
                    color: color,
                    weight: 3,
                    fillOpacity: 0.2
                };
            },

            pointToLayer: function (feature, latlng) {
                const item = feature.properties || {};

                let color = '#3388ff';

                switch (item.status_type) {
                    case 'CRÍTICO':
                    case 'RED':
                        color = '#ff3b3b';
                        break;
                    case 'ALERTA':
                    case 'YELLOW':
                        color = '#ffb300';
                        break;
                    case 'OK':
                    case 'GREEN':
                        color = '#00c853';
                        break;
                }

                return L.circleMarker(latlng, {
                    radius: 6,
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.9
                });
            }
        });

        geoJsonLayer.addTo(map);
    }

    return map;
};