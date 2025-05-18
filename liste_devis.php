<?php
include 'auth_check.php';
require_once 'model/Database.php';
require_once 'model/Devis.php';

// Connexion PDO
$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

// Instancier la classe Devis
$devisObj = new Devis($pdo);

// Récupérer les filtres depuis $_GET
$filters = [
    'date_debut' => $_GET['date_debut'] ?? null,
    'date_fin' => $_GET['date_fin'] ?? null,
    'emis_par' => $_GET['emis_par'] ?? null,
    'destine_a' => $_GET['destine_a'] ?? null,
];

// Appeler la méthode de récupération avec filtres
$devis = $devisObj->getDevisFiltres($filters);

// Calcul du montant total TTC et du nombre de devis
$total_ttc = 0;
$nb_devis = 0;
foreach ($devis as $de) {
    $total_ttc += $de['total_ttc'];
    $nb_devis++;
}
?>
<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Devis - BTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom_style_liste_devis.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #8aabb3, #00a584);
            color: #1d2b57;
        }

        .navbar {
            background-color: #00a584;
        }

        .navbar-brand img {
            height: 50px;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #8aabb3 !important;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #00a584;
            border-color: #00a584;
        }

        .btn-primary:hover {
            background-color: #8aabb3;
            border-color: #8aabb3;
        }

        .btn-secondary {
            background-color: #8aabb3;
            border-color: #8aabb3;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #00a584;
            border-color: #00a584;
            color: #fff;
        }

        .footer {
            background-color: #00a584;
            color: #ffffff;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 10px;
        }

        .footer .social-icons a {
            color: #8aabb3;
            margin: 0 10px;
            font-size: 20px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer .social-icons a:hover {
            color: #fff;
        }

        h1,
        h2 {
            color: #00a584;
            font-weight: bold;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.08);
            color: #1d2b57;
            transition: box-shadow 0.3s, border-color 0.3s;
            border: 2px solid #8aabb3;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-header {
            background: #8aabb3;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 1rem;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .card-footer {
            background: #f8f9fa;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            padding: 0.75rem 1rem;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-view,
        .btn-hide,
        .btn-edit,
        .btn-validate {
            background: #00a584;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 0.4rem 0.8rem;
            font-size: 0.95rem;
            margin-right: 0.3rem;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view:hover,
        .btn-edit:hover,
        .btn-validate:hover {
            background: #8aabb3;
            color: #fff;
        }

        .btn-hide {
            background: #8aabb3;
        }

        .btn-hide:hover {
            background: #00a584;
        }

        .footer-validation {
            padding: 0.5rem 1rem 1rem 1rem;
            text-align: right;
        }

        .btn-validate.commerciale {
            background: #00a584;
        }

        .btn-validate.generale {
            background: #8aabb3;
        }

        .btn-validate:hover {
            background: #00a584;
        }

        .validated {
            color: #00a584;
            font-weight: bold;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem 1.5rem;
        }

        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>



    <!-- Menu -->

    <nav class="navbar navbar-expand-lg navbar-dark">

        <div class="container">

            <a class="navbar-brand" href="#">

                <img src="img/logo.jpg" alt="Logo">

            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <?php include 'menu.php'; ?>
            </div>

        </div>

    </nav>



    <div class="container">



        <h1 class="text-center mb-4">Liste des Devis (<?php echo $nb_devis; ?>)</h1>





        <!-- Formulaire de recherche -->

        <form method="GET" action="liste_devis.php" class="row g-3 mb-4">

            <div class="col-md-3">

                <label for="date_debut" class="form-label">Date début</label>

                <input type="date" id="date_debut" name="date_debut" class="form-control">

            </div>

            <div class="col-md-3">

                <label for="date_fin" class="form-label">Date fin</label>

                <input type="date" id="date_fin" name="date_fin" class="form-control">

            </div>

            <div class="col-md-3">

                <label for="emis_par" class="form-label">Émis par</label>

                <input type="text" id="emis_par" name="emis_par" class="form-control" placeholder="Nom de l'émetteur">

            </div>

            <div class="col-md-3">

                <label for="destine_a" class="form-label">Destiné à</label>

                <input type="text" id="destine_a" name="destine_a" class="form-control" placeholder="Nom du destinataire">

            </div>

            <div class="col-md-3">

                <button type="submit" class="btn btn-primary mt-4">Rechercher</button>

            </div>

        </form>



        <!-- Display total amount -->

        <div class="mt-4">

            <h4 class="text-end">Montant Total TTC: <span class="text-success"><?php echo number_format($total_ttc, 0, ',', ' '); ?> FCFA</span></h4>

        </div>



        <!-- Button to export filtered quotes in PDF -->

        <div class="text-end mt-3">

            <a target="_blank" href="https://app.cafici.net/devis/request/export_resultat.php?<?php echo http_build_query($_GET); ?>" class="btn btn-primary">

                <i class="fas fa-file-pdf"></i> Exporter en PDF

            </a>

            <a target="_blank" href="generer_devis.php" class="btn btn-primary">

                <i class="fas fa-plus-circle"></i> Ajouter un devis

            </a>

        </div>



        <!-- Button to redirect to generate quote -->

        <div class="text-center mb-4">

            &nbsp;

        </div>



        <!-- Grid displaying quotes -->

        <div class="card-grid">
            <!-- PHP code to fetch and display quotes from the database -->
            <?php foreach ($devis as $de) : ?>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-file-invoice"></i> <?= htmlspecialchars($de['numero_devis']) ?>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <p><strong>Délai Livraison:</strong> <?= htmlspecialchars($de['delai_livraison']) ?></p>
                            <p><strong>Date Émission:</strong> <?= htmlspecialchars($de['date_emission']) ?></p>
                            <p><strong>Date Expiration:</strong> <?= htmlspecialchars($de['date_expiration']) ?></p>
                            <p><strong>Émis Par:</strong> <?= htmlspecialchars($de['emis_par']) ?></p>
                            <p><strong>Destiné À:</strong> <?= htmlspecialchars($de['destine_a']) ?></p>
                            <p><strong>Total HT:</strong> <?= htmlspecialchars($de['total_ht']) ?> FCFA</p>
                            <p><strong>Total TTC:</strong> <?= htmlspecialchars($de['total_ttc']) ?> FCFA</p>
                            <p><strong>Date de Création:</strong> <?= htmlspecialchars($de['created_at']) ?></p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn-view" target="_blank" href="request/export_pdf.php?devisId=<?= $de['id'] ?>"><i class="fas fa-eye"></i> Visualiser</a>
                        <a class="btn-hide" href="request/masquer_devis.php?devisId=<?= $de['id'] ?>"><i class="fas fa-eye-slash"></i> Masquer</a>
                        <a class="btn-edit" href="modifier_devis.php?devisId=<?= $de['id'] ?>"><i class="fas fa-edit"></i> Modifier</a>
                    </div>
                    <div class="footer-validation">
                        <?php if (!$de['validation_commerciale']) : ?>
                            <a class="btn-validate commerciale" href="request/valider_commerciale.php?devisId=<?= $de['id'] ?>"><i class="fas fa-check-circle"></i> Valider Commerciale</a>
                        <?php elseif (!$de['validation_generale']) : ?>
                            <a class="btn-validate generale" href="request/valider_generale.php?devisId=<?= $de['id'] ?>"><i class="fas fa-check-circle"></i> Valider Générale</a>
                        <?php else : ?>
                            <span class="validated"><i class="fas fa-check-double"></i> Déjà Validé</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



    </div>



    <!-- Footer -->

    <footer class="footer text-white text-center py-3">

        <div class="container">

            <p>&copy; <?php echo gmdate('Y'); ?> FIDEST. Tous droits réservés.</p>

            <div class="social-icons">

                <a href="#" class="fab fa-facebook-f"></a>

                <a href="#" class="fab fa-twitter"></a>

                <a href="#" class="fab fa-linkedin-in"></a>

            </div>

        </div>

    </footer>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>