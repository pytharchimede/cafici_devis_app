<?php
session_start();
include('../model/Database.php');
require_once('../model/Devis.php');


$databaseObj = new Database();
$pdo = $databaseObj->getConnection();
$devisObj = new Devis($pdo);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emisPar = $_POST['emisPar'] ?? '';
    $destineA = $_POST['destineA'] ?? '';
    $numeroDevis = $_POST['numeroDevis'] ?? '';
    $delaiLivraison = $_POST['delaiLivraison'] ?? '';
    $dateEmission = $_POST['dateEmission'] ?? '';
    $dateExpiration = $_POST['dateExpiration'] ?? '';
    $termesConditions = $_POST['termesConditions'] ?? '';
    $piedDePage = $_POST['piedDePage'] ?? '';
    $totalHT = $_POST['totalHT'] ?? '0';
    $totalTTC = $_POST['totalTTC'] ?? '0';
    $tva = $_POST['tva'] ?? '0';
    $clientId = $_POST['client_id'] ?? null;
    $offreId = $_POST['offre_id'] ?? null;
    $tvaFacturable = $_POST['tvaFacturable'] ?? '0';
    $publierDevis = $_POST['publierDevis'] ?? '0';
    $correspondant = $_POST['correspondant'] ?? '';

    if (!$clientId || !$offreId) {
        echo "Client ou offre manquant.";
        exit;
    }

    $logo = '';
    if (isset($_FILES['logoUpload']) && $_FILES['logoUpload']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logoUpload']['tmp_name'];
        $fileName = $_FILES['logoUpload']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = 'logo_' . uniqid() . '.' . $fileExtension;
        $uploadFileDir = '../logo/';
        $dest_path = $uploadFileDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $logo = $newFileName;
        } else {
            echo "Erreur lors du déplacement du fichier.";
            exit;
        }
    }

    // Récupération des lignes de devis
    $lignes = [];
    $designations = $_POST['designation'] ?? [];
    $prix = $_POST['prix'] ?? [];
    $quantites = $_POST['quantite'] ?? [];
    $tvas = $_POST['tva'] ?? [];
    $remises = $_POST['remise'] ?? [];
    $totaux = $_POST['total'] ?? [];

    for ($i = 0; $i < count($designations); $i++) {
        $lignes[] = [
            'designation' => $designations[$i] ?? '',
            'prix' => $prix[$i] ?? 0,
            'quantite' => $quantites[$i] ?? 0,
            'tva' => $tvas[$i] ?? 0,
            'remise' => $remises[$i] ?? 0,
            'total' => $totaux[$i] ?? 0
        ];
    }

    // Utilisation de la méthode creerDevis de la classe Devis
    $devisId = $devisObj->creerDevis([
        'numero_devis' => $numeroDevis,
        'delai_livraison' => $delaiLivraison,
        'date_emission' => $dateEmission,
        'date_expiration' => $dateExpiration,
        'emis_par' => $emisPar,
        'destine_a' => $destineA,
        'termes_conditions' => $termesConditions,
        'pied_de_page' => $piedDePage,
        'total_ht' => $totalHT,
        'total_ttc' => $totalTTC,
        'logo' => $logo,
        'client_id' => $clientId,
        'offre_id' => $offreId,
        'tva_facturable' => $tvaFacturable,
        'publier_devis' => $publierDevis,
        'tva' => $tva,
        'correspondant' => $correspondant
    ], $lignes);

    if ($devisId) {
        echo "<h1>Devis enregistré avec succès</h1>";
        $_SESSION['devisId'] = $devisId;
    } else {
        echo "Erreur lors de l'enregistrement du devis.";
    }
}
