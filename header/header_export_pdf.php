<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../fpdf186/fpdf.php");
require_once(__DIR__ . "/../phpqrcode/qrlib.php");
require_once(__DIR__ . "/../model/User.php");
require_once(__DIR__ . "/../model/Database.php");
require_once(__DIR__ . "/../model/Devis.php");
require_once(__DIR__ . "/../model/Client.php");
require_once(__DIR__ . "/../model/Offre.php");
require_once(__DIR__ . "/../model/UniteMesure.php");
require_once(__DIR__ . "/../model/Utils.php");


$pdo = Database::getConnection();
$userObj = new User($pdo);
$devisObj = new Devis($pdo);
$clientObj = new Client($pdo);
$offreObj = new Offre($pdo);

$directeurCommercial = $userObj->findDirecteurCommercial();
$directeurGeneral = $userObj->findDirecteurGeneral();

// Vérifiez que le devisId est défini dans la session ou dans l'URL
if (!isset($_SESSION['devisId']) && !isset($_GET['devisId'])) {
    die('ID de devis non défini.');
}

// Prioriser l'ID du devis reçu via $_GET
if (isset($_GET['devisId'])) {
    $devisId = $_GET['devisId'];
    $_SESSION['devisId'] = $devisId;
} else {
    $devisId = $_SESSION['devisId'];
}

// Récupérer les données du devis via la classe
$devis = $devisObj->getDevisById($devisId);
if (!$devis) {
    die('Devis non trouvé.');
}

// Récupérer les lignes du devis via la classe
$lignes = $devisObj->getLignesDevis($devisId);

// Récupérer le client via la classe
$client = $clientObj->getClientById($devis['client_id']);
if (!$client) {
    die('Client non trouvé.');
}

// Récupérer l'offre via la classe
$offre = $offreObj->getOffreById($devis['offre_id']);
if (!$offre) {
    die('Offre non trouvée.');
}
