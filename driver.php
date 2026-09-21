<?php
require_once "config.php";
session_start();

if (isset($_SESSION["driver_id"])) {
    if (!isset($_SESSION["district_prefix"]) || $_SESSION["district_prefix"] !== $prefix) {
        session_unset();
        session_destroy();
        session_start();
    }
}

if (isset($_POST["action"]) && $_POST["action"] === "update_gps") {
    header("Content-Type: application/json");
    if (!isset($_SESSION["driver_id"]) || $_SESSION["district_prefix"] !== $prefix) {
        echo json_encode(["status" => "error", "message" => "Unauthorized access"]);
        exit();
    }

    $bus_no = $_SESSION["bus_number"] ?? "Unassigned";
    $lat    = floatval($_POST["lat"]);
    $lng    = floatval($_POST["lng"]);
    $speed  = floatval($_POST["speed"]);
    $status = ($_POST["status"] === "active") ? "active" : "inactive";

    $conn->query("DELETE FROM `{$prefix}_gps` WHERE updated_at < NOW() - INTERVAL 2 HOUR");

    $stmt = $conn->prepare("INSERT INTO `{$prefix}_gps` (bus_number, lat, lng, speed, status) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE lat=?, lng=?, speed=?, status=?");
    $stmt->bind_param("sddsssdds", $bus_no, $lat, $lng, $speed, $status, $lat, $lng, $speed, $status);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    exit();
}

$note_msg = "";
if (isset($_POST["submit_note"]) && isset($_SESSION["driver_id"])) {
    $note = trim($_POST["note"]);
    if (!empty($note)) {
        $stmt = $conn->prepare("INSERT INTO `{$prefix}_notes` (bus_number, driver_name, note) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_SESSION["bus_number"], $_SESSION["driver_name"], $note);
        if ($stmt->execute()) {
            $note_msg = '<div class="alert alert-success">✓ Note sent to Bus Shop & Management!</div>';
        }
    }
}

$login_msg = "";
if (isset($_POST["login_driver"])) {
    $phone = trim($_POST["phone"]);
    $pass  = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM `{$prefix}_drivers` WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $res  = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($pass, $user["password"])) {
        $_SESSION["driver_id"]       = $user["id"];
        $_SESSION["driver_name"]     = $user["first_name"] . " " . $user["last_name"];
        $_SESSION["bus_number"]      = $user["bus_number"];
        $_SESSION["district_prefix"] = $prefix;
    } else {
        $login_msg = '<div class="alert alert-error">✗ Invalid driver credentials. Please try again.</div>';
    }
}

if (isset($_GET["action"]) && $_GET["action"] === "logout") {
    session_destroy();
    header("Location: driver.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>Driver Portal | <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    .driver-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 24px;
    }

    .driver-header-card {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: white;
      padding: 24px;
      border-radius: 12px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .driver-info h3 {
      margin: 0 0 8px 0;
      font-size: 1.5rem;
    }

    .driver-info p {
      margin: 4px 0;
      opacity: 0.9;
    }

    .gps-button-group {
      display: flex;
      gap: 12px;
      margin-bottom: 24px;
    }

    .map-section {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 24px;
    }

    #map {
      width: 100%;
      height: 300px;
      border-radius: 8px;
      margin-bottom: 16px;
    }

    .gps-status {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px;
      background: #f1f5f9;
      border-radius: 8px;
      font-size: 0.9rem;
    }

    .status-indicator {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #94a3b8;
    }

    .status-indicator.active {
      background: #10b981;
      box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
    }

    .note-section {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
    }

    .note-section h3 {
      margin-top: 0;
      margin-bottom: 16px;
    }

    .login-card {
      max-width: 500px;
      margin: 60px auto;
    }

    @media (max-width: 768px) {
      .driver-header-card {
        flex-direction: column;
        gap: 16px;
        text-align: center;
      }

      .gps-button-group {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }

      #map {
        height: 250px;
      }
    }
  </style>
</head>
<body>
  <?php 
  if (isset($_SESSION["driver_id"])) {
    render_header("Driver Portal", ["Dashboard" => "driver.php"], true);
  } else {
    render_header("Driver Login");
  }
  ?>

  <div class="driver-container">
    <?php if (!isset($_SESSION["driver_id"])): ?>
      <!-- LOGIN FORM -->
      <div class="login-card card">
        <h2 style="text-align: center; color: #1e293b; margin-bottom: 24px;">Driver Login</h2>
        
        <?php echo $login_msg; ?>

        <form method="POST">
          <div class="form-group">
            <label for="phone">📱 Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Your registered phone number" required>
            <div class="form-help">Enter the phone number associated with your driver account</div>
          </div>

          <div class="form-group">
            <label for="password">🔐 Password</label>
            <input type="password" id="password" name="password" placeholder="Your password" required>
            <div class="form-help">Enter your secure password</div>
          </div>

          <button type="submit" name="login_driver" class="btn btn-primary btn-lg btn-full">Log In</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #64748b;">
          Need help? Contact your fleet manager for account assistance.
        </p>
      </div>

    <?php else: ?>
      <!-- DRIVER DASHBOARD -->
      <div class="driver-header-card">
        <div class="driver-info">
          <h3><?php echo htmlspecialchars($_SESSION["driver_name"]); ?></h3>
          <p>Bus #<?php echo htmlspecialchars($_SESSION["bus_number"]); ?></p>
          <p style="font-size: 0.9rem; opacity: 0.8;">Driver Portal</p>
        </div>
      </div>

      <div class="gps-button-group">
        <button id="gps-btn" class="btn btn-success btn-lg btn-full" onclick="toggleGPS()">
          📍 Start Sharing Location
        </button>
      </div>

      <div class="map-section">
        <h3 style="margin-top: 0; margin-bottom: 16px;">Live Location</h3>
        <div id="map"></div>
        <div class="gps-status">
          <div class="status-indicator" id="statusDot"></div>
          <span id="statusText">Ready to share location</span>
        </div>
      </div>

      <div class="note-section">
        <h3>📝 Report Issue or Maintenance Note</h3>
        <?php echo $note_msg; ?>
        
        <form method="POST">
          <div class="form-group">
            <label for="note">Describe the issue:</label>
            <textarea id="note" name="note" placeholder="Report any maintenance issues, mechanical problems, or concerns..." required></textarea>
            <div class="form-help">This message will be sent to the bus shop and management team</div>
          </div>
          <button type="submit" name="submit_note" class="btn btn-warning btn-lg btn-full">Send Note to Bus Shop</button>
        </form>
      </div>

    <?php endif; ?>
  </div>

  <?php render_footer(); ?>

  <script>
    let map, marker, watchId = null, sendInterval = null, isSharing = false;
    let latestLat = null, latestLng = null, latestSpeed = 0, firstFixAcquired = false;

    function initMap() {
      map = L.map("map").setView([32.2988, -90.1848], 13);
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);
    }

    <?php if (isset($_SESSION["driver_id"])): ?>
      window.addEventListener('load', initMap);
    <?php endif; ?>

    function updateStatusDisplay() {
      const statusDot = document.getElementById("statusDot");
      const statusText = document.getElementById("statusText");
      if (isSharing) {
        statusDot.classList.add("active");
        statusText.textContent = "✓ Actively sharing your location";
      } else {
        statusDot.classList.remove("active");
        statusText.textContent = "Ready to share location";
      }
    }

    function sendGpsUpdate(statusVal) {
      if (!isSharing && statusVal === "active") return;
      if (statusVal === "active" && (latestLat === null || latestLng === null)) return;

      const fd = new FormData();
      fd.append("action", "update_gps");
      fd.append("lat", latestLat || 0);
      fd.append("lng", latestLng || 0);
      fd.append("speed", latestSpeed || 0);
      fd.append("status", statusVal);

      fetch("driver.php", { method: "POST", body: fd })
        .then(res => res.json())
        .catch(err => console.error("Error:", err));
    }

    function toggleGPS() {
      const btn = document.getElementById("gps-btn");
      if (!isSharing) {
        if ("geolocation" in navigator) {
          isSharing = true;
          firstFixAcquired = false;
          btn.innerText = "🛑 Stop Sharing Location";
          btn.className = "btn btn-danger btn-lg btn-full";

          watchId = navigator.geolocation.watchPosition((pos) => {
            latestLat = pos.coords.latitude;
            latestLng = pos.coords.longitude;
            latestSpeed = pos.coords.speed || 0;

            if (!marker) {
              marker = L.marker([latestLat, latestLng], {
                icon: L.divIcon({
                  html: '<div style="font-size: 1.5rem;">🚌</div>',
                  iconSize: [32, 32],
                  className: 'driver-marker'
                })
              }).addTo(map).bindPopup("Your bus location").openPopup();
            } else {
              marker.setLatLng([latestLat, latestLng]);
            }
            map.setView([latestLat, latestLng], 15);

            if (!firstFixAcquired) {
              firstFixAcquired = true;
              sendGpsUpdate("active");
            }
            updateStatusDisplay();
          }, (err) => {
            isSharing = false;
            btn.innerText = "📍 Start Sharing Location";
            btn.className = "btn btn-success btn-lg btn-full";
            updateStatusDisplay();
            alert("GPS Error: " + err.message);
          }, { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 });

          sendInterval = setInterval(() => sendGpsUpdate("active"), 30000);
        } else {
          alert("Geolocation is not supported on your device.");
        }
      } else {
        isSharing = false;
        if (watchId) navigator.geolocation.clearWatch(watchId);
        if (sendInterval) clearInterval(sendInterval);

        btn.innerText = "📍 Start Sharing Location";
        btn.className = "btn btn-success btn-lg btn-full";

        if (marker) {
          map.removeLayer(marker);
          marker = null;
        }

        sendGpsUpdate("inactive");
        latestLat = null; latestLng = null; latestSpeed = 0;
        updateStatusDisplay();
      }
    }
  </script>
</body>
</html>
