<?php

require_once '../../vendor/autoload.php';



//use nusoap_server;



$server = new nusoap_server();



// Konfigurasi WSDL

$server->configureWSDL('kalkulator_service', 'urn:kalkulator');



// Definisikan fungsi yang akan ditawarkan

function kali($a, $b) {

    return $a * $b;

}



function tambah($a, $b) {

    return $a + $b;

}



function kurang($a, $b) {

    return $a - $b;

}



function bagi($a, $b) {

    if ($b == 0) {

        return "Error: Pembagian dengan nol tidak diperbolehkan.";

    }

    else {

        return $a / $b;

    }

}



// Daftarkan fungsi ke server

$server->register('kali',

    array('a' => 'xsd:int', 'b' => 'xsd:int'), // Parameter input

    array('return' => 'xsd:int'),              // Parameter output

    'urn:kalkulator',                          // Namespace

    'urn:kalkulator#kali',                     // SOAP Action

    'rpc',                                     // Style

    'encoded',                                 // Use

    'Mengalikan dua angka'                     // Dokumentasi

);



$server->register('tambah',

    array('a' => 'xsd:int', 'b' => 'xsd:int'), // Parameter input

    array('return' => 'xsd:int'),              // Parameter output

    'urn:kalkulator',                          // Namespace

    'urn:kalkulator#tambah',                   // SOAP Action

    'rpc',                                     // Style

    'encoded',                                 // Use

    'Menjumlahkan dua angka'                   // Dokumentasi

);



$server->register('bagi',

    array('a' => 'xsd:int', 'b' => 'xsd:int'), // Parameter input

    array('return' => 'xsd:float'),              // Parameter output

    'urn:kalkulator',                          // Namespace

    'urn:kalkulator#bagi',                   // SOAP Action

    'rpc',                                     // Style

    'encoded',                                 // Use

    'Membagi dua angka'                   // Dokumentasi

);



$server->register('kurang',

    array('a' => 'xsd:int', 'b' => 'xsd:int'), // Parameter input

    array('return' => 'xsd:int'),              // Parameter output

    'urn:kalkulator',                          // Namespace

    'urn:kalkulator#kurang',                   // SOAP Action

    'rpc',                                     // Style

    'encoded',                                 // Use

    'Mengurangi dua angka'                   // Dokumentasi

);



// Proses request

$server->service(file_get_contents("php://input"));

exit;