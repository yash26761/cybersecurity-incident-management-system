<?php
session_start();
require_once "includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: user_dashboard.php");
    exit();
}

$incidentId = (int) $_GET["id"];

$sql = "SELECT id, title, description, severity, status, incident_date, created_at
        FROM incidents
        WHERE id = ? AND reported_by = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $incidentId, $userId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: user_dashboard.php");
    exit();
}

$incident = $result->fetch_assoc();
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

    <title>
        Incident Details - Cybersecurity Incident System
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        .details-card {
            border: none;
            border-radius: 16px;
        }

        .detail-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        .description-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            min-height: 120px;
            white-space: normal;
        }

        .incident-title {
            font-weight: 700;
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
            href="logout.php"
            class="btn btn-danger"
        >
            Logout
        </a>

    </div>

</nav>


<div class="container py-4">


    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Incident Details
            </h2>

            <p class="text-muted mb-0">
                Review the details and current status of your incident.
            </p>

        </div>


        <a
            href="user_dashboard.php"
            class="btn btn-outline-secondary"
        >
            ← Dashboard
        </a>

    </div>


    <!-- Incident Card -->

    <div class="card details-card shadow-sm">

        <div class="card-body p-4">


            <!-- Title -->

            <div class="mb-4">

                <small class="text-muted">
                    Incident Title
                </small>

                <h3 class="incident-title mt-1">

                    <?php
                    echo htmlspecialchars($incident["title"]);
                    ?>

                </h3>

            </div>


            <!-- Basic Information -->

            <div class="row g-3 mb-4">


                <!-- ID -->

                <div class="col-md-4">

                    <div class="detail-box h-100">

                        <small class="text-muted">
                            Incident ID
                        </small>

                        <div class="fw-bold mt-1">
                            #<?php echo $incident["id"]; ?>
                        </div>

                    </div>

                </div>


                <!-- Severity -->

                <div class="col-md-4">

                    <div class="detail-box h-100">

                        <small class="text-muted">
                            Severity
                        </small>

                        <div class="mt-1">


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


                        </div>

                    </div>

                </div>


                <!-- Status -->

                <div class="col-md-4">

                    <div class="detail-box h-100">

                        <small class="text-muted">
                            Current Status
                        </small>

                        <div class="mt-1">


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


                        </div>

                    </div>

                </div>

            </div>


            <!-- Description -->

            <div class="mb-4">

                <small class="text-muted">
                    Description
                </small>

                <div class="description-box mt-2">

                    <?php
                    echo nl2br(
                        htmlspecialchars($incident["description"])
                    );
                    ?>

                </div>

            </div>


            <!-- Dates -->

            <div class="row g-3">


                <div class="col-md-6">

                    <div class="detail-box">

                        <small class="text-muted">
                            Incident Date
                        </small>

                        <div class="fw-semibold mt-1">

                            <?php
                            echo htmlspecialchars(
                                $incident["incident_date"]
                            );
                            ?>

                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="detail-box">

                        <small class="text-muted">
                            Reported On
                        </small>

                        <div class="fw-semibold mt-1">

                            <?php
                            echo htmlspecialchars(
                                $incident["created_at"]
                            );
                            ?>

                        </div>

                    </div>

                </div>


            </div>


        </div>

    </div>


</div>


</body>

</html>



