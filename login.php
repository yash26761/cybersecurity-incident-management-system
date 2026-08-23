<?php
session_start();
require_once "includes/config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {

                header("Location: admin/dashboard.php");

            } else {

                header("Location: user_dashboard.php");

            }

            exit();

        } else {

            $message = "Invalid email or password!";

        }

    } else {

        $message = "Invalid email or password!";

    }
}
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
        Login - Cybersecurity Incident System
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .login-header {
            background: #212529;
            color: white;
            padding: 28px 20px;
            text-align: center;
        }

        .shield {
            font-size: 42px;
            margin-bottom: 8px;
        }

        .login-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: #adb5bd;
            font-size: 14px;
            margin-bottom: 0;
        }

        .form-control {
            border-radius: 9px;
            padding: 11px 13px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .login-btn {
            border-radius: 9px;
            padding: 11px;
            font-weight: 600;
        }

        .register-link {
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text {
            font-size: 13px;
            color: #6c757d;
        }

    </style>

</head>


<body>


<div class="container">

    <div class="row justify-content-center">

        <div class="col-12 col-sm-10 col-md-6 col-lg-5">


            <div class="card login-card shadow">


                <!-- Header -->

                <div class="login-header">

                    <div class="shield">
                        🛡️
                    </div>

                    <h2 class="login-title">
                        Welcome Back
                    </h2>

                    <p class="login-subtitle">
                        Cybersecurity Incident Management System
                    </p>

                </div>


                <!-- Login Form -->

                <div class="card-body p-4 p-md-5">


                    <?php if ($message != ""): ?>

                        <div
                            class="alert alert-danger"
                            role="alert"
                        >

                            <?php
                            echo htmlspecialchars($message);
                            ?>

                        </div>

                    <?php endif; ?>


                    <form method="POST">


                        <!-- Email -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold"
                                for="email"
                            >
                                Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                autocomplete="email"
                                required
                            >

                        </div>


                        <!-- Password -->

                        <div class="mb-4">

                            <label
                                class="form-label fw-semibold"
                                for="password"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                        </div>


                        <!-- Login -->

                        <button
                            type="submit"
                            class="btn btn-primary w-100 login-btn"
                        >
                            🔐 Login
                        </button>


                    </form>


                    <!-- Register -->

                    <div class="text-center mt-4">

                        <span class="text-muted">
                            Don't have an account?
                        </span>

                        <a
                            href="register.php"
                            class="register-link"
                        >
                            Create Account
                        </a>

                    </div>


                    <div class="text-center mt-4">

                        <div class="footer-text">
                            Secure access to incident management
                        </div>

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>


</body>

</html>




