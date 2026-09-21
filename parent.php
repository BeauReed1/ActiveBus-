<?php
require_once "config.php";

if (isset($_GET["get_gps"])) {
    header("Content-Type: application/json");
    $bus_filter = $_GET["bus"] ?? null;
    
    if ($bus_filter && $bus_filter !== "") {
        $stmt = $conn->prepare("SELECT * FROM `{$prefix}_gps` WHERE status = 'active' AND bus_number = ?");
        $stmt->bind_param("s", $bus_filter);
        $stmt->execute();
        $res = $stmt->get_result();
    } else {
        $res = $conn->query("SELECT * FROM `{$prefix}_gps` WHERE status = 'active'");
    }
    
    $buses = [];
    while($row = $res->fetch_assoc()) { $buses[] = $row; }
    echo json_encode($buses);
    exit();
}

$buses_res = $conn->query("SELECT bus_number, route_number FROM `{$prefix}_buses` ORDER BY bus_number ASC");
$selected_bus = $_GET["bus"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Bus Tracker | <?php echo htmlspecialchars($school); ?></title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    .tracker-container {
      display: grid;
      grid-template-columns: 350px 1fr;
      gap: 24px;
      min-height: calc(100vh - 100px);
      max-width: 1400px;
      margin: 0 auto;
      padding: 24px;
    }

    .bus-selector {
      background: white;
      border-radius: 12px;
      padding: 24px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      height: fit-content;
      position: sticky;
      top: 100px;
    }

    .bus-selector h3 {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 16px;
      color: #1e293b;
    }

    .bus-selector select {
      width: 100%;
      margin-bottom: 16px;
    }

    .bus-info {
      background: #f1f5f9;
      padding: 16px;
      border-radius: 8px;
      margin-top: 16px;
      display: none;
    }

    .bus-info.active {
      display: block;
    }

    .bus-info p {
      margin: 8px 0;
      font-size: 0.9rem;
    }

    .bus-info strong {
      color: #2563eb;
    }

    .map-wrapper {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #e2e8f0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    #map {
      width: 100%;
      height: 500px;
    }

    .legend {
      background: white;
      padding: 16px;
      border-radius: 8px;
      font-size: 0.9rem;
      margin-top: 16px;
      border: 1px solid #e2e8f0;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 8px 0;
    }

    .legend-icon {
      font-size: 1.2rem;
    }

    @media (max-width: 1024px) {
      .tracker-container {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .bus-selector {
        position: static;
      }

      #map {
        height: 400px;
      }
    }

    @media (max-width: 768px) {
      .tracker-container {
        padding: 16px;
        gap: 16px;
      }

      #map {
        height: 350px;
      }
    }
  </style>
</head>
<body>
  <?php render_header("Live Bus Tracker", ["Tracking" => "parent.php"]); ?>

  <div class="tracker-container">
    <div class="bus-selector">
      <h3>🚌 Select a Bus</h3>
      <form method="GET" action="parent.php" id="busForm">
        <select name="bus" onchange="document.getElementById('busForm').submit();">
          <option value="">-- View All Active Buses --</option>
          <?php if ($buses_res && $buses_res->num_rows > 0): ?>
            <?php while($b = $buses_res->fetch_assoc()): ?>
              <option value="<?php echo htmlspecialchars($b["bus_number"]); ?>" <?php echo ($selected_bus === $b["bus_number"]) ? "selected" : ""; ?>>
                Bus <?php echo htmlspecialchars($b["bus_number"]); ?> (Route <?php echo htmlspecialchars($b["route_number"]); ?>)
              </option>
            <?php endwhile; ?>
          <?php endif; ?>
        </select>
      </form>

      <div id="busInfo" class="bus-info">
        <p><strong>Bus Number:</strong> <span id="busNum">-</span></p>
        <p><strong>Status:</strong> <span id="busStatus">-</span></p>
        <p><strong>Speed:</strong> <span id="busSpeed">-</span></p>
        <p><strong>Last Updated:</strong> <span id="busTime">-</span></p>
      </div>

      <div class="legend">
        <h4 style="margin: 0 0 12px 0; font-size: 0.95rem;">Legend</h4>
        <div class="legend-item">
          <div class="legend-icon">🚌</div>
          <span>Active Bus</span>
        </div>
        <div class="legend-item">
          <div class="legend-icon">📍</div>
          <span>Bus Location</span>
        </div>
      </div>
    </div>

    <div class="map-wrapper">
      <div id="map"></div>
    </div>
  </div>

  <?php render_footer(); ?>

  <script>
    const busParam = "<?php echo htmlspecialchars($selected_bus); ?>";
    const map = L.map("map").setView([32.2988, -90.1848], 12);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    let markers = {};
    let busData = {};

    function pollGPS() {
      let endpoint = "parent.php?get_gps=1";
      if (busParam && busParam !== "") {
        endpoint += "&bus=" + encodeURIComponent(busParam);
      }

      fetch(endpoint)
        .then(res => res.json())
        .then(data => {
          data.forEach(bus => {
            busData[bus.bus_number] = bus;
            if (!markers[bus.bus_number]) {
              markers[bus.bus_number] = L.marker([bus.lat, bus.lng], {
                icon: L.divIcon({
                  html: '<div style="font-size: 1.5rem;">🚌</div>',
                  iconSize: [32, 32],
                  className: 'bus-marker'
                })
              }).addTo(map).bindPopup("Bus " + bus.bus_number);
            } else {
              markers[bus.bus_number].setLatLng([bus.lat, bus.lng]);
            }
            
            if (busParam && busParam !== "") {
              map.setView([bus.lat, bus.lng], 15);
              document.getElementById("busNum").textContent = bus.bus_number;
              document.getElementById("busStatus").textContent = bus.status === "active" ? "✓ Active" : "Inactive";
              document.getElementById("busSpeed").textContent = Math.round(bus.speed) + " mph";
              document.getElementById("busTime").textContent = new Date(bus.updated_at).toLocaleTimeString();
              document.getElementById("busInfo").classList.add("active");
            }
          });
        })
        .catch(err => console.error("Error:", err));
    }

    window.addEventListener('load', () => {
      pollGPS();
      setInterval(pollGPS, 30000);
    });
  </script>
</body>
</html>
