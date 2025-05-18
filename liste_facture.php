<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Factures - BTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #8aabb3, #00a584);
            color: #1d2b57;
            font-family: Arial, sans-serif;
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

        .page-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: transparent;
        }

        .spinner-container {
            text-align: center;
            color: #1d2b57;
        }

        .spinner-container .spinner-border {
            width: 5rem;
            height: 5rem;
            border-width: 0.25em;
            color: #00a584 !important;
        }

        .spinner-container h1 {
            margin-top: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #00a584;
        }

        .spinner-container p {
            color: #8aabb3;
            font-size: 1.1rem;
        }

        .footer {
            background-color: #00a584;
            color: #fff;
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
    </style>
</head>

<body>

    <!-- Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img style="width:auto; height:50px;" src="img/logo.jpg" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php include 'menu.php'; ?>
            </div>
        </div>
    </nav>

    <!-- Page container with spinner -->
    <div class="page-container">
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <h1>Page des Factures en Construction</h1>
            <p>Cette page est actuellement en développement. Merci de votre patience.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center py-3">
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
    <script src="js/script.js"></script>

</body>

</html>