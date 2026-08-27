<?php
session_start();
require_once "includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

$sql = "SELECT id, title, severity, status, incident_date
        FROM incidents
        WHERE reported_by = ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$myIncidents = $stmt->get_result();

$total = $myIncidents->num_rows;


// Get incident status counts

$openStmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM incidents
     WHERE reported_by = ? AND status = 'Open'"
);

$openStmt->bind_param("i", $userId);
$openStmt->execute();

$openIncidents = $openStmt->get_result()->fetch_assoc()["total"];


$progressStmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM incidents
     WHERE reported_by = ? AND status = 'In Progress'"
);

$progressStmt->bind_param("i", $userId);
$progressStmt->execute();

$progressIncidents = $progressStmt->get_result()->fetch_assoc()["total"];


$resolvedStmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM incidents
     WHERE reported_by = ? AND status = 'Resolved'"
);

$resolvedStmt->bind_param("i", $userId);
$resolvedStmt->execute();

$resolvedIncidents = $resolvedStmt->get_result()->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="assets/css/style.css">

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Dashboard - Cybersecurity Incident System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        .welcome-card {
            border: none;
            border-radius: 16px;
            background: white;
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card.active {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25) !important;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
        }

        .incident-card {
            border: none;
            border-radius: 16px;
        }

        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .empty-state {
            padding: 40px 20px;
        }

    </style>

</head>

<body>


<!-- Navbar -->

<nav class="navbar navbar-dark bg-dark shadow-sm">

    <div class="container">

        <span class="navbar-brand">
            🛡️ Cybersecurity Incident Management
        </span>

        <a href="logout.php" class="btn btn-danger">
            Logout
        </a>

    </div>

</nav>


<div class="container py-4">


    <!-- Welcome Section -->

    <div class="card welcome-card shadow-sm p-4 mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold mb-2">
                    Welcome,
                    <?php echo htmlspecialchars($_SESSION["name"]); ?>
                    👋
                </h2>

                <p class="text-muted mb-0">
                    Report and track your cybersecurity incidents from one place.
                </p>

            </div>

            <div class="col-md-4 text-md-end mt-3 mt-md-0">

                <a
                    href="report_incident.php"
                    class="btn btn-primary px-4"
                >
                    + Report Incident
                </a>

            </div>

        </div>

    </div>


    <!-- Statistics -->

    <div class="row g-3 mb-4">


        <!-- Total -->

        <div class="col-6 col-md-3">

            <div
                class="card stat-card shadow-sm h-100 active"
                data-filter="All"
                onclick="filterIncidents('All', this)"
            >

                <div class="card-body p-4">

                    <p class="text-muted mb-1">
                        My Incidents
                    </p>

                    <div class="stat-number">
                        <?php echo $total; ?>
                    </div>

                    <small class="text-muted">
                        Total incidents reported
                    </small>

                </div>

            </div>

        </div>


        <!-- Open -->

        <div class="col-6 col-md-3">

            <div
                class="card stat-card shadow-sm h-100"
                data-filter="Open"
                onclick="filterIncidents('Open', this)"
            >

                <div class="card-body p-4">

                    <p class="text-muted mb-1">
                        Open
                    </p>

                    <div class="stat-number text-danger">
                        <?php echo $openIncidents; ?>
                    </div>

                    <small class="text-muted">
                        Awaiting action
                    </small>

                </div>

            </div>

        </div>


        <!-- In Progress -->

        <div class="col-6 col-md-3">

            <div
                class="card stat-card shadow-sm h-100"
                data-filter="In Progress"
                onclick="filterIncidents('In Progress', this)"
            >

                <div class="card-body p-4">

                    <p class="text-muted mb-1">
                        In Progress
                    </p>

                    <div class="stat-number text-warning">
                        <?php echo $progressIncidents; ?>
                    </div>

                    <small class="text-muted">
                        Currently being handled
                    </small>

                </div>

            </div>

        </div>


        <!-- Resolved -->

        <div class="col-6 col-md-3">

            <div
                class="card stat-card shadow-sm h-100"
                data-filter="Resolved"
                onclick="filterIncidents('Resolved', this)"
            >

                <div class="card-body p-4">

                    <p class="text-muted mb-1">
                        Resolved
                    </p>

                    <div class="stat-number text-success">
                        <?php echo $resolvedIncidents; ?>
                    </div>

                    <small class="text-muted">
                        Successfully resolved
                    </small>

                </div>

            </div>

        </div>

    </div>


    <!-- Incidents -->

    <div class="card incident-card shadow-sm">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h4 class="fw-bold mb-1">
                        My Incidents
                    </h4>

                    <p class="text-muted mb-0">
                        View and track your reported incidents.
                    </p>

                </div>

                <span
                    id="filterLabel"
                    class="badge bg-primary"
                >
                    All Incidents
                </span>

            </div>


            <?php if ($myIncidents->num_rows > 0): ?>

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="incidentTable"
                    >

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Title</th>

                                <th>Severity</th>

                                <th>Status</th>

                                <th>Incident Date</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php while ($incident = $myIncidents->fetch_assoc()): ?>

                            <tr
                                data-status="<?php echo htmlspecialchars($incident["status"]); ?>"
                            >

                                <td class="fw-semibold">
                                    #<?php echo $incident["id"]; ?>
                                </td>


                                <td>

                                    <span class="fw-semibold">
                                        <?php
                                        echo htmlspecialchars(
                                            $incident["title"]
                                        );
                                        ?>
                                    </span>

                                </td>


                                <!-- Severity -->

                                <td>

                                    <?php if ($incident["severity"] === "High"): ?>

                                        <span class="badge bg-danger">
                                            High
                                        </span>

                                    <?php elseif ($incident["severity"] === "Medium"): ?>

                                        <span class="badge bg-warning text-dark">
                                            Medium
                                        </span>

                                    <?php elseif ($incident["severity"] === "Low"): ?>

                                        <span class="badge bg-success">
                                            Low
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            <?php
                                            echo htmlspecialchars(
                                                $incident["severity"]
                                            );
                                            ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Status -->

                                <td>

                                    <?php if ($incident["status"] === "Open"): ?>

                                        <span class="badge bg-danger">
                                            Open
                                        </span>

                                    <?php elseif ($incident["status"] === "In Progress"): ?>

                                        <span class="badge bg-warning text-dark">
                                            In Progress
                                        </span>

                                    <?php elseif ($incident["status"] === "Resolved"): ?>

                                        <span class="badge bg-success">
                                            Resolved
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            <?php
                                            echo htmlspecialchars(
                                                $incident["status"]
                                            );
                                            ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $incident["incident_date"]
                                    );
                                    ?>
                                </td>


                                <td>

                                    <a
                                        href="view_incident.php?id=<?php echo $incident["id"]; ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        👁️ View
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

                <!-- No Filter Results -->

                <div
                    id="noResults"
                    class="text-center empty-state d-none"
                >

                    <div class="fs-1 mb-3">
                        🔍
                    </div>

                    <h5 class="fw-bold">
                        No incidents found
                    </h5>

                    <p class="text-muted mb-0">
                        There are no incidents with this status.
                    </p>

                </div>

            <?php else: ?>

                <!-- Empty State -->

                <div class="text-center empty-state">

                    <div class="fs-1 mb-3">
                        🛡️
                    </div>

                    <h5 class="fw-bold">
                        No incidents reported yet
                    </h5>

                    <p class="text-muted">
                        If you experience a cybersecurity issue, report it here.
                    </p>

                    <a
                        href="report_incident.php"
                        class="btn btn-primary"
                    >
                        + Report Your First Incident
                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<script>

function filterIncidents(status, selectedCard) {

    const rows = document.querySelectorAll(
        "#incidentTable tbody tr"
    );

    const filterLabel = document.getElementById(
        "filterLabel"
    );

    const noResults = document.getElementById(
        "noResults"
    );


    // Remove active state from all cards

    document.querySelectorAll(".stat-card").forEach(function(card) {

        card.classList.remove("active");

    });


    // Add active state to selected card

    selectedCard.classList.add("active");


    let visibleRows = 0;


    rows.forEach(function(row) {

        const rowStatus = row.getAttribute(
            "data-status"
        );


        if (status === "All" || rowStatus === status) {

            row.style.display = "";

            visibleRows++;

        } else {

            row.style.display = "none";

        }

    });


    // Update filter label

    if (status === "All") {

        filterLabel.textContent = "All Incidents";

    } else {

        filterLabel.textContent = status;

    }


    // Show/hide no-results message

    if (visibleRows === 0) {

        noResults.classList.remove("d-none");

    } else {

        noResults.classList.add("d-none");

    }

}

</script>


</body>

</html>