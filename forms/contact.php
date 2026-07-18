<?php
// Allow cross-origin for testing (remove * in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Change to your email
$receiving_email_address = "abinithiselvakumar21@gmail.com";

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// Sanitize inputs
$name    = isset($_POST['name']) ? strip_tags($_POST['name']) : '';
$email   = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? strip_tags($_POST['subject']) : 'Contact Form';
$message = isset($_POST['message']) ? strip_tags($_POST['message']) : '';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Email headers
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
$mail_sent = mail($receiving_email_address, $subject, $message, $headers);

if ($mail_sent) {
    echo json_encode(["status" => "success", "message" => "Message sent successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to send email"]);
}
?>
<script>
  document.querySelector("#contactForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("contact.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        console.error("Error:", error);
        alert("There was a problem sending the form.");
    });
});
</script>