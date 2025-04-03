<?php
// Database connection parameters
$host = 'localhost';     // Often 'localhost' for local development
$dbname = 'orders';  // The database name you created
$username = 'root';      // Typically 'root' for local development
$password = '';  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;        // Default empty password for local development


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Be more specific in production
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


require './php_mailer/src/Exception.php';
require './php_mailer/src/PHPMailer.php';
require './php_mailer/src/SMTP.php';

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit();
}

// Receive JSON payload
$jsonInput = file_get_contents('php://input');
$orderData = json_decode($jsonInput, true);

// Input validation
if (!$orderData) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Invalid JSON input'
    ]);
    exit();
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Insert order main details
    $orderQuery = "INSERT INTO orders (
        order_type, 
        first_name, 
        last_name, 
        email, 
        phone, 
        subtotal, 
        tax, 
        total, 
        payment_method, 
        special_instructions,
        street,
        city,
        zipcode,
        apartment,
        pickup_date,
        pickup_time,
        delivery_date,
        delivery_time
    ) VALUES (
        :order_type, 
        :first_name, 
        :last_name, 
        :email, 
        :phone, 
        :subtotal, 
        :tax, 
        :total, 
        :payment_method, 
        :special_instructions,
        :street,
        :city,
        :zipcode,
        :apartment,
        :pickup_date,
        :pickup_time,
        :delivery_date,
        :delivery_time
    )";

    $orderStmt = $pdo->prepare($orderQuery);
    $orderStmt->execute([
        ':order_type' => $orderData['order_type'],
        ':first_name' => $orderData['first_name'],
        ':last_name' => $orderData['last_name'],
        ':email' => $orderData['email'],
        ':phone' => $orderData['phone'],
        ':subtotal' => $orderData['subtotal'],
        ':tax' => $orderData['tax'],
        ':total' => $orderData['total'],
        ':payment_method' => $orderData['payment_method'] ?? 'online',
        ':special_instructions' => $orderData['special_instructions'] ?? '',
        ':street' => $orderData['street'] ?? null,
        ':city' => $orderData['city'] ?? null,
        ':zipcode' => $orderData['zipcode'] ?? null,
        ':apartment' => $orderData['apartment'] ?? null,
        ':pickup_date' => $orderData['pickup_date'] ?? null,
        ':pickup_time' => $orderData['pickup_time'] ?? null,
        ':delivery_date' => $orderData['delivery_date'] ?? null,
        ':delivery_time' => $orderData['delivery_time'] ?? null
    ]);

    // Get the last inserted order ID
    $orderId = $pdo->lastInsertId();

    // Insert order items
    $itemQuery = "INSERT INTO order_items (
        order_id, 
        product_name, 
        price, 
        quantity
    ) VALUES (
        :order_id, 
        :product_name, 
        :price, 
        :quantity
    )";

    $itemStmt = $pdo->prepare($itemQuery);

    foreach ($orderData['items'] as $item) {
        $itemStmt->execute([
            ':order_id' => $orderId,
            ':product_name' => $item['name'],
            ':price' => $item['price'],
            ':quantity' => $item['quantity']
        ]);
    }

    // Commit transaction
    $pdo->commit();

    // Respond with success
    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'order_id' => $orderId,
        'message' => 'Order processed successfully'
    ]);

} catch (PDOException $e) {
    // Rollback transaction in case of error
    $pdo->rollBack();

    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Order processing failed: ' . $e->getMessage()
    ]);
}


function sendOrderConfirmationEmail($orderData, $order_id) {
    // Use PHPMailer to send confirmation email
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration remains the same
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your email adress';
        $mail->Password = 'your app password code';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email content
        $mail->setFrom('noreply@breadandbrew.com', 'Bread & Brew');
        $mail->addAddress($orderData['email']);
        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation #" . $order_id;

        // Generate styled email body
        $emailBody = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    line-height: 1.6;
                    color: #4A2511;
                    background-color: #FFF8E7;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: white;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                    border-radius: 15px;
                    overflow: hidden;
                }
                .email-header {
                    background: linear-gradient(rgba(74, 37, 17, 0.8), rgba(74, 37, 17, 0.8));
                    color: #FFF;
                    text-align: center;
                    padding: 20px;
                }
                .email-header h1 {
                    font-family: 'Playfair Display', serif;
                    margin: 0;
                    font-size: 2rem;
                }
                .email-content {
                    padding: 30px;
                }
                .order-details {
                    background-color: #FFF8E7;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .order-items {
                    border-bottom: 2px solid #E0CAA8;
                    padding-bottom: 15px;
                    margin-bottom: 15px;
                }
                .order-item {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 10px;
                }
                .total-section {
                    font-weight: 600;
                    color: #4A2511;
                }
                .footer {
                    text-align: center;
                    padding: 15px;
                    background-color: #FFF8E7;
                    font-size: 0.9rem;
                    color: #6B4D33;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>Bread & Brew</h1>
                    <p>Order Confirmation</p>
                </div>
                
                <div class='email-content'>
                    <div class='order-details'>
                        <h2>Order #" . htmlspecialchars($order_id) . "</h2>
                        
                        <div class='order-items'>";
        
        // Add items to email
        foreach ($orderData['items'] as $item) {
            $emailBody .= "
                            <div class='order-item'>
                                <span>" . htmlspecialchars($item['name']) . " x " . htmlspecialchars($item['quantity']) . "</span>
                                <span>$" . number_format($item['price'] * $item['quantity'], 2) . "</span>
                            </div>";
        }
        
        $emailBody .= "
                        </div>
                        
                        <div class='total-section'>
                            <div class='order-item'>
                                <span>Subtotal</span>
                                <span>$" . number_format($orderData['subtotal'], 2) . "</span>
                            </div>
                            <div class='order-item'>
                                <span>Tax</span>
                                <span>$" . number_format($orderData['tax'], 2) . "</span>
                            </div>
                            <div class='order-item'>
                                <span>Total</span>
                                <span>$" . number_format($orderData['total'], 2) . "</span>
                            </div>
                        </div>
                    </div>
                    
                    <p>Thank you for your order from Bread & Brew! We appreciate your business.</p>
                </div>
                
                <div class='footer'>
                    &copy; " . date('Y') . " Bread & Brew. All rights reserved.
                </div>
            </div>
        </body>
        </html>
        ";

        $mail->Body = $emailBody;

        $mail->send();
    } catch (Exception $e) {
        // Log email sending error
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }
}

sendOrderConfirmationEmail($orderData, $orderId);
?>
