<?php
session_start();

// Vérifier si les données de session existent
if (!isset($_SESSION['subscription_data'])) {
    header("Location: subs.php");
    exit();
}

// Vérifier si le code a expiré (30 minutes)
$expiry_time = 30 * 60; // 30 minutes en secondes
if (time() - $_SESSION['subscription_data']['timestamp'] > $expiry_time) {
    $_SESSION['error'] = "Votre code de confirmation a expiré. Veuillez vous réinscrire.";
    unset($_SESSION['subscription_data']);
    header("Location: subscribe.html");
    exit();
}

// Traitement du code de confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $entered_code = filter_input(INPUT_POST, 'confirmation_code', );
    $stored_code = $_SESSION['subscription_data']['confirmation_code'];
    
    if ($entered_code === $stored_code) {
        // Le code est correct, connexion à la base de données pour enregistrer l'utilisateur
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bread_and_brew";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Préparation de la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO subscribers (name, email, plan, payment_method, created_at) 
                                    VALUES (:name, :email, :plan, :payment, NOW())");
            
            // Récupération des données de session
            $name = $_SESSION['subscription_data']['name'];
            $email = $_SESSION['subscription_data']['email'];
            $plan = $_SESSION['subscription_data']['plan'];
            $payment = $_SESSION['subscription_data']['payment'];
            
            // Liaison des paramètres
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':plan', $plan);
            $stmt->bindParam(':payment', $payment);
            
            // Exécution de la requête
            $stmt->execute();
            
            // Création d'un message de succès
            $_SESSION['success'] = "Votre abonnement a été confirmé avec succès! Bienvenue dans notre club.";
            
            // Suppression des données temporaires
            unset($_SESSION['subscription_data']);
            
            // Redirection vers la page d'accueil ou une page de succès
            header("Location: subscription_success.php");
            exit();
            
        } catch(PDOException $e) {
            $_SESSION['error'] = "Une erreur est survenue lors de l'enregistrement: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Le code de confirmation est incorrect. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - Bread & Brew</title>
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
        
        .confirmation-container {
            max-width: 500px;
            margin: 100px auto;
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .confirmation-icon {
            font-size: 3rem;
            color: #4A2511;
            margin-bottom: 20px;
        }
        
        .confirmation-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #4A2511;
            margin-bottom: 15px;
        }
        
        .confirmation-text {
            color: #6B4D33;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .code-input {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .code-input input {
            width: 50px;
            height: 60px;
            font-size: 1.5rem;
            text-align: center;
            border: 2px solid #E0CAA8;
            border-radius: 5px;
            outline: none;
        }
        
        .code-input input:focus {
            border-color: #4A2511;
        }
        
        .resend-link {
            display: block;
            margin-top: 15px;
            color: #4A2511;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .confirm-button {
            padding: 12px 30px;
            background-color: #4A2511;
            color: #FFF;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .confirm-button:hover {
            background-color: #6B4D33;
            transform: translateY(-3px);
        }
        
        .error-message {
            color: #e74c3c;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .timer {
            font-size: 0.9rem;
            color: #6B4D33;
            margin-top: 20px;
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

    <div class="confirmation-container">
        <div class="confirmation-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h2 class="confirmation-title">Vérification de votre email</h2>
        <p class="confirmation-text">Nous avons envoyé un code à 6 chiffres à <strong><?php echo htmlspecialchars($_SESSION['subscription_data']['email']); ?></strong>. Veuillez entrer ce code ci-dessous pour confirmer votre abonnement.</p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="code-input">
                <input type="text" name="confirmation_code" id="confirmation_code" maxlength="6" pattern="[0-9]{6}" required>
            </div>
            
            <button type="submit" name="confirm" class="confirm-button">Confirmer</button>
        </form>
        
        <div class="timer" id="countdown">Le code expire dans: 30:00</div>
        
        <a href="#" class="resend-link" id="resend-link">Je n'ai pas reçu de code. Renvoyer</a>
    </div>

    <script>
        // Script pour le compte à rebours
        const countdownElement = document.getElementById('countdown');
        const resendLink = document.getElementById('resend-link');
        
        let timeLeft = 30 * 60; // 30 minutes en secondes
        
        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownElement.textContent = `Le code expire dans: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                countdownElement.textContent = "Le code a expiré";
                resendLink.style.display = "block";
                alert("Votre code a expiré. Veuillez demander un nouveau code ou recommencer l'inscription.");
                window.location.href = "subscribe.html";
            }
            
            timeLeft--;
        }
        
        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();
        
        // Fonction pour gérer la saisie automatique dans les champs de code
        document.getElementById('confirmation_code').addEventListener('input', function(e) {
            const input = e.target;
            if (input.value.length > input.maxLength) {
                input.value = input.value.slice(0, input.maxLength);
            }
        });
        
        // Gestion du renvoi de code
        resendLink.addEventListener('click', function(e) {
            e.preventDefault();
            // Ici, vous pouvez implémenter la logique pour renvoyer un nouveau code
            // Pour l'instant, on recharge simplement la page
            window.location.reload();
        });
    </script>
</body>
</html>