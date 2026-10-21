<?php
/**
 * OFSYS Admin - enquiry leads, with a branch-wise breakdown.
 */
require_once __DIR__ . '/includes/bootstrap.php';
admin_require_login();
require_once __DIR__ . '/includes/leads-store.php';

$self   = ADMIN_PATH . '/leads.php';
$leads  = leads_all();
$labels = leads_branch_labels();
$counts = leads_branch_counts($leads);

// Delete one lead, then come back to the same filtered view.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') === 'delete') {
    admin_verify_csrf();
    $removed = leads_delete((string) ($_POST['key'] ?? ''));
    admin_flash($removed ? 'Lead deleted.' : 'That lead was not found.', $removed ? 'success' : 'error');
    $back = http_build_query(array_filter([
        'branch' => $_POST['branch'] ?? '',
        'type'   => $_POST['type'] ?? '',
        'q'      => $_POST['q'] ?? '',
        'from'   => $_POST['from'] ?? '',
        'to'     => $_POST['to'] ?? '',
    ]));
    header('Location: ' . $self . ($back !== '' ? '?' . $back : ''));
    exit;
}

$filters = [
    'branch' => isset($_GET['branch']) && isset($labels[$_GET['branch']]) ? (string) $_GET['branch'] : '',
    'type'   => isset($_GET['type']) ? trim(strip_tags((string) $_GET['type'])) : '',
    'q'      => isset($_GET['q']) ? trim(strip_tags((string) $_GET['q'])) : '',
    'from'   => isset($_GET['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $_GET['from']) ? (string) $_GET['from'] : '',
    'to'     => isset($_GET['to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $_GET['to']) ? (string) $_GET['to'] : '',
];

$rows = leads_filter($leads, $filters);

// Only the form types actually present, so the dropdown stays honest.
$formTypes = [];
foreach ($leads as $lead) {
    $formTypes[$lead['form_type']] = leads_form_label($lead['form_type']);
}
asort($formTypes);

// Download the list exactly as filtered on screen.
if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="ofsys-leads-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // so Excel opens the file as UTF-8
    fputcsv($out, ['Date', 'Name', 'Phone', 'Email', 'Course', 'Branch', 'Came from', 'Message']);
    foreach ($rows as $row) {
        fputcsv($out, [
            $row['timestamp'], $row['full_name'], $row['phone'], $row['email'],
            $row['course'], $row['branch'], leads_form_label($row['form_type']), $row['message'],
        ]);
    }
    fclose($out);
    exit;
}

/** The current filters as a query string, with some values swapped out. */
$linkWith = function (array $override) use ($filters) {
    $query = http_build_query(array_filter(array_merge($filters, $override)));

    return $query === '' ? '' : '?' . $query;
};

$shortName = function ($label) {
    return str_replace('OFSYS - ', '', $label);
};

$adminTitle = 'Leads';
include __DIR__ . '/includes/header.php';
?>

<!-- Branch-wise totals: the four campuses, plus anything with no branch -->
<div class="ad-stat-grid">
  <a class="ad-stat <?php echo $filters['branch'] === '' ? 'is-active' : ''; ?>"
     href="<?php echo $self . $linkWith(['branch' => '']); ?>">
    <span class="ad-stat-icon"><i class="fa-solid fa-address-book"></i></span>
    <span class="ad-stat-value"><?php echo count($leads); ?></span>
    <span class="ad-stat-label">All Leads</span>
    <span class="ad-stat-sub">all four branches together</span>
  </a>
  <?php foreach ($labels as $key => $label): ?>
    <?php if ($key === 'other' && (int) $counts[$key] === 0) { continue; } ?>
    <a class="ad-stat <?php echo $filters['branch'] === $key ? 'is-active' : ''; ?>"
       href="<?php echo $self . $linkWith(['branch' => $key]); ?>">
      <span class="ad-stat-icon">
        <i class="fa-solid <?php echo $key === 'other' ? 'fa-circle-question' : 'fa-location-dot'; ?>"></i>
      </span>
      <span class="ad-stat-value"><?php echo (int) $counts[$key]; ?></span>
      <span class="ad-stat-label"><?php echo e($shortName($label)); ?></span>
      <span class="ad-stat-sub"><?php echo $key === 'other' ? 'no branch chosen' : 'leads for this branch'; ?></span>
    </a>
  <?php endforeach; ?>
</div>

<div class="ad-card">
  <div class="ad-card-head">
    <h2 class="ad-card-title">
      <?php echo $filters['branch'] === '' ? 'All Branches' : e($shortName($labels[$filters['branch']])); ?>
      <span class="ad-count"><?php echo count($rows); ?></span>
    </h2>
    <a class="ad-btn ad-btn-line" href="<?php echo $self . $linkWith(['export' => 'csv']); ?>">
      <i class="fa-solid fa-file-csv"></i> Download as Excel (CSV)
    </a>
  </div>

  <form class="ad-filter" method="GET" action="<?php echo $self; ?>">
    <div>
      <label class="ad-label" for="fBranch">Branch</label>
      <select class="ad-input" id="fBranch" name="branch">
        <option value="">All branches (<?php echo count($leads); ?>)</option>
        <?php foreach ($labels as $key => $label): ?>
          <option value="<?php echo e($key); ?>" <?php echo $filters['branch'] === $key ? 'selected' : ''; ?>>
            <?php echo e($shortName($label)); ?> (<?php echo (int) $counts[$key]; ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="ad-label" for="fType">Came from</label>
      <select class="ad-input" id="fType" name="type">
        <option value="">All forms</option>
        <?php foreach ($formTypes as $key => $label): ?>
          <option value="<?php echo e($key); ?>" <?php echo $filters['type'] === (string) $key ? 'selected' : ''; ?>>
            <?php echo e($label); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="ad-label" for="fFrom">From</label>
      <input class="ad-input" type="date" id="fFrom" name="from" value="<?php echo e($filters['from']); ?>">
    </div>
    <div>
      <label class="ad-label" for="fTo">To</label>
      <input class="ad-input" type="date" id="fTo" name="to" value="<?php echo e($filters['to']); ?>">
    </div>
    <div class="ad-filter-grow">
      <label class="ad-label" for="fQ">Search</label>
      <input class="ad-input" type="search" id="fQ" name="q" value="<?php echo e($filters['q']); ?>"
             placeholder="Name, phone, email or course">
    </div>
    <div class="ad-filter-actions">
      <button type="submit" class="ad-btn ad-btn-primary"><i class="fa-solid fa-filter"></i> Apply</button>
      <a class="ad-btn ad-btn-line" href="<?php echo $self; ?>">Reset</a>
    </div>
  </form>

  <?php if (!$rows): ?>
    <div class="ad-empty">
      <i class="fa-solid fa-address-book"></i>
      <p><?php echo $leads
            ? 'No leads match these filters.'
            : 'No enquiries yet. Everything sent through the website forms shows up here.'; ?></p>
    </div>
  <?php else: ?>
    <div class="ad-table-wrap">
      <table class="ad-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Student</th>
            <th>Course</th>
            <th>Branch</th>
            <th>Came from</th>
            <th>Message</th>
            <th class="ad-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <?php
              $stamp = $row['timestamp'] !== '' ? strtotime($row['timestamp']) : false;
              $wa    = leads_wa_number($row['phone']);
            ?>
            <tr>
              <td>
                <?php echo $stamp ? e(date('d M Y', $stamp)) : '&mdash;'; ?>
                <?php if ($stamp): ?><small><?php echo e(date('g:i A', $stamp)); ?></small><?php endif; ?>
              </td>
              <td>
                <strong><?php echo e($row['full_name']); ?></strong>
                <?php if ($row['phone'] !== ''): ?>
                  <small><a href="tel:<?php echo e($row['phone']); ?>"><?php echo e($row['phone']); ?></a></small>
                <?php endif; ?>
                <?php if ($row['email'] !== ''): ?>
                  <small><a href="mailto:<?php echo e($row['email']); ?>"><?php echo e($row['email']); ?></a></small>
                <?php endif; ?>
              </td>
              <td><?php echo $row['course'] !== '' ? e($row['course']) : '&mdash;'; ?></td>
              <td>
                <span class="ad-branch-tag is-<?php echo e($row['branch_key']); ?>">
                  <?php echo e($row['branch'] !== '' ? $shortName($row['branch']) : 'Not specified'); ?>
                </span>
              </td>
              <td><?php echo e(leads_form_label($row['form_type'])); ?></td>
              <td class="ad-msg"><?php echo $row['message'] !== '' ? e($row['message']) : '&mdash;'; ?></td>
              <td class="ad-right">
                <?php if ($wa !== ''): ?>
                  <a class="ad-icon-btn" href="https://wa.me/<?php echo e($wa); ?>" target="_blank" rel="noopener" title="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                  </a>
                <?php endif; ?>
                <?php if ($row['phone'] !== ''): ?>
                  <a class="ad-icon-btn" href="tel:<?php echo e($row['phone']); ?>" title="Call">
                    <i class="fa-solid fa-phone"></i>
                  </a>
                <?php endif; ?>
                <form method="POST" action="<?php echo $self; ?>" class="ad-inline"
                      data-confirm="Delete this lead? This cannot be undone.">
                  <?php echo admin_csrf_field(); ?>
                  <input type="hidden" name="do" value="delete">
                  <input type="hidden" name="key" value="<?php echo e($row['key']); ?>">
                  <?php foreach ($filters as $fName => $fValue): ?>
                    <input type="hidden" name="<?php echo e($fName); ?>" value="<?php echo e($fValue); ?>">
                  <?php endforeach; ?>
                  <button type="submit" class="ad-icon-btn is-danger" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
