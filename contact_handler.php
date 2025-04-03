<?php
// Initialiser les variables
$firstname = $lastname = $email = $phone = $message = "";
$errors = [];
$success = "";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer les données du formulaire
    $firstname = filter_input(INPUT_POST, 'firstname');
    $lastname = filter_input(INPUT_POST, 'lastname');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone');
    $message = filter_input(INPUT_POST, 'message');
    
    // Validation des données
    if (empty($firstname)) {
        $errors[] = "Le prénom est requis";
    }
    
    if (empty($lastname)) {
        $errors[] = "Le nom est requis";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Une adresse email valide est requise";
    }
    
    if (empty($phone)) {
        $errors[] = "Le numéro de téléphone est requis";
    }
    
    if (empty($message)) {
        $errors[] = "Le message est requis";
    }
    
    // Si aucune erreur, envoyer l'email avec PHPMailer
    if (empty($errors)) {
        // Utiliser Composer pour charger PHPMailer ou inclure manuellement les fichiers
        // Si vous n'utilisez pas Composer, téléchargez PHPMailer et incluez les fichiers comme ceci:
        require './php_mailer/src/Exception.php';
        require './php_mailer/src/PHPMailer.php';
        require './php_mailer/src/SMTP.php';

        
        // Importer les classes nécessaires
       
        
        // Créer une nouvelle instance de PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Configuration du serveur
            $mail->isSMTP();                                      // Utiliser SMTP
            $mail->Host       = 'smtp.gmail.com';                 // Serveur SMTP de Gmail
            $mail->SMTPAuth   = true;                             // Activer l'authentification SMTP
            $mail->Username   = 'amineamineamine24oz@gmail.com';          // Votre adresse Gmail
            $mail->Password   = 'mbrkzohxajvxrjpa'; // Votre mot de passe d'application Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Activer le chiffrement TLS
            $mail->Port       = 587;                              // Port TCP à utiliser
            
            // Destinataires
            $mail->setFrom('amineamineamine24oz@gmail.com', 'Bread & Brew Contact Form');
            $mail->addAddress('amineamineamine24oz@gmail.com');     // Ajouter un destinataire
            $mail->addReplyTo($email, $firstname . ' ' . $lastname);
            
            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message de contact de Bread & Brew';
            
            // Corps du message en HTML
            $mail->Body = "
            <h2>Nouveau message du formulaire de contact Bread & Brew</h2>
            <p><strong>Nom:</strong> {$firstname} {$lastname}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Téléphone:</strong> {$phone}</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
            ";
            
            // Version texte alternative
            $mail->AltBody = "Nouveau message de contact de Bread & Brew\n\n" .
                             "Nom: {$firstname} {$lastname}\n" .
                             "Email: {$email}\n" .
                             "Téléphone: {$phone}\n\n" .
                             "Message:\n{$message}";
            
            // Envoyer l'email
            $mail->send();
            $success = "Votre message a été envoyé avec succès!";
            
            // Réinitialiser les champs du formulaire après envoi réussi
            $firstname = $lastname = $email = $phone = $message = "";
            
        } catch (Exception $e) {
            $errors[] = "Une erreur s'est produite lors de l'envoi du message: {$mail->ErrorInfo}";
        }
    }
}
?>