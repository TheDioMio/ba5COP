//Função global que inicia o mapa em read-only, "opts" é VITAL para o mapa funcionar, e é depois convertido para JSON
window.initCopMapReadOnly = function (opts) {
    //ID do elemento HTML onde o Leaflet vai ser montado, Se não vier nada, usa "map" por defeito
    const elId = opts.elId || 'map';

    //O mapa read-only depende sempre de uma imagem de fundo, se não houver imageUrl, não faz sentido continuar
    if (!opts.imageUrl) {
        console.error('initCopMapReadOnly: imageUrl é obrigatório');
        return;
    }

    //Esta funcionalidade foi pensada apenas para modo "image", ou seja, mapa baseado numa imagem estática
    if (opts.mode !== 'image') {
        throw new Error('Este mapa está preparado apenas para mode=image');
    }

    //Converte largura/altura da imagem para Number (porque podem vir como string do PHP/JSON)
    const IMG_W = Number(opts.imageWidth);
    const IMG_H = Number(opts.imageHeight);

    //Sem largura e altura válidas, o Leaflet não sabe qual é a área útil do mapa
    if (!IMG_W || !IMG_H) {
        console.error('initCopMapReadOnly: imageWidth e imageHeight são obrigatórios');
        return;
    }

    // Cria a instância principal do Leaflet, usa CRS.Simple porque estamos a trabalhar com coordenadas "planas" da imagem
    const map = L.map(elId, {
        crs: L.CRS.Simple,
        minZoom: opts.minZoom ?? -2,
        maxZoom: opts.maxZoom ?? 4,
        zoomControl: false,
        attributionControl: false,
        scrollWheelZoom: opts.scrollWheelZoom ?? false,
    });

    //Define os limites totais da imagem
    //Em CRS.Simple, o canto superior esquerdo é [0,0] e o canto inferior direito é [altura, largura].
    const bounds = [[0, 0], [IMG_H, IMG_W]];

    //Centro geométrico da imagem
    const center = [IMG_H / 2, IMG_W / 2];

    //Coloca a imagem de fundo no mapa
    L.imageOverlay(opts.imageUrl, bounds).addTo(map);

    //Guarda a layer GeoJSON atual, é útil para remover a layer antiga quando se faz reload, evitando duplicar features.
    let geoJsonLayer = null;

    // Ajusta o mapa para mostrar a imagem inteira dentro da área disponível.
    // "contain" = caber toda a imagem no ecrã.
    function fitContain() {
        // Obriga o Leaflet a recalcular o tamanho real do container.
        map.invalidateSize(true);

        // Ajusta a câmara para caberem os bounds todos.
        map.fitBounds(bounds, { animate: false });

        // Limita a navegação para não fugir da imagem.
        map.setMaxBounds(bounds);
    }

    // Ajusta o mapa para "encher" melhor a área visível.
    // Pode cortar mais um pouco, mas ocupa melhor o espaço.
    function fitCover() {
        map.invalidateSize(true);
        // Calcula um zoom adequado para cobrir a área.
        const zoom = map.getBoundsZoom(bounds, true);

        // Define a vista com centro no meio da imagem e o zoom calculado.
        map.setView(center, zoom, { animate: false });

        map.setMaxBounds(bounds);
    }

    //Encaixe inicial da imagem no mapa.
    fitContain();

    // Segundo ajusto ligeiramente atrasado, para deixar o html estabilizar primeiro
    setTimeout(fitContain, 200);

    // Variável para debounce do resize da janela.
    let resizeTimeout;

    // Quando a janela muda de tamanho, espera um pouco e depois recalcula o tamanho do mapa.
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            map.invalidateSize(true);
        }, 100);
    });

    // Vai buscar o container real do mapa no DOM.
    const container = document.getElementById(elId);

    // Se existir ResizeObserver, observa diretamente o container.
    // Isto é útil quando o mapa muda de tamanho por alterações de layout
    // internas (ex.: navbar escondida/mostrada), mesmo sem resize da janela.
    if (container && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(() => {
            map.invalidateSize(true);
        });
        observer.observe(container);
    }

    // Função auxiliar que traduz o estado operacional numa cor.
    // Serve para não repetir o mesmo switch em vários sítios.
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

            // Cor default caso não haja estado reconhecido.
            default:
                return '#3388ff';
        }
    }

    // Função auxiliar que traduz o location_type de uma localização num ícone, e muda-o consoante o status_type
    function getLocationIcon(locationTypeId, statusType) {
        const base = opts.iconsBaseUrl || '';

        if (Number(locationTypeId) === 1) {
            let file = 'house-default.png';

            switch (statusType) {
                case 'RED':
                    file = 'house-red.png';
                    break;

                case 'YELLOW':
                    file = 'house-yellow.png';
                    break;

                case 'GREEN':
                    file = 'house-green.png';
                    break;
            }

            return L.icon({
                iconUrl: base + '/' + file,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });
        } else if(Number(locationTypeId) === 7) {
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
        // Validação mínima:
        // tem de existir um objeto com "features" e esse campo deve ser array.
        if (!featureCollection || !Array.isArray(featureCollection.features)) {
            console.warn('initCopMapReadOnly: featureCollection inválida');
            return;
        }

        // Se já existir uma layer desenhada anteriormente remove-a antes de voltar a desenhar
        if (geoJsonLayer) {
            map.removeLayer(geoJsonLayer);
        }

        //Cria uma layer Leaflet a partir do GeoJSON, o Leaflet trata automaticamente de Point / LineString / Polygon, etc.
        geoJsonLayer = L.geoJSON(featureCollection, {
            // Corre uma vez por cada feature desenhada.
            // Aqui normalmente ligamos popups, eventos, tooltips, etc.
            onEachFeature: function (feature, layer) {
                const item = feature.properties || {};

                // Tenta ler primeiro os campos "bonitos" vindos do backend,
                // e só depois os nomes alternativos antigos.
                const locationType = item.location_type_name ?? item.location_type ?? '—';
                const statusType = item.status_type_name ?? item.status_type ?? '—';

                // Constrói o HTML do popup.
                const popupHtml = `
                    <strong>${item.name ?? 'Sem nome'}</strong><br>
                    Tipo: ${locationType}<br>
                    Estado: ${statusType}<br>
                    Crítico: ${Number(item.is_critical) ? 'Sim' : 'Não'}<br>
                    ${item.notes ? `<br>Notas: ${item.notes}` : ''}
                `;

                // Associa o popup à feature/layer.
                layer.bindPopup(popupHtml);
            },

            // Define o estilo de linhas e polígonos.
            // Isto não afeta pontos quando usamos pointToLayer.
            style: function (feature) {
                const item = feature.properties || {};

                // Vai buscar o estado da feature.
                const statusType = item.status_type_name ?? item.status_type ?? null;

                // Traduz estado em cor.
                const color = getStatusColor(statusType);

                // Devolve o estilo gráfico da feature.
                return {
                    color: color,
                    weight: 3,
                    fillOpacity: 0.2
                };
            },

            //AQUI transforma-se os markers "points"!
            pointToLayer: function (feature, latlng) {
                const item = feature.properties || {};
                const statusType = item.status_type_name ?? item.status_type ?? null;
                const color = getStatusColor(statusType);

                const customIcon = getLocationIcon(item.location_type_id, statusType);

                // se existir ícone, usa marker com ícone
                if (customIcon) {
                    return L.marker(latlng, {
                        icon: customIcon
                    });
                }

                // se não existir ícone, usa círculo normal
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
    // Pode usar uma featureCollection já embebida nas opções
    // OU ir buscar os dados por fetch a um endpoint.
    function loadFeatures() {

        // Caso 1:
        // os dados já vieram no próprio objeto opts.
        if (opts.featureCollection && Array.isArray(opts.featureCollection.features)) {
            renderFeatures(opts.featureCollection);
            return;
        }

        // Caso 2:
        // os dados têm de ser pedidos ao backend/frontend via URL.
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

                    // Quando o JSON chega, desenha as features no mapa.
                    renderFeatures(data);
                })
                .catch(function (error) {
                    console.error('Erro ao carregar itens do mapa:', error);
                });

            return;
        }

        // Se não houver dados embebidos nem URL para fetch,
        // o mapa fica só com a imagem de fundo.
        console.warn('initCopMapReadOnly: nem featureCollection nem locationsIndexUrl foram fornecidos');
    }

    // Arranca o carregamento inicial das features.
    loadFeatures();

    // Devolve uma pequena API pública do mapa.
    // Isto permite, noutro ponto do código, fazer por exemplo:
    // window.copMapReadOnly.fitContain()
    // window.copMapReadOnly.fitCover()
    // window.copMapReadOnly.reload()
    return {
        map: map,
        fitContain: fitContain,
        fitCover: fitCover,
        reload: loadFeatures
    };
};