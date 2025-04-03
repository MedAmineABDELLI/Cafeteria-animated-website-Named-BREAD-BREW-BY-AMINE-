<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering immediately
ob_start();

// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './php_mailer/src/Exception.php';
require './php_mailer/src/PHPMailer.php';
require './php_mailer/src/SMTP.php';

// Ensure session is started only once
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Debugging: Log all incoming POST data
file_put_contents('debug_post.log', print_r($_POST, true));

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bread_and_brew";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log database connection error
    file_put_contents('debug_db_error.log', $e->getMessage());
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subscribe'])) {
    // More robust input filtering
    $name = trim(strip_tags($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $plan = trim(strip_tags($_POST['plan'] ?? ''));
    $payment = trim(strip_tags($_POST['payment'] ?? ''));

    // Validate inputs
    if (empty($name) || empty($email) || empty($plan) || empty($payment)) {
        file_put_contents('debug_validation.log', "Validation failed: Empty fields\n" . 
            "Name: $name\nEmail: $email\nPlan: $plan\nPayment: $payment");
        echo "Tous les champs sont obligatoires.";
        ob_end_flush();
        exit();
    }

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        ob_end_flush();
        exit();
    }

    // Générer un code de confirmation aléatoire à 6 chiffres
    $confirmation_code = sprintf("%06d", mt_rand(1, 999999));
    
    // Stocker les données temporairement dans la session
    $_SESSION['subscription_data'] = [
        'name' => $name,
        'email' => $email,
        'plan' => $plan,
        'payment' => $payment,
        'confirmation_code' => $confirmation_code,
        'timestamp' => time() 
    ];

    // Envoyer l'email de confirmation avec PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Configuration du serveur SMTP
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
        $mail->Debugoutput = function($str, $level) {
            file_put_contents('debug_smtp.log', "$level: $str\n", FILE_APPEND);
        };

        $mail->isSMTP();                                      // Utiliser SMTP
        $mail->Host       = 'smtp.gmail.com';                 // Serveur SMTP de Gmail
        $mail->SMTPAuth   = true;                             // Activer l'authentification SMTP
        $mail->Username   = 'amineamineamine24oz@gmail.com';          // Votre adresse Gmail
        $mail->Password   = 'mbrkzohxajvxrjpa'; // Votre mot de passe d'application Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Activer le chiffrement TLS
        $mail->Port       = 587;

        // Destinataires
        $mail->setFrom('amineamineamine24oz@gmail.com', 'Bread & Brew');
        $mail->addAddress($email, $name);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Code de confirmation - Abonnement Bread & Brew';
        $mail->Body    = "
            <html>
            <head>
                <style>
                    body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #4A2511; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .code { font-size: 32px; font-weight: bold; text-align: center; padding: 15px; 
                            background-color: #FFF8E7; border-radius: 5px; margin: 20px 0; color: #4A2511; }
                    .footer { font-size: 12px; text-align: center; margin-top: 30px; color: #6B4D33; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Merci de vous être abonné à Bread & Brew!</h1>
                    </div>
                    <p>Bonjour $name,</p>
                    <p>Merci d'avoir choisi notre formule <strong>$plan</strong>. Pour confirmer votre abonnement, veuillez utiliser le code à 6 chiffres ci-dessous :</p>
                    <div class='code'>$confirmation_code</div>
                    <p>Ce code est valable pendant 30 minutes. Veuillez retourner sur notre site pour finaliser votre inscription.</p>
                    <p>À bientôt dans notre café!</p>
                    <div class='footer'>
                        <p>Si vous n'avez pas demandé cet abonnement, veuillez ignorer cet email.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        $mail->AltBody = "Bonjour $name,\n\nMerci d'avoir choisi notre formule $plan. Pour confirmer votre abonnement, veuillez utiliser le code à 6 chiffres suivant :\n\n$confirmation_code\n\nCe code est valable pendant 30 minutes. Veuillez retourner sur notre site pour finaliser votre inscription.\n\nÀ bientôt dans notre café!";

        // Envoyer l'email
        if($mail->send()) {
            // Log successful email sending
            file_put_contents('debug_email_success.log', "Email envoyé à $email le " . date('Y-m-d H:i:s'));
            echo 'confirm_subscription.php';
        } else {
            // Log email sending failure
            file_put_contents('debug_email_error.log', "Échec de l'envoi de l'email à $email");
            echo "Erreur lors de l'envoi de l'email.";
        }
        
        ob_end_flush();
        exit();
    } catch (Exception $e) {
        // Log exception details
        file_put_contents('debug_exception.log', $e->getMessage());
        echo "Une erreur est survenue : " . $e->getMessage();
        ob_end_flush();
        exit();
    }
}

// Si quelqu'un essaie d'accéder directement à ce fichier sans soumettre le formulaire
echo 'subs.php';
ob_end_flush();
exit();
?>