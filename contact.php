<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Bread & Brew</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'contact_handler.php'; ?>
    
    <!-- Barre de Navigation -->
    <header class="navbar">
        <div class="logo">
            <img src="cafe-vintage-logo-design-isnpiration-pour-coffee-shop_427676-94.jpg" alt="Bread & Brew Logo" class="logo-img">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="menu.html">Menu</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
                <li><a href="order.html">Order</a></li>
                <li><a href="subs.php">SUBSCRIBE</a></li>
            </ul>
        </nav>
    </header>

    <!-- Section Contact -->
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-info">
                <h1>Have Any Questions?</h1>
                <p>Our cozy space is perfect for birthdays, showers, and more. Contact us to inquire about availability.</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <div class="contact-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="firstname" name="firstname" placeholder="First name*" required value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" id="lastname" name="lastname" placeholder="Last name*" required value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email address*" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="Phone number*" required value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" placeholder="Message*" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                    </div>
                    <div class="form-group submit-group">
                        <button type="submit" class="submit-button">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="cafe-vintage-logo-design-isnpiration-pour-coffee-shop_427676-94.jpg" alt="Bread & Brew Logo" class="footer-logo-img">
                <p class="footer-est">EST. 2023</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; 2024. All Rights Reserved.</p>
            </div>
            <div class="footer-social">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>