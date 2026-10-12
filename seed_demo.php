<?php
/**
 * Cybersecurity Incident Management System
 * Demo Data Seeder (Idempotent & Safe)
 * 
 * Usage:
 *   php seed_demo.php
 */

require_once __DIR__ . "/includes/config.php";

echo "=====================================================" . PHP_EOL;
echo "  CYBERSECURITY INCIDENT MANAGEMENT SYSTEM - SEEDER  " . PHP_EOL;
echo "=====================================================" . PHP_EOL . PHP_EOL;

// 1. Configure Demo Accounts
// Dedicated presentation accounts prevent modifying or exposing personal passwords.
$demoAccounts = [
    [
        'name'     => 'Demo Security Analyst',
        'email'    => 'demo_user@incident.com',
        'password' => 'User@Demo123',
        'role'     => 'user'
    ],
    [
        'name'     => 'Demo SOC Administrator',
        'email'    => 'demo_admin@incident.com',
        'password' => 'Admin@Demo123',
        'role'     => 'admin'
    ]
];

$userIdMap = [];

foreach ($demoAccounts as $acc) {
    $stmt = $conn->prepare("SELECT id, name, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $acc['email']);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $existing = $res->fetch_assoc();
        $userIdMap[$acc['role']] = (int)$existing['id'];
        echo "[EXISTS] User '{$acc['name']}' ({$acc['email']}) [ID: {$existing['id']}]" . PHP_EOL;
    } else {
        // Create dedicated demo account
        $hash = password_hash($acc['password'], PASSWORD_DEFAULT);
        $insertStmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $acc['name'], $acc['email'], $hash, $acc['role']);
        
        if ($insertStmt->execute()) {
            $newId = (int)$insertStmt->insert_id;
            $userIdMap[$acc['role']] = $newId;
            echo "[CREATED] User '{$acc['name']}' ({$acc['email']}) [ID: {$newId}]" . PHP_EOL;
        } else {
            echo "[ERROR] Failed to create '{$acc['email']}': " . $conn->error . PHP_EOL;
        }
    }
}

// Ensure we have a valid reporter ID for sample incidents
$reporterId = $userIdMap['user'] ?? null;
if (!$reporterId) {
    // Fallback to first regular user in DB
    $userCheck = $conn->query("SELECT id FROM users WHERE role = 'user' LIMIT 1");
    if ($userCheck && $u = $userCheck->fetch_assoc()) {
        $reporterId = (int)$u['id'];
    }
}

if (!$reporterId) {
    die("[FATAL] No regular user account found to associate sample incidents with." . PHP_EOL);
}

echo PHP_EOL . "Sample incidents will be associated with User ID: {$reporterId}" . PHP_EOL . PHP_EOL;

// 2. Define Realistic Sample Incidents
// Covers all 5 required cybersecurity categories and all 3 lifecycle statuses (Open, In Progress, Resolved).
$sampleIncidents = [
    [
        'title'         => 'Executive Impersonation Phishing Email',
        'description'   => "Multiple employees in Accounts received an urgent email purportedly sent by the Chief Technology Officer requesting immediate off-cycle vendor payment verification and gift card purchases. The message originated from a deceptive lookalike domain ('company-support-desk.com') using spoofed SMTP headers. Two recipients reported the message without clicking internal hyperlinks.",
        'severity'      => 'High',
        'status'        => 'Open',
        'incident_date' => date('Y-m-d', strtotime('-2 days')),
        'admin_remarks' => 'Incident logged. Initial domain reputation checks confirm malicious registrar. Network administrators notified to implement mail exchange perimeter filtering.'
    ],
    [
        'title'         => 'Repeated Failed SSH & Web Portal Login Attempts',
        'description'   => "Automated intrusion monitoring logged over 140 rapid consecutive failed authentication attempts against administrative gateway endpoints from external IP 198.51.100.45. The pattern indicates credential stuffing targeting standard system username lists. Progressive delay lockout prevented account compromise.",
        'severity'      => 'High',
        'status'        => 'In Progress',
        'incident_date' => date('Y-m-d', strtotime('-4 days')),
        'admin_remarks' => 'Originating IP range blocked at boundary firewall. Authentication logs audited; no unauthorized sessions were established. Monitoring for rotating source IPs.'
    ],
    [
        'title'         => 'Ransomware Trojan Detected on Finance Workstation',
        'description'   => "Endpoint detection software flagged Trojan.Win32.WannaCrypt variant attempting unauthorized file modification and shadow volume deletion on workstation WS-FIN-04. The background antivirus engine terminated the malicious process and quarantined the dropped DLL binary payload.",
        'severity'      => 'Critical',
        'status'        => 'In Progress',
        'incident_date' => date('Y-m-d', strtotime('-5 days')),
        'admin_remarks' => 'Workstation quarantined from internal network segment. Forensic memory image captured. Restored critical files from validated immutable backup storage.'
    ],
    [
        'title'         => 'Anomalous Outbound Data Transfer Over Port 443',
        'description'   => "Network flow sensors recorded unexpected outbound data transfer spikes totaling 3.8 GB directed toward an unclassified overseas IP address during non-business hours (02:40 AM). Destination server is not part of authorized corporate cloud infrastructure.",
        'severity'      => 'Medium',
        'status'        => 'Open',
        'incident_date' => date('Y-m-d', strtotime('-1 day')),
        'admin_remarks' => 'Assigned for deep packet inspection and network traffic flow reconstruction with the Network Operations Center.'
    ],
    [
        'title'         => 'Compromised Vendor VPN Credential Revocation',
        'description'   => "A third-party contractor reported potential credential exposure in a public security breach dump. The compromised user account possessed active remote VPN access to the corporate staging environment.",
        'severity'      => 'Medium',
        'status'        => 'Resolved',
        'incident_date' => date('Y-m-d', strtotime('-8 days')),
        'admin_remarks' => 'Active VPN sessions terminated immediately. User certificate revoked and active directory credentials reset. Enforced hardware-token multi-factor authentication. Incident successfully closed.'
    ]
];

// 3. Idempotently Insert Incidents
foreach ($sampleIncidents as $inc) {
    // Check if incident already exists by title
    $checkStmt = $conn->prepare("SELECT id FROM incidents WHERE title = ?");
    $checkStmt->bind_param("s", $inc['title']);
    $checkStmt->execute();
    $checkRes = $checkStmt->get_result();

    if ($checkRes->num_rows > 0) {
        $row = $checkRes->fetch_assoc();
        echo "[EXISTS] Incident: '{$inc['title']}' [ID: {$row['id']}]" . PHP_EOL;
    } else {
        $insertIncident = $conn->prepare(
            "INSERT INTO incidents (title, description, severity, status, admin_remarks, reported_by, incident_date)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $insertIncident->bind_param(
            "sssssis",
            $inc['title'],
            $inc['description'],
            $inc['severity'],
            $inc['status'],
            $inc['admin_remarks'],
            $reporterId,
            $inc['incident_date']
        );

        if ($insertIncident->execute()) {
            echo "[CREATED] Incident: '{$inc['title']}' (Severity: {$inc['severity']} | Status: {$inc['status']})" . PHP_EOL;
        } else {
            echo "[ERROR] Failed to insert '{$inc['title']}': " . $conn->error . PHP_EOL;
        }
    }
}

echo PHP_EOL . "=====================================================" . PHP_EOL;
echo "  DEMO DATA INITIALIZATION COMPLETE                  " . PHP_EOL;
echo "=====================================================" . PHP_EOL;
echo "Demo Accounts Available:" . PHP_EOL;
echo "  User:  demo_user@incident.com  (Password: User@Demo123)" . PHP_EOL;
echo "  Admin: demo_admin@incident.com (Password: Admin@Demo123)" . PHP_EOL;
echo "=====================================================" . PHP_EOL;
