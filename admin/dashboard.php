<?php
session_start();
require_once "../includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}


// Get selected status filter

$selectedStatus = $_GET["status"] ?? "";

$allowedStatuses = ["Open", "In Progress", "Resolved"];

if ($selectedStatus !== "" && !in_array($selectedStatus, $allowedStatuses)) {
    $selectedStatus = "";
}


// Get incident counts

$totalResult = $conn->query(
    "SELECT COUNT(*) AS total FROM incidents"
);
$totalIncidents = $totalResult->fetch_assoc()["total"];


$openResult = $conn->query(
    "SELECT COUNT(*) AS total FROM incidents WHERE status = 'Open'"
);
$openIncidents = $openResult->fetch_assoc()["total"];


$progressResult = $conn->query(
    "SELECT COUNT(*) AS total FROM incidents WHERE status = 'In Progress'"
);
$progressIncidents = $progressResult->fetch_assoc()["total"];


$resolvedResult = $conn->query(
    "SELECT COUNT(*) AS total FROM incidents WHERE status = 'Resolved'"
);
$resolvedIncidents = $resolvedResult->fetch_assoc()["total"];


// Get incidents based on selected filter

if ($selectedStatus !== "") {

    $recentStmt = $conn->prepare("
        SELECT id, title, severity, status, created_at
        FROM incidents
        WHERE status = ?
        ORDER BY id DESC
    ");

    $recentStmt->bind_param("s", $selectedStatus);
    $recentStmt->execute();

    $recentIncidents = $recentStmt->get_result();

} else {

    $recentIncidents = $conn->query("
        SELECT id, title, severity, status, created_at
        FROM incidents
        ORDER BY id DESC
    ");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Admin Dashboard - Cybersecurity Incident System
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f7fb;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .welcome-card {
            border: none;
            border-radius: 16px;
            background: white;
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
        }

        .incident-card {
            border: none;
            border-radius: 16px;
        }

        .table td {
            vertical-align: middle;
        }

        .table th {
            white-space: nowrap;
        }

        .btn {
            border-radius: 8px;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .active-filter {
            border: 2px solid #212529;
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

        <a
            href="../logout.php"
            class="btn btn-danger"
        >
            Logout
        </a>

    </div>

</nav>


<div class="container py-4">


    <!-- Welcome Section -->

    <div class="card welcome-card shadow-sm p-4 mb-4">

        <h2 class="fw-bold mb-2">

            Welcome,
            <?php echo htmlspecialchars($_SESSION["name"]); ?>
            👋

        </h2>

        <p class="text-muted mb-0">

            Monitor, review and manage cybersecurity incidents.

        </p>

    </div>


    <!-- Statistics -->

    <div class="row g-3 mb-4">


        <!-- Total -->

        <div class="col-6 col-md-3">

            <a
                href="dashboard.php"
                class="text-decoration-none text-dark"
            >

                <div
                    class="card stat-card shadow-sm h-100
                    <?php echo ($selectedStatus === "") ? "active-filter" : ""; ?>"
                >

                    <div class="card-body p-4">

                        <p class="text-muted mb-1">
                            Total Incidents
                        </p>

                        <div class="stat-number">
                            <?php echo $totalIncidents; ?>
                        </div>

                        <small class="text-muted">
                            All reported incidents
                        </small>

                    </div>

                </div>

            </a>

        </div>


        <!-- Open -->

        <div class="col-6 col-md-3">

            <a
                href="dashboard.php?status=Open"
                class="text-decoration-none text-dark"
            >

                <div
                    class="card stat-card shadow-sm h-100
                    <?php echo ($selectedStatus === "Open") ? "active-filter" : ""; ?>"
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

            </a>

        </div>


        <!-- In Progress -->

        <div class="col-6 col-md-3">

            <a
                href="dashboard.php?status=In%20Progress"
                class="text-decoration-none text-dark"
            >

                <div
                    class="card stat-card shadow-sm h-100
                    <?php echo ($selectedStatus === "In Progress") ? "active-filter" : ""; ?>"
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

            </a>

        </div>


        <!-- Resolved -->

        <div class="col-6 col-md-3">

            <a
                href="dashboard.php?status=Resolved"
                class="text-decoration-none text-dark"
            >

                <div
                    class="card stat-card shadow-sm h-100
                    <?php echo ($selectedStatus === "Resolved") ? "active-filter" : ""; ?>"
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

            </a>

        </div>

    </div>


    <!-- Recent Incidents -->

    <div class="card incident-card shadow-sm">

        <div class="card-body p-4">


            <div class="mb-3">

                <h4 class="fw-bold mb-1">

                    <?php
                    if ($selectedStatus === "") {
                        echo "All Incidents";
                    } else {
                        echo htmlspecialchars($selectedStatus) . " Incidents";
                    }
                    ?>

                </h4>

                <p class="text-muted mb-0">

                    <?php
                    if ($selectedStatus === "") {
                        echo "View all reported cybersecurity incidents.";
                    } else {
                        echo "View incidents currently marked as "
                            . htmlspecialchars($selectedStatus)
                            . ".";
                    }
                    ?>

                </p>

            </div>


            <?php if ($recentIncidents->num_rows > 0): ?>


                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Title</th>

                                <th>Severity</th>

                                <th>Status</th>

                                <th>Date</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while ($incident = $recentIncidents->fetch_assoc()): ?>


                            <tr>


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
                                        $incident["created_at"]
                                    );
                                    ?>

                                </td>


                                <!-- Action -->

                                <td>

                                    <a
                                        href="view_incident.php?id=<?php echo $incident["id"]; ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        👁️ Open
                                    </a>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                        </tbody>

                    </table>

                </div>


            <?php else: ?>


                <!-- Empty State -->

                <div class="text-center empty-state">

                    <div class="fs-1 mb-3">
                        🛡️
                    </div>

                    <h5 class="fw-bold">
                        No incidents found
                    </h5>

                    <p class="text-muted mb-0">
                        There are no incidents with this status.
                    </p>

                </div>

            <?php endif; ?>


        </div>

    </div>


</div>


</body>

</html>