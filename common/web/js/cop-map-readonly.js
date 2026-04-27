// Função global que inicia o mapa em read-only.
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

    L.imageOverlay(opts.imageUrl, bounds).addTo(map);

    let geoJsonLayer = null;
    let allMapLayers = [];

    const activeFilters = {
        statusRed: true,
        statusYellow: true,
        statusGreen: true,
        roads: true,
        buildings: true,
        vedation: true,
        criticalPoints: true,
        incidents: true,
        tasks: true,
        lodgingSites: true,
        others: true,
    };

    const visibleFilters = {
        statusRed: true,
        statusYellow: true,
        statusGreen: true,
        roads: true,
        buildings: true,
        vedation: true,
        criticalPoints: true,
        incidents: false,
        tasks: false,
        lodgingSites: true,
        others: true,
    };

    const filterLabels = {
        statusRed: 'INOP',
        statusYellow: 'Alerta',
        statusGreen: 'Operacional',
        roads: 'Vias e corredores logísticos',
        buildings: 'Edifícios',
        vedation: 'Vedação',
        criticalPoints: 'Pontos críticos',
        incidents: 'Incidentes',
        tasks: 'Tarefas WO',
        lodgingSites: 'Alojamentos',
        others: 'Outros',
    };

    createFilterControl();

    function createFilterControl() {
        const FilterControl = L.Control.extend({
            options: {
                position: 'topright'
            },

            onAdd: function () {
                const wrapper = L.DomUtil.create('div', 'cop-layer-filter-wrapper');

                wrapper.innerHTML = `
                <button type="button" class="cop-layer-filter-toggle" title="Mostrar/ocultar filtros">
                    <i class="fas fa-layer-group"></i>
                </button>

                <div class="cop-layer-filter-panel is-open">
                    <div class="cop-layer-filter-header">
                        <div>
                            <strong class="cop-layer-filter-title">Filtros</strong>
                        </div>
                    </div>

                    <div class="cop-layer-filter-list">
                        ${Object.entries(filterLabels)
                    .filter(([key]) => visibleFilters[key] === true)
                    .map(([key, label]) => `
                                <label class="cop-layer-filter-item">
                                    <input
                                        type="checkbox"
                                        data-filter="${key}"
                                        ${activeFilters[key] ? 'checked' : ''}
                                    >
                                    <span class="cop-layer-filter-check"></span>
                                    <span class="cop-layer-filter-text">${label}</span>
                                </label>
                            `).join('')}
                    </div>
                </div>
            `;

                L.DomEvent.disableClickPropagation(wrapper);
                L.DomEvent.disableScrollPropagation(wrapper);

                const toggleBtn = wrapper.querySelector('.cop-layer-filter-toggle');
                const panel = wrapper.querySelector('.cop-layer-filter-panel');

                toggleBtn.addEventListener('click', function () {
                    panel.classList.toggle('is-open');
                    panel.classList.toggle('is-closed');
                });

                wrapper.querySelectorAll('input[type="checkbox"]').forEach(input => {
                    input.addEventListener('change', function () {
                        activeFilters[this.dataset.filter] = this.checked;
                        applyFilters();
                    });
                });

                return wrapper;
            }
        });

        map.addControl(new FilterControl());
    }

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

    fitContain();
    setTimeout(fitContain, 200);

    let resizeTimeout;

    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            map.invalidateSize(true);
        }, 100);
    });

    const container = document.getElementById(elId);

    if (container && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(() => {
            map.invalidateSize(true);
        });
        observer.observe(container);
    }

    function getStatusColor(status) {
        switch (status) {
            case 'CRÍTICO':
            case 'RED':
                return '#ff3b3b';

            case 'ALERTA':
            case 'YELLOW':
                return '#ffb300';

            case 'OK':
            case 'GREEN':
                return '#00c853';

            default:
                return '#3388ff';
        }
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function getLocationIcon(entityType, locationTypeId, statusType) {
        const base = opts.iconsBaseUrl || '';

        if (entityType === 'lodging_site') {
            return L.icon({
                iconUrl: base + '/house-default.png',
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });
        }

        if (Number(locationTypeId) === 1) {
            let file = 'building-default.png';

            switch (statusType) {
                case 'RED':
                case 'CRÍTICO':
                    file = 'building-red.png';
                    break;
                case 'YELLOW':
                case 'ALERTA':
                    file = 'building-yellow.png';
                    break;
                case 'GREEN':
                case 'OK':
                    file = 'building-green.png';
                    break;
            }

            return L.icon({
                iconUrl: base + '/' + file,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });
        }

        if (Number(locationTypeId) === 7) {
            let file = 'navid-default.png';

            switch (statusType) {
                case 'CRÍTICO':
                case 'RED':
                    file = 'navid-red.png';
                    break;
                case 'ALERTA':
                case 'YELLOW':
                    file = 'navid-yellow.png';
                    break;
                case 'OK':
                case 'GREEN':
                    file = 'navid-green.png';
                    break;
            }

            return L.icon({
                iconUrl: base + '/' + file,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });
        }

        return null;
    }

    function getLayerFilters(layer) {
        const item = layer.feature?.properties || {};
        const entityType = item.entity_type || 'location';
        const locationTypeId = Number(item.location_type_id);
        const statusType = item.status_type_name ?? item.status_type ?? null;

        const filters = [];

        if (statusType === 'RED' || statusType === 'CRÍTICO') {
            filters.push('statusRed');
        }

        if (statusType === 'YELLOW' || statusType === 'ALERTA') {
            filters.push('statusYellow');
        }

        if (statusType === 'GREEN' || statusType === 'OK') {
            filters.push('statusGreen');
        }

        if (entityType === 'lodging_site') {
            filters.push('lodgingSites');
        } else if (entityType === 'incident') {
            filters.push('incidents');
        } else if (entityType === 'task') {
            filters.push('tasks');
        } else if (locationTypeId === 1) {
            filters.push('buildings');
        } else if (locationTypeId === 4) {
            filters.push('roads');
        } else if (locationTypeId === 5) {
            filters.push('vedation');
        } else if (Number(item.is_critical) || locationTypeId === 7) {
            filters.push('criticalPoints');
        } else {
            filters.push('others');
        }

        return filters;
    }

    function applyFilters() {
        allMapLayers.forEach(layer => {
            const filters = getLayerFilters(layer);

            const shouldShow = filters.every(filterKey => {
                return activeFilters[filterKey] !== false;
            });

            if (shouldShow) {
                if (!map.hasLayer(layer)) {
                    layer.addTo(map);
                }
            } else {
                if (map.hasLayer(layer)) {
                    map.removeLayer(layer);
                }
            }
        });
    }

    function renderFeatures(featureCollection) {
        if (!featureCollection || !Array.isArray(featureCollection.features)) {
            console.warn('initCopMapReadOnly: featureCollection inválida');
            return;
        }

        allMapLayers.forEach(layer => {
            if (map.hasLayer(layer)) {
                map.removeLayer(layer);
            }
        });

        allMapLayers = [];

        geoJsonLayer = L.geoJSON(featureCollection, {
            onEachFeature: function (feature, layer) {
                const item = feature.properties || {};
                const entityType = item.entity_type || 'location';

                if (entityType === 'location') {
                    const locationType = item.location_type_name ?? item.location_type ?? '—';

                    const popupHtml = `
                        <div class="cop-popup">
                            <div class="cop-popup__head">
                                <div class="cop-popup__title-wrap">
                                    <span class="cop-popup__title">${escapeHtml(item.name ?? 'Sem nome')}</span>
                                    ${
                        Number(item.is_critical)
                            ? '<span class="cop-popup__badge cop-popup__badge--critical">Crítico</span>'
                            : '<span class="cop-popup__badge cop-popup__badge--normal">Normal</span>'
                    }
                                </div>
                            </div>

                            <div class="cop-popup__body">
                                <div class="cop-popup__row">
                                    <span class="cop-popup__label">Tipo</span>
                                    <span class="cop-popup__value">${escapeHtml(locationType ?? '—')}</span>
                                </div>

                                ${
                        item.notes
                            ? `
                                        <div class="cop-popup__notes">
                                            <span class="cop-popup__label">Notas</span>
                                            <div class="cop-popup__notes-text">${escapeHtml(item.notes)}</div>
                                        </div>
                                        `
                            : ''
                    }
                            </div>
                        </div>
                    `;

                    layer.bindPopup(popupHtml, {
                        className: 'cop-leaflet-popup',
                        maxWidth: 320,
                        minWidth: 240
                    });

                    return;
                }

                if (entityType === 'lodging_site') {
                    const popupHtml = `
                        <div class="cop-popup">
                            <div class="cop-popup__head">
                                <div class="cop-popup__title-wrap">
                                    <span class="cop-popup__title">${escapeHtml(item.name ?? 'Sem nome')}</span>
                                    <span class="cop-popup__badge cop-popup__badge--normal">Alojamento</span>
                                </div>
                            </div>

                            <div class="cop-popup__body">
                                <div class="cop-popup__row">
                                    <span class="cop-popup__label">Tipo</span>
                                    <span class="cop-popup__value">Alojamento</span>
                                </div>

                                <div class="cop-popup__row">
                                    <span class="cop-popup__label">Capacidade total</span>
                                    <span class="cop-popup__value">${escapeHtml(item.capacity_total ?? '—')}</span>
                                </div>

                                <div class="cop-popup__row">
                                    <span class="cop-popup__label">Disponíveis</span>
                                    <span class="cop-popup__value">${escapeHtml(item.capacity_available ?? '—')}</span>
                                </div>

                                ${
                        item.notes
                            ? `
                                        <div class="cop-popup__notes">
                                            <span class="cop-popup__label">Notas</span>
                                            <div class="cop-popup__notes-text">${escapeHtml(item.notes)}</div>
                                        </div>
                                        `
                            : ''
                    }
                            </div>
                        </div>
                    `;

                    layer.bindPopup(popupHtml, {
                        className: 'cop-leaflet-popup',
                        maxWidth: 320,
                        minWidth: 240
                    });
                }
            },

            style: function (feature) {
                const item = feature.properties || {};
                const entityType = item.entity_type || 'location';

                if (entityType === 'lodging_site') {
                    return {
                        color: '#4dabf7',
                        weight: 3,
                        fillOpacity: 0.2
                    };
                }

                const statusType = item.status_type_name ?? item.status_type ?? null;
                const color = getStatusColor(statusType);

                return {
                    color: color,
                    weight: 3,
                    fillOpacity: 0.2
                };
            },

            pointToLayer: function (feature, latlng) {
                const item = feature.properties || {};
                const entityType = item.entity_type || 'location';
                const statusType = item.status_type_name ?? item.status_type ?? null;

                const color = entityType === 'lodging_site'
                    ? '#4dabf7'
                    : getStatusColor(statusType);

                const customIcon = getLocationIcon(entityType, item.location_type_id, statusType);

                if (customIcon) {
                    return L.marker(latlng, {
                        icon: customIcon
                    });
                }

                return L.circleMarker(latlng, {
                    radius: 6,
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.9
                });
            }
        });

        geoJsonLayer.eachLayer(function (layer) {
            allMapLayers.push(layer);
        });

        applyFilters();
    }

    function loadFeatures() {
        if (opts.featureCollection && Array.isArray(opts.featureCollection.features)) {
            renderFeatures(opts.featureCollection);
            return;
        }

        if (opts.locationsIndexUrl) {
            fetch(opts.locationsIndexUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    return response.json();
                })
                .then(function (data) {
                    renderFeatures(data);
                })
                .catch(function (error) {
                    console.error('Erro ao carregar itens do mapa:', error);
                });

            return;
        }

        console.warn('initCopMapReadOnly: nem featureCollection nem locationsIndexUrl foram fornecidos');
    }

    loadFeatures();

    return {
        map: map,
        fitContain: fitContain,
        fitCover: fitCover,
        reload: loadFeatures
    };
};