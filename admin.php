<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    .admin-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 24px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 32px;
    }

    .stat-card {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
      font-size: 2.5rem;
      margin-bottom: 8px;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #2563eb;
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #64748b;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 24px;
      margin-bottom: 24px;
    }

    .admin-card {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .admin-card h3 {
      margin-top: 0;
      margin-bottom: 16px;
      color: #1e293b;
    }

    .admin-list {
      list-style: none;
    }

    .admin-list li {
      padding: 12px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .admin-list li:last-child {
      border-bottom: none;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-sm {
      padding: 6px 12px;
      font-size: 0.85rem;
    }

    #adminMap {
      width: 100%;
      height: 400px;
      border-radius: 8px;
    }

    @media (max-width: 768px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }

      #adminMap {
        height: 300px;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>
<body>
  <?php render_header("Admin Dashboard", ["Administration" => "admin.php"]); ?>

  <div class="admin-container">
    <div class="page-header">
      <h1 class="page-title">📊 Administration Dashboard</h1>
      <p class="page-subtitle">Manage your district's bus fleet and operations</p>
    </div>

    <!-- STATS SECTION -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">🚌</div>
        <div class="stat-value">24</div>
        <div class="stat-label">Total Buses</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">18</div>
        <div class="stat-label">Active Drivers</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✓</div>
        <div class="stat-value">22</div>
        <div class="stat-label">Buses Operating</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value">3</div>
        <div class="stat-label">Maintenance Issues</div>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="dashboard-grid">
      <!-- LIVE MAP -->
      <div class="admin-card" style="grid-column: 1 / -1;">
        <h3>🗺️ Live Fleet Map</h3>
        <div id="adminMap"></div>
      </div>

      <!-- ACTIVE BUSES -->
      <div class="admin-card">
        <h3>🚌 Active Buses</h3>
        <ul class="admin-list">
          <li>
            <span><strong>Bus 101</strong> - Route A</span>
            <span class="badge badge-success">Active</span>
          </li>
          <li>
            <span><strong>Bus 102</strong> - Route B</span>
            <span class="badge badge-success">Active</span>
          </li>
          <li>
            <span><strong>Bus 103</strong> - Route C</span>
            <span class="badge badge-warning">Maintenance</span>
          </li>
          <li>
            <span><strong>Bus 104</strong> - Route D</span>
            <span class="badge badge-success">Active</span>
          </li>
          <li style="border-bottom: none; text-align: center; padding: 16px;">
            <a href="#" class="btn btn-outline btn-sm">View All Buses</a>
          </li>
        </ul>
      </div>

      <!-- DRIVER STATUS -->
      <div class="admin-card">
        <h3>👨‍✈️ Driver Status</h3>
        <ul class="admin-list">
          <li>
            <span><strong>John Smith</strong></span>
            <span class="badge badge-success">On Duty</span>
          </li>
          <li>
            <span><strong>Sarah Johnson</strong></span>
            <span class="badge badge-success">On Duty</span>
          </li>
          <li>
            <span><strong>Mike Davis</strong></span>
            <span class="badge badge-error">Off Duty</span>
          </li>
          <li>
            <span><strong>Emma Wilson</strong></span>
            <span class="badge badge-success">On Duty</span>
          </li>
          <li style="border-bottom: none; text-align: center; padding: 16px;">
            <a href="#" class="btn btn-outline btn-sm">View All Drivers</a>
          </li>
        </ul>
      </div>

      <!-- MAINTENANCE QUEUE -->
      <div class="admin-card">
        <h3>🔧 Pending Maintenance</h3>
        <ul class="admin-list">
          <li>
            <div>
              <strong>Bus 103</strong><br>
              <small style="color: #64748b;">Brake inspection needed</small>
            </div>
            <span class="badge badge-warning">Urgent</span>
          </li>
          <li>
            <div>
              <strong>Bus 107</strong><br>
              <small style="color: #64748b;">Oil change due</small>
            </div>
            <span class="badge badge-info">Normal</span>
          </li>
          <li>
            <div>
              <strong>Bus 112</strong><br>
              <small style="color: #64748b;">Tire rotation</small>
            </div>
            <span class="badge badge-info">Normal</span>
          </li>
          <li style="border-bottom: none; text-align: center; padding: 16px;">
            <a href="#" class="btn btn-outline btn-sm">View All Issues</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="admin-card">
      <h3>⚡ Quick Actions</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
        <button class="btn btn-primary">➕ Add Bus</button>
        <button class="btn btn-primary">➕ Add Driver</button>
        <button class="btn btn-secondary">📋 View Reports</button>
        <button class="btn btn-secondary">⚙️ Settings</button>
        <button class="btn btn-secondary">📧 Send Message</button>
        <button class="btn btn-secondary">📊 Analytics</button>
      </div>
    </div>
  </div>

  <?php render_footer(); ?>

  <script>
    const adminMap = L.map("adminMap").setView([32.2988, -90.1848], 12);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(adminMap);

    // Sample bus markers
    const buses = [
      {num: 101, lat: 32.3, lng: -90.18, status: "active"},
      {num: 102, lat: 32.29, lng: -90.19, status: "active"},
      {num: 103, lat: 32.31, lng: -90.17, status: "inactive"},
    ];

    buses.forEach(bus => {
      L.marker([bus.lat, bus.lng], {
        icon: L.divIcon({
          html: '<div style="font-size: 1.5rem;">🚌</div>',
          iconSize: [32, 32]
        })
      }).addTo(adminMap).bindPopup(`Bus ${bus.num}<br>Status: ${bus.status}`);
    });
  </script>
</body>
</html>
