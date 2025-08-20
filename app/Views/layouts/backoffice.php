<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - Innovation App' ?></title>
    <link rel="stylesheet" href="/assets/css/backoffice.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .custom-select select {
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            background-color: white;
            font-size: 14px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-select select:hover {
            border-color: #007bff;
        }

        .custom-select select:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }

        .custom-select select option[value="en_evaluation"] { background-color: #ffd700; }
        .custom-select select option[value="approuvee"] { background-color: #90EE90; }
        .custom-select select option[value="rejetee"] { background-color: #FFB6B6; }

        .status-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            background: linear-gradient(145deg, #0056b3, #004494);
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-cogs"></i>
                    <span>Admin Panel</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="/admin/users" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="/admin/thematiques" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/thematiques') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span>Thématiques</span>
                </a>
                <a href="/admin/ideas" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/ideas') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-lightbulb"></i>
                    <span>Les Idées</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <i class="fas fa-user-shield"></i>
                    <div>
                        <div class="user-name"><?= htmlspecialchars(Session::get('user_prenom') . ' ' . Session::get('user_nom')) ?></div>
                        <div class="user-role">Administrateur</div>
                    </div>
                </div>
                <a href="/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <h1><?= $pageTitle ?? 'Administration' ?></h1>
                <div class="breadcrumb">
                    <?php if (isset($breadcrumb)): ?>
                        <?php foreach ($breadcrumb as $item): ?>
                            <?php if (isset($item['url'])): ?>
                                <a href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
                            <?php else: ?>
                                <span><?= $item['label'] ?></span>
                            <?php endif; ?>
                            <?php if ($item !== end($breadcrumb)): ?>
                                <i class="fas fa-chevron-right"></i>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </header>

            <div class="content-body">
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
    </div>

    <script src="/assets/js/validation.js"></script>
    <script src="/assets/js/admin.js"></script>
</body>
</html>

