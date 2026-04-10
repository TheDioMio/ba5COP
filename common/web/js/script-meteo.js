// URL da action do controller para METAR atual
const apiURL = "index.php?r=dashboard/meteo";

// URL da action do controller para TAF / previsão 24H
const tafApiURL = "index.php?r=dashboard/taf";


// ================================
// FUNÇÕES AUXILIARES GERAIS
// ================================

// Converte graus (0–360) em direção cardinal (N, NE, E, etc.)
function getWindDirection(deg) {
    if (deg === null || deg === undefined || deg === "VRB") return deg || "--";

    const directions = ["N", "NE", "E", "SE", "S", "SW", "W", "NW"];
    const index = Math.round(deg / 45) % 8;

    return directions[index];
}


// Converte ISO datetime para hora local PT (HH:mm)
function formatLocalTimePT(isoString) {
    if (!isoString) return "--:--";

    const date = new Date(isoString);

    return date.toLocaleTimeString("pt-PT", {
        hour: "2-digit",
        minute: "2-digit"
    });
}


// Vai buscar a hora ZULU / UTC diretamente do datetime da API
function formatZuluTime(isoString) {
    if (!isoString) return "--:-- Z";

    const date = new Date(isoString);

    const hours = String(date.getUTCHours()).padStart(2, "0");
    const minutes = String(date.getUTCMinutes()).padStart(2, "0");

    return `${hours}:${minutes} Z`;
}


// Formato operacional tipo 09ABR26
function formatOperationalDate(isoString) {
    if (!isoString) return "--";

    const date = new Date(isoString);

    const day = String(date.getUTCDate()).padStart(2, "0");

    const months = [
        "JAN", "FEV", "MAR", "ABR", "MAI", "JUN",
        "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"
    ];

    const month = months[date.getUTCMonth()];
    const year = String(date.getUTCFullYear()).slice(-2);

    return `${day}${month}${year}`;
}


// Converte timestamp unix para HH:mmZ
function formatUnixZuluTime(unixTime) {
    if (!unixTime) return "--:-- Z";

    const date = new Date(unixTime * 1000);

    const hours = String(date.getUTCHours()).padStart(2, "0");
    const minutes = String(date.getUTCMinutes()).padStart(2, "0");

    return `${hours}:${minutes} Z`;
}


// Converte timestamp unix para HH:mm PT
function formatUnixLocalTimePT(unixTime) {
    if (!unixTime) return "--:--";

    const date = new Date(unixTime * 1000);

    return date.toLocaleTimeString("pt-PT", {
        hour: "2-digit",
        minute: "2-digit"
    });
}

function translateWx(wx) {
    if (!wx) return "sem fenómeno significativo";

    const normalized = wx.toUpperCase();

    if (normalized === "SHRA") return "aguaceiros";
    if (normalized === "-DZ BR") return "chuvisco e neblina";
    if (normalized === "+DZ BR") return "chuvisco forte e neblina";
    if (normalized === "BR") return "neblina";
    if (normalized === "FG") return "nevoeiro";

    return wx;
}


// Formato operacional tipo 09ABR26 para timestamp unix
function formatUnixOperationalDate(unixTime) {
    if (!unixTime) return "--";

    const date = new Date(unixTime * 1000);

    const day = String(date.getUTCDate()).padStart(2, "0");

    const months = [
        "JAN", "FEV", "MAR", "ABR", "MAI", "JUN",
        "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"
    ];

    const month = months[date.getUTCMonth()];
    const year = String(date.getUTCFullYear()).slice(-2);

    return `${day}${month}${year}`;
}


// Converte visibilidade da API para km
function visibilityToKm(visib) {
    if (!visib && visib !== 0) return "--";

    const visibStr = String(visib).trim();

    // Caso "6+"
    if (visibStr.includes("+")) {
        const val = parseFloat(visibStr);
        const km = Math.round(val * 1.609);
        return "≥ " + km + " km";
    }

    // Caso fração tipo "1/2"
    if (visibStr.includes("/")) {
        const parts = visibStr.split("/");
        if (parts.length === 2) {
            const numerator = parseFloat(parts[0]);
            const denominator = parseFloat(parts[1]);

            if (!isNaN(numerator) && !isNaN(denominator) && denominator !== 0) {
                const val = numerator / denominator;
                const km = Math.round(val * 1.609);
                return km + " km";
            }
        }
    }

    // Número simples
    const val = parseFloat(visibStr);
    if (!isNaN(val)) {
        const km = Math.round(val * 1.609);
        return km + " km";
    }

    return visibStr;
}


// ================================
// LÓGICA METAR ATUAL
// ================================

// Determina o estado operacional da meteorologia atual
function getMeteoStatus(meteo) {
    const raw = meteo.rawOb || "";
    const fltCat = meteo.fltCat || "";
    const wind = Number(meteo.wspd || 0);
    const cover = meteo.cover || "";

    // CAVOK + VFR = normal
    if (cover === "CAVOK" && fltCat === "VFR") {
        return {
            label: "NORMAL",
            className: "is-success"
        };
    }

    // Situações críticas
    if (
        raw.includes("TSRA") ||
        raw.includes("FG") ||
        raw.includes("G30KT") ||
        fltCat === "IFR" ||
        fltCat === "LIFR"
    ) {
        return {
            label: "CRÍTICO",
            className: "is-danger"
        };
    }

    // Situações de alerta
    if (
        raw.includes("SHRA") ||
        raw.includes("BR") ||
        wind >= 15 ||
        fltCat === "MVFR"
    ) {
        return {
            label: "ALERTA",
            className: "is-warning"
        };
    }

    return {
        label: "NORMAL",
        className: "is-success"
    };
}


// ================================
// LÓGICA TAF / PREVISÃO 24H
// ================================

// Texto curto do tipo de alteração TAF
function getForecastChangeLabel(fcst) {
    if (!fcst) return "BASE";

    // PROB + TEMPO
    if (fcst.fcstChange === "TEMPO" && fcst.probability) {
        return "PROB " + fcst.probability + "% TEMPO";
    }

    // Só PROB (caso raro)
    if (fcst.fcstChange === "PROB" && fcst.probability) {
        return "PROB " + fcst.probability + "%";
    }

    // Outros casos (BECMG, TEMPO sem prob, etc.)
    if (fcst.fcstChange) {
        return fcst.fcstChange;
    }

    return "BASE";
}


// Resume nuvens de forma legível
function getCloudSummary(fcst) {
    if (!fcst || !fcst.clouds || !fcst.clouds.length) {
        return "sem nebulosidade significativa";
    }

    return fcst.clouds.map(function (cloud) {
        let txt = cloud.cover || "";

        if (cloud.base !== null && cloud.base !== undefined) {
            txt += " " + cloud.base + " ft";
        }

        if (cloud.type) {
            txt += " " + cloud.type;
        }

        return txt.trim();
    }).join(" / ");
}

function getForecastOperationalSummary(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) {
        return {
            base: {
                label: "SEM DADOS",
                className: "is-warning",
                detail: "--"
            },
            risk: {
                label: "SEM DADOS",
                className: "is-warning",
                detail: "--"
            },
            trend: {
                label: "SEM DADOS",
                className: "is-warning",
                detail: "--"
            }
        };
    }

    const fcsts = taf.fcsts;

    // =========================
    // 1. ESTADO BASE (primeiro bloco)
    // =========================
    const first = fcsts[0];

    const firstRisk = getForecastBlockRisk(first);

    let base = {
        label: "Condição inicial estável",
        className: "is-success",
        detail: ""
    };

    if (firstRisk === 2) {
        base.label = "Condição inicial condicionada";
        base.className = "is-warning";
    }

    if (firstRisk === 3) {
        base.label = "Condição inicial crítica";
        base.className = "is-danger";
    }

    const windDir = getWindDirection(first.wdir);
    const windSpd = first.wspd ?? "--";
    const vis = visibilityToKm(first.visib);

    base.detail = `Vento ${windDir} ${windSpd} kt · Vis ${vis}`;


    // =========================
    // 2. PRINCIPAL AGRAVAMENTO (pior bloco)
    // =========================
    let worstBlock = first;
    let worstScore = getForecastBlockRisk(first);

    fcsts.forEach(function (fcst) {
        const score = getForecastBlockRisk(fcst);
        if (score > worstScore) {
            worstScore = score;
            worstBlock = fcst;
        }
    });

    let risk = {
        label: "Sem agravamento relevante",
        className: "is-success",
        detail: "Sem alterações significativas nas próximas 24h"
    };

    if (worstScore === 2) {
        risk.label = "Agravamento moderado";
        risk.className = "is-warning";
    }

    if (worstScore === 3) {
        risk.label = "Agravamento crítico";
        risk.className = "is-danger";
    }

    const from = formatUnixZuluTime(worstBlock.timeFrom);
    const to = formatUnixZuluTime(worstBlock.timeTo);

    risk.detail = `${from}–${to} · ${describeForecastBlock(worstBlock)}`;


    // =========================
    // 3. EVOLUÇÃO (comparação início vs fim)
    // =========================
    const firstScore = getForecastBlockRisk(fcsts[0]);
    const lastScore = getForecastBlockRisk(fcsts[fcsts.length - 1]);

    let trend = {
        label: "Sem alteração",
        className: "is-success",
        detail: "Condições estáveis ao longo do período"
    };

    if (lastScore > firstScore) {
        trend = {
            label: "A degradar",
            className: "is-danger",
            detail: "Os blocos finais indicam agravamento face ao início do período"
        };
    }

    if (lastScore < firstScore) {
        trend = {
            label: "A melhorar",
            className: "is-positive",
            detail: "Os blocos finais indicam melhoria face ao início do período"
        };
    }

    return { base, risk, trend };
}




// Cria frase legível por bloco: "de X a Y vai estar assim..."
function describeForecastBlock(fcst) {
    const parts = [];

    const windDir = getWindDirection(fcst.wdir);
    const windSpd = fcst.wspd;
    const gust = fcst.wgst;
    const visib = fcst.visib;
    const wx = translateWx(fcst.wxString);
    const clouds = getCloudSummary(fcst);

    // Tipo de bloco
    parts.push(getForecastChangeLabel(fcst));

    // Vento
    if (windSpd !== null && windSpd !== undefined) {
        let windText = "vento";

        if (windDir && windDir !== "--") {
            windText += " " + windDir;
        }

        windText += " " + windSpd + " kt";

        if (gust) {
            windText += ", rajadas até " + gust + " kt";
        }

        parts.push(windText);
    }

    // Visibilidade
    if (visib !== null && visib !== undefined && visib !== "") {
        parts.push("visibilidade " + visibilityToKm(visib));
    }

    // Fenómenos
    if (wx) {
        parts.push(wx);
    } else {
        parts.push("sem fenómeno significativo");
    }

    // Nuvens
    parts.push(clouds);

    return parts.join(" · ");
}


// Dá um score ao bloco para avaliar risco
function getForecastBlockRisk(fcst) {
    const wx = (fcst.wxString || "").toUpperCase();
    const gust = Number(fcst.wgst || 0);
    const wind = Number(fcst.wspd || 0);
    const vis = parseVisibilityValue(fcst.visib);

    if (
        wx.includes("TS") ||
        wx.includes("FG") ||
        gust >= 30 ||
        (vis !== null && vis < 3)
    ) {
        return 3;
    }

    if (
        wx.includes("SHRA") ||
        wx.includes("RA") ||
        wx.includes("BR") ||
        gust >= 20 ||
        wind >= 15 ||
        (vis !== null && vis < 6)
    ) {
        return 2;
    }

    return 1;
}

function parseVisibilityValue(visib) {
    if (visib === null || visib === undefined || visib === "") return null;

    if (typeof visib === "number") return visib;

    const v = String(visib).trim();

    if (v.includes("+")) {
        const parsed = parseFloat(v);
        return isNaN(parsed) ? null : parsed;
    }

    if (v.includes("/")) {
        const parts = v.split("/");
        if (parts.length === 2) {
            const numerator = parseFloat(parts[0]);
            const denominator = parseFloat(parts[1]);
            if (!isNaN(numerator) && !isNaN(denominator) && denominator !== 0) {
                return numerator / denominator;
            }
        }
        return null;
    }

    const parsed = parseFloat(v);
    return isNaN(parsed) ? null : parsed;
}

// Estado global da previsão
function getForecastStatus(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) {
        return {
            label: "SEM DADOS",
            className: "is-warning"
        };
    }

    let worstScore = 1;

    taf.fcsts.forEach(function (fcst) {
        const score = getForecastBlockRisk(fcst);
        if (score > worstScore) {
            worstScore = score;
        }
    });

    if (worstScore === 3) {
        return {
            label: "CRÍTICO",
            className: "is-danger"
        };
    }

    if (worstScore === 2) {
        return {
            label: "ALERTA",
            className: "is-warning"
        };
    }

    return {
        label: "NORMAL",
        className: "is-success"
    };
}

function applyTrendBoxState(textId, detailId, data) {
    const textEl = document.getElementById(textId);
    const detailEl = document.getElementById(detailId);
    const box = textEl.closest(".cop-trend-box-modal");

    textEl.innerText = data.label;
    detailEl.innerText = data.detail;

    box.classList.remove("is-success", "is-warning", "is-danger", "is-positive");
    box.classList.add(data.className);
}

// Vento base
function getBaseWind(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) return "--";

    const first = taf.fcsts[0];
    const dir = getWindDirection(first.wdir);
    const spd = first.wspd ?? "--";

    return `${dir} ${spd} kt`;
}


// Rajada máxima
function getMaxGust(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) return "--";

    const gusts = taf.fcsts
        .map(fcst => Number(fcst.wgst || 0))
        .filter(g => g > 0);

    if (!gusts.length) return "--";

    return Math.max(...gusts) + " kt";
}


// Visibilidade mínima
function getMinVisibility(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) return "--";

    let minVis = null;
    let minOriginal = null;

    taf.fcsts.forEach(function (fcst) {
        const raw = fcst.visib;
        const numeric = parseVisibilityValue(raw);

        if (numeric !== null && (minVis === null || numeric < minVis)) {
            minVis = numeric;
            minOriginal = raw;
        }
    });

    if (minOriginal === null) return "--";

    return visibilityToKm(minOriginal);
}


// Fenómeno crítico
function getCriticalWeather(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) return "--";

    let criticalBlock = null;
    let criticalScore = 1;

    taf.fcsts.forEach(function (fcst) {
        const score = getForecastBlockRisk(fcst);
        if (score > criticalScore) {
            criticalScore = score;
            criticalBlock = fcst;
        }
    });

    if (!criticalBlock) {
        return "Sem fenómeno relevante";
    }

    if (criticalBlock.wxString) {
        return criticalBlock.wxString;
    }

    if (parseVisibilityValue(criticalBlock.visib) !== null && parseVisibilityValue(criticalBlock.visib) < 6) {
        return "Visibilidade reduzida";
    }

    if (Number(criticalBlock.wgst || 0) >= 20 || Number(criticalBlock.wspd || 0) >= 15) {
        return "Vento significativo";
    }

    return "Condição crítica por teto/visibilidade";
}


// Timeline em formato operacional por blocos
function buildForecastTimelineHtml(taf) {
    if (!taf || !taf.fcsts || !taf.fcsts.length) {
        return "<div class='cop-empty-state'>Sem previsão disponível.</div>";
    }

    let html = "";

    taf.fcsts.forEach(function (fcst) {
        const fromZulu = formatUnixZuluTime(fcst.timeFrom);
        const toZulu = formatUnixZuluTime(fcst.timeTo);
        const fromPt = formatUnixLocalTimePT(fcst.timeFrom);
        const toPt = formatUnixLocalTimePT(fcst.timeTo);

        html += `
            <div class="cop-system-row">
                <div class="cop-system-main">
                    <span class="cop-system-name">
                        ${fromZulu} → ${toZulu} · ${fromPt} → ${toPt} PT
                    </span>
                    <small class="cop-system-detail">
                        ${describeForecastBlock(fcst)}
                    </small>
                </div>
            </div>
        `;
    });

    return html;
}


// ================================
// AJAX 1 - METAR ATUAL
// ================================

$.ajax({
    method: "GET",
    url: apiURL,

    success: function (response) {
        if (!response || !response.data || !response.data[0]) {
            console.error("Sem dados meteorológicos.");
            return;
        }

        const meteoBA5 = response.data[0];
        const windDir = getWindDirection(meteoBA5.wdir);
        const status = getMeteoStatus(meteoBA5);

        const summary =
            (meteoBA5.cover || "--") + " · " +
            (meteoBA5.temp ?? "--") + "°C · " +
            "Vento " + windDir + " " + (meteoBA5.wspd ?? "--") + " kt";

        // KPI
        document.getElementById("meteoStatus").innerText = status.label;
        document.getElementById("meteoStatus").className = "cop-kpi-value " + status.className;
        document.getElementById("meteoSummary").innerText = summary;
        document.getElementById("meteoTime").innerText =
            formatLocalTimePT(meteoBA5.reportTime) + " PT · " +
            formatZuluTime(meteoBA5.reportTime);

        // Modal - METAR
        document.getElementById("meteoModalStatus").innerText = status.label;
        document.getElementById("meteoModalStatus").className = "cop-modal-kpi-value " + status.className;

        document.getElementById("meteoModalFltCat").innerText = meteoBA5.fltCat ?? "--";
        document.getElementById("meteoModalReportTime").innerHTML =
            formatOperationalDate(meteoBA5.reportTime) + "<br>" +
            formatZuluTime(meteoBA5.reportTime) + " · " +
            formatLocalTimePT(meteoBA5.reportTime) + " PT";

        document.getElementById("meteoModalStation").innerText = meteoBA5.icaoId ?? "--";

        document.getElementById("meteoModalCover").innerText = meteoBA5.cover ?? "--";
        document.getElementById("meteoModalVisib").innerText = visibilityToKm(meteoBA5.visib);
        document.getElementById("meteoModalType").innerText = meteoBA5.metarType ?? "--";

        document.getElementById("meteoModalWdir").innerText = windDir;
        document.getElementById("meteoModalWspd").innerText = (meteoBA5.wspd ?? "--") + " kt";
        document.getElementById("meteoModalWdirDeg").innerText = (meteoBA5.wdir ?? "--") + "°";
        document.getElementById("meteoModalWindSummary").innerText =
            "Vento " + windDir + " a " + (meteoBA5.wspd ?? "--") + " kt";

        document.getElementById("meteoModalTemp").innerText = (meteoBA5.temp ?? "--") + "°C";
        document.getElementById("meteoModalDewp").innerText = (meteoBA5.dewp ?? "--") + "°C";
        document.getElementById("meteoModalAltim").innerText = (meteoBA5.altim ?? "--") + " hPa";
        document.getElementById("meteoModalElev").innerText = (meteoBA5.elev ?? "--") + " m";

        document.getElementById("meteoModalIcao").innerText = meteoBA5.icaoId ?? "--";
        document.getElementById("meteoModalName").innerText = meteoBA5.name ?? "--";
        document.getElementById("meteoModalLat").innerText = meteoBA5.lat ?? "--";
        document.getElementById("meteoModalLon").innerText = meteoBA5.lon ?? "--";

        document.getElementById("meteoModalRawOb").innerText = meteoBA5.rawOb ?? "--";
    },

    error: function (error) {
        console.error("ERRO-API: Erro ao pedir os dados METAR:", error);

        document.getElementById("meteoStatus").innerText = "ERRO";
        document.getElementById("meteoStatus").className = "cop-kpi-value is-danger";
        document.getElementById("meteoSummary").innerText = "sem dados meteorológicos";
        document.getElementById("meteoTime").innerText = "--:--";
    }
});


// ================================
// AJAX 2 - TAF / PREVISÃO 24H
// ================================
$.ajax({
    method: "GET",
    url: tafApiURL,

    success: function (response) {
        if (!response || !response.data || !response.data[0]) {
            console.error("Sem dados TAF.");
            return;
        }

        const taf = response.data[0];
        const forecastStatus = getForecastStatus(taf);
        const forecastSummary = getForecastOperationalSummary(taf);

        // Cabeçalho
        document.getElementById("meteoForecastStatus").innerText = forecastStatus.label;
        document.getElementById("meteoForecastStatus").className =
            "cop-modal-kpi-value " + forecastStatus.className;

        document.getElementById("meteoForecastIssued").innerHTML =
            formatOperationalDate(taf.issueTime) + "<br>" +
            formatZuluTime(taf.issueTime) + " · " +
            formatLocalTimePT(taf.issueTime) + " PT";

        document.getElementById("meteoForecastValidity").innerHTML =
            formatUnixOperationalDate(taf.validTimeFrom) + " " +
            formatUnixZuluTime(taf.validTimeFrom) + " → " +
            formatUnixOperationalDate(taf.validTimeTo) + " " +
            formatUnixZuluTime(taf.validTimeTo);

        document.getElementById("meteoForecastStation").innerText = taf.icaoId ?? "--";

        // Síntese operacional
        applyTrendBoxState("meteoForecastBase", "meteoForecastBaseDetail", forecastSummary.base);
        applyTrendBoxState("meteoForecastRisk", "meteoForecastRiskDetail", forecastSummary.risk);
        applyTrendBoxState("meteoForecastTrend", "meteoForecastTrendDetail", forecastSummary.trend);

        // Timeline dos blocos
        document.getElementById("meteoForecastTimeline").innerHTML = buildForecastTimelineHtml(taf);

        // Resumo técnico
        document.getElementById("meteoForecastWind").innerText = getBaseWind(taf);
        document.getElementById("meteoForecastMaxGust").innerText = getMaxGust(taf);
        document.getElementById("meteoForecastMinVisib").innerText = getMinVisibility(taf);
        document.getElementById("meteoForecastWx").innerText = getCriticalWeather(taf);

        // Localização
        document.getElementById("meteoForecastIcao").innerText = taf.icaoId ?? "--";
        document.getElementById("meteoForecastName").innerText = taf.name ?? "--";
        document.getElementById("meteoForecastLat").innerText = taf.lat ?? "--";
        document.getElementById("meteoForecastLon").innerText = taf.lon ?? "--";

        // TAF bruto
        document.getElementById("meteoForecastRaw").innerText = taf.rawTAF ?? "--";
    },

    error: function (error) {
        console.error("ERRO-API: Erro ao pedir os dados TAF:", error);

        document.getElementById("meteoForecastStatus").innerText = "ERRO";
        document.getElementById("meteoForecastStatus").className = "cop-modal-kpi-value is-danger";

        document.getElementById("meteoForecastIssued").innerText = "--";
        document.getElementById("meteoForecastValidity").innerText = "--";
        document.getElementById("meteoForecastStation").innerText = "--";

        applyTrendBoxState("meteoForecastBase", "meteoForecastBaseDetail", {
            label: "--",
            detail: "--",
            className: "is-warning"
        });

        applyTrendBoxState("meteoForecastRisk", "meteoForecastRiskDetail", {
            label: "--",
            detail: "--",
            className: "is-warning"
        });

        applyTrendBoxState("meteoForecastTrend", "meteoForecastTrendDetail", {
            label: "--",
            detail: "--",
            className: "is-warning"
        });

        document.getElementById("meteoForecastTimeline").innerHTML =
            "<div class='cop-empty-state'>Sem previsão disponível.</div>";

        document.getElementById("meteoForecastWind").innerText = "--";
        document.getElementById("meteoForecastMaxGust").innerText = "--";
        document.getElementById("meteoForecastMinVisib").innerText = "--";
        document.getElementById("meteoForecastWx").innerText = "--";

        document.getElementById("meteoForecastIcao").innerText = "--";
        document.getElementById("meteoForecastName").innerText = "--";
        document.getElementById("meteoForecastLat").innerText = "--";
        document.getElementById("meteoForecastLon").innerText = "--";

        document.getElementById("meteoForecastRaw").innerText = "--";
    }
});