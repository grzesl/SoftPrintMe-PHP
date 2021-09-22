<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

$connector = new NetworkPrintConnector("192.168.1.100", 9100);
$printer = new Printer($connector);

try {
    $date = new DateTime();
    $datestr = $date->format('Y-m-d H:i:s');

    $printer->text("Printout date:" . $datestr . "\n");
    
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("This is SoftPrintMe\n");
    $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
    $printer->text("Integration with PHP Mike42\\Escpos\n");


    $tux = EscposImage::load("tux.png", false);
    
    $printer->bitImage($tux);
    $printer->text("Regular Tux.\n");
    $printer->feed();

    $printer->selectPrintMode();
    $printer->qrCode("https://softprint.me");
    $printer->feed();
    $printer->barcode("1234567890");

    $printer->selectPrintMode(); //uninit printout
    $printer->setJustification(Printer::JUSTIFY_LEFT);

    $printer->cut();
    

    flush(); // flush tcp connection
    sleep(1); // take some time before disconnect
}
finally{ 
    $printer->close();
}