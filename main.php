<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ActiveBus - <?php echo htmlspecialchars($school); ?> Bus Tracking</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .hero {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: white;
      padding: 60px 40px;
      text-align: center;
      margin-bottom: 60px;
    }
    
    .hero h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 16px;
    }
    
    .hero p {
      font-size: 1.1rem;
      opacity: 0.95;
    }
    
    .roles-section {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .roles-title {
      text-align: center;
      margin-bottom: 48px;
    }
    
    .roles-title h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 8px;
    }
    
    .roles-title p {
      font-size: 1.1rem;
      color: #64748b;
    }
    
    @media (max-width: 768px) {
      .hero {
        padding: 40px 20px;
      }
      
      .hero h1 {
        font-size: 1.8rem;
      }
      
      .roles-title h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="hero">
    <h1>🚌 ActiveBus</h1>
    <p>Real-time GPS tracking and fleet management for <?php echo htmlspecialchars($school); ?></p>
  </div>

  <div class="roles-section">
    <div class="roles-title">
      <h2>Select Your Role</h2>
      <p>Choose how you want to access the system</p>
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
