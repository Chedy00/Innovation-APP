<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Innovation App</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h1>Connexion</h1>
            <p class="subtitle">Système de Gestion d'Idées et d'Innovation</p>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login" id="loginForm">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <span class="error-text" id="email-error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error-text" id="password-error"></span>
                </div>

                <button type="submit" class="btn-login">Se connecter</button>
            </form>

            <div class="demo-accounts">
                <h3>Comptes de démonstration :</h3>
                <div class="demo-account">
                    <strong>Administrateur :</strong> admin@innovation.com / password
                </div>
                <div class="demo-account">
                    <strong>Salarié :</strong> chedybouhlel00@gmail.com / password
                </div>
                <div class="demo-account">
                    <strong>Évaluateur :</strong> chedy.bouhlel@esprit.tn / password
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/validation.js"></script>
    <script>
        // Validation du formulaire de connexion
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            // Reset des erreurs
            clearErrors();
            
            // Validation email
            if (!email.value.trim()) {
                showError('email-error', 'L\'email est requis.');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError('email-error', 'Format d\'email invalide.');
                isValid = false;
            }
            
            // Validation mot de passe
            if (!password.value.trim()) {
                showError('password-error', 'Le mot de passe est requis.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>

