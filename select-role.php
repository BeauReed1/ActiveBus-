<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Role - <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php render_header("Role Selection", null, false); ?>

  <div style="max-width: 1000px; margin: 0 auto; padding: 40px 20px;">
    <div style="text-align: center; margin-bottom: 48px;">
      <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 8px;">Select Your Role</h1>
      <p style="font-size: 1.1rem; color: #64748b;">Choose how you want to access the system</p>
    </div>

    <div class="grid grid-2">
      <a href="parent.php" class="role-card">
        <div class="role-icon">👨‍👩‍👧</div>
        <div class="role-title">Parent & Student</div>
        <div class="role-description">Track your child's bus location in real-time</div>
      </a>

      <a href="driver.php" class="role-card">
        <div class="role-icon">🚌</div>
        <div class="role-title">Bus Driver</div>
        <div class="role-description">Share your location and report maintenance issues</div>
      </a>

      <a href="bus_shop.php" class="role-card">
        <div class="role-icon">🔧</div>
        <div class="role-title">Bus Shop & Maintenance</div>
        <div class="role-description">Manage vehicle maintenance and fleet operations</div>
      </a>

      <a href="admin.php" class="role-card">
        <div class="role-icon">🏫</div>
        <div class="role-title">School Admin</div>
        <div class="role-description">Monitor buses, drivers, and operations</div>
      </a>

      <a href="it.php" class="role-card">
        <div class="role-icon">💻</div>
        <div class="role-title">IT Console</div>
        <div class="role-description">System monitoring and telemetry data</div>
      </a>

      <a href="billing.php" class="role-card">
        <div class="role-icon">💳</div>
        <div class="role-title">Billing & Account</div>
        <div class="role-description">Manage subscription and billing information</div>
      </a>
    </div>
  </div>

  <?php render_footer(); ?>
</body>
</html>
