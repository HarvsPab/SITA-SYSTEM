<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S.I.T.A. Tracking</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background: #1a73e8;
      padding: 20px;
      color: white;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      z-index: 10;
    }
    .sidebar h2 {
      margin-bottom: 40px;
      text-align: center;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 12px;
      margin: 5px 0;
      border-radius: 5px;
      display: block;
      transition: all 0.3s ease;
    }
    .sidebar a:hover {
      background: #34495e;
    }
    .map-container {
      flex-grow: 1;
      position: relative;
    }
    #map {
      width: 100%;
      height: 100vh;
    }
    .map-buttons {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      z-index: 1000;
    }
    .map-button {
      background: #1a73e8;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .map-button:hover {
      background: #34495e;
    }
    .delete-btn {
      background-color: red;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
    }
    .delete-btn:hover {
      background-color: darkred;
    }
    .leaflet-marker-icon {
      width: auto !important;
      height: auto !important;
    }
    .leaflet-marker-icon .marker-label {
      background: white;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
      white-space: nowrap;
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .current-location-marker {
      background-color: blue;
      border-radius: 50%;
      border: 2px solid white;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>S.I.T.A.</h2>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="ordinance.php"><i class="fas fa-gavel"></i> Ordinances</a>
    <a href="violations.php"><i class="fas fa-exclamation-triangle"></i> Violations</a>
    <a href="report.php"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="tracking.php"><i class="fas fa-map-marker-alt"></i> Tracking</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="#" class="logoutButton">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
  
  <div class="map-container">
    <div class="map-buttons">
      <button class="map-button" onclick="enableAddingPins()">
        <i class="fas fa-map-pin"></i> Add Pin
      </button>
      <button class="map-button" onclick="findMyLocation()">
        <i class="fas fa-location-arrow"></i> My Location
      </button>
    </div>
    <div id="map"></div>
  </div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    // Initialize map
    let map = L.map('map').setView([16.003420, 120.669953], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Global variables for location tracking
    let currentLocationMarker = null;
    let locationTrackingActive = false;

    // Custom icon class to always show label
    let CustomIcon = L.Icon.extend({
      options: {
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      },
      createIcon: function() {
        let div = document.createElement('div');
        let icon = this._createIcon('icon');
        let label = document.createElement('div');
        
        div.appendChild(icon);
        
        label.innerHTML = this.options.labelText || '';
        label.className = 'marker-label';
        div.appendChild(label);
        
        div.className = 'leaflet-marker-icon';
        return div;
      }
    });

    // Retrieve existing markers
    let markers = JSON.parse(localStorage.getItem('mapMarkers')) || [];

    // Function to add markers with label
    function addMarker(lat, lng, note) {
      // Create custom icon with label
      let customIcon = new CustomIcon({
        iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
        labelText: note
      });

      // Create marker with custom icon
      let marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
      
      // Add popup with delete functionality
      marker.bindPopup(`
        <div style="text-align: center;">
          <strong>${note}</strong>
          <br>
          <button class="delete-btn" onclick="removeMarker(${lat}, ${lng})">🗑 Delete</button>
        </div>
      `);

      return marker;
    }

    // Restore existing markers
    markers.forEach(marker => addMarker(marker.lat, marker.lng, marker.note));

    // Enable pin adding functionality
    function enableAddingPins() {
      map.once('click', function(e) {
        let note = prompt("Enter note for this pin:");
        if (note) {
          addMarker(e.latlng.lat, e.latlng.lng, note);
          markers.push({ lat: e.latlng.lat, lng: e.latlng.lng, note: note });
          localStorage.setItem('mapMarkers', JSON.stringify(markers));
        }
      });
    }

    // Find and track my location
    function findMyLocation() {
      // Check if geolocation is supported
      if ("geolocation" in navigator) {
        // Remove any existing location marker
        if (currentLocationMarker) {
          map.removeLayer(currentLocationMarker);
        }

        // Show loading indicator (optional)
        alert("Finding your location...");

        // Get current position
        navigator.geolocation.getCurrentPosition(
          function(position) {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            // Center map on current location
            map.setView([lat, lng], 16);

            // Create a custom location marker
            currentLocationMarker = L.circleMarker([lat, lng], {
              radius: 10,
              fillColor: "#3388ff",
              color: "#ffffff",
              weight: 2,
              opacity: 1,
              fillOpacity: 0.7,
              className: 'current-location-marker'
            }).addTo(map);

            // Add popup to location marker
            currentLocationMarker.bindPopup(`
              <div style="text-align: center;">
                <strong>Your Location</strong>
                <br>
                <button class="map-button" onclick="addCurrentLocationPin()">
                  Add Pin Here
                </button>
              </div>
            `).openPopup();
          },
          function(error) {
            // Handle location error
            switch(error.code) {
              case error.PERMISSION_DENIED:
                alert("Location access denied. Please enable location permissions.");
                break;
              case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
              case error.TIMEOUT:
                alert("Location request timed out.");
                break;
              default:
                alert("An unknown error occurred while finding your location.");
            }
          }
        );
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    // Add pin at current location
    function addCurrentLocationPin() {
      if (currentLocationMarker) {
        let note = prompt("Enter note for this location:");
        if (note) {
          let latlng = currentLocationMarker.getLatLng();
          addMarker(latlng.lat, latlng.lng, note);
          markers.push({ 
            lat: latlng.lat, 
            lng: latlng.lng, 
            note: note 
          });
          localStorage.setItem('mapMarkers', JSON.stringify(markers));
        }
      }
    }

    // Remove marker function
    function removeMarker(lat, lng) {
      // Remove marker from the map
      map.eachLayer(function(layer) {
        if (layer instanceof L.Marker) {
          if (layer.getLatLng().lat === lat && layer.getLatLng().lng === lng) {
            map.removeLayer(layer);
          }
        }
      });

      // Remove marker from the markers array
      markers = markers.filter(m => m.lat !== lat || m.lng !== lng);
      
      // Update localStorage
      localStorage.setItem('mapMarkers', JSON.stringify(markers));
    }

    // Logout functionality
    document.addEventListener("DOMContentLoaded", function () {
      let logoutButton = document.querySelector(".logoutButton");
      if (logoutButton) {
        logoutButton.addEventListener("click", function (event) {
          event.preventDefault();
          let confirmAction = confirm("Are you sure you want to log out?");
          if (confirmAction) {
            window.location.href = "../logout.php";
          }
        });
      }
    });
  </script>
</body>
</html>