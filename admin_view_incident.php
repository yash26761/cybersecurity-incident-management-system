<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: admin_dashboard.php");
    exit();
}

$incidentId = (int) $_GET["id"];

$message = "";
$messageType = "";


// Update incident
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST["status"];
    $adminRemarks = trim($_POST["admin_remarks"]);

    $allowedStatuses = ["Open", "In Progress", "Resolved"];

    if (!in_array($status, $allowedStatuses)) {

        $message = "Invalid status selected.";
        $messageType = "danger";

    } else {

        $updateSql = "UPDATE incidents
                      SET status = ?, admin_remarks = ?
                      WHERE id = ?";

        $updateStmt = $conn->prepare($updateSql);

        $updateStmt->bind_param(
            "ssi",
            $status,
            $adminRemarks,
            $incidentId
        );

        if ($updateStmt->execute()) {

            $message = "Incident updated successfully!";
            $messageType = "success";

        } else {

            $message = "Unable to update incident.";
            $messageType = "danger";

        }
    }
}


// Get incident details
$sql = "SELECT
            incidents.id,
            incidents.title,
            incidents.description,
            incidents.severity,
            incidents.status,
            incidents.admin_remarks,
            incidents.incident_date,
            incidents.created_at,
            users.name AS reporter_name,
            users.email AS reporter_email
        FROM incidents
        INNER JOIN users
            ON incidents.reported_by = users.id
        WHERE incidents.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $incidentId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: admin_dashboard.php");
    exit();
}

$incident = $result->fetch_assoc();
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
        Manage Incident - Admin
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

        .main-card {
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
        }

        .update-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
        }

        .btn {
            border-radius: 8px;
        }

        .incident-title {
            font-weight: 700;
        }

        .section-title {
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
                Manage Incident
            </h2>

            <p class="text-muted mb-0">
                Review and manage the reported cybersecurity incident.
            </p>

        </div>


        <a
            href="admin_dashboard.php"
            class="btn btn-outline-secondary"
        >
            ← Dashboard
        </a>

    </div>


    <!-- Message -->

    <?php if ($message !== ""): ?>

        <div
            class="alert alert-<?php echo $messageType; ?> shadow-sm"
            role="alert"
        >

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- Main Incident Card -->

    <div class="card main-card shadow-sm">

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


            <!-- Incident Information -->

            <div class="row g-3 mb-4">


                <!-- ID -->

                <div class="col-md-4">

                    <div class="detail-box h-100">

                        <small class="text-muted">
                            Incident ID
                        </small>

                        <div class="fw-bold mt-1">

                            #<?php
                            echo $incident["id"];
                            ?>

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


            <!-- Reporter Information -->

            <h5 class="section-title mb-3">
                Reporter Information
            </h5>


            <div class="row g-3 mb-4">


                <div class="col-md-6">

                    <div class="detail-box">

                        <small class="text-muted">
                            Reported By
                        </small>

                        <div class="fw-semibold mt-1">

                            <?php
                            echo htmlspecialchars(
                                $incident["reporter_name"]
                            );
                            ?>

                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="detail-box">

                        <small class="text-muted">
                            Email
                        </small>

                        <div class="fw-semibold mt-1">

                            <?php
                            echo htmlspecialchars(
                                $incident["reporter_email"]
                            );
                            ?>

                        </div>

                    </div>

                </div>


            </div>


            <!-- Description -->

            <h5 class="section-title mb-3">
                Incident Description
            </h5>


            <div class="description-box mb-4">

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $incident["description"]
                    )
                );
                ?>

            </div>


            <!-- Dates -->

            <div class="row g-3 mb-4">


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


            <hr class="my-4">


            <!-- Update Section -->

            <div class="update-card">

                <h4 class="section-title mb-1">
                    Update Incident
                </h4>

                <p class="text-muted mb-4">
                    Change the incident status and add administrative remarks.
                </p>


                <form method="POST">


                    <!-- Status -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Open"
                                <?php
                                echo (
                                    $incident["status"] === "Open"
                                )
                                ? "selected"
                                : "";
                                ?>
                            >
                                Open
                            </option>


                            <option
                                value="In Progress"
                                <?php
                                echo (
                                    $incident["status"] === "In Progress"
                                )
                                ? "selected"
                                : "";
                                ?>
                            >
                                In Progress
                            </option>


                            <option
                                value="Resolved"
                                <?php
                                echo (
                                    $incident["status"] === "Resolved"
                                )
                                ? "selected"
                                : "";
                                ?>
                            >
                                Resolved
                            </option>

                        </select>

                    </div>


                    <!-- Remarks -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Admin Remarks
                        </label>

                        <textarea
                            name="admin_remarks"
                            class="form-control"
                            rows="4"
                            placeholder="Enter remarks or resolution details..."
                        ><?php
                        echo htmlspecialchars(
                            $incident["admin_remarks"] ?? ""
                        );
                        ?></textarea>

                    </div>


                    <!-- Update Button -->

                    <button
                        type="submit"
                        class="btn btn-success px-4"
                    >
                        ✓ Update Incident
                    </button>


                </form>

            </div>


        </div>

    </div>


</div>


</body>

</html>