// Função global que inicia o mapa em read-only.
// "opts" é VITAL para o mapa funcionar, e é depois convertido para JSON.
window.initCopMapReadOnly = function (opts) {
    // ID do elemento HTML onde o Leaflet vai ser montado.
    // Se não vier nada, usa "map" por defeito.
    const elId = opts.elId || 'map';

    // O mapa read-only depende sempre de uma imagem de fundo.
    if (!opts.imageUrl) {
        console.error('initCopMapReadOnly: imageUrl é obrigatório');
        return;
    }

    // Este mapa foi pensado apenas para modo "image".
    if (opts.mode !== 'image') {
        throw new Error('Este mapa está preparado apenas para mode=image');
    }

    // Converte largura/altura da imagem para Number.
    const IMG_W = Number(opts.imageWidth);
    const IMG_H = Number(opts.imageHeight);

    // Sem largura e altura válidas, o Leaflet não sabe a área útil do mapa.
    if (!IMG_W || !IMG_H) {
        console.error('initCopMapReadOnly: imageWidth e imageHeight são obrigatórios');
        return;
    }

    // Cria a instância principal do Leaflet.
    // Usa CRS.Simple porque estamos a trabalhar com coordenadas planas de imagem.
    const map = L.map(elId, {
        crs: L.CRS.Simple,
        minZoom: opts.minZoom ?? -2,
        maxZoom: opts.maxZoom ?? 4,
        zoomControl: false,
        attributionControl: false,
        scrollWheelZoom: opts.scrollWheelZoom ?? false,
    });

    // Define os limites totais da imagem.
    const bounds = [[0, 0], [IMG_H, IMG_W]];

    // Centro geométrico da imagem.
    const center = [IMG_H / 2, IMG_W / 2];

    // Coloca a imagem de fundo no mapa.
    L.imageOverlay(opts.imageUrl, bounds).addTo(map);

    // Guarda a layer GeoJSON atual.
    let geoJsonLayer = null;

    // Ajusta o mapa para mostrar a imagem inteira.
    function fitContain() {
        map.invalidateSize(true);
        map.fitBounds(bounds, { animate: false });
        map.setMaxBounds(bounds);
    }

    // Ajusta o mapa para "encher" melhor a área visível.
    function fitCover() {
        map.invalidateSize(true);
        const zoom = map.getBoundsZoom(bounds, true);
        map.setView(center, zoom, { animate: false });
        map.setMaxBounds(bounds);
    }

    // Encaixe inicial da imagem.
    fitContain();

    // Segundo ajuste ligeiramente atrasado.
    setTimeout(fitContain, 200);

    // Variável para debounce do resize da janela.
    let resizeTimeout;

    // Quando a janela muda de tamanho, recalcula o mapa.
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            map.invalidateSize(true);
        }, 100);
    });

    // Vai buscar o container real do mapa no DOM.
    const container = document.getElementById(elId);

    // Se existir ResizeObserver, observa diretamente o container.
    if (container && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(() => {
            map.invalidateSize(true);
        });
        observer.observe(container);
    }

    // Função auxiliar que traduz o estado operacional numa cor.
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

    // Função auxiliar para escapar texto antes de o meter no popup.
    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // Função auxiliar que traduz o tipo de entidade / location_type num ícone.
    function getLocationIcon(entityType, locationTypeId, statusType) {
        const base = opts.iconsBaseUrl || '';

        // Ícone para lodging_site
        if (entityType === 'lodging_site') {
            let file = 'house-default.png';

            //swich da capacidade disponível dos alojamentos. Por fazer.
            // switch () {
            //
            // }

            return L.icon({
                iconUrl: base + '/' + file,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });
        } 

        // Ícones para locations normais
        if (Number(locationTypeId) === 1) {
            let file = 'building-default.png';

            switch (statusType) {
                case 'RED':
                    file = 'building-red.png';
                    break;

                case 'YELLOW':
                    file = 'building-yellow.png';
                    break;

                case 'GREEN':
                    file = 'building-green.png';
                    break;
            }

            return L.icon({
                iconUrl: base + '/' + file,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });
        } else if (Number(locationTypeId) === 7) {
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

    // Recebe um FeatureCollection GeoJSON e desenha-o no mapa.
    function renderFeatures(featureCollection) {
        if (!featureCollection || !Array.isArray(featureCollection.features)) {
            console.warn('initCopMapReadOnly: featureCollection inválida');
            return;
        }

        // Se já existir uma layer desenhada anteriormente remove-a antes de voltar a desenhar
        if (geoJsonLayer) {
            map.removeLayer(geoJsonLayer);
        }

        // Cria uma layer Leaflet a partir do GeoJSON.
        geoJsonLayer = L.geoJSON(featureCollection, {
            onEachFeature: function (feature, layer) {
                const item = feature.properties || {};
                const entityType = item.entity_type || 'location';

                // =========================
                // LOCATION
                // =========================
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

                // =========================
                // LODGING SITE
                // =========================
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

            // Define o estilo de linhas e polígonos.
            style: function (feature) {
                const item = feature.properties || {};
                const entityType = item.entity_type || 'location';

                // Alojamentos sem status_type usam uma cor própria
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

            // Aqui transformam-se os "points" em markers.
            pointToLayer: function (feature, latlng) {
                const item = feature.properties || {};
                const entityType = item.entity_type || 'location';
                const statusType = item.status_type_name ?? item.status_type ?? null;

                const color = entityType === 'lodging_site'
                    ? '#4dabf7'
                    : getStatusColor(statusType);

                const customIcon = getLocationIcon(entityType, item.location_type_id, statusType);

                // Se existir ícone, usa marker com ícone
                if (customIcon) {
                    return L.marker(latlng, {
                        icon: customIcon
                    });
                }

                // Se não existir ícone, usa círculo normal
                return L.circleMarker(latlng, {
                    radius: 6,
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.9
                });
            }
        });

        // Adiciona a layer GeoJSON ao mapa.
        geoJsonLayer.addTo(map);
    }

    // Carrega as features do mapa.
    function loadFeatures() {
        // Caso 1: os dados já vieram no próprio objeto opts.
        if (opts.featureCollection && Array.isArray(opts.featureCollection.features)) {
            renderFeatures(opts.featureCollection);
            return;
        }

        // Caso 2: os dados têm de ser pedidos via URL.
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

    // Arranca o carregamento inicial das features.
    loadFeatures();

    // Devolve uma pequena API pública do mapa.
    return {
        map: map,
        fitContain: fitContain,
        fitCover: fitCover,
        reload: loadFeatures
    };
};