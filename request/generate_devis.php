<?php
session_start();
include('../../logi/connex.php');

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

    // Vérification des champs obligatoires
    if (!$clientId || !$offreId) {
        echo "Client ou offre manquant.";
        exit;
    }

    $logo = '';
    // Gestion du logo
    if (isset($_FILES['logoUpload']) && $_FILES['logoUpload']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logoUpload']['tmp_name'];
        $fileName = $_FILES['logoUpload']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Générer un nom unique pour le logo
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

    // Enregistrer le devis
    $stmt = $con->prepare("INSERT INTO devis (numero_devis, delai_livraison, date_emission, date_expiration, emis_par, destine_a, termes_conditions, pied_de_page, total_ht, total_ttc, logo, client_id, offre_id, tva_facturable, publier_devis, tva, correspondant) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([
        $numeroDevis,
        $delaiLivraison,
        $dateEmission,
        $dateExpiration,
        $emisPar,
        $destineA,
        $termesConditions,
        $piedDePage,
        $totalHT,
        $totalTTC,
        $logo,
        $clientId,
        $offreId,
        $tvaFacturable,
        $publierDevis,
        $tva,
        $correspondant
    ]);

    if (!$success) {
        print_r($stmt->errorInfo());
        exit;
    }

    // Récupérer l'ID du devis nouvellement créé
    $devisId = $con->lastInsertId();

    // Enregistrement des lignes de devis
    $designations = $_POST['designation'] ?? [];
    $prix = $_POST['prix'] ?? [];
    $quantites = $_POST['quantite'] ?? [];
    $tvas = $_POST['tva'] ?? [];
    $remises = $_POST['remise'] ?? [];
    $totaux = $_POST['total'] ?? [];

    for ($i = 0; $i < count($designations); $i++) {
        $designation = $designations[$i] ?? '';
        $prixUnitaire = $prix[$i] ?? 0;
        $quantite = $quantites[$i] ?? 0;
        $tvaLigne = $tvas[$i] ?? 0;
        $remise = $remises[$i] ?? 0;
        $total = $totaux[$i] ?? 0;

        $stmt = $con->prepare("INSERT INTO ligne_devis (devis_id, designation, prix, quantite, tva, remise, total) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$devisId, $designation, $prixUnitaire, $quantite, $tvaLigne, $remise, $total]);
    }

    echo "<h1>Devis enregistré avec succès</h1>";
    $_SESSION['devisId'] = $devisId;
}
