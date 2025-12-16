<?php
// contact.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false]);
  exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$name    = trim($data['name'] ?? '');
$email   = trim($data['email'] ?? '');
$phone   = trim($data['phone'] ?? '');
$message = trim($data['message'] ?? '');
$honeypot = trim($data['company_website'] ?? '');

// Basic validation
if ($honeypot !== '') { // bot
  http_response_code(200);
  echo json_encode(['ok' => true]);
  exit;
}
if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['ok' => false]);
  exit;
}

// --- CONFIG: your branding ---
$fromName = "Asani Enterprises";
$fromEmail = "info@asanienterprises.com";  // must exist on your email service
$toInternal = "info@asanienterprises.com";

// Build internal notification
$subjectInternal = "New website inquiry — Asani Enterprises";
$bodyInternal =
"New inquiry received:\n\n".
"Name: $name\n".
"Email: $email\n".
"Phone: $phone\n\n".
"Message:\n$message\n\n".
"IP: ".($_SERVER['REMOTE_ADDR'] ?? 'unknown')."\n".
"User Agent: ".($_SERVER['HTTP_USER_AGENT'] ?? 'unknown')."\n";

// Luxury customer confirmation
$subjectCustomer = "We’ve received your request";
$bodyCustomer =
"Dear $name,\n\n".
"Thank you for contacting Asani Enterprises.\n\n".
"Your message has been received and is currently under review by our team. ".
"Should additional details be required, we will reach out directly.\n\n".
"This message confirms receipt only and does not constitute an agreement or commitment of services.\n\n".
"Warm regards,\n".
"Asani Enterprises\n".
"info@asanienterprises.com\n".
"www.asanienterprises.com\n";

// Headers (simple + compatible)
$headersFrom = "From: $fromName <$fromEmail>\r\n";
$headersReplyTo = "Reply-To: $name <$email>\r\n";
$headersText = "Content-Type: text/plain; charset=UTF-8\r\n";

// Send internal + customer confirmation
$ok1 = mail($toInternal, $subjectInternal, $bodyInternal, $headersFrom.$headersReplyTo.$headersText);
$ok2 = mail($email, $subjectCustomer, $bodyCustomer, $headersFrom.$headersText);

if ($ok1 && $ok2) {
  echo json_encode(['ok' => true]);
} else {
  http_response_code(500);
  echo json_encode(['ok' => false]);
}
