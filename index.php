<?php
session_start();
$isLoggedIn = isset($_SESSION["user_id"]);
$userRole = $isLoggedIn && isset($_SESSION["role"]) ? $_SESSION["role"] : "";
$userName = $isLoggedIn && isset($_SESSION["name"]) ? $_SESSION["name"] : "";
$dashboardUrl = ($userRole === "admin") ? "admin/dashboard.php" : "user_dashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Incident Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Project Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        :root {
            --primary-navy: #0f172a;
            --accent-blue: #0d6efd;
            --card-border: #e2e8f0;
            --text-muted: #64748b;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .navbar {
            background-color: #0f172a;
        }

        .hero-section {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 5rem 0 4.5rem;
        }

        .badge-cyber {
            background-color: rgba(13, 110, 253, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(96, 165, 250, 0.3);
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 0.4rem 0.85rem;
            border-radius: 50rem;
        }

        .info-card {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            background: #ffffff;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            height: 100%;
        }

        .info-card:hover {
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            transform: translateY(-2px);
        }

        .feature-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 1rem;
        }

        .step-bubble {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #0f172a;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .section-heading {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .section-subheading {
            color: var(--text-muted);
            max-width: 650px;
            margin: 0 auto 2.5rem;
        }

        footer {
            background-color: #0f172a;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        footer a {
            color: #cbd5e1;
            text-decoration: none;
        }

        footer a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <span class="fs-4">🛡️</span>
                <span class="fw-semibold">CyberIncident Management</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#features">Key Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#workflow">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#benefits">Benefits</a>
                    </li>

                    <li class="nav-item ms-lg-2 d-flex gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm px-3" data-bs-toggle="modal" data-bs-target="#demoModal">
                            <i class="bi bi-mortarboard-fill me-1"></i> Demo Guide
                        </button>
                        <?php if ($isLoggedIn): ?>
                            <a href="<?php echo htmlspecialchars($dashboardUrl); ?>" class="btn btn-outline-light btn-sm px-3">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                            <a href="logout.php" class="btn btn-danger btn-sm px-3">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-light btn-sm px-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                            </a>
                            <a href="register.php" class="btn btn-primary btn-sm px-3">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 1. Welcome / Hero Section -->
    <header class="hero-section">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="badge-cyber d-inline-block mb-3">
                        <i class="bi bi-shield-check me-1"></i> Incident Response & Tracking Platform
                    </div>
                    <h1 class="display-5 fw-bold mb-3 lh-sm">
                        Cybersecurity Incident Management System
                    </h1>
                    <p class="lead text-light opacity-75 mb-4">
                        Welcome to the centralized incident reporting and monitoring platform. Report security concerns, monitor resolution workflows, and coordinate incident management with administrators in one place.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <?php if ($isLoggedIn): ?>
                            <a href="<?php echo htmlspecialchars($dashboardUrl); ?>" class="btn btn-primary btn-lg px-4 py-2">
                                <i class="bi bi-speedometer2 me-1"></i> Open Dashboard
                            </a>
                            <?php if ($userRole === "user"): ?>
                                <a href="report_incident.php" class="btn btn-outline-light btn-lg px-4 py-2">
                                    <i class="bi bi-plus-circle me-1"></i> Report an Incident
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-lg px-4 py-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Account
                            </a>
                            <a href="register.php" class="btn btn-outline-light btn-lg px-4 py-2">
                                <i class="bi bi-person-plus me-1"></i> Create Account
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-lg text-dark p-4 rounded-4 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-shield-lock-fill text-primary me-2"></i>Quick Overview
                            </h6>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Active System</span>
                        </div>
                        <p class="text-muted small mb-3">
                            A secure web application designed to maintain integrity and visibility across security incidents.
                        </p>
                        <div class="list-group list-group-flush small">
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person-badge text-primary me-2"></i>Access Level</span>
                                <span class="fw-semibold text-secondary">User &amp; Administrator</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-exclamation-triangle text-warning me-2"></i>Severity Classification</span>
                                <span class="fw-semibold text-secondary">Low, Medium, High, Critical</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-clock-history text-info me-2"></i>Status Lifecycle</span>
                                <span class="fw-semibold text-secondary">Open &rarr; In Progress &rarr; Resolved</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. About the Project Section -->
    <section id="about" class="py-5 bg-white">
        <div class="container py-3">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="text-primary fw-semibold small text-uppercase tracking-wider">About The System</span>
                    <h2 class="section-heading mt-1">What is a Cybersecurity Incident Management System?</h2>
                    <p class="text-secondary mt-3">
                        A Cybersecurity Incident Management System is a centralized solution used to record, evaluate, and track security events—such as malware detections, unauthorized access attempts, system vulnerabilities, and data security issues.
                    </p>
                    <p class="text-secondary">
                        Instead of handling incident reports through scattered emails or verbal communications, this platform standardizes the reporting process. It ensures every incident is logged with accurate metadata, classified by severity, reviewed by designated administrators, and tracked until resolution.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 rounded-4 bg-light border">
                        <h5 class="fw-bold text-dark mb-3">Why This System is Useful</h5>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3 text-secondary">
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill text-primary mt-1"></i>
                                <div><strong>Centralized Records:</strong> Consolidates all submitted security reports in a structured database for accountability and tracking.</div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill text-primary mt-1"></i>
                                <div><strong>Role-Based Control:</strong> Keeps reporter submissions private to their accounts while granting administrators full management access.</div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill text-primary mt-1"></i>
                                <div><strong>Transparent Status:</strong> Eliminates ambiguity by letting reporters see administrative remarks and updates in real time.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Key Features Section -->
    <section id="features" class="py-5">
        <div class="container py-3">
            <div class="text-center">
                <span class="text-primary fw-semibold small text-uppercase">Core Functionality</span>
                <h2 class="section-heading">Key Features</h2>
                <p class="section-subheading">
                    Engineered around the core workflows supported by the system, ensuring seamless interaction for users and administrators.
                </p>
            </div>

            <div class="row g-4">
                <!-- Incident Reporting -->
                <div class="col-md-6 col-lg-3">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-primary-subtle text-primary">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Incident Reporting</h5>
                        <p class="text-secondary small mb-0">
                            Registered users can securely report security incidents with descriptive titles, event details, occurrence dates, and severity levels.
                        </p>
                    </div>
                </div>

                <!-- Incident Tracking -->
                <div class="col-md-6 col-lg-3">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-success-subtle text-success">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Incident Tracking</h5>
                        <p class="text-secondary small mb-0">
                            Users can view all their submitted incident reports, inspect detailed individual views, and filter incidents by current status.
                        </p>
                    </div>
                </div>

                <!-- Incident Status Monitoring -->
                <div class="col-md-6 col-lg-3">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-warning-subtle text-warning">
                            <i class="bi bi-activity"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Status Monitoring</h5>
                        <p class="text-secondary small mb-0">
                            Provides real-time visibility into the lifecycle of each incident as it progresses from Open, to In Progress, to Resolved.
                        </p>
                    </div>
                </div>

                <!-- Administrator Management -->
                <div class="col-md-6 col-lg-3">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-info-subtle text-info">
                            <i class="bi bi-shield-shaded"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Admin Management</h5>
                        <p class="text-secondary small mb-0">
                            Administrators have dedicated dashboard controls to review all reported incidents, view reporter information, update statuses, and add administrative remarks.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. How It Works Section -->
    <section id="workflow" class="py-5 bg-white">
        <div class="container py-3">
            <div class="text-center">
                <span class="text-primary fw-semibold small text-uppercase">Step-by-Step</span>
                <h2 class="section-heading">How It Works</h2>
                <p class="section-subheading">
                    The end-to-end incident lifecycle from initial submission to administrative resolution.
                </p>
            </div>

            <div class="row g-4 text-center">
                <!-- Step 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3">
                        <div class="step-bubble">1</div>
                        <h5 class="fw-bold">Sign Up &amp; Log In</h5>
                        <p class="text-secondary small">
                            Users register an account or log in with password verification and role-based session authorization.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3">
                        <div class="step-bubble">2</div>
                        <h5 class="fw-bold">Report Incident</h5>
                        <p class="text-secondary small">
                            Users submit incident information including title, detailed description, severity rating, and incident date.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3">
                        <div class="step-bubble">3</div>
                        <h5 class="fw-bold">Admin Review</h5>
                        <p class="text-secondary small">
                            Administrators access the admin dashboard to inspect submitted incidents, reporter details, and timestamp records.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-3">
                        <div class="step-bubble">4</div>
                        <h5 class="fw-bold">Update &amp; Resolve</h5>
                        <p class="text-secondary small">
                            Administrators update the status and attach administrative remarks, allowing users to track the resolution directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Benefits Section -->
    <section id="benefits" class="py-5">
        <div class="container py-3">
            <div class="text-center">
                <span class="text-primary fw-semibold small text-uppercase">Advantages</span>
                <h2 class="section-heading">Key Benefits</h2>
                <p class="section-subheading">
                    How this system helps streamline security workflows and enhances organization.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-primary-subtle text-primary">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Organized Incident Records</h5>
                        <p class="text-secondary small mb-0">
                            Replaces unstructured reporting channels with a standardized database schema, making it simple to review past events and historical incident logs.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-success-subtle text-success">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Transparent Progress</h5>
                        <p class="text-secondary small mb-0">
                            Reporters receive full visibility into whether their reported issue is open, under active investigation, or resolved with official remarks.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card info-card p-4">
                        <div class="feature-icon-wrapper bg-info-subtle text-info">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Efficient Management</h5>
                        <p class="text-secondary small mb-0">
                            Clear administrative controls allow security administrators to triage reports based on severity levels and focus resources on critical threats.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Navigation / Call to Action Section -->
    <section class="py-5 bg-white border-top">
        <div class="container py-3 text-center">
            <h3 class="fw-bold text-dark mb-3">Ready to Access the System?</h3>
            <p class="text-secondary mb-4 mx-auto" style="max-width: 550px;">
                Log in to report a security incident, check the status of your existing reports, or access administrative management tools.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <?php if ($isLoggedIn): ?>
                    <a href="<?php echo htmlspecialchars($dashboardUrl); ?>" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-speedometer2 me-1"></i> Go to Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger px-4 py-2">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </a>
                    <a href="register.php" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-person-plus me-1"></i> Create Account
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 border-top">
        <div class="container text-center">
            <div class="mb-2">
                <span class="fw-semibold text-light">Cybersecurity Incident Reporting &amp; Management System</span>
            </div>
            <p class="mb-0 text-white-50 small">
                &copy; <?php echo date("Y"); ?> All rights reserved. Built for security incident logging and tracking.
            </p>
        </div>
    </footer>

    <!-- Demo Guide Modal -->
    <div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="demoModalLabel">
                        <i class="bi bi-shield-lock-fill text-warning me-2"></i>Demo Guide
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Demo User -->
                        <div class="col-12">
                            <div class="card border-primary-subtle bg-light p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-primary mb-0">Demo User</h6>
                                    <span class="badge bg-primary">User</span>
                                </div>
                                <div class="small mb-1"><strong>Email:</strong> <code>demo_user@incident.com</code></div>
                                <div class="small mb-3"><strong>Password:</strong> <code>User@Demo123</code></div>
                                <a href="login.php?demo=user" class="btn btn-primary btn-sm w-100 fw-semibold">
                                    Use Demo User
                                </a>
                            </div>
                        </div>

                        <!-- Demo Admin -->
                        <div class="col-12">
                            <div class="card border-danger-subtle bg-light p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-danger mb-0">Demo Admin</h6>
                                    <span class="badge bg-danger">Admin</span>
                                </div>
                                <div class="small mb-1"><strong>Email:</strong> <code>demo_admin@incident.com</code></div>
                                <div class="small mb-3"><strong>Password:</strong> <code>Admin@Demo123</code></div>
                                <a href="login.php?demo=admin" class="btn btn-danger btn-sm w-100 fw-semibold">
                                    Use Demo Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
