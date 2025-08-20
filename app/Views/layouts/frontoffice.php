<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Innovation App' ?></title>
    <link rel="stylesheet" href="/assets/css/frontoffice.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-lightbulb"></i>
                    <span>Innovation Hub</span>
                </div>
                
                <nav class="nav">
                    <?php if (Session::get('user_role') === 'salarie'): ?>
                        <a href="/salarie/ideas" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/salarie/ideas') === 0 ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Mes Idées
                        </a>
                        <a href="/salarie/ideas/submit" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/salarie/ideas/submit' ? 'active' : '' ?>">
                            <i class="fas fa-plus"></i> Nouvelle Idée
                        </a>
                    <?php elseif (Session::get('user_role') === 'evaluateur'): ?>
                        <a href="/evaluateur/ideas" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/evaluateur/ideas' ? 'active' : '' ?>">
                            <i class="fas fa-clipboard-list"></i> À Évaluer
                        </a>
                        <a href="/evaluateur/ideas/top" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/evaluateur/ideas/top' ? 'active' : '' ?>">
                            <i class="fas fa-trophy"></i> Top Idées
                        </a>
                        <a href="/evaluateur/evaluations" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/evaluateur/evaluations' ? 'active' : '' ?>">
                            <i class="fas fa-star"></i> Mes Évaluations
                        </a>
                    <?php endif; ?>
                </nav>

                <div class="user-menu">
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span><?= htmlspecialchars(Session::get('user_prenom') . ' ' . Session::get('user_nom')) ?></span>
                        <span class="role-badge role-<?= Session::get('user_role') ?>">
                            <?= ucfirst(Session::get('user_role')) ?>
                        </span>
                    </div>
                    <a href="/logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Innovation Hub - Système de Gestion d'Idées et d'Innovation</p>
        </div>
    </footer>

    <script src="/assets/js/validation.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>

