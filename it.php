<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IT Console | <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    .console-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 24px;
    }

    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 16px;
      margin-bottom: 32px;
    }

    .metric {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .metric-label {
      font-size: 0.9rem;
      color: #64748b;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .metric-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 8px;
    }

    .metric-status {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.85rem;
    }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #10b981;
    }

    .status-dot.warning {
      background: #f59e0b;
    }

    .status-dot.error {
      background: #ef4444;
    }

    .console-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 24px;
      margin-bottom: 24px;
    }

    .console-section {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .console-section h3 {
      margin-top: 0;
      margin-bottom: 16px;
      color: #1e293b;
      font-size: 1.1rem;
    }

    .log-entry {
      padding: 12px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 0.9rem;
      font-family: 'Monaco', 'Courier New', monospace;
      color: #64748b;
    }

    .log-entry:last-child {
      border-bottom: none;
    }

    .log-time {
      color: #94a3b8;
      margin-right: 8px;
    }

    .log-error {
      color: #ef4444;
    }

    .log-warning {
      color: #f59e0b;
    }

    .log-success {
      color: #10b981;
    }

    .server-check {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px;
      border-bottom: 1px solid #f1f5f9;
    }

    .server-check:last-child {
      border-bottom: none;
    }

    .server-status {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .stats-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }

    .stats-table th {
      background: #f1f5f9;
      padding: 12px;
      text-align: left;
      font-weight: 600;
      color: #1e293b;
      border-bottom: 1px solid #e2e8f0;
    }

    .stats-table td {
      padding: 12px;
      border-bottom: 1px solid #f1f5f9;
    }

    .stats-table tr:last-child td {
      border-bottom: none;
    }

    @media (max-width: 768px) {
      .metrics-grid {
        grid-template-columns: 1fr;
      }

      .console-grid {
        grid-template-columns: 1fr;
      }

      .console-section {
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>
  <?php render_header("IT Telemetry", ["System" => "it.php"]); ?>

  <div class="console-container">
    <div class="page-header" style="margin-bottom: 32px;">
      <h1 class="page-title">💻 IT Telemetry Console</h1>
      <p class="page-subtitle">System monitoring and performance metrics</p>
    </div>

    <!-- KEY METRICS -->
    <div class="metrics-grid">
      <div class="metric">
        <div class="metric-label">🟢 System Health</div>
        <div class="metric-value">98.5%</div>
        <div class="metric-status">
          <div class="status-dot"></div>
          <span>All systems operational</span>
        </div>
      </div>

      <div class="metric">
        <div class="metric-label">📡 API Uptime</div>
        <div class="metric-value">99.9%</div>
        <div class="metric-status">
          <div class="status-dot"></div>
          <span>Last 24 hours</span>
        </div>
      </div>

      <div class="metric">
        <div class="metric-label">📊 Active Devices</div>
        <div class="metric-value">24</div>
        <div class="metric-status">
          <div class="status-dot"></div>
          <span>GPS trackers connected</span>
        </div>
      </div>

      <div class="metric">
        <div class="metric-label">🔄 Data Sync</div>
        <div class="metric-value">Real-time</div>
        <div class="metric-status">
          <div class="status-dot"></div>
          <span>0ms latency</span>
        </div>
      </div>

      <div class="metric">
        <div class="metric-label">💾 Database</div>
        <div class="metric-value">2.3 GB</div>
        <div class="metric-status">
          <div class="status-dot warning"></div>
          <span>85% capacity used</span>
        </div>
      </div>

      <div class="metric">
        <div class="metric-label">🔐 Security</div>
        <div class="metric-value">100%</div>
        <div class="metric-status">
          <div class="status-dot"></div>
          <span>All checks passed</span>
        </div>
      </div>
    </div>

    <!-- MONITORING SECTIONS -->
    <div class="console-grid">
      <!-- SERVER STATUS -->
      <div class="console-section">
        <h3>🖥️ Server Status</h3>
        <div class="server-check">
          <div class="server-status">
            <div class="status-dot"></div>
            <span>Web Server</span>
          </div>
          <span style="color: #10b981; font-weight: 600;">Up</span>
        </div>
        <div class="server-check">
          <div class="server-status">
            <div class="status-dot"></div>
            <span>Database Server</span>
          </div>
          <span style="color: #10b981; font-weight: 600;">Up</span>
        </div>
        <div class="server-check">
          <div class="server-status">
            <div class="status-dot"></div>
            <span>API Server</span>
          </div>
          <span style="color: #10b981; font-weight: 600;">Up</span>
        </div>
        <div class="server-check">
          <div class="server-status">
            <div class="status-dot"></div>
            <span>Cache Server</span>
          </div>
          <span style="color: #10b981; font-weight: 600;">Up</span>
        </div>
      </div>

      <!-- RESOURCE USAGE -->
      <div class="console-section">
        <h3>📈 Resource Usage</h3>
        <table class="stats-table">
          <tr>
            <th>Resource</th>
            <th>Usage</th>
            <th>Status</th>
          </tr>
          <tr>
            <td>CPU</td>
            <td>34%</td>
            <td><span class="badge badge-success">Normal</span></td>
          </tr>
          <tr>
            <td>Memory</td>
            <td>62%</td>
            <td><span class="badge badge-success">Normal</span></td>
          </tr>
          <tr>
            <td>Disk Space</td>
            <td>45%</td>
            <td><span class="badge badge-success">Normal</span></td>
          </tr>
          <tr>
            <td>Network</td>
            <td>12 Mbps</td>
            <td><span class="badge badge-success">Good</span></td>
          </tr>
        </table>
      </div>

      <!-- RECENT LOGS -->
      <div class="console-section" style="grid-column: 1 / -1;">
        <h3>📋 System Activity Log</h3>
        <div class="log-entry">
          <span class="log-time">[14:45:32]</span>
          <span class="log-success">✓ GPS update received</span> - Bus 101 location: 32.2988, -90.1848
        </div>
        <div class="log-entry">
          <span class="log-time">[14:44:15]</span>
          <span class="log-success">✓ Driver logged in</span> - John Smith (Bus 101)
        </div>
        <div class="log-entry">
          <span class="log-time">[14:42:03]</span>
          <span class="log-success">✓ Maintenance report submitted</span> - Bus 103 brake inspection
        </div>
        <div class="log-entry">
          <span class="log-time">[14:38:47]</span>
          <span class="log-success">✓ Database backup completed</span> - Size: 2.3GB
        </div>
        <div class="log-entry">
          <span class="log-time">[14:35:22]</span>
          <span class="log-warning">⚠ High CPU usage detected</span> - Peak: 78% (resolved)
        </div>
        <div class="log-entry">
          <span class="log-time">[14:32:10]</span>
          <span class="log-success">✓ API request processed</span> - Response time: 145ms
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="console-section">
      <h3>⚡ System Actions</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
        <button class="btn btn-primary">🔄 Restart Server</button>
        <button class="btn btn-primary">💾 Backup Database</button>
        <button class="btn btn-primary">🔍 Run Diagnostics</button>
        <button class="btn btn-secondary">⚙️ System Settings</button>
        <button class="btn btn-secondary">📊 View Reports</button>
        <button class="btn btn-secondary">🔐 Security Check</button>
      </div>
    </div>
  </div>

  <?php render_footer(); ?>
</body>
</html>
