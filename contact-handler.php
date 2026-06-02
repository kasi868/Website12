<?php
require_once __DIR__ . '/config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Invalid request.";
    exit;
}

$to = "makebaig@gmail.com";
$name = trim($_POST["name"] ?? '');
$email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
$phone = trim($_POST["phone"] ?? '');
$subject = trim($_POST["Subject"] ?? $_POST["subject"] ?? '');
$message = trim($_POST["message"] ?? '');

if ($name === '' || $email === '' || $message === '') {
    echo "Please fill in all required fields.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

$safeName = mysqli_real_escape_string($conn, $name);
$safeEmail = mysqli_real_escape_string($conn, $email);
$safePhone = mysqli_real_escape_string($conn, $phone);
$safeSubject = mysqli_real_escape_string($conn, $subject);
$safeMessage = mysqli_real_escape_string($conn, $message);

mysqli_query($conn, "INSERT INTO messages (name, email, phone, subject, message) VALUES ('$safeName', '$safeEmail', '$safePhone', '$safeSubject', '$safeMessage')");

$emailSubject = "New Contact Form Submission: " . $subject;
$emailBody = "You have received a new message from your website contact form.\n\n"
    . "Name: $name\n"
    . "Email: $email\n"
    . "Phone: $phone\n"
    . "Subject: $subject\n"
    . "Message:\n$message\n";

$headers  = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

@mail($to, $emailSubject, $emailBody, $headers);

echo "Message sent successfully!";
?>
