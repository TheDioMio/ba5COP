window.initCopMapReadOnly = function (opts) {
    const elId = opts.elId || 'map';

    if (opts.mode !== 'image') {
        throw new Error('Este initCopMapReadOnly está preparado para mode=image (planta).');
    }

    const IMG_W = Number(opts.imageWidth);
    const IMG_H = Number(opts.imageHeight);

    const map = L.map(elId, {
        crs: L.CRS.Simple,
        minZoom: opts.minZoom ?? -2,
        maxZoom: opts.maxZoom ?? 4,
        zoomControl: false
    });

    const bounds = [[0, 0], [IMG_H, IMG_W]];
    L.imageOverlay(opts.imageUrl, bounds).addTo(map);
    map.fitBounds(bounds, { animate: false });
    map.setMaxBounds(bounds);

    setTimeout(() => map.invalidateSize(true), 200);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    function setLayerMeta(layer, item) {
        layer._locationId = item.id;
        layer._locationName = item.name;
        layer._locationTypeId = item.location_type_id;
        layer._locationStatusId = item.status_type_id;
        layer._locationNotes = item.notes;

        const popupHtml = `
            <strong>${item.name ?? 'Sem nome'}</strong><br>
            Tipo: ${item.location_type_id}<br>
            Estado: ${item.status_type_id}<br>
            ${item.notes ? `Notas: ${item.notes}` : ''}
        `;

        layer.bindPopup(popupHtml);
    }

    async function loadLocations() {
        const res = await fetch(opts.locationsIndexUrl, {
            credentials: 'same-origin'
        });

        const fc = await res.json();

        drawnItems.clearLayers();

        L.geoJSON(fc, {
            onEachFeature: (feature, layer) => {
                const props = feature.properties || {};

                setLayerMeta(layer, {
                    id: feature.id,
                    name: props.name ?? '',
                    location_type_id: props.location_type_id ?? 3,
                    status_type_id: props.status_type_id ?? 1,
                    notes: props.notes ?? ''
                });

                drawnItems.addLayer(layer);
            }
        });
    }

    loadLocations().catch(err => {
        console.error('Erro ao carregar localizações:', err);
    });

    return { map, reload: loadLocations };
};