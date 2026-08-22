<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $severity = $_POST["severity"];
    $incident_date = $_POST["incident_date"];
    $status = "Open";
    $reported_by = $_SESSION["user_id"];

    $sql = "INSERT INTO incidents 
            (title, description, severity, status, reported_by, incident_date)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssss",
        $title,
        $description,
        $severity,
        $status,
        $reported_by,
        $incident_date
    );

    if ($stmt->execute()) {

    header("Location: user_dashboard.php");
    exit();

} else {

    $message = "Error reporting incident.";

}

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Report Incident</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <span class="navbar-brand">
            🛡️ Cybersecurity Incident Management
        </span>

        <a href="user_dashboard.php" class="btn btn-secondary">
    Dashboard
        </a>

    </div>

</nav>


<div class="container mt-5">

    <div class="card shadow-sm">

        <div class="card-body">

            <h2 class="mb-4">Report Cybersecurity Incident</h2>


            <?php if ($message != ""): ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="mb-3">

                    <label class="form-label">
                        Incident Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Enter incident title"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"
                        placeholder="Describe the incident"
                        required
                    ></textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Severity
                    </label>

                    <select name="severity" class="form-select" required>

                        <option value="">Select Severity</option>

                        <option value="Low">Low</option>

                        <option value="Medium">Medium</option>

                        <option value="High">High</option>

                        <option value="Critical">Critical</option>

                    </select>

                </div>



                <div class="mb-3">

                    <label class="form-label">
                        Incident Date
                    </label>

                    <input
                        type="date"
                        name="incident_date"
                        class="form-control"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Report Incident
                </button>


                <a
                    href="user_dashboard.php"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

</body>

</html>