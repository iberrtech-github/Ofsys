<?php
/**
 * OFSYS Education - Lead & Contact Form Submission Handler
 */

require_once __DIR__ . '/includes/data.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
    exit;
}

$fullName = isset($_POST['full_name']) ? trim(strip_tags($_POST['full_name'])) : '';
$phone    = isset($_POST['phone_number']) ? trim(strip_tags($_POST['phone_number'])) : (isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '');
$email    = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$course   = isset($_POST['interested_course']) ? trim(strip_tags($_POST['interested_course'])) : (isset($_POST['course']) ? trim(strip_tags($_POST['course'])) : '');
$branch   = isset($_POST['preferred_branch']) ? trim(strip_tags($_POST['preferred_branch'])) : (isset($_POST['branch']) ? trim(strip_tags($_POST['branch'])) : '');
$message  = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$formType = isset($_POST['form_type']) ? trim(strip_tags($_POST['form_type'])) : 'general_enquiry';

// Basic Validation
if (empty($fullName)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please provide your full name.'
    ]);
    exit;
}

if (empty($phone) || strlen($phone) < 7) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please provide a valid phone number.'
    ]);
    exit;
}

// Assemble lead record
$leadId = uniqid('lead', true);
$lead = [
    'id'        => $leadId,
    'timestamp' => date('Y-m-d H:i:s'),
    'form_type' => $formType,
    'full_name' => $fullName,
    'phone'     => $phone,
    'email'     => $email,
    'course'    => $course,
    'branch'    => $branch,
    'message'   => $message,
    'ip'        => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Save to local leads storage
$leadsFile = __DIR__ . '/leads.json';
$leads = [];
if (file_exists($leadsFile)) {
    $existing = file_get_contents($leadsFile);
    $decoded = json_decode($existing, true);
    if (is_array($decoded)) {
        $leads = $decoded;
    }
}

$leads[] = $lead;
file_put_contents($leadsFile, json_encode($leads, JSON_PRETTY_PRINT));

// ---- Follow-up options shown after a successful submit -------------------
// The learner can continue the same enquiry on WhatsApp or over email, with
// everything they just typed pre-filled for them.
$summaryLines = [
    'Name: ' . $fullName,
    'Phone: ' . $phone
];
if ($email !== '')   { $summaryLines[] = 'Email: ' . $email; }
if ($course !== '')  { $summaryLines[] = 'Course: ' . $course; }
if ($branch !== '')  { $summaryLines[] = 'Preferred Campus: ' . $branch; }
if ($message !== '') { $summaryLines[] = 'Message: ' . $message; }

$waText = "Hello " . SITE_NAME . ", I just submitted an enquiry on your website.\n\n"
        . implode("\n", $summaryLines);

$mailSubject = 'Course Enquiry - ' . ($course !== '' ? $course : $fullName);
$mailBody    = "Hello " . SITE_NAME . ",\n\nI just submitted an enquiry on your website.\n\n"
             . implode("\n", $summaryLines) . "\n\nThank you.";

echo json_encode([
    'status'  => 'success',
    'lead_id' => $leadId,
    'message' => 'Thank you, ' . htmlspecialchars($fullName) . '! Your enquiry has been received. Our senior academic counselor will call you shortly.',
    'follow_up' => [
        'prompt'   => 'Thank you, ' . htmlspecialchars($fullName) . '! Send your enquiry directly:',
        'whatsapp' => [
            'label' => 'Through WhatsApp',
            'note'  => HF_PHONE_DISPLAY,
            'url'   => 'https://wa.me/' . PHONE_CLEAN . '?text=' . rawurlencode($waText)
        ],
        'email' => [
            'label' => 'Through Mail',
            'note'  => EMAIL_PRIMARY,
            'url'   => 'mailto:' . EMAIL_PRIMARY
                       . '?subject=' . rawurlencode($mailSubject)
                       . '&body=' . rawurlencode($mailBody)
        ]
    ]
]);
exit;
