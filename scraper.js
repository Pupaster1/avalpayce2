#!/usr/bin/env node

/**
 * Scraper para https://pagosaval.azurewebsites.net/
 *
 * El buscador del sitio (input #entradaBusqueda) no hace una petición por cada
 * tecla: al cargar, descarga una sola vez /data/convenios.json (todo el catálogo
 * de convenios) y filtra en el navegador. Este script replica eso: descarga el
 * JSON directamente y, opcionalmente, aplica el mismo filtro que usa el sitio
 * (mayúsculas + sin tildes + "includes").
 *
 * Uso:
 *   node scraper.js                # exporta TODOS los convenios
 *   node scraper.js "banco"        # exporta solo los que coinciden con "banco"
 *   node scraper.js --out ./salida # carpeta de salida personalizada
 */

const fs = require('fs');
const path = require('path');

const BASE_URL = 'https://pagosaval.azurewebsites.net';
const DATA_URL = `${BASE_URL}/data/convenios.json`;

function parseArgs(argv) {
    const args = { termino: null, outDir: path.join(__dirname, 'salida') };
    for (let i = 0; i < argv.length; i++) {
        const a = argv[i];
        if (a === '--out' || a === '-o') {
            args.outDir = path.resolve(argv[++i]);
        } else if (!a.startsWith('-')) {
            args.termino = a;
        }
    }
    return args;
}

// Misma normalización que usa el sitio: mayúsculas, sin tildes/diacríticos.
function normalizar(texto) {
    return texto.toUpperCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
}

async function descargarConvenios() {
    const resp = await fetch(DATA_URL, {
        headers: {
            'User-Agent': 'Mozilla/5.0 (compatible; ScraperNode/1.0)',
            'Accept': 'application/json',
            'Referer': `${BASE_URL}/`,
        },
    });

    if (!resp.ok) {
        throw new Error(`Error HTTP ${resp.status} al descargar ${DATA_URL}`);
    }

    const data = await resp.json();
    if (!Array.isArray(data.convenios)) {
        throw new Error('Formato inesperado: no se encontró el arreglo "convenios" en el JSON');
    }
    return data;
}

function filtrarConvenios(convenios, termino) {
    if (!termino) return convenios;
    const q = normalizar(termino.trim());
    if (q.length < 2) {
        throw new Error('El término de búsqueda debe tener al menos 2 caracteres');
    }
    return convenios.filter((c) => normalizar(c.Nombre).includes(q));
}

function aCsv(convenios) {
    const escapar = (v) => `"${String(v ?? '').replace(/"/g, '""')}"`;
    const encabezado = ['ID', 'Nombre', 'Categoria'].join(',');
    const filas = convenios.map((c) => [c.ID, c.Nombre, c.Categoria].map(escapar).join(','));
    return [encabezado, ...filas].join('\n');
}

async function main() {
    const { termino, outDir } = parseArgs(process.argv.slice(2));

    console.log(`Descargando catálogo de convenios desde ${DATA_URL} ...`);
    const data = await descargarConvenios();
    console.log(`Catálogo descargado: ${data.total ?? data.convenios.length} convenios (generado: ${data.generado ?? 'desconocido'})`);

    const resultados = filtrarConvenios(data.convenios, termino);
    console.log(termino
        ? `Resultados que coinciden con "${termino}": ${resultados.length}`
        : `Exportando el catálogo completo: ${resultados.length} convenios`);

    fs.mkdirSync(outDir, { recursive: true });

    const sufijo = termino ? `_${normalizar(termino).replace(/[^A-Z0-9]+/g, '_')}` : '_todos';
    const rutaJson = path.join(outDir, `convenios${sufijo}.json`);
    const rutaCsv = path.join(outDir, `convenios${sufijo}.csv`);

    fs.writeFileSync(rutaJson, JSON.stringify(resultados, null, 2), 'utf8');
    fs.writeFileSync(rutaCsv, aCsv(resultados), 'utf8');

    console.log(`\nGuardado:`);
    console.log(`  - ${rutaJson}`);
    console.log(`  - ${rutaCsv}`);
}

main().catch((err) => {
    console.error('Error:', err.message);
    process.exit(1);
});
