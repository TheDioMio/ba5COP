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

    // Geoman toolbar, configurar a toolbar
    map.pm.addControls({
        position: 'topleft',
        drawMarker: true,
        drawPolyline: true,
        drawRectangle: true,
        drawPolygon: true,
        drawCircle: true,
        drawCircleMarker: true,
        drawText: true,
        editMode: true,
        dragMode: true,
        cutPolygon: true,
        removalMode: true,
    });

    map.pm.setGlobalOptions({
        snappable: true,
        snapDistance: 20,
        layerGroup: drawnItems,
    });

    let pendingLayer = null;
    let editLayer = null;

    const modalEl = document.getElementById('locationModal');
    const modal = new bootstrap.Modal(modalEl);

    const locIdInput = document.getElementById('loc-id');
    const locNameInput = document.getElementById('loc-name');
    const locTypeInput = document.getElementById('loc-type');
    const locStatusInput = document.getElementById('loc-status');
    const locNotesInput = document.getElementById('loc-notes');
    const saveBtn = document.getElementById('saveLocationBtn');
    const cancelBtn = document.getElementById('cancelLocationBtn');
    const locIsCriticalInput = document.getElementById('loc-is-critical');

    function guessLocationType(geometryType) {
        if (geometryType === 'Point') return 3;
        if (geometryType === 'LineString') return 4;
        if (geometryType === 'Polygon') return 2;
        return 3;
    }

    function resetForm() {
        locIdInput.value = '';
        locNameInput.value = '';
        locTypeInput.value = '3';
        locStatusInput.value = '1';
        locNotesInput.value = '';
        locIsCriticalInput.checked = false;
    }

    function fillFormFromLayer(layer) {
        const geo = layer.toGeoJSON();
        locIdInput.value = layer._locationId || '';
        locNameInput.value = layer._locationName || '';
        locTypeInput.value = String(layer._locationTypeId || guessLocationType(geo.geometry.type));
        locStatusInput.value = String(layer._locationStatusId || 1);
        locNotesInput.value = layer._locationNotes || '';
        locIsCriticalInput.checked = Boolean(layer._locationIsCritical);
    }

    function setLayerMeta(layer, item) {
        layer._locationId = item.id;
        layer._locationName = item.name;
        layer._locationTypeId = item.location_type_id;
        layer._locationStatusId = item.status_type_id;
        layer._locationNotes = item.notes;
        layer._locationIsCritical = item.is_critical;

        const popupHtml = `
            <strong>${item.name ?? 'Sem nome'}</strong><br>
            Tipo: ${item.location_type_id}<br>
            Estado: ${item.status_type_id}<br>
            Crítico: ${item.is_critical ? 'Sim' : 'Não'}<br>
            ${item.notes ? `Notas: ${item.notes}` : ''}
        `;
        layer.bindPopup(popupHtml);

        layer.off('click');
        layer.on('click', function () {
            editLayer = layer;
            pendingLayer = null;
            fillFormFromLayer(layer);
            modal.show();
        });
    }

    async function apiFetch(url, payload = null) {
        const headers = { 'Content-Type': 'application/json' };
        if (opts.csrfToken) {
            headers['X-CSRF-Token'] = opts.csrfToken;
        }

        const res = await fetch(url, {
            method: 'POST',
            headers,
            credentials: 'same-origin',
            body: payload ? JSON.stringify(payload) : '{}'
        });

        if (!res.ok) {
            throw new Error(await res.text());
        }

        return res.json();
    }

    async function loadLocations() {
        const res = await fetch(opts.locationsIndexUrl, { credentials: 'same-origin' });
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
                    notes: props.notes ?? '',
                    is_critical: props.is_critical ?? 0
                });

                drawnItems.addLayer(layer);
            }
        });
    }




    // CREATE
    map.on('pm:create', (e) => {
        pendingLayer = e.layer;
        editLayer = null;

        resetForm();
        locTypeInput.value = String(guessLocationType(pendingLayer.toGeoJSON().geometry.type));
        modal.show();
    });

    // GEOMETRY UPDATE (edit/drag)
    map.on('pm:edit', async (e) => {
        const layer = e.layer;
        if (!layer || !layer._locationId) return;

        try {
            const url = opts.locationsUpdateUrl.replace('__ID__', layer._locationId);
            await apiFetch(url, {
                geometry: layer.toGeoJSON().geometry
            });
        } catch (err) {
            console.error(err);
            alert('Erro a atualizar geometria.');
        }
    });

    map.on('pm:dragend', async (e) => {
        const layer = e.layer;
        if (!layer || !layer._locationId) return;

        try {
            const url = opts.locationsUpdateUrl.replace('__ID__', layer._locationId);
            await apiFetch(url, {
                geometry: layer.toGeoJSON().geometry
            });
        } catch (err) {
            console.error(err);
            alert('Erro a mover geometria.');
        }
    });

    // DELETE
    map.on('pm:remove', async (e) => {
        const layer = e.layer;
        if (!layer || !layer._locationId) return;

        try {
            const url = opts.locationsDeleteUrl.replace('__ID__', layer._locationId);
            await apiFetch(url);
        } catch (err) {
            console.error(err);
            alert('Erro a apagar.');
        }
    });

    saveBtn.addEventListener('click', async () => {
        const payload = {
            name: locNameInput.value.trim() || 'Novo local',
            location_type_id: parseInt(locTypeInput.value, 10),
            status_type_id: parseInt(locStatusInput.value, 10),
            notes: locNotesInput.value.trim() || null,
            is_critical: locIsCriticalInput.checked ? 1 : 0,
        };

        try {
            // CREATE
            if (pendingLayer) {
                payload.geometry = pendingLayer.toGeoJSON().geometry;

                const out = await apiFetch(opts.locationsCreateUrl, payload);

                setLayerMeta(pendingLayer, {
                    id: out.id,
                    name: payload.name,
                    location_type_id: payload.location_type_id,
                    status_type_id: payload.status_type_id,
                    notes: payload.notes,
                    is_critical: payload.is_critical
                });

                pendingLayer = null;
            }
            // UPDATE ATTRIBUTES
            else if (editLayer) {
                const url = opts.locationsUpdateUrl.replace('__ID__', editLayer._locationId);

                await apiFetch(url, payload);

                setLayerMeta(editLayer, {
                    id: editLayer._locationId,
                    name: payload.name,
                    location_type_id: payload.location_type_id,
                    status_type_id: payload.status_type_id,
                    notes: payload.notes,
                    is_critical: payload.is_critical
                });

                editLayer = null;
            }

            modal.hide();
            resetForm();
        } catch (err) {
            console.error(err);
            alert('Erro ao guardar.');
        }
    });

    cancelBtn.addEventListener('click', () => {
        // se foi um create cancelado, remove a layer desenhada
        if (pendingLayer && !pendingLayer._locationId) {
            drawnItems.removeLayer(pendingLayer);
        }
        pendingLayer = null;
        editLayer = null;
        resetForm();
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        if (pendingLayer && !pendingLayer._locationId) {
            drawnItems.removeLayer(pendingLayer);
        }
        pendingLayer = null;
        editLayer = null;
        resetForm();
    });

    loadLocations();

    return { map, reload: loadLocations };
};