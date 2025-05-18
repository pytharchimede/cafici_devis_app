<?php

require_once 'model/Database.php';
require_once 'model/Devis.php';
require_once 'model/Client.php';
require_once 'model/Offre.php';

$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

$devisObj = new Devis($pdo);
$clientObj = new Client($pdo);
$offreObj = new Offre($pdo);

// Récupérer les devis
$devis = $devisObj->getAllDevis();
$clients = $clientObj->getAllClients();
$offres = $offreObj->getAllOffres();


$nb_devis = count($devis);
$index_actuel = $nb_devis + 1;

$code_devis = 'CAFI-DEV-PAB-' . $index_actuel;
