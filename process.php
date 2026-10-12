<?php
// Server-side processing: never trust a fee calculated only in the browser.
function clean_input(string $key): string {
    return trim((string)($_POST[$key] ?? ''));
}
function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
function money(int $amount): string {
    return 'KSh ' . number_format($amount);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$fullName = clean_input('full_name');
$admissionNo = clean_input('admission_no');
$email = clean_input('email');
$phone = clean_input('phone');
$year = clean_input('year_of_study');
$category = clean_input('category');
$daysRaw = clean_input('days');
$meal = clean_input('meal');
$workshop = clean_input('workshop');

$errors = [];
if ($fullName === '') $errors[] = 'Full name is required.';
if ($admissionNo === '') $errors[] = 'Admission number is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
if (!preg_match('/^[0-9]{10}$/', $phone)) $errors[] = 'Phone number must contain exactly 10 digits.';
if (!in_array($year, ['1','2','3','4','5','6'], true)) $errors[] = 'Select a valid year of study.';
if (!in_array($category, ['conference','workshop'], true)) $errors[] = 'Select a participation category.';
if (!in_array($daysRaw, ['1','2','3'], true)) $errors[] = 'Conference days must be between 1 and 3.';
if (!in_array($meal, ['yes','no'], true)) $errors[] = 'Select a meal option.';
if ($category === 'workshop' && !in_array($workshop, ['Web Development','Cybersecurity Basics','Data Analytics'], true)) {
    $errors[] = 'Select a valid workshop.';
}

if ($errors) {
    http_response_code(400);
    ?>
    <!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Registration Error</title><link rel="stylesheet" href="css/style.css"></head><body>
    <main class="confirmation-wrap"><section class="confirmation-card"><p class="eyebrow">CHECK YOUR DETAILS</p><h1>Registration not completed</h1><p>Please return to the form and correct the following:</p><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul><a class="button" href="register.html">Return to form</a></section></main></body></html>
    <?php
    exit;
}

$days = (int)$daysRaw;
$registrationFee = 1000;
$attendanceFee = $days * 500;
$workshopFee = ($category === 'workshop') ? 750 : 0;
$mealFee = ($meal === 'yes') ? $days * 300 : 0;

// Individual requirement for admission numbers ending in 0:
// 10% discount on registration + attendance when attending 3 days.
$discount = ($days === 3) ? (int)round(($registrationFee + $attendanceFee) * 0.10) : 0;
$total = $registrationFee + $attendanceFee + $workshopFee + $mealFee - $discount;
$categoryLabel = ($category === 'workshop') ? 'Workshop Participant' : 'Conference Only';
$workshopLabel = ($category === 'workshop') ? $workshop : 'Not selected';
$mealLabel = ($meal === 'yes') ? 'Yes' : 'No';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Successful | STI Conference</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header"><a class="brand" href="index.html"><span class="brand-mark">ST</span><span>STI Conference</span></a><nav><a href="index.html">Home</a><a href="register.html">Register</a></nav></header>
  <main class="confirmation-wrap">
    <section class="confirmation-card">
      <div class="success-icon">✓</div><p class="eyebrow">REGISTRATION COMPLETE</p><h1>Registration successful!</h1>
      <p class="muted">Thank you, <?= h($fullName) ?>. Your registration details have been received.</p>
      <div class="confirmation-details">
        <div><span>Participant</span><strong><?= h($fullName) ?></strong></div>
        <div><span>Admission number</span><strong><?= h($admissionNo) ?></strong></div>
        <div><span>Email</span><strong><?= h($email) ?></strong></div>
        <div><span>Phone</span><strong><?= h($phone) ?></strong></div>
        <div><span>Year of study</span><strong>Year <?= h($year) ?></strong></div>
        <div><span>Participation</span><strong><?= h($categoryLabel) ?></strong></div>
        <div><span>Conference days</span><strong><?= $days ?></strong></div>
        <div><span>Workshop</span><strong><?= h($workshopLabel) ?></strong></div>
        <div><span>Lunch included</span><strong><?= h($mealLabel) ?></strong></div>
      </div>
      <div class="server-total"><span>Total amount payable</span><strong><?= money($total) ?></strong></div>
      <?php if ($discount > 0): ?><p class="discount-note">Your 3-day discount saved you <?= money($discount) ?>.</p><?php endif; ?>
      <p class="hint">This amount was recalculated by PHP on the server.</p>
      <div class="form-actions"><a class="text-link" href="index.html">Home</a><a class="button" href="register.html">Register another participant</a></div>
    </section>
  </main>
  <footer><span>© 2026 Student Technology &amp; Innovation Conference</span><span>Thank you for participating</span></footer>
</body>
</html>
