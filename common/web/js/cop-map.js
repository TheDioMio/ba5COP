window.initCopMap = function (opts) {
    // ID do elemento HTML onde o mapa vai ser renderizado
    const elId = opts.elId || 'map';

    // Este mapa foi feito apenas para modo "imagem/planta"
    if (opts.mode !== 'image') {
        throw new Error('Este initCopMap está preparado para mode=image (planta).');
    }

    // Dimensões da imagem-base da planta
    const IMG_W = Number(opts.imageWidth);
    const IMG_H = Number(opts.imageHeight);

    // Inicialização do mapa Leaflet com CRS.Simple
    const map = L.map(elId, {
        crs: L.CRS.Simple,
        minZoom: opts.minZoom ?? -2,
        maxZoom: opts.maxZoom ?? 4,
        zoomControl: true
    });

    // Limites da imagem no sistema de coordenadas simples
    const bounds = [[0, 0], [IMG_H, IMG_W]];

    // Coloca a imagem da planta como overlay de fundo
    L.imageOverlay(opts.imageUrl, bounds).addTo(map);

    // Ajusta o zoom inicial para apanhar a imagem toda
    map.fitBounds(bounds, { animate: false });

    // Impede o utilizador de sair para fora da imagem
    map.setMaxBounds(bounds);

    // Garante que o Leaflet recalcula o tamanho do mapa após render
    setTimeout(() => map.invalidateSize(true), 200);

    // Grupo onde ficam todas as layers desenhadas ou carregadas
    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    // Toolbar do Leaflet Geoman para desenho/remoção
    map.pm.addControls({
        position: 'topleft',
        drawMarker: true,
        drawPolyline: true,
        drawRectangle: true,
        drawPolygon: true,
        drawCircle: true,
        drawCircleMarker: false,
        drawText: false,
        editMode: false,
        dragMode: false,
        cutPolygon: false,
        removalMode: true,
    });

    // Opções globais do Geoman
    map.pm.setGlobalOptions({
        snappable: true,
        snapDistance: 20,
        layerGroup: drawnItems,
    });

    // Layer temporária criada no mapa mas ainda não guardada na BD
    let pendingLayer = null;

    // Layer atualmente em edição
    let editLayer = null;

    // Modal Bootstrap do formulário
    const modalEl = document.getElementById('locationModal');
    const modal = new bootstrap.Modal(modalEl);

    // Inputs genéricos
    const locIdInput = document.getElementById('loc-id');
    const entityKindInput = document.getElementById('entity-kind');

    // Inputs da location
    const locNameInput = document.getElementById('loc-name');
    const locTypeInput = document.getElementById('loc-type');
    const locStatusInput = document.getElementById('loc-status');
    const locNotesInput = document.getElementById('loc-notes');
    const locIsCriticalInput = document.getElementById('loc-is-critical');

    // Inputs do lodging_site
    const lodgingNameInput = document.getElementById('lodging-name');
    const lodgingCapacityTotalInput = document.getElementById('lodging-capacity-total');
    const lodgingCapacityAvailableInput = document.getElementById('lodging-capacity-available');
    const lodgingNotesInput = document.getElementById('lodging-notes');

    // Blocos do modal
    const locationFields = document.getElementById('location-fields');
    const lodgingFields = document.getElementById('lodging-fields');

    // Botões do modal
    const saveBtn = document.getElementById('saveLocationBtn');
    const cancelBtn = document.getElementById('cancelLocationBtn');

    /**
     * Tenta adivinhar um location_type_id com base no tipo de geometria
     * Point      -> 3
     * LineString -> 4
     * Polygon    -> 2
     */
    function guessLocationType(geometryType) {
        if (geometryType === 'Point') return 3;
        if (geometryType === 'LineString') return 4;
        if (geometryType === 'Polygon') return 2;
        return 3;
    }

    /**
     * Mostra/esconde os blocos do modal consoante o tipo de entidade
     */
    function toggleEntityFields() {
        const kind = entityKindInput?.value || 'location';

        if (kind === 'lodging_site') {
            if (locationFields) locationFields.style.display = 'none';
            if (lodgingFields) lodgingFields.style.display = 'block';
        } else {
            if (locationFields) locationFields.style.display = 'block';
            if (lodgingFields) lodgingFields.style.display = 'none';
        }
    }

    /**
     * Limpa o formulário do modal
     */
    function resetForm() {
        locIdInput.value = '';
        if (entityKindInput) entityKindInput.value = 'location';

        // location
        if (locNameInput) locNameInput.value = '';
        if (locTypeInput) locTypeInput.value = '3';
        if (locStatusInput) locStatusInput.value = '1';
        if (locNotesInput) locNotesInput.value = '';
        if (locIsCriticalInput) locIsCriticalInput.checked = false;

        // lodging
        if (lodgingNameInput) lodgingNameInput.value = '';
        if (lodgingCapacityTotalInput) lodgingCapacityTotalInput.value = '';
        if (lodgingCapacityAvailableInput) lodgingCapacityAvailableInput.value = '';
        if (lodgingNotesInput) lodgingNotesInput.value = '';

        toggleEntityFields();
    }

    /**
     * Preenche o formulário com os dados de uma layer do tipo "location"
     */
    function fillLocationFormFromLayer(layer) {
        const geo = layer.toGeoJSON();

        locIdInput.value = layer._entityId || '';
        if (entityKindInput) entityKindInput.value = 'location';

        if (locNameInput) locNameInput.value = layer._locationName || '';
        if (locTypeInput) locTypeInput.value = String(layer._locationTypeId || guessLocationType(geo.geometry.type));
        if (locStatusInput) locStatusInput.value = String(layer._locationStatusId || 1);
        if (locNotesInput) locNotesInput.value = layer._locationNotes || '';
        if (locIsCriticalInput) locIsCriticalInput.checked = Boolean(layer._locationIsCritical);

        toggleEntityFields();
    }

    /**
     * Preenche o formulário com os dados de uma layer do tipo "lodging_site"
     */
    function fillLodgingFormFromLayer(layer) {
        locIdInput.value = layer._entityId || '';
        if (entityKindInput) entityKindInput.value = 'lodging_site';

        if (lodgingNameInput) lodgingNameInput.value = layer._lodgingName || '';
        if (lodgingCapacityTotalInput) lodgingCapacityTotalInput.value = layer._lodgingCapacityTotal ?? '';
        if (lodgingCapacityAvailableInput) lodgingCapacityAvailableInput.value = layer._lodgingCapacityAvailable ?? '';
        if (lodgingNotesInput) lodgingNotesInput.value = layer._lodgingNotes || '';

        toggleEntityFields();
    }

    /**
     * Escapa texto para evitar partir o HTML do popup
     */
    function esc(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    /**
     * Associa metadados e popup a uma layer.
     * Esta função suporta:
     * - location
     * - lodging_site
     */
    function setLayerMeta(layer, item) {
        layer._entityType = item.entity_type || 'location';
        layer._entityId = item.id;

        // =========================
        // CASO: LOCATION
        // =========================
        if (layer._entityType === 'location') {
            layer._locationName = item.name ?? '';
            layer._locationTypeId = item.location_type_id ?? 3;
            layer._locationTypeName = item.location_type_name ?? null;
            layer._locationStatusId = item.status_type_id ?? 1;
            layer._locationStatusName = item.status_type_name ?? null;
            layer._locationNotes = item.notes ?? '';
            layer._locationIsCritical = item.is_critical ?? 0;

            const popupHtml = `
                <strong>${esc(item.name ?? 'Sem nome')}</strong><br>
                Tipo: ${esc(item.location_type_name ?? item.location_type_id ?? '—')}<br>
                Estado: ${esc(item.status_type_name ?? item.status_type_id ?? '—')}<br>
                Crítico: ${Number(item.is_critical) ? 'Sim' : 'Não'}<br>
                ${item.notes ? `<br>Notas: ${esc(item.notes)}` : ''}
            `;

            layer.bindPopup(popupHtml);
        }

        // =========================
        // CASO: LODGING SITE
        // =========================
        if (layer._entityType === 'lodging_site') {
            layer._lodgingName = item.name ?? '';
            layer._lodgingCapacityTotal = item.capacity_total ?? null;
            layer._lodgingCapacityAvailable = item.capacity_available ?? null;
            layer._lodgingNotes = item.notes ?? '';

            const popupHtml = `
                <strong>${esc(item.name ?? 'Sem nome')}</strong><br>
                Tipo: Alojamento<br>
                Capacidade total: ${esc(item.capacity_total ?? '—')}<br>
                Disponíveis: ${esc(item.capacity_available ?? '—')}<br>
                ${item.notes ? `<br>Notas: ${esc(item.notes)}` : ''}
            `;

            layer.bindPopup(popupHtml);
        }

        // Clique na layer abre o modal da entidade respetiva
        layer.off('click');
        layer.on('click', function () {
            pendingLayer = null;
            editLayer = layer;

            if (layer._entityType === 'location') {
                fillLocationFormFromLayer(layer);
            } else if (layer._entityType === 'lodging_site') {
                fillLodgingFormFromLayer(layer);
            }

            modal.show();
        });
    }

    /**
     * Helper genérico para pedidos POST ao backend
     */
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

    /**
     * Carrega todas as geometrias do backend
     */
    async function loadLocations() {
        const res = await fetch(opts.locationsIndexUrl, { credentials: 'same-origin' });
        const fc = await res.json();

        drawnItems.clearLayers();

        L.geoJSON(fc, {
            onEachFeature: (feature, layer) => {
                const props = feature.properties || {};
                const entityType = props.entity_type || 'location';

                if (entityType === 'location') {
                    setLayerMeta(layer, {
                        entity_type: 'location',
                        id: feature.id,
                        name: props.name ?? '',
                        location_type_id: props.location_type_id ?? 3,
                        location_type_name: props.location_type_name ?? null,
                        status_type_id: props.status_type_id ?? 1,
                        status_type_name: props.status_type_name ?? null,
                        notes: props.notes ?? '',
                        is_critical: props.is_critical ?? 0
                    });
                }

                if (entityType === 'lodging_site') {
                    setLayerMeta(layer, {
                        entity_type: 'lodging_site',
                        id: feature.id,
                        name: props.name ?? '',
                        capacity_total: props.capacity_total ?? null,
                        capacity_available: props.capacity_available ?? null,
                        notes: props.notes ?? ''
                    });
                }

                drawnItems.addLayer(layer);
            }
        });
    }

    // Alteração manual do tipo de entidade no modal
    if (entityKindInput) {
        entityKindInput.addEventListener('change', toggleEntityFields);
    }

    // =========================================================
    // CREATE
    // =========================================================
    map.on('pm:create', (e) => {
        pendingLayer = e.layer;
        editLayer = null;

        resetForm();

        // por defeito, ao criar, assume location
        if (entityKindInput) {
            entityKindInput.value = 'location';
        }

        if (locTypeInput) {
            locTypeInput.value = String(guessLocationType(pendingLayer.toGeoJSON().geometry.type));
        }

        toggleEntityFields();
        modal.show();
    });

    // =========================================================
    // UPDATE GEOMETRY
    // =========================================================
    map.on('pm:edit', async (e) => {
        const layer = e.layer;
        if (!layer || !layer._entityId) return;

        try {
            let url = null;

            if (layer._entityType === 'location') {
                url = opts.locationsUpdateUrl.replace('__ID__', layer._entityId);
            } else if (layer._entityType === 'lodging_site') {
                url = opts.lodgingUpdateUrl.replace('__ID__', layer._entityId);
            }

            if (!url) return;

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
        if (!layer || !layer._entityId) return;

        try {
            let url = null;

            if (layer._entityType === 'location') {
                url = opts.locationsUpdateUrl.replace('__ID__', layer._entityId);
            } else if (layer._entityType === 'lodging_site') {
                url = opts.lodgingUpdateUrl.replace('__ID__', layer._entityId);
            }

            if (!url) return;

            await apiFetch(url, {
                geometry: layer.toGeoJSON().geometry
            });
        } catch (err) {
            console.error(err);
            alert('Erro a mover geometria.');
        }
    });

    // =========================================================
    // DELETE
    // =========================================================
    map.on('pm:remove', async (e) => {
        const layer = e.layer;
        if (!layer || !layer._entityId) return;

        try {
            let url = null;

            if (layer._entityType === 'location') {
                url = opts.locationsDeleteUrl.replace('__ID__', layer._entityId);
            } else if (layer._entityType === 'lodging_site') {
                url = opts.lodgingDeleteUrl.replace('__ID__', layer._entityId);
            }

            if (!url) return;

            await apiFetch(url);
        } catch (err) {
            console.error(err);
            alert('Erro a apagar.');
        }
    });

    // =========================================================
    // SAVE
    // =========================================================
    saveBtn.addEventListener('click', async () => {
        const kind = entityKindInput?.value || 'location';

        try {
            // =========================
            // LOCATION
            // =========================
            if (kind === 'location') {
                const payload = {
                    name: locNameInput.value.trim() || 'Novo local',
                    location_type_id: parseInt(locTypeInput.value, 10),
                    status_type_id: parseInt(locStatusInput.value, 10),
                    notes: locNotesInput.value.trim() || null,
                    is_critical: locIsCriticalInput.checked ? 1 : 0,
                };

                // CREATE
                if (pendingLayer) {
                    payload.geometry = pendingLayer.toGeoJSON().geometry;

                    const out = await apiFetch(opts.locationsCreateUrl, payload);

                    setLayerMeta(pendingLayer, {
                        entity_type: 'location',
                        id: out.id,
                        name: payload.name,
                        location_type_id: payload.location_type_id,
                        status_type_id: payload.status_type_id,
                        notes: payload.notes,
                        is_critical: payload.is_critical
                    });

                    pendingLayer = null;
                }
                // UPDATE
                else if (editLayer && editLayer._entityType === 'location') {
                    const url = opts.locationsUpdateUrl.replace('__ID__', editLayer._entityId);

                    await apiFetch(url, payload);

                    setLayerMeta(editLayer, {
                        entity_type: 'location',
                        id: editLayer._entityId,
                        name: payload.name,
                        location_type_id: payload.location_type_id,
                        status_type_id: payload.status_type_id,
                        notes: payload.notes,
                        is_critical: payload.is_critical
                    });

                    editLayer = null;
                }
            }

            // =========================
            // LODGING SITE
            // =========================
            if (kind === 'lodging_site') {
                const payload = {
                    name: lodgingNameInput.value.trim() || 'Novo alojamento',
                    capacity_total: parseInt(lodgingCapacityTotalInput.value, 10) || 0,
                    capacity_available: parseInt(lodgingCapacityAvailableInput.value, 10) || 0,
                    notes: lodgingNotesInput.value.trim() || null,
                };

                // CREATE
                if (pendingLayer) {
                    payload.geometry = pendingLayer.toGeoJSON().geometry;

                    const out = await apiFetch(opts.lodgingCreateUrl, payload);

                    setLayerMeta(pendingLayer, {
                        entity_type: 'lodging_site',
                        id: out.id,
                        name: payload.name,
                        capacity_total: payload.capacity_total,
                        capacity_available: payload.capacity_available,
                        notes: payload.notes
                    });

                    pendingLayer = null;
                }
                // UPDATE
                else if (editLayer && editLayer._entityType === 'lodging_site') {
                    const url = opts.lodgingUpdateUrl.replace('__ID__', editLayer._entityId);

                    await apiFetch(url, payload);

                    setLayerMeta(editLayer, {
                        entity_type: 'lodging_site',
                        id: editLayer._entityId,
                        name: payload.name,
                        capacity_total: payload.capacity_total,
                        capacity_available: payload.capacity_available,
                        notes: payload.notes
                    });

                    editLayer = null;
                }
            }

            modal.hide();
            resetForm();
        } catch (err) {
            console.error(err);
            alert('Erro ao guardar.');
        }
    });

    // =========================================================
    // CANCEL / RESET
    // =========================================================
    cancelBtn.addEventListener('click', () => {
        if (pendingLayer && !pendingLayer._entityId) {
            drawnItems.removeLayer(pendingLayer);
        }

        pendingLayer = null;
        editLayer = null;
        resetForm();
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        if (pendingLayer && !pendingLayer._entityId) {
            drawnItems.removeLayer(pendingLayer);
        }

        pendingLayer = null;
        editLayer = null;
        resetForm();
    });

    // Estado inicial dos campos do modal
    resetForm();

    // Carrega dados iniciais do mapa
    loadLocations();

    // Expõe mapa e função reload
    return { map, reload: loadLocations };
};