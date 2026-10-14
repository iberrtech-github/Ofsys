<?php
/**
 * OFSYS Education - Emails a saved enquiry to the admissions inbox.
 *
 * The visitor picks "Through Mail" in the popup that follows a form submit;
 * the browser posts the lead id it got back from form-handler.php and this
 * script mails that lead to EMAIL_PRIMARY, so nothing has to be retyped.
 */

require_once __DIR__ . '/includes/data.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$leadId = isset($_POST['lead_id']) ? trim(strip_tags($_POST['lead_id'])) : '';

if ($leadId === '') {
    echo json_encode(['status' => 'error', 'message' => 'Missing enquiry reference.']);
    exit;
}

// ---- Find the lead that was just saved -----------------------------------
$leadsFile = __DIR__ . '/leads.json';
$leads     = [];

if (file_exists($leadsFile)) {
    $decoded = json_decode(file_get_contents($leadsFile), true);
    if (is_array($decoded)) {
        $leads = $decoded;
    }
}

$lead = null;
foreach ($leads as $row) {
    if (isset($row['id']) && $row['id'] === $leadId) {
        $lead = $row;
        break;
    }
}

if ($lead === null) {
    echo json_encode(['status' => 'error', 'message' => 'Enquiry not found. Please submit the form again.']);
    exit;
}

// ---- Build the mail ------------------------------------------------------
$fields = [
    'Name'             => $lead['full_name'] ?? '',
    'Phone'            => $lead['phone'] ?? '',
    'Email'            => $lead['email'] ?? '',
    'Course'           => $lead['course'] ?? '',
    'Preferred Campus' => $lead['branch'] ?? '',
    'Message'          => $lead['message'] ?? '',
    'Submitted From'   => $lead['form_type'] ?? '',
    'Submitted On'     => $lead['timestamp'] ?? ''
];

$subject = 'New Course Enquiry - ' . ($lead['full_name'] ?? 'Website Visitor')
         . (!empty($lead['course']) ? ' (' . $lead['course'] . ')' : '');

$bodyLines = ['A new enquiry has been submitted on ' . SITE_NAME . '.', ''];
foreach ($fields as $label => $value) {
    if ($value !== '') {
        $bodyLines[] = $label . ': ' . $value;
    }
}
$body = implode("\r\n", $bodyLines);

$headers   = [];
$headers[] = 'From: ' . SITE_NAME . ' Website <no-reply@ofsysedu.com>';
$headers[] = 'Reply-To: ' . (!empty($lead['email']) ? $lead['email'] : EMAIL_PRIMARY);
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'X-Mailer: PHP/' . phpversion();

$sent = @mail(EMAIL_PRIMARY, $subject, $body, implode("\r\n", $headers));

if ($sent) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Mail sent successfully to ' . EMAIL_PRIMARY . '. Our team will reply to you shortly.'
    ]);
    exit;
}

// Mail server unavailable: hand the browser a ready-to-send mailto instead.
echo json_encode([
    'status'   => 'error',
    'message'  => 'We could not send the mail automatically. Tap below to send it from your own mail app.',
    'fallback' => 'mailto:' . EMAIL_PRIMARY
                  . '?subject=' . rawurlencode($subject)
                  . '&body=' . rawurlencode(implode("\n", $bodyLines))
]);
exit;
