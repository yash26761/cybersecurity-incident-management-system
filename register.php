<?php
require_once "includes/config.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {

        $message = "Passwords do not match!";
        $messageType = "danger";

    } else {

        // Check if email already exists
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {

            $message = "An account with this email already exists!";
            $messageType = "danger";

        } else {

            // Store password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // New accounts are always normal users
            $role = "user";

            $sql = "INSERT INTO users (name, email, password, role)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssss",
                $name,
                $email,
                $hashedPassword,
                $role
            );

            if ($stmt->execute()) {

                $message = "Account created successfully! You can now login.";
                $messageType = "success";

            } else {

                $message = "Something went wrong. Please try again.";
                $messageType = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account - Cybersecurity Incident System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">
                        🛡️ Create Account
                    </h2>

                    <?php if ($message != ""): ?>

                        <div class="alert alert-<?php echo $messageType; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>

                    <?php endif; ?>


                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter your name"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Create a password"
                                minlength="6"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                placeholder="Confirm your password"
                                minlength="6"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Create Account
                        </button>

                    </form>


                    <div class="text-center mt-3">

                        <span>Already have an account?</span>

                        <a href="login.php">
                            Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>
