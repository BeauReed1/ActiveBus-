<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Shop | <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    .shop-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 24px;
    }

    .tabs {
      display: flex;
      gap: 12px;
      margin-bottom: 24px;
      border-bottom: 2px solid #e2e8f0;
    }

    .tab {
      padding: 12px 20px;
      border: none;
      background: none;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 500;
      color: #64748b;
      border-bottom: 3px solid transparent;
      transition: all 0.2s;
    }

    .tab.active {
      color: #2563eb;
      border-bottom-color: #2563eb;
    }

    .tab:hover {
      color: #2563eb;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .maintenance-card {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 16px;
      display: flex;
      justify-content: space-between;
      align-items: start;
      gap: 16px;
    }

    .maintenance-info {
      flex: 1;
    }

    .maintenance-bus {
      font-size: 1.2rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 8px;
    }

    .maintenance-issue {
      color: #64748b;
      margin-bottom: 12px;
    }

    .maintenance-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
      font-size: 0.9rem;
      margin-top: 12px;
    }

    .detail-item {
      color: #64748b;
    }

    .detail-label {
      font-weight: 600;
      color: #1e293b;
    }

    .maintenance-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .note-item {
      background: #f1f5f9;
      border-left: 4px solid #2563eb;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 16px;
    }

    .note-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }

    .note-bus {
      font-weight: 700;
      color: #1e293b;
    }

    .note-driver {
      font-size: 0.9rem;
      color: #64748b;
    }

    .note-time {
      font-size: 0.85rem;
      color: #94a3b8;
    }

    .note-text {
      color: #1e293b;
      line-height: 1.5;
    }

    @media (max-width: 768px) {
      .maintenance-card {
        flex-direction: column;
      }

      .maintenance-actions {
        justify-content: flex-start;
      }

      .maintenance-details {
        grid-template-columns: 1fr;
      }

      .tabs {
        flex-wrap: wrap;
      }
    }
  </style>
</head>
<body>
  <?php render_header("Bus Shop Management", ["Shop" => "bus_shop.php"]); ?>

  <div class="shop-container">
    <div class="page-header" style="margin-bottom: 32px;">
      <h1 class="page-title">🔧 Bus Shop & Maintenance</h1>
      <p class="page-subtitle">Manage fleet maintenance, repairs, and vehicle status</p>
    </div>

    <!-- TABS -->
    <div class="tabs">
      <button class="tab active" onclick="showTab('maintenance')">📋 Maintenance Queue</button>
      <button class="tab" onclick="showTab('notes')">📝 Driver Notes</button>
      <button class="tab" onclick="showTab('inventory')">📦 Inventory</button>
      <button class="tab" onclick="showTab('reports')">📊 Reports</button>
    </div>

    <!-- MAINTENANCE QUEUE TAB -->
    <div id="maintenance" class="tab-content active">
      <div style="margin-bottom: 24px;">
        <h2 style="color: #1e293b;">Pending Maintenance</h2>
        <p style="color: #64748b;">Tasks that need attention</p>
      </div>

      <div class="maintenance-card">
        <div class="maintenance-info">
          <div class="maintenance-bus">🚌 Bus 103</div>
          <div class="maintenance-issue"><strong>Brake inspection needed</strong></div>
          <div class="maintenance-details">
            <div class="detail-item">
              <div class="detail-label">Status</div>
              <div>Urgent</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Reported</div>
              <div>2 hours ago</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Miles</div>
              <div>45,230</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Last Service</div>
              <div>30 days ago</div>
            </div>
          </div>
        </div>
        <div class="maintenance-actions">
          <button class="btn btn-primary btn-sm">In Progress</button>
          <button class="btn btn-secondary btn-sm">Details</button>
        </div>
      </div>

      <div class="maintenance-card">
        <div class="maintenance-info">
          <div class="maintenance-bus">🚌 Bus 107</div>
          <div class="maintenance-issue"><strong>Oil change due</strong></div>
          <div class="maintenance-details">
            <div class="detail-item">
              <div class="detail-label">Status</div>
              <div>Normal</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Reported</div>
              <div>5 hours ago</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Miles</div>
              <div>67,890</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Next Due</div>
              <div>500 miles</div>
            </div>
          </div>
        </div>
        <div class="maintenance-actions">
          <button class="btn btn-primary btn-sm">In Progress</button>
          <button class="btn btn-secondary btn-sm">Details</button>
        </div>
      </div>

      <div class="maintenance-card">
        <div class="maintenance-info">
          <div class="maintenance-bus">🚌 Bus 112</div>
          <div class="maintenance-issue"><strong>Tire rotation needed</strong></div>
          <div class="maintenance-details">
            <div class="detail-item">
              <div class="detail-label">Status</div>
              <div>Normal</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Reported</div>
              <div>Yesterday</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Miles</div>
              <div>52,100</div>
            </div>
            <div class="detail-item">
              <div class="detail-label">Tire Condition</div>
              <div>Good</div>
            </div>
          </div>
        </div>
        <div class="maintenance-actions">
          <button class="btn btn-primary btn-sm">Schedule</button>
          <button class="btn btn-secondary btn-sm">Details</button>
        </div>
      </div>
    </div>

    <!-- DRIVER NOTES TAB -->
    <div id="notes" class="tab-content">
      <div style="margin-bottom: 24px;">
        <h2 style="color: #1e293b;">Driver Maintenance Reports</h2>
        <p style="color: #64748b;">Notes from drivers about bus conditions</p>
      </div>

      <div class="note-item">
        <div class="note-header">
          <div>
            <div class="note-bus">Bus 101 - John Smith</div>
            <div class="note-driver">Driver Report</div>
          </div>
          <div class="note-time">30 minutes ago</div>
        </div>
        <div class="note-text">
          Bus is making a squeaking noise when braking on the right side. Recommend immediate brake inspection. Vehicle is safe to operate but should be serviced soon.
        </div>
      </div>

      <div class="note-item">
        <div class="note-header">
          <div>
            <div class="note-bus">Bus 105 - Sarah Johnson</div>
            <div class="note-driver">Driver Report</div>
          </div>
          <div class="note-time">2 hours ago</div>
        </div>
        <div class="note-text">
          Check engine light came on during morning route. Vehicle performance seems normal. May need diagnostic scan.
        </div>
      </div>

      <div class="note-item">
        <div class="note-header">
          <div>
            <div class="note-bus">Bus 103 - Mike Davis</div>
            <div class="note-driver">Driver Report</div>
          </div>
          <div class="note-time">4 hours ago</div>
        </div>
        <div class="note-text">
          Windshield wipers need replacement. Left wiper blade is worn and not clearing properly. Safety concern for wet weather driving.
        </div>
      </div>
    </div>

    <!-- INVENTORY TAB -->
    <div id="inventory" class="tab-content">
      <div style="margin-bottom: 24px;">
        <h2 style="color: #1e293b;">Maintenance Inventory</h2>
        <p style="color: #64748b;">Common parts and supplies stock</p>
      </div>

      <div class="grid grid-2">
        <div class="card">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
              <div style="font-weight: 700; color: #1e293b;">Oil Filters</div>
              <div style="font-size: 0.9rem; color: #64748b;">Qty: 24 units</div>
            </div>
            <span class="badge badge-success">In Stock</span>
          </div>
          <div style="background: #f1f5f9; padding: 8px; border-radius: 6px; font-size: 0.9rem;">
            <strong>Need to reorder:</strong> 10 units
          </div>
        </div>

        <div class="card">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
              <div style="font-weight: 700; color: #1e293b;">Air Filters</div>
              <div style="font-size: 0.9rem; color: #64748b;">Qty: 8 units</div>
            </div>
            <span class="badge badge-warning">Low Stock</span>
          </div>
          <div style="background: #fffbeb; padding: 8px; border-radius: 6px; font-size: 0.9rem;">
            <strong>⚠️ Order soon:</strong> 15 units needed
          </div>
        </div>

        <div class="card">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
              <div style="font-weight: 700; color: #1e293b;">Brake Pads</div>
              <div style="font-size: 0.9rem; color: #64748b;">Qty: 6 sets</div>
            </div>
            <span class="badge badge-error">Critical</span>
          </div>
          <div style="background: #fef2f2; padding: 8px; border-radius: 6px; font-size: 0.9rem;">
            <strong>🔴 Order immediately:</strong> 20 sets needed
          </div>
        </div>

        <div class="card">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
              <div style="font-weight: 700; color: #1e293b;">Wiper Blades</div>
              <div style="font-size: 0.9rem; color: #64748b;">Qty: 12 pairs</div>
            </div>
            <span class="badge badge-success">In Stock</span>
          </div>
          <div style="background: #f1f5f9; padding: 8px; border-radius: 6px; font-size: 0.9rem;">
            <strong>Next reorder:</strong> 2 weeks
          </div>
        </div>
      </div>
    </div>

    <!-- REPORTS TAB -->
    <div id="reports" class="tab-content">
      <div style="margin-bottom: 24px;">
        <h2 style="color: #1e293b;">Reports & Analytics</h2>
        <p style="color: #64748b;">Fleet maintenance statistics</p>
      </div>

      <div class="grid grid-2">
        <div class="card">
          <div style="margin-bottom: 16px;">
            <h3 style="margin: 0 0 8px 0;">Fleet Status</h3>
            <div style="font-size: 0.9rem; color: #64748b;">Current operational status</div>
          </div>
          <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between;">
              <span>Operating</span>
              <strong style="color: #10b981;">22/24 buses</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
              <span>In Maintenance</span>
              <strong style="color: #f59e0b;">2/24 buses</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
              <span>Uptime</span>
              <strong style="color: #2563eb;">91.7%</strong>
            </div>
          </div>
        </div>

        <div class="card">
          <div style="margin-bottom: 16px;">
            <h3 style="margin: 0 0 8px 0;">Recent Activity</h3>
            <div style="font-size: 0.9rem; color: #64748b;">Last 30 days</div>
          </div>
          <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between;">
              <span>Services Completed</span>
              <strong>18</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
              <span>Avg. Service Time</span>
              <strong>3.2 hours</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
              <span>Cost</span>
              <strong>$12,450</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php render_footer(); ?>

  <script>
    function showTab(tabName) {
      const contents = document.querySelectorAll('.tab-content');
      const tabs = document.querySelectorAll('.tab');
      
      contents.forEach(content => content.classList.remove('active'));
      tabs.forEach(tab => tab.classList.remove('active'));
      
      document.getElementById(tabName).classList.add('active');
      event.target.classList.add('active');
    }
  </script>
</body>
</html>
