<?php
session_start();  
include "db_conn.php";  

// Include Composer autoloader to access PHPMailer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function insertOrder($con, $productName, $quantity, $type, $recipientEmail) {
    $stmt = $con->prepare("INSERT INTO orders (product_name, quantity, type, recipient_email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $productName, $quantity, $type, $recipientEmail);
    $stmt->execute();
    $stmt->close();
}

$mail = new PHPMailer(true);

try {
    // Set mailer to use SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'dynacare.sis@gmail.com';
    $mail->Password = 'altrzkxockdikipf '; // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
        
    // Sender info
    $mail->setFrom('dynacare2025@gmail.com', 'DynaCare');
    
    
    $recipient = $_POST['recipient'];
    $mail->addAddress($recipient);  // Send to recipient

    // Prepare email content
    $mail->Subject = 'Order from DynaCare';
    $mailContent = "";

    // Process form data and add to email body
    $mailContent .= "<h3>Medicine Orders:</h3><table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
                    <tr><th>Product Name</th><th>Quantity</th></tr>";

    // Loop through and insert medicine orders into the database and email body
    for ($i = 1; $i <= count($_POST) / 2 - 1; $i++) {
        if (isset($_POST["medicine_name_$i"]) && isset($_POST["medicine_quantity_$i"])) {
            $productName = $_POST["medicine_name_$i"];
            $quantity = $_POST["medicine_quantity_$i"];
            insertOrder($con, $productName, $quantity, 'medicine', $recipient);  // Insert into DB
            $mailContent .= "<tr><td>$productName</td><td style='text-align: center;'>$quantity</td></tr>";
        }
    }
    $mailContent .= "</table>";

    // Process supplies orders similarly
    $mailContent .= "<h3>Supplies Orders:</h3><table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
                    <tr><th>Product Name</th><th>Quantity</th></tr>";
    
    for ($i = 1; $i <= count($_POST) / 2 - 1; $i++) {
        if (isset($_POST["supplies_name_$i"]) && isset($_POST["supplies_quantity_$i"])) {
            $productName = $_POST["supplies_name_$i"];
            $quantity = $_POST["supplies_quantity_$i"];
            insertOrder($con, $productName, $quantity, 'supplies', $recipient);  // Insert into DB
            $mailContent .= "<tr><td>$productName</td><td style='text-align: center;'>$quantity</td></tr>";
        }
    }
    $mailContent .= "</table>";

   
    $mail->Body = $mailContent;
    $mail->isHTML(true); 

    // Send the email
    if ($mail->send()) {
       
        $_SESSION['message'] = 'Order sent successfully!';
        $_SESSION['message_type'] = 'success'; 

        
        header('Location: cashier/sales.php');
        exit(); 
    } else {
        // Set error message in session
        $_SESSION['message'] = 'Order could not be sent. Please try again later.';
        $_SESSION['message_type'] = 'danger';  

        
        header('Location: ../reorder_form.php');
        exit();  
    }
} catch (Exception $e) {
   
    $_SESSION['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
    $_SESSION['message_type'] = 'danger';  

  
    header('Location: ./reorder_form.php');
    exit();  
}
?>
