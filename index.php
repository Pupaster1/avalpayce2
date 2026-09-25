<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aval Pay Center</title>
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon-avalpay.svg">
    <link rel="stylesheet" href="assets/estilos-avalpay.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@700&family=Plus+Jakarta+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
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
                    <li><a href="index.php" class="enlace-navegacion activo">Inicio</a></li>
                    <li><a href="#" class="enlace-navegacion">¿Qué quieres pagar hoy?</a></li>
                    <li><a href="#" class="enlace-navegacion">Validar mi pago</a></li>
                    <li><a href="#" class="enlace-navegacion">Centro de ayuda</a></li>
                    <li class="desplegable">
                        <a href="#" class="enlace-navegacion">Ingresa <span class="flechita">˅</span></a>
                    </li>
                    <li><a href="#" class="boton boton-contorno">Regístrate</a></li>
                </ul>
            </nav>

            <div class="boton-menu-movil">
                <span>Menú</span>
                <div class="hamburguesa">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </header>

    <main class="contenedor">
        <div class="indicadores">
            <span class="punto punto-azul-oscuro"></span>
            <span class="punto punto-rojo"></span>
            <span class="punto punto-azul-claro"></span>
            <span class="punto punto-verde"></span>
        </div>

        <div class="fila-titulo-busqueda">
            <h1 class="titulo-principal">¿Qué deseas pagar hoy?</h1>

            <div class="contenedor-busqueda">
                <div class="caja-busqueda-relativa">
                    <div class="caja-busqueda">
                        <span class="icono-busqueda" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor"
                                    stroke-width="2"></path>
                                <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round"></path>
                            </svg>
                        </span>
                        <input type="text" id="entradaBusqueda" placeholder="Escribe el nombre del convenio"
                            class="entrada-busqueda" autocomplete="off">
                    </div>
                    <ul id="listaResultados" class="lista-resultados oculto"></ul>
                </div>
                <button id="botonBuscar" class="boton boton-primario boton-buscar" aria-label="Buscar">
                    <svg class="icono-boton-buscar" viewBox="0 0 24 24" width="18" height="18" fill="none"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor"
                            stroke-width="2"></path>
                        <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                    <span class="texto-boton-buscar">Buscar</span>
                </button>
            </div>
        </div>

        <div class="cabecera-pagos-rapidos">
            <h2 class="subtitulo-seccion">Pagos Rápidos</h2>
            <a href="#" class="ver-todas">Ver todas &gt;</a>
        </div>

        <div class="cuadricula-categorias">
            <div class="tarjeta-categoria">
                <div class="contenedor-icono">
                    <img src="assets/img/category25.svg" alt="Obligaciones Aval">
                </div>
                <span class="nombre-categoria">Obligaciones<br>Aval</span>
            </div>

            <div class="tarjeta-categoria">
                <div class="contenedor-icono">
                    <img src="assets/img/category19.svg" alt="Servicios Públicos">
                </div>
                <span class="nombre-categoria">Servicios<br>Públicos</span>
            </div>

            <div class="tarjeta-categoria">
                <div class="contenedor-icono">
                    <img src="assets/img/category6.svg" alt="Conjuntos Residenciales">
                </div>
                <span class="nombre-categoria">Conjuntos<br>Residenciales</span>
            </div>

            <div class="tarjeta-categoria">
                <div class="contenedor-icono">
                    <img src="assets/img/category16.svg" alt="Salud Y Medicina">
                </div>
                <span class="nombre-categoria">Salud<br>Y Medicina</span>
            </div>
        </div>

        <div class="contenedor-banner">
            <picture>
                <source media="(max-width: 768px)" srcset="assets/img/banner-movil.png">
                <img src="assets/img/banner.png" alt="Gana sin tanta vuelta" class="banner-promocional">
            </picture>
        </div>

        <section class="seccion-beneficios">
            <div class="beneficios__contenedor">
                <div class="beneficios__media">
                    <img src="assets/img/chica.png" alt="Beneficios" class="beneficios__imagen-principal">
                    <div class="beneficios__mosaico">
                        <img src="assets/img/mesero.png" alt="Beneficio" class="beneficios__imagen-secundaria">
                        <img src="assets/img/cine.png" alt="Beneficio" class="beneficios__imagen-secundaria">
                    </div>
                </div>

                <div class="beneficios__contenido">
                    <h2 class="beneficios__titulo">Beneficios</h2>
                    <div class="beneficios__indicadores">
                        <span class="punto punto-azul-oscuro"></span>
                        <span class="punto punto-rojo"></span>
                        <span class="punto punto-azul-claro"></span>
                        <span class="punto punto-verde"></span>
                    </div>

                    <h3 class="beneficios__subtitulo">En un sólo lugar</h3>
                    <p class="beneficios__descripcion">
                        Accede a más de 25 mil convenios públicos y privados para pago a nivel nacional 24 horas
                        disponibles.
                    </p>

                    <div class="beneficios__controles">
                        <button type="button" class="beneficios__flecha" aria-label="Anterior">‹</button>
                        <div class="beneficios__puntos" aria-label="Paginación">
                            <span class="beneficios__punto beneficios__punto--activo"></span>
                            <span class="beneficios__punto"></span>
                            <span class="beneficios__punto"></span>
                        </div>
                        <button type="button" class="beneficios__flecha" aria-label="Siguiente">›</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="seccion-recordatorio">
            <div class="recordatorio__contenedor">
                <div class="recordatorio__contenido">
                    <h2 class="recordatorio__titulo">No olvides</h2>
                    <p class="recordatorio__descripcion">
                        Tener a la mano el número de referencia de pago o datos de tú obligación y recursos disponibles
                        para realizar tu pago de manera ágil.
                    </p>
                    <a href="#" class="boton boton-contorno recordatorio__boton">Saber más</a>
                </div>
                <img src="assets/img/chico-telefono.png" alt="No olvides" class="recordatorio__imagen">
            </div>

            <div class="recordatorio__controles">
                <button type="button" class="recordatorio__flecha" aria-label="Anterior">‹</button>
                <div class="recordatorio__puntos" aria-label="Paginación">
                    <span class="recordatorio__punto recordatorio__punto--activo"></span>
                    <span class="recordatorio__punto"></span>
                    <span class="recordatorio__punto"></span>
                </div>
                <button type="button" class="recordatorio__flecha" aria-label="Siguiente">›</button>
            </div>

            <div class="registro__contenedor">
                <h2 class="registro__titulo">Regístrate y realiza tus pagos más rápido</h2>
                <p class="registro__descripcion">Ten acceso a pagos recurrentes e historial de pagos</p>
                <a href="#" class="boton boton-contorno registro__boton">Regístrate</a>
            </div>
        </section>

        <section class="seccion-medios-pago">
            <div class="medios-pago__cabecera">
                <h2 class="medios-pago__titulo">Medios de Pago</h2>
                <p class="medios-pago__descripcion">Los medios de pago son opcionales de acuerdo a lo configurado para
                    cada servicio/convenio.</p>
            </div>

            <div class="medios-pago__tarjetas">
                <div class="medios-pago__tarjeta">
                    <img src="assets/img/aval-group-logo.png" alt="Grupo Aval" class="medios-pago__tarjeta-logo">
                    <h3 class="medios-pago__tarjeta-titulo">Bancos Aval</h3>
                    <p class="medios-pago__tarjeta-texto">Cuatro de los bancos y cuentas corrientes de AV Villas, Banco
                        de Bogotá, Banco de Occidente, Banco Popular.</p>
                </div>
                <div class="medios-pago__tarjeta">
                    <img src="assets/img/pse-logo.png" alt="PSE" class="medios-pago__tarjeta-logo">
                    <h3 class="medios-pago__tarjeta-titulo">Botón PSE</h3>
                    <p class="medios-pago__tarjeta-texto">Cuenta de ahorro y corriente con la red interbancaria
                        habilitada en PSE.</p>
                </div>
                <div class="medios-pago__tarjeta">
                    <img src="assets/img/visa-logo.png" alt="VISA" class="medios-pago__tarjeta-logo">
                    <h3 class="medios-pago__tarjeta-titulo">Tarjetas de crédito</h3>
                    <p class="medios-pago__tarjeta-texto">Realiza tus pagos con tarjeta de crédito o débito. Acepta
                        Visa, MasterCard y demás tarjetas del mercado.</p>
                </div>
            </div>

            <div class="medios-pago__sellos">
                <div class="medios-pago__pie">
                    <div class="medios-pago__pie-col medios-pago__pie-col--izquierda" aria-label="Bancos">
                        <img src="assets/img/bogota-logo.svg" alt="Banco de Bogotá" class="medios-pago__banco-logo">
                        <img src="assets/img/logo-v-banco-occidente.svg" alt="Banco de Occidente"
                            class="medios-pago__banco-logo">
                        <img src="assets/img/logo-v-banco-popular.png" alt="Banco Popular"
                            class="medios-pago__banco-logo">
                        <img src="assets/img/logo-v-banco-avvillas.svg" alt="AV Villas" class="medios-pago__banco-logo">
                    </div>

                    <div class="medios-pago__pie-col medios-pago__pie-col--centro" aria-label="Contacto">
                        <img src="assets/img/aval-group-logo.png" alt="Grupo Aval" class="medios-pago__aval-logo">
                        <p class="medios-pago__linea">Línea nacional: 01 8000 51 2825</p>
                        <p class="medios-pago__linea">Línea Bogotá: (601) 7432626</p>
                    </div>

                    <div class="medios-pago__pie-col medios-pago__pie-col--derecha" aria-label="Seguridad">
                        <img src="assets/img/logo-norton@2x.png" alt="Norton" class="medios-pago__sello-seguridad">
                    </div>
                </div>

                <div class="medios-pago__enlaces">
                    <a href="#" class="medios-pago__enlace">Consultar términos y condiciones de uso</a>
                    <a href="#" class="medios-pago__enlace">Recomendaciones de seguridad</a>
                </div>
            </div>
        </section>
    </main>

    <div class="aviso-cookies" id="avisoCookies" role="dialog" aria-live="polite" aria-label="Aviso de cookies">
        <div class="aviso-cookies__contenido">
            <p class="aviso-cookies__texto">
                Este portal web utiliza cookies, para mejorar tu experiencia en nuestro portal. Si no cambias esta
                configuración en tu navegador, entendemos que aceptas el uso de las mismas.
            </p>
            <div class="aviso-cookies__acciones">
                <button type="button" class="aviso-cookies__aceptar" id="botonAceptarCookies">Aceptar</button>
                <a href="#" class="aviso-cookies__enlace">Leer más aquí</a>
            </div>
        </div>
    </div>

    <!-- Modal Simple de Convenio -->
    <div id="modalConvenio" class="modal-simple" role="dialog" aria-hidden="true">
        <div class="modal-simple__fondo"></div>
        <div class="modal-simple__contenedor">
            <div class="modal-simple__icono">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="24" fill="#f0faff"></circle>
                    <path d="M24 14C24 14 18 20 18 24C18 28 24 32 24 32C24 32 30 28 30 24C30 20 24 14 24 14Z"
                        fill="#0b7dbb"></path>
                    <circle cx="24" cy="24" r="3" fill="#ffffff"></circle>
                </svg>
            </div>

            <div class="modal-simple__mensaje">
                <p>Para continuar, ingresa el número de convenio o código de obligación en la barra de búsqueda
                    superior.</p>
            </div>

            <button type="button" class="modal-simple__boton">Aceptar</button>
        </div>
    </div>

    <button type="button" class="boton-ayuda" aria-label="Centro de ayuda">
        <svg class="boton-ayuda__icono" viewBox="0 0 24 24" width="26" height="26" fill="none"
            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"></circle>
            <path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.8.4-1 .8-1 1.7" stroke="white" stroke-width="2"
                stroke-linecap="round"></path>
            <circle cx="12" cy="17" r="1" fill="white"></circle>
        </svg>
    </button>

    <script src="assets/script.js"></script>
</body>

</html>