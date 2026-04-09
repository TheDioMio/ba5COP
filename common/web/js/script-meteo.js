// URL da action do controller (proxy para a API externa)
const apiURL = "index.php?r=dashboard/meteo";


// Converte graus (0–360) em direção cardinal (N, NE, E, etc.)
function getWindDirection(deg) {
    if (deg === null || deg === undefined) return "--";

    const directions = ["N", "NE", "E", "SE", "S", "SW", "W", "NW"];
    const index = Math.round(deg / 45) % 8;

    return directions[index];
}


// Converte ISO datetime para hora local PT (HH:mm)
function formatLocalTimePT(isoString) {
    if (!isoString) return "--:--";

    const date = new Date(isoString);

    return date.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    });
}


// Vai buscar a hora ZULU / UTC diretamente do datetime da API
function formatZuluTime(isoString) {
    if (!isoString) return "--:-- Z";

    const date = new Date(isoString);

    const hours = String(date.getUTCHours()).padStart(2, '0');
    const minutes = String(date.getUTCMinutes()).padStart(2, '0');

    return `${hours}:${minutes} Z`;
}

function formatOperationalDate(isoString) {
    if (!isoString) return "--";

    const date = new Date(isoString);

    const day = String(date.getUTCDate()).padStart(2, '0');

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
    if (!visib) return "--";

    const visibStr = String(visib);

    // caso "6+ ou outros sla+"
    if (visibStr.includes("+")) {
        const val = parseFloat(visibStr);
        const km = Math.round(val * 1.609);
        return "≥ " + km + " km";
    }

    // caso fração (ex: 1/2)
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

    // número normal
    const val = parseFloat(visibStr);
    if (!isNaN(val)) {
        const km = Math.round(val * 1.609);
        return km + " km";
    }

    return visibStr;
}


// Determina o estado operacional da meteorologia
function getMeteoStatus(meteo) {
    const raw = meteo.rawOb || "";
    const fltCat = meteo.fltCat || "";
    const wind = Number(meteo.wspd || 0);
    const cover = meteo.cover || "";

    // PRIORIDADE MÁXIMA → CAVOK
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

// Pedido AJAX à action do controller
$.ajax({
    method: "GET",
    url: apiURL,

    success: function (response) {
        // Validação básica
        if (!response || !response.data || !response.data[0]) {
            console.error("Sem dados meteorológicos.");
            return;
        }

        // Objeto com os dados METAR
        const meteoBA5 = response.data[0];

        // Direção cardinal do vento
        const windDir = getWindDirection(meteoBA5.wdir);

        // Estado operacional do KPI
        const status = getMeteoStatus(meteoBA5);

        // Resumo do card principal
        const summary =
            (meteoBA5.cover || "--") + " · " +
            (meteoBA5.temp ?? "--") + "°C · " +
            "Vento " + windDir + " " + (meteoBA5.wspd ?? "--") + " kt";


        // ================================
        // KPI (CARD PRINCIPAL)
        // ================================
        document.getElementById("meteoStatus").innerText = status.label;
        document.getElementById("meteoStatus").className = "cop-kpi-value " + status.className;
        document.getElementById("meteoSummary").innerText = summary;
        document.getElementById("meteoTime").innerText =
            formatLocalTimePT(meteoBA5.reportTime) + " PT · " +
            formatZuluTime(meteoBA5.reportTime);


        // ================================
        // MODAL - KPI SUPERIOR
        // ================================
        document.getElementById("meteoModalStatus").innerText = status.label;
        document.getElementById("meteoModalStatus").className = "cop-modal-kpi-value " + status.className;

        document.getElementById("meteoModalFltCat").innerText = meteoBA5.fltCat ?? "--";
        document.getElementById("meteoModalReportTime").innerHTML =
            formatOperationalDate(meteoBA5.reportTime) + "<br>" +
            formatZuluTime(meteoBA5.reportTime) + " · " +
            formatLocalTimePT(meteoBA5.reportTime) + " PT";
        document.getElementById("meteoModalStation").innerText = meteoBA5.icaoId ?? "--";


        // ================================
        // MODAL - CONDIÇÃO GERAL
        // ================================
        document.getElementById("meteoModalCover").innerText = meteoBA5.cover ?? "--";
        document.getElementById("meteoModalVisib").innerText = visibilityToKm(meteoBA5.visib);
        document.getElementById("meteoModalType").innerText = meteoBA5.metarType ?? "--";


        // ================================
        // MODAL - VENTO
        // ================================
        document.getElementById("meteoModalWdir").innerText = windDir;
        document.getElementById("meteoModalWspd").innerText = (meteoBA5.wspd ?? "--") + " kt";
        document.getElementById("meteoModalWdirDeg").innerText = (meteoBA5.wdir ?? "--") + "°";
        document.getElementById("meteoModalWindSummary").innerText =
            "Vento " + windDir + " a " + (meteoBA5.wspd ?? "--") + " kt";


        // ================================
        // MODAL - ATMOSFERA
        // ================================
        document.getElementById("meteoModalTemp").innerText = (meteoBA5.temp ?? "--") + "°C";
        document.getElementById("meteoModalDewp").innerText = (meteoBA5.dewp ?? "--") + "°C";
        document.getElementById("meteoModalAltim").innerText = (meteoBA5.altim ?? "--") + " hPa";
        document.getElementById("meteoModalElev").innerText = (meteoBA5.elev ?? "--") + " m";


        // ================================
        // MODAL - ESTAÇÃO
        // ================================
        document.getElementById("meteoModalIcao").innerText = meteoBA5.icaoId ?? "--";
        document.getElementById("meteoModalName").innerText = meteoBA5.name ?? "--";
        document.getElementById("meteoModalLat").innerText = meteoBA5.lat ?? "--";
        document.getElementById("meteoModalLon").innerText = meteoBA5.lon ?? "--";


        // ================================
        // MODAL - METAR BRUTO
        // ================================
        document.getElementById("meteoModalRawOb").innerText = meteoBA5.rawOb ?? "--";
    },

    error: function (error) {
        console.error("ERRO-API: Erro ao pedir os dados à API:", error);

        document.getElementById("meteoStatus").innerText = "ERRO";
        document.getElementById("meteoStatus").className = "cop-kpi-value is-danger";
        document.getElementById("meteoSummary").innerText = "sem dados meteorológicos";
        document.getElementById("meteoTime").innerText = "--:--";
    }
});