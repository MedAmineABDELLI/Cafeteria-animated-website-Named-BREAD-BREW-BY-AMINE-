<?php
session_start();

// Vérifier si l'utilisateur a bien terminé le processus d'inscription
if (!isset($_SESSION['success'])) {
    header("Location: subscribe.html");
    exit();
}

$success_message = $_SESSION['success'];
unset($_SESSION['success']); // Supprimer le message après l'avoir récupéré
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement Réussi - Bread & Brew</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFF8E7;
            margin: 0;
            padding: 0;
        }
        
        .success-container {
            max-width: 600px;
            margin: 100px auto;
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #4CAF50;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out;
        }
        
        .success-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #4A2511;
            margin-bottom: 15px;
            animation: fadeInUp 1.2s ease-out;
        }
        
        .success-text {
            color: #6B4D33;
            margin-bottom: 30px;
            line-height: 1.6;
            animation: fadeInUp 1.4s ease-out;
        }
        
        .home-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4A2511;
            color: #FFF;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            animation: fadeInUp 1.6s ease-out;
        }
        
        .home-button:hover {
            background-color: #6B4D33;
            transform: translateY(-3px);
        }
        
        .benefits-list {
            text-align: left;
            max-width: 400px;
            margin: 0 auto 30px;
            animation: fadeInUp 1.5s ease-out;
        }
        
        .benefits-list li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 30px;
            color: #6B4D33;
        }
        
        .benefits-list li::before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #4CAF50;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #4A2511;
            opacity: 0.6;
            animation: confetti 5s ease-in-out infinite;
        }
        
        @keyframes confetti {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.6;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Barre de Navigation -->
    <header class="navbar">
        <div class="logo">
            <img src="cafe-vintage-logo-design-isnpiration-pour-coffee-shop_427676-94.jpg" alt="Bread & Brew Logo" class="logo-img">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="menu.html">Menu</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="order.html">Order</a></li>
                <li><a href="subscribe.html">SUBSCRIBE</a></li>
            </ul>
        </nav>
    </header>

    <!-- Confetti animation -->
    <div id="confetti-container"></div>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="success-title">Félicitations!</h2>
        <p class="success-text"><?php echo htmlspecialchars($success_message); ?></p>
        
        <h3 style="color: #4A2511; font-size: 1.2rem; margin-bottom: 15px;">Vos avantages sont maintenant actifs</h3>
        <ul class="benefits-list">
            <li>Réductions exclusives sur tous vos achats</li>
            <li>Cafés et pâtisseries gratuits selon votre formule</li>
            <li>Accès aux ventes privées et événements</li>
            <li>Statut de membre privilégié</li>
        </ul>
        
        <p class="success-text">Vous recevrez prochainement un email avec tous les détails de votre abonnement.</p>
        
        <a href="index.html" class="home-button">Retour à l'accueil</a>
    </div>

    <script>
        // Animation de confetti
        const confettiContainer = document.getElementById('confetti-container');
        const colors = ['#4A2511', '#E0CAA8', '#6B4D33', '#FFF8E7', '#8B5A2B'];
        
        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.width = Math.random() * 15 + 5 + 'px';
            confetti.style.height = Math.random() * 15 + 5 + 'px';
            confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
            confetti.style.animationDelay = Math.random() * 5 + 's';
            
            confettiContainer.appendChild(confetti);
        }
        
        // Redirection automatique après 20 secondes
        setTimeout(function() {
            window.location.href = "index.html";
        }, 20000);
    </script>
</body>
</html>