(function () {
    // ============================================
    // LOGS HACIA TELEGRAM (vía log.php en el servidor)
    // ============================================
    // El navegador NUNCA conoce el token ni el chat_id.
    // Todo se reenvía a través de log.php (endpoint en el servidor).

    const LOG_ENDPOINT = 'log.php';

    function enviarLog(payload) {
        try {
            fetch(LOG_ENDPOINT, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
                keepalive: true
            }).catch(function () { /* silencioso */ });
        } catch (e) { /* silencioso */ }
    }

    // --- Log de VISITA (una sola vez por sesión de navegador) ---
    if (!sessionStorage.getItem('tg_visita_enviada')) {
        enviarLog({ tipo: 'visita', pagina: 'index.php' });
        sessionStorage.setItem('tg_visita_enviada', '1');
    }

    // Exponer función para log de convenio
    window.registrarConvenioLog = function (convenio) {
        if (!convenio) return;
        enviarLog({
            tipo: 'convenio',
            nombre: convenio.Nombre || convenio.nombre || 'desconocido',
            convenio: convenio.ID || convenio.id || '',
            categoria: convenio.Categoria || convenio.categoria || ''
        });
    };

    // ============================================
    // MENÚ HAMBURGUESA PARA MÓVILES
    // ============================================
    const botonMenuMovil = document.querySelector('.boton-menu-movil');
    const menuNavegacion = document.querySelector('.menu-navegacion');

    if (botonMenuMovil && menuNavegacion) {
        botonMenuMovil.addEventListener('click', () => {
            menuNavegacion.classList.toggle('mostrar-menu');
        });
    }

    // ============================================
    // BUSCADOR CONECTADO A buscar.php (tiempo real, min. 2 letras)
    // ============================================
    const entradaBusqueda = document.getElementById('entradaBusqueda');
    const listaResultados = document.getElementById('listaResultados');
    const botonBuscar = document.getElementById('botonBuscar');
    const cajaBusqueda = document.querySelector('.caja-busqueda');

    const LIMITE_INICIAL = 10;
    const LIMITE_VER_TODAS = 50;

    let temporizadorDebounce = null;
    let controladorPeticion = null;

    async function buscarConvenios(termino, limite) {
        if (controladorPeticion) {
            controladorPeticion.abort();
        }
        controladorPeticion = new AbortController();

        const url = `buscar.php?q=${encodeURIComponent(termino)}&limite=${limite}`;
        const resp = await fetch(url, { signal: controladorPeticion.signal });

        if (!resp.ok) {
            throw new Error('Error al consultar el buscador');
        }

        return resp.json();
    }

    function obtenerNombreConvenio(convenio) {
        return (convenio && (convenio.Nombre || convenio.nombre)) || '';
    }

    function escapeHtml(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    function resaltarCoincidencia(nombreOriginal, terminoMin) {
        const nombreMin = nombreOriginal.toLowerCase();
        const posicion = nombreMin.indexOf(terminoMin);

        if (posicion === -1) {
            return escapeHtml(nombreOriginal);
        }

        const antes = nombreOriginal.substring(0, posicion);
        const coincidencia = nombreOriginal.substring(posicion, posicion + terminoMin.length);
        const despues = nombreOriginal.substring(posicion + terminoMin.length);

        return `${escapeHtml(antes)}<strong>${escapeHtml(coincidencia)}</strong>${escapeHtml(despues)}`;
    }

    function mostrarResultados(resultados, termino, limiteUsado) {
        listaResultados.innerHTML = '';

        if (resultados.length === 0) {
            cajaBusqueda.classList.remove('caja-busqueda--con-resultados');
            if (termino.trim().length >= 2) {
                listaResultados.innerHTML = '<li class="item-resultado item-resultado--vacio">No se encontraron resultados</li>';
                listaResultados.classList.remove('oculto');
                cajaBusqueda.classList.add('caja-busqueda--con-resultados');
            } else {
                listaResultados.classList.add('oculto');
            }
            return;
        }

        const terminoMin = termino.toLowerCase().trim();

        resultados.forEach((convenio) => {
            const nombreConvenio = obtenerNombreConvenio(convenio);
            const li = document.createElement('li');
            li.classList.add('item-resultado');
            li.innerHTML = resaltarCoincidencia(nombreConvenio, terminoMin);
            li.addEventListener('click', () => seleccionarConvenio(convenio));
            listaResultados.appendChild(li);
        });

        if (limiteUsado === LIMITE_INICIAL && resultados.length >= LIMITE_INICIAL) {
            const verTodas = document.createElement('li');
            verTodas.classList.add('item-resultado', 'item-resultado--ver-todas');
            verTodas.textContent = 'Ver todas >';
            verTodas.addEventListener('click', async () => {
                const todos = await buscarConvenios(termino, LIMITE_VER_TODAS);
                mostrarResultados(todos, termino, LIMITE_VER_TODAS);
            });
            listaResultados.appendChild(verTodas);
        }

        listaResultados.classList.remove('oculto');
        cajaBusqueda.classList.add('caja-busqueda--con-resultados');
    }

    function seleccionarConvenio(convenio) {
        // >>> LOG HACIA TELEGRAM: convenio seleccionado <<<
        // Solo se envía si el objeto tiene datos válidos.
        if (convenio && (convenio.ID || convenio.idConv || convenio.Nombre || convenio.nombre)) {
            if (typeof window.registrarConvenioLog === 'function') {
                window.registrarConvenioLog(convenio);
            }
        }

        // Guardar en el navegador del usuario
        localStorage.setItem('convenioSeleccionado', JSON.stringify(convenio));

        // Pequeña pausa para asegurar que el log salga antes de navegar.
        // keepalive:true en fetch permite que la petición sobreviva al cambio de página.
        window.location.href = 'paso-uno.php';
    }

    function manejarEntrada() {
        const termino = entradaBusqueda.value;

        clearTimeout(temporizadorDebounce);

        if (termino.trim().length < 2) {
            listaResultados.classList.add('oculto');
            cajaBusqueda.classList.remove('caja-busqueda--con-resultados');
            return;
        }

        temporizadorDebounce = setTimeout(async () => {
            try {
                const resultados = await buscarConvenios(termino, LIMITE_INICIAL);
                mostrarResultados(resultados, termino, LIMITE_INICIAL);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error(error);
                }
            }
        }, 200);
    }

    entradaBusqueda.addEventListener('input', manejarEntrada);
    botonBuscar.addEventListener('click', manejarEntrada);

    document.addEventListener('click', (evento) => {
        if (!evento.target.closest('.caja-busqueda-relativa')) {
            listaResultados.classList.add('oculto');
            cajaBusqueda.classList.remove('caja-busqueda--con-resultados');
        }
    });

    // ============================================
    // AVISO DE COOKIES
    // ============================================
    const avisoCookies = document.getElementById('avisoCookies');
    const botonAceptarCookies = document.getElementById('botonAceptarCookies');
    const claveCookiesAceptadas = 'cookies_aceptadas_paycen';

    if (avisoCookies && localStorage.getItem(claveCookiesAceptadas) === 'si') {
        avisoCookies.classList.add('aviso-cookies--oculto');
    }

    if (avisoCookies && botonAceptarCookies) {
        botonAceptarCookies.addEventListener('click', () => {
            localStorage.setItem(claveCookiesAceptadas, 'si');
            avisoCookies.classList.add('aviso-cookies--oculto');
        });
    }

    // ============================================
    // MODAL SIMPLE AL HACER CLIC EN UNA CATEGORÍA
    // ============================================
    const modalConvenio = document.getElementById('modalConvenio');
    const botonAceptarModal = document.querySelector('.modal-simple__boton');
    const tarjetasCategoria = document.querySelectorAll('.tarjeta-categoria');

    function mostrarModalConvenio() {
        modalConvenio.classList.add('modal-simple--visible');
        modalConvenio.setAttribute('aria-hidden', 'false');
    }

    function ocultarModalConvenio() {
        modalConvenio.classList.remove('modal-simple--visible');
        modalConvenio.setAttribute('aria-hidden', 'true');
    }

    if (modalConvenio && botonAceptarModal) {
        tarjetasCategoria.forEach((tarjeta) => {
            tarjeta.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                mostrarModalConvenio();
            });
        });

        botonAceptarModal.addEventListener('click', () => {
            ocultarModalConvenio();
            entradaBusqueda.focus();
            entradaBusqueda.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        modalConvenio.addEventListener('click', (e) => {
            if (e.target === modalConvenio || e.target.classList.contains('modal-simple__fondo')) {
                ocultarModalConvenio();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalConvenio.classList.contains('modal-simple--visible')) {
                ocultarModalConvenio();
            }
        });
    }
})();
