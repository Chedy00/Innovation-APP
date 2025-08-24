<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - Innovation Hub</title>
    <link rel="stylesheet" href="/assets/css/frontoffice.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="error-page">
        <div class="error-container">
            <div class="error-content">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <h1 class="error-title">404</h1>
                <h2 class="error-subtitle">Page non trouvée</h2>
                
                <p class="error-message">
                    Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
                </p>
                
                <div class="error-actions">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home"></i> Retour à l'Accueil
                    </a>
                    <button onclick="history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Page Précédente
                    </button>
                </div>
            </div>
            
            <div class="error-illustration">
                <div class="floating-elements">
                    <div class="element element-1"><i class="fas fa-lightbulb"></i></div>
                    <div class="element element-2"><i class="fas fa-cog"></i></div>
                    <div class="element element-3"><i class="fas fa-star"></i></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
        }

        .error-container {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 400px;
        }

        .error-content {
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .error-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 4rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .error-subtitle {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 1rem;
        }

        .error-message {
            color: var(--gray-600);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .error-illustration {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .floating-elements {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .element {
            position: absolute;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            animation: float 3s ease-in-out infinite;
        }

        .element-1 {
            top: 20%;
            left: 20%;
            background: rgba(251, 191, 36, 0.2);
            color: #f59e0b;
            animation-delay: 0s;
        }

        .element-2 {
            top: 60%;
            right: 20%;
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            animation-delay: 1s;
        }

        .element-3 {
            bottom: 20%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @media (max-width: 768px) {
            .error-container {
                grid-template-columns: 1fr;
                max-width: 400px;
            }

            .error-content {
                padding: 2rem 1.5rem;
            }

            .error-title {
                font-size: 3rem;
            }

            .error-subtitle {
                font-size: 1.25rem;
            }

            .error-illustration {
                min-height: 200px;
            }

            .floating-elements {
                width: 150px;
                height: 150px;
            }

            .element {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
</body>
</html>

