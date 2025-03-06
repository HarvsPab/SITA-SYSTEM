
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.T.A. - Violations</title>
    <style>
        :root {
            --primary: #1a73e8;
            --primary-dark: #0d47a1;
            --secondary: #f8f9fa;
            --accent: #ff6d00;
            --text-dark: #202124;
            --text-light: #5f6368;
            --danger: #d32f2f;
            --success: #388e3c;
            --warning: #f57c00;
            --border: #dadce0;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: var(--text-dark);
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 240px;
            background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 12px 20px;
            transition: all 0.2s;
        }
        
        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar a.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border-left: 4px solid white;
        }
        
        .sidebar a i {
            margin-right: 12px;
            font-size: 18px;
        }
        
        .content {
            flex: 1;
            padding: 25px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        
        h1 {
            margin-top: 0;
            font-size: 26px;
            margin-bottom: 24px;
            color: var(--primary-dark);
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .form-row label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text-dark);
        }
        
        .form-row input, .form-row select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
            transition: border 0.2s;
        }
        
        .form-row input:focus, .form-row select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }
        
        .form-column {
            flex: 1;
            min-width: 150px;
        }
        
        .section {
            margin-bottom: 24px;
            background-color: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        
        .section-title {
            font-weight: 500;
            margin-bottom: 16px;
            color: var(--primary-dark);
            font-size: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }
        
        button {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            padding: 10px 16px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        button:hover {
            background-color: #e8eaed;
        }
        
        .search-container {
            display: flex;
            margin-bottom: 24px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
        }
        
        .search-container input {
            flex: 1;
            padding: 14px 20px;
            border: none;
            font-size: 15px;
        }
        
        .search-container input:focus {
            outline: none;
        }
        
        .search-container button {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0;
            padding: 0 24px;
            font-weight: 500;
        }
        
        .search-container button:hover {
            background-color: var(--primary-dark);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        
        table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 2px solid #e8eaed;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e8eaed;
            color: var(--text-light);
        }
        
        table tr:hover td {
            background-color: rgba(0,0,0,0.01);
        }
        
        .footer {
            font-size: 13px;
            color: var(--text-light);
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }
        
        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            background-color: var(--secondary);
            border: 1px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        
        .edit-btn {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .edit-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .resolved-btn {
            background-color: var(--success);
            color: white;
            border-color: var(--success);
        }
        
        .resolved-btn:hover {
            background-color: #2e7d32;
        }
        
        .status-pending {
            color: var(--warning);
            font-weight: 500;
        }
        
        .status-resolved {
            color: var(--success);
            font-weight: 500;
        }
        
        .status-disputed {
            color: var(--danger);
            font-weight: 500;
        }
        
        .toggle-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 24px 0;
            padding: 16px;
            background-color: #f8f9fa;
            border: 1px solid var(--border);
            border-radius: 8px;
        }
        
        .toggle-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .toggle-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .hidden {
            display: none;
        }
        
        .resolved-table-container {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            background-color: #f8f9fa;
            margin-top: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        
        .resolved-header {
            font-size: 18px;
            margin-bottom: 12px;
            color: var(--primary);
            font-weight: 500;
        }
        
        .offense-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 12px;
            margin-left: 5px;
        }
        
        .first-offense {
            background-color: #e8f5e9;
            color: var(--success);
            border: 1px solid #c8e6c9;
        }
        
        .second-offense {
            background-color: #fff3e0;
            color: var(--warning);
            border: 1px solid #ffe0b2;
        }
        
        .multiple-offense {
            background-color: #ffebee;
            color: var(--danger);
            border: 1px solid #ffcdd2;
        }
        
        .offender-history {
            margin-top: 15px;
            padding: 16px;
            background-color: #f5f5f5;
            border-radius: 4px;
            display: none;
        }
        
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            margin-bottom: 12px;
        }
        
        .history-title {
            font-weight: 500;
            color: var(--primary);
        }
        
        .history-table {
            font-size: 13px;
            box-shadow: none;
        }
        
        .view-history-btn {
            background-color: #757575;
            color: white;
            border-color: #757575;
        }
        
        .view-history-btn:hover {
            background-color: #616161;
        }
        
        /* Highlight search bar with animation */
        @keyframes searchPulse {
            0% { box-shadow: 0 0 0 0 rgba(26, 115, 232, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(26, 115, 232, 0); }
            100% { box-shadow: 0 0 0 0 rgba(26, 115, 232, 0); }
        }
        
        .search-container {
            animation: searchPulse 2s infinite;
        }
        
        /* Compact field styles */
        .compact-field {
            padding: 8px 10px !important;
            font-size: 13px !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>S.I.T.A.</h2>
            </div>
            <a href="#"><i class="icon">🏠</i> Dashboard</a>
            <a href="#"><i class="icon">📋</i> Ordinances</a>
            <a href="#" class="active"><i class="icon">⚠️</i> Violations</a>
            <a href="#"><i class="icon">📊</i> Reports</a>
            <a href="#"><i class="icon">📍</i> Tracking</a>
            <div style="margin-top: 30px;"></div>
            <a href="#"><i class="icon">🚪</i> Logout</a>
        </div>
        
        <div class="content">
            <h1>Violations Management</h1>
            
            <!-- Highlighted Search Container -->
            <div class="search-container">
                <input type="text" placeholder="Search for ticket number, offender name, or vehicle details...">
                <button>Search</button>
            </div>
            
            <div class="section">
                <div class="form-row">
                    <div class="form-column">
                        <label for="ticket-number">Ticket Number</label>
                        <input type="text" id="ticket-number" class="compact-field">
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Offender Details</div>
                <div class="form-row">
                    <div class="form-column">
                        <input type="text" placeholder="Offender's Name" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Offender's Address" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="License No." class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="date" placeholder="mm/dd/yyyy" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Violation's Place" class="compact-field">
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Vehicle Details</div>
                <div class="form-row">
                    <div class="form-column">
                        <input type="text" placeholder="Type of Vehicle" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Plate No." class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Vehicle Registration and Insurance Policy Number" class="compact-field">
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Officer Details</div>
                <div class="form-row">
                    <div class="form-column">
                        <input type="text" placeholder="Officer Rank" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Officer Name" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Badge No" class="compact-field">
                    </div>
                    <div class="form-column">
                        <input type="datetime-local" class="compact-field">
                    </div>
                    <div class="form-column">
                        <select class="compact-field">
                            <option value="">Status</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="disputed">Disputed</option>
                        </select>
                    </div>
                    <div class="form-column">
                        <input type="text" placeholder="Fines" class="compact-field">
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Violation Name</div>
                <div class="form-row">
                    <div class="form-column">
                        <select class="compact-field">
                            <option value="dazzling">Dazzling light</option>
                            <option value="speeding">Speeding</option>
                            <option value="illegal_parking">Illegal Parking</option>
                            <option value="no_license">No License</option>
                        </select>
                    </div>
                    <div class="form-column">
                        <select id="offense-count" class="compact-field">
                            <option value="">Offense Count</option>
                            <option value="first">First Offense</option>
                            <option value="second">Second Offense</option>
                            <option value="multiple">Multiple Offense</option>
                        </select>
                    </div>
                    <div>
                        <button>Add Violation</button>
                    </div>
                </div>
            </div>
            
            <!-- Active Violations Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TICKET NO.</th>
                        <th>OFFENDER</th>
                        <th>VEHICLE</th>
                        <th>OFFICER</th>
                        <th>DATE</th>
                        <th>STATUS</th>
                        <th>FINES</th>
                        <th>VIOLATION</th>
                        <th>COUNT</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>VIO-001</td>
                        <td>TKT-2025-001</td>
                        <td>Name: John Smith, Address: San Manuel, License No:  1234, Violation Location: Namek st.</td>
                        <td>Sedan, Plate No: ABC-123, Vehicle Registration Number: 1301-ABCD, Insurance Policy Number: 1234</td>
                        <td>Sgt. Rodriguez Pillar, Badge No: 1234</td>
                        <td>02/24/2025</td>
                        <td class="status-pending">Pending</td>
                        <td>$150.00</td>
                        <td>Dazzling light</td>
                        <td><span class="offense-badge first-offense">1st</span></td>
                        <td>
                            <button class="action-btn edit-btn">Edit</button>
                            <button class="action-btn resolved-btn">Resolved</button>
                            <button class="action-btn view-history-btn" onclick="toggleHistory('history-1')">History</button>
                        </td>
                    </tr>
                    <tr id="history-1" class="offender-history">
                        <td colspan="11">
                            <div class="history-header">
                                <div class="history-title">Violation History for John Smith (License: DL-12345)</div>
                            </div>
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>VIOLATION</th>
                                        <th>LOCATION</th>
                                        <th>STATUS</th>
                                        <th>FINE AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>02/24/2025</td>
                                        <td>Dazzling light</td>
                                        <td>Main St & 5th Ave</td>
                                        <td class="status-pending">Pending</td>
                                        <td>$150.00</td>
                                    </tr>
                                    <!-- No previous history -->
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>VIO-003</td>
                        <td>TKT-2025-003</td>
                        <td>Name: David Lee, Address: San Manuel2, License No: 1234, Violation Location: Namek 2 st.</td>
                        <td>Pickup,  Plate No: ABC-1235, Vehicle Registration Number: 1301-ABCE, Insurance Policy Number: 1235</td>
                        <td>Cpl. Martinez Pillaro, Badge No: 1234</td>
                        <td>02/25/2025</td>
                        <td class="status-disputed">Disputed</td>
                        <td>$150.00</td>
                        <td>Illegal Parking</td>
                        <td><span class="offense-badge second-offense">2nd</span></td>
                        <td>
                            <button class="action-btn edit-btn">Edit</button>
                            <button class="action-btn resolved-btn">Resolved</button>
                            <button class="action-btn view-history-btn" onclick="toggleHistory('history-2')">History</button>
                        </td>
                    </tr>
                    <tr id="history-2" class="offender-history">
                        <td colspan="11">
                            <div class="history-header">
                                <div class="history-title">Violation History for David Lee (License: DL-67890)</div>
                            </div>
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>VIOLATION</th>
                                        <th>LOCATION</th>
                                        <th>STATUS</th>
                                        <th>FINE AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>02/25/2025</td>
                                        <td>Illegal Parking</td>
                                        <td>Park Ave & 12th St</td>
                                        <td class="status-disputed">Disputed</td>
                                        <td>$150.00</td>
                                    </tr>
                                    <tr>
                                        <td>01/12/2025</td>
                                        <td>Illegal Parking</td>
                                        <td>Central Station</td>
                                        <td class="status-resolved">Resolved</td>
                                        <td>$75.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>VIO-006</td>
                        <td>TKT-2025-006</td>
                        <td>Name: Robert Chen, Address: San Manuel3, License No: 1234, Violation Location: Namek st. again</td>
                        <td>SUV, Plate No: ABC-1236, Vehicle Registration Number: 1301-ABCF, Insurance Policy Number: 1236</td>
                        <td>Ofc. Parker Petre, Badge No: 1234</td>
                        <td>02/24/2025</td>
                        <td class="status-pending">Pending</td>
                        <td>$300.00</td>
                        <td>Speeding</td>
                        <td><span class="offense-badge multiple-offense">3rd+</span></td>
                        <td>
                            <button class="action-btn edit-btn">Edit</button>
                            <button class="action-btn resolved-btn">Resolved</button>
                            <button class="action-btn view-history-btn" onclick="toggleHistory('history-3')">History</button>
                        </td>
                    </tr>
                    <tr id="history-3" class="offender-history">
                        <td colspan="11">
                            <div class="history-header">
                                <div class="history-title">Violation History for Robert Chen (License: DL-54321)</div>
                            </div>
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>VIOLATION</th>
                                        <th>LOCATION</th>
                                        <th>STATUS</th>
                                        <th>FINE AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>02/24/2025</td>
                                        <td>Speeding</td>
                                        <td>Highway 101</td>
                                        <td class="status-pending">Pending</td>
                                        <td>$300.00</td>
                                    </tr>
                                    <tr>
                                        <td>12/15/2024</td>
                                        <td>Speeding</td>
                                        <td>Oak Street</td>
                                        <td class="status-resolved">Resolved</td>
                                        <td>$250.00</td>
                                    </tr>
                                    <tr>
                                        <td>10/03/2024</td>
                                        <td>No License</td>
                                        <td>Pine Avenue</td>
                                        <td class="status-resolved">Resolved</td>
                                        <td>$200.00</td>
                                    </tr>
                                    <tr>
                                        <td>07/22/2024</td>
                                        <td>Speeding</td>
                                        <td>Main Street</td>
                                        <td class="status-resolved">Resolved</td>
                                        <td>$200.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Toggle Button for Resolved Violations -->
            <div class="toggle-container">
                <div class="resolved-header">Resolved Violations History</div>
                <button class="toggle-btn" id="toggleResolved">Show Resolved Violations</button>
            </div>
            
            <!-- Resolved Violations Table (Hidden by Default) -->
            <div class="resolved-table-container hidden" id="resolvedTableContainer">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TICKET NO.</th>
                            <th>OFFENDER</th>
                            <th>VEHICLE</th>
                            <th>OFFICER</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                            <th>FINES</th>
                            <th>VIOLATION</th>
                            <th>COUNT</th>
                            <th>RESOLVED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>VIO-002</td>
                            <td>TKT-2025-002</td>
                            <td>Name: Maria Garcia, Address: Asingan, License No: 1234, Violation Location: dragon st.</td>
                            <td>SUV, Plate No: ABC-1237, Vehicle Registration Number: 1301-ABCFG, Insurance Policy Number: 1237</td>
                            <td>Ofc. Johnson Mobile legends, Badge No: 1234</td>
                            <td>02/25/2025</td>
                            <td class="status-resolved">Resolved</td>
                            <td>$200.00</td>
                            <td>Speeding</td>
                            <td><span class="offense-badge first-offense">1st</span></td>
                            <td>02/26/2025</td>
                        </tr>
                        <tr>
                            <td>VIO-004</td>
                            <td>TKT-2025-004</td>
                            <td>Name: Sarah Wilson, Address: Asingan 2, License No: 1234, Violation Location: dragon ball st. </td>
                            <td>Sedan, Plate No: ABC-1238, Vehicle Registration Number: 1301-ABCFH, Insurance Policy Number: 1238</td>
                            <td>Sgt. Thompson ForThree, Badge No: 1234</td>
                            <td>02/23/2025</td>
                            <td class="status-resolved">Resolved</td>
                            <td>$125.00</td>
                            <td>Dazzling light</td>
                            <td><span class="offense-badge first-offense">1st</span></td>
                            <td>02/24/2025</td>
                        </tr>
                        <tr>
                            <td>VIO-005</td>
                            <td>TKT-2025-005</td>
                            <td>Name: Michael Brown, Address: Asingan 3, License No: 1234, Violation Location: dragon ball again st.</td>
                            <td>Motorcycle, Plate No: ABC-1239, Vehicle Registration Number: 1301-ABCFI, Insurance Policy Number: 1239</td>
                            <td>Ofc. Williams SkateSpear, Badge No: 1234</td>
                            <td>02/22/2025</td>
                            <td class="status-resolved">Resolved</td>
                            <td>$180.00</td>
                            <td>No License</td>
                            <td><span class="offense-badge second-offense">2nd</span></td>
                            <td>02/25/2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    
    <!-- JavaScript to handle toggling the resolved violations table and offense history -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleResolved');
            const tableContainer = document.getElementById('resolvedTableContainer');
            
            toggleButton.addEventListener('click', function() {
                // Toggle the visibility of the resolved violations table
                tableContainer.classList.toggle('hidden');
                
                // Update the button text
                if (tableContainer.classList.contains('hidden')) {
                    toggleButton.textContent = 'Show Resolved Violations';
                } else {
                    toggleButton.textContent = 'Hide Resolved Violations';
                }
            });
        });
        
        // Function to toggle offense history visibility
        function toggleHistory(historyId) {
            const historyRow = document.getElementById(historyId);
            if (historyRow.style.display === 'table-row') {
                historyRow.style.display = 'none';
            } else {
                // Hide all history rows first
                const allHistoryRows = document.querySelectorAll('.offender-history');
                allHistoryRows.forEach(row => {
                    row.style.display = 'none';
                });
                
                // Show the selected history row
                historyRow.style.display = 'table-row';
            }
        }
    </script>
</body>
</html>
