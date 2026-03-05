window.initCopMap = function (opts) {
    const elId = opts.elId || 'map';

    if (opts.mode !== 'image') {
        throw new Error('Este initCopMap está preparado para mode=image (planta).');
    }

    const IMG_W = Number(opts.imageWidth);
    const IMG_H = Number(opts.imageHeight);

    const map = L.map(elId, {
        crs: L.CRS.Simple,
        minZoom: opts.minZoom ?? -2,
        maxZoom: opts.maxZoom ?? 4,
        zoomControl: true
    });

    const bounds = [[0, 0], [IMG_H, IMG_W]];
    L.imageOverlay(opts.imageUrl, bounds).addTo(map);
    map.fitBounds(bounds, { animate: false });
    map.setMaxBounds(bounds);

    setTimeout(() => map.invalidateSize(true), 200);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: {
            marker: true,
            polyline: true,
            polygon: true,
            rectangle: true,
            circle: false,
            circlemarker: false,
        }
    });
    map.addControl(drawControl);

    function apiFetch(url, payload) {
        const headers = { 'Content-Type': 'application/json' };
        if (opts.csrfToken) headers['X-CSRF-Token'] = opts.csrfToken;

        return fetch(url, {
            method: 'POST',
            headers,
            credentials: 'same-origin',
            body: payload ? JSON.stringify(payload) : '{}'
        }).then(async r => {
            if (!r.ok) throw new Error(await r.text());
            return r.json();
        });
    }

    async function loadLocations() {
        const res = await fetch(opts.locationsIndexUrl, { credentials: 'same-origin' });
        const fc = await res.json();

        drawnItems.clearLayers();

        L.geoJSON(fc, {
            onEachFeature: (feature, layer) => {
                layer._locationId = feature.id;
                const name = feature?.properties?.name ?? `Location #${feature.id}`;
                layer.bindPopup(name);
            }
        }).eachLayer(l => drawnItems.addLayer(l));
    }

    // CREATE
    map.on(L.Draw.Event.CREATED, async (e) => {
        const layer = e.layer;
        drawnItems.addLayer(layer);

        const feature = layer.toGeoJSON(); // Feature
        try {
            const out = await apiFetch(opts.locationsCreateUrl, {
                name: 'Novo local',
                location_type_id: 3,  // POINT
                status_type_id: 1,    // GREEN
                geometry: feature.geometry
            });
            layer._locationId = out.id;
        } catch (err) {
            console.error(err);
            drawnItems.removeLayer(layer);
            alert('Erro a guardar location.');
        }
    });

    // UPDATE
    map.on(L.Draw.Event.EDITED, async (e) => {
        const promises = [];
        e.layers.eachLayer(layer => {
            if (!layer._locationId) return;
            const feature = layer.toGeoJSON();
            const url = opts.locationsUpdateUrl.replace('__ID__', layer._locationId);
            promises.push(apiFetch(url, { geometry: feature.geometry }));
        });

        try {
            await Promise.all(promises);
        } catch (err) {
            console.error(err);
            alert('Erro a atualizar. Faz refresh.');
        }
    });

    // DELETE
    map.on(L.Draw.Event.DELETED, async (e) => {
        const promises = [];
        e.layers.eachLayer(layer => {
            if (!layer._locationId) return;
            const url = opts.locationsDeleteUrl.replace('__ID__', layer._locationId);
            promises.push(apiFetch(url));
        });

        try {
            await Promise.all(promises);
        } catch (err) {
            console.error(err);
            alert('Erro a apagar. Faz refresh.');
        }
    });

    loadLocations();

    return { map, reload: loadLocations };
};