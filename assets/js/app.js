/**
 * Cybersecurity Incident Management System - frontend interactions.
 * This file contains the JavaScript previously embedded in login.php and
 * user_dashboard.php. Function names remain global for existing onclick handlers.
 */

function fillDemo(role) {
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    if (role === 'user') {
        emailField.value = 'demo_user@incident.com';
        passwordField.value = 'User@Demo123';
    } else if (role === 'admin') {
        emailField.value = 'demo_admin@incident.com';
        passwordField.value = 'Admin@Demo123';
    }
}

// Automatically populate if navigated with ?demo=user or ?demo=admin
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const demo = params.get('demo');
    if (demo === 'user' || demo === 'admin') {
        fillDemo(demo);
    }
});

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
