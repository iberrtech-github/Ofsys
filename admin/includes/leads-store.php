<?php
/**
 * OFSYS Admin - leads storage.
 *
 * The public forms append every submission to leads.json (see form-handler.php),
 * so the admin reads that same file. Each lead carries the branch the visitor
 * picked, which is what the branch-wise counts below are built from.
 */

define('LEADS_FILE', PROJECT_ROOT . '/leads.json');

/** Every lead in the file, newest first, each with a stable 'key'. */
function leads_all()
{
    if (!is_file(LEADS_FILE)) {
        return [];
    }

    $decoded = json_decode((string) file_get_contents(LEADS_FILE), true);
    if (!is_array($decoded)) {
        return [];
    }

    $leads = [];
    foreach ($decoded as $row) {
        if (!is_array($row)) {
            continue;
        }

        // Leads saved before the admin existed have no id, so the key falls
        // back to a hash of the record itself - stable as long as it is stored.
        $row['key']        = isset($row['id']) && $row['id'] !== ''
            ? (string) $row['id']
            : sha1(json_encode($row));
        $row['timestamp']  = (string) ($row['timestamp'] ?? '');
        $row['form_type']  = (string) ($row['form_type'] ?? '');
        $row['full_name']  = (string) ($row['full_name'] ?? '');
        $row['phone']      = (string) ($row['phone'] ?? '');
        $row['email']      = (string) ($row['email'] ?? '');
        $row['course']     = (string) ($row['course'] ?? '');
        $row['branch']     = (string) ($row['branch'] ?? '');
        $row['message']    = (string) ($row['message'] ?? '');
        $row['branch_key'] = leads_branch_key($row['branch']);

        $leads[] = $row;
    }

    // Newest enquiry first: that is the one the counselor has to call.
    usort($leads, function ($a, $b) {
        return strcmp($b['timestamp'], $a['timestamp']);
    });

    return $leads;
}

/** Removes one lead by its key. Returns true when a record was dropped. */
function leads_delete($key)
{
    if (!is_file(LEADS_FILE)) {
        return false;
    }

    $decoded = json_decode((string) file_get_contents(LEADS_FILE), true);
    if (!is_array($decoded)) {
        return false;
    }

    $kept    = [];
    $removed = false;
    foreach ($decoded as $row) {
        if (!is_array($row)) {
            continue;
        }
        $rowKey = isset($row['id']) && $row['id'] !== '' ? (string) $row['id'] : sha1(json_encode($row));
        if (!$removed && $rowKey === $key) {
            $removed = true;
            continue;
        }
        $kept[] = $row;
    }

    if ($removed) {
        file_put_contents(LEADS_FILE, json_encode($kept, JSON_PRETTY_PRINT), LOCK_EX);
    }

    return $removed;
}

/**
 * The branches a lead can belong to, in the order the admin shows them.
 * Keys match $branches in includes/data.php; 'other' catches anything else.
 */
function leads_branch_labels()
{
    global $branches;

    $labels = [];
    foreach (['perinthalmanna', 'kochi', 'manjeri', 'nilambur'] as $key) {
        $labels[$key] = isset($branches[$key]['name']) ? $branches[$key]['name'] : ucfirst($key);
    }
    $labels['other'] = 'Not specified';

    return $labels;
}

/**
 * Works out which branch a stored label belongs to. The text is matched on the
 * town name because the wording has changed over time ("Perinthalmanna (Head
 * Office)" in the early leads, "OFSYS - Perinthalmanna" in the current forms).
 */
function leads_branch_key($branch)
{
    $branch = strtolower($branch);
    foreach (['perinthalmanna', 'kochi', 'manjeri', 'nilambur'] as $key) {
        if ($branch !== '' && strpos($branch, $key) !== false) {
            return $key;
        }
    }

    return 'other';
}

/** How many leads each branch has, keyed the same way as the labels. */
function leads_branch_counts(array $leads)
{
    $counts = array_fill_keys(array_keys(leads_branch_labels()), 0);
    foreach ($leads as $lead) {
        $counts[$lead['branch_key']]++;
    }

    return $counts;
}

/** A readable name for the form a lead came from. */
function leads_form_label($type)
{
    $labels = [
        'discount_modal'   => 'Popup Enquiry',
        'contact_page'     => 'Contact Page',
        'course_sidebar'   => 'Course Page',
        'general_enquiry'  => 'General Enquiry',
    ];

    if (isset($labels[$type])) {
        return $labels[$type];
    }

    return $type === '' ? 'Enquiry' : ucwords(str_replace(['_', '-'], ' ', $type));
}

/** Applies the branch / form / search / date filters from the query string. */
function leads_filter(array $leads, array $f)
{
    $branch = $f['branch'] ?? '';
    $type   = $f['type'] ?? '';
    $query  = strtolower(trim($f['q'] ?? ''));
    $from   = $f['from'] ?? '';
    $to     = $f['to'] ?? '';

    return array_values(array_filter($leads, function ($lead) use ($branch, $type, $query, $from, $to) {
        if ($branch !== '' && $lead['branch_key'] !== $branch) {
            return false;
        }
        if ($type !== '' && $lead['form_type'] !== $type) {
            return false;
        }
        if ($from !== '' && substr($lead['timestamp'], 0, 10) < $from) {
            return false;
        }
        if ($to !== '' && substr($lead['timestamp'], 0, 10) > $to) {
            return false;
        }
        if ($query !== '') {
            $haystack = strtolower(implode(' ', [
                $lead['full_name'], $lead['phone'], $lead['email'],
                $lead['course'], $lead['branch'], $lead['message'],
            ]));
            if (strpos($haystack, $query) === false) {
                return false;
            }
        }

        return true;
    }));
}

/** Digits-only phone, with the country code, for a wa.me link. */
function leads_wa_number($phone)
{
    $digits = preg_replace('/\D+/', '', $phone);
    if ($digits === '') {
        return '';
    }
    if (strlen($digits) === 10) {
        $digits = '91' . $digits;
    } elseif (strpos($digits, '0') === 0) {
        $digits = '91' . ltrim($digits, '0');
    }

    return $digits;
}
