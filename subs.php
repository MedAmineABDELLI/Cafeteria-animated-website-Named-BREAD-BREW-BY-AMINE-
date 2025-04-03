
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bread & Brew - Abonnement</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Styles spécifiques à la page d'abonnement */
        .section-subscribe {
            padding: 60px 10%;
            background-color: #FFF8E7;
            min-height: 70vh;
        }

        .subscribe-header {
            text-align: center;
            margin-bottom: 50px;
            animation: fadeIn 1.2s ease-out;
        }

        .subscribe-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #4A2511;
            margin-bottom: 15px;
        }

        .subscribe-subtitle {
            font-size: 1.1rem;
            color: #6B4D33;
            max-width: 700px;
            margin: 0 auto;
        }

        .plans-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }

        .plan-card {
            background-color: #FFFFFF;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 300px;
            text-align: center;
            transition: all 0.3s ease;
            animation: fadeIn 1.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .plan-badge {
            position: absolute;
            top: 10px;
            right: -30px;
            background-color: #4A2511;
            color: #FFF;
            padding: 5px 30px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .plan-icon {
            font-size: 2.5rem;
            color: #4A2511;
            margin-bottom: 15px;
        }

        .plan-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #4A2511;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 600;
            color: #4A2511;
            margin-bottom: 15px;
        }

        .plan-features {
            list-style: none;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .plan-features li {
            margin-bottom: 10px;
            color: #6B4D33;
            position: relative;
            padding-left: 20px;
        }

        .plan-features li::before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #4A2511;
        }

        .subscribe-button {
            padding: 10px 25px;
            background-color: #4A2511;
            color: #FFF;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
            display: inline-block;
            width: 80%;
        }

        .subscribe-button:hover {
            background-color: #6B4D33;
            transform: translateY(-3px);
        }

        .subscription-form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.8s ease-out;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #4A2511;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            color: #4A2511;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0CAA8;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #4A2511;
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0CAA8;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            background-color: #FFF;
            cursor: pointer;
        }

        .form-info {
            font-size: 0.8rem;
            color: #6B4D33;
            margin-top: 5px;
        }

        .form-submit {
            padding: 10px 25px;
            background-color: #4A2511;
            color: #FFF;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
            width: 100%;
            font-size: 1rem;
        }

        .form-submit:hover {
            background-color: #6B4D33;
            transform: translateY(-3px);
        }

        .decoration-beans {
            position: absolute;
            z-index: 0;
            opacity: 0.1;
        }

        .bean-top-left {
            top: 5%;
            left: 5%;
            font-size: 4rem;
            transform: rotate(-15deg);
        }

        .bean-bottom-right {
            bottom: 5%;
            right: 5%;
            font-size: 4rem;
            transform: rotate(25deg);
        }

        @media (max-width: 992px) {
            .plans-container {
                gap: 20px;
            }
            
            .plan-card {
                width: calc(50% - 20px);
                min-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .plan-card {
                width: 100%;
                max-width: 400px;
            }
        }

        @media (max-width: 576px) {
            .section-subscribe {
                padding: 40px 5%;
            }
            
            .subscribe-title {
                font-size: 2rem;
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
                <li><a href="subscribe.html" class="active">SUBSCRIBE</a></li>
            </ul>
        </nav>
    </header>

    <!-- Section Abonnement -->
    <section class="section-subscribe">
        <div class="decoration-beans bean-top-left">
            <i class="fas fa-coffee"></i>
        </div>
        <div class="decoration-beans bean-bottom-right">
            <i class="fas fa-coffee"></i>
        </div>

        <div class="subscribe-header">
            <h1 class="subscribe-title">Nos Formules d'Abonnement</h1>
            <p class="subscribe-subtitle">Rejoignez notre club exclusif et recevez des coupons de réduction sur vos produits préférés. Plus vous êtes fidèle, plus vous économisez !</p>
        </div>

        <div class="plans-container">
            <!-- Plan Basic -->
            <div class="plan-card">
                <div class="plan-icon">
                    <i class="fas fa-cookie-bite"></i>
                </div>
                <h3 class="plan-name">Gourmand</h3>
                <div class="plan-price">5€</div>
                <p>par mois</p>
                <ul class="plan-features">
                    <li>Réduction de 5% sur tous vos achats</li>
                    <li>1 café offert par mois</li>
                    <li>Accès aux ventes privées</li>
                </ul>
                <button onclick="selectPlan('Gourmand')" class="subscribe-button">Choisir</button>
            </div>
            
            <!-- Plan Premium -->
            <div class="plan-card">
                <div class="plan-badge">Populaire</div>
                <div class="plan-icon">
                    <i class="fas fa-bread-slice"></i>
                </div>
                <h3 class="plan-name">Passionné</h3>
                <div class="plan-price">10€</div>
                <p>par mois</p>
                <ul class="plan-features">
                    <li>Réduction de 10% sur tous vos achats</li>
                    <li>2 cafés offerts par mois</li>
                    <li>1 pâtisserie offerte</li>
                    <li>Accès aux ventes privées</li>
                </ul>
                <button onclick="selectPlan('Passionné')" class="subscribe-button">Choisir</button>
            </div>
            
            <!-- Plan Ultimate -->
            <div class="plan-card">
                <div class="plan-icon">
                    <i class="fas fa-mug-hot"></i>
                </div>
                <h3 class="plan-name">Connaisseur</h3>
                <div class="plan-price">20€</div>
                <p>par mois</p>
                <ul class="plan-features">
                    <li>Réduction de 15% sur tous vos achats</li>
                    <li>5 cafés offerts par mois</li>
                    <li>2 pâtisseries offertes</li>
                    <li>Accès aux ventes privées</li>
                    <li>Invitations aux dégustations exclusives</li>
                </ul>
                <button onclick="selectPlan('Connaisseur')" class="subscribe-button">Choisir</button>
            </div>
        </div>

        <!-- Formulaire d'abonnement -->
        <!-- Formulaire d'abonnement modifié -->
<div class="subscription-form">
    <h3 class="form-title">Inscrivez-vous</h3>
    
    <?php if(isset($_SESSION['error'])): ?>
    <div style="color: #e74c3c; margin-bottom: 15px; padding: 10px; background-color: #fadbd8; border-radius: 5px;">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>
    
    <form id="subscribe-form" action="process_subscription.php" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input type="text" id="name" name="name" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" class="form-input" required>
            <div class="form-info">Nous ne partagerons jamais votre e-mail avec des tiers.</div>
        </div>
        
        <div class="form-group">
            <label for="plan" class="form-label">Formule choisie</label>
            <select id="plan" name="plan" class="form-select" required>
                <option value="">Sélectionnez une formule</option>
                <option value="gourmand">Gourmand (5€/mois)</option>
                <option value="passionne">Passionné (10€/mois)</option>
                <option value="connaisseur">Connaisseur (20€/mois)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="payment" class="form-label">Méthode de paiement</label>
            <select id="payment" name="payment" class="form-select" required>
                <option value="">Sélectionnez une méthode</option>
                <option value="card">Carte bancaire</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>
        
        <button type="submit" name="subscribe" class="form-submit">S'abonner maintenant</button>
        </form>
    </div>
    </section>

    <script>
    document.getElementById('subscribe-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Create FormData object
    var formData = new FormData(this);
    
    // Add the subscribe parameter
    formData.append('subscribe', 'true');
    
    // Fetch API to submit form
    fetch('process_subscription.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        console.log('Server response:', result); // Log the server response
        
        if (result.includes('confirm_subscription.php')) {
            // Successful submission, redirect to confirmation page
            window.location.href = 'confirm_subscription.php';
        } else {
            // Show error message
            alert(result);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue. Veuillez réessayer.');
    });
});
    </script>
</body>
</html>