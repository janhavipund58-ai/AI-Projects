<?php
include "db.php";

/* =========================
   DASHBOARD STATISTICS
   ========================= */

// Total Resources
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM resources");
$row = mysqli_fetch_assoc($result);
$total_resources = $row['total'];

// Total Projects
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM projects");
$row = mysqli_fetch_assoc($result);
$total_projects = $row['total'];

// Available Resources
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM resources
     WHERE availability_status = 'Available'"
);
$row = mysqli_fetch_assoc($result);
$available_resources = $row['total'];

// Busy Resources
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM resources
     WHERE availability_status = 'Busy'"
);
$row = mysqli_fetch_assoc($result);
$busy_resources = $row['total'];

// Active Allocations
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM allocations
     WHERE status = 'Active'"
);
$row = mysqli_fetch_assoc($result);
$active_allocations = $row['total'];


/* =========================
   PROJECT ALLOCATION DATA
   ========================= */

$project_names = [];
$project_allocations = [];

$result = mysqli_query(
    $conn,
    "SELECT
        p.project_name,
        COALESCE(SUM(a.allocation_percentage), 0) AS total_allocation
     FROM projects p
     LEFT JOIN allocations a
        ON p.project_id = a.project_id
     GROUP BY p.project_id, p.project_name
     ORDER BY p.project_id"
);

while ($row = mysqli_fetch_assoc($result)) {
    $project_names[] = $row['project_name'];
    $project_allocations[] = $row['total_allocation'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>IT Resource Allocation Dashboard</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
        }

        /* =========================
           SIDEBAR
           ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #1f2937;
            padding: 25px 15px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 21px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 13px 15px;
            margin: 7px 0;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #374151;
            padding-left: 20px;
        }

        .logout {
            margin-top: 25px;
            background: #dc2626;
        }

        .logout:hover {
            background: #b91c1c !important;
        }


        /* =========================
           MAIN CONTENT
           ========================= */

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .header h1 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
        }


        /* =========================
           STATISTICS CARDS
           ========================= */

        .stats-container {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));

            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .stat-card p {
            font-size: 35px;
            font-weight: bold;
            color: #111827;
        }


        /* =========================
           MENU CARDS
           ========================= */

        .menu-title {
            margin-bottom: 15px;
            color: #111827;
        }

        .menu-container {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));

            gap: 15px;
            margin-bottom: 30px;
        }

        .menu-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #111827;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
        }

        .menu-card h3 {
            margin-bottom: 8px;
        }

        .menu-card p {
            color: #6b7280;
            font-size: 14px;
        }


        /* =========================
           CHARTS
           ========================= */

        .charts-container {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(350px, 1fr));

            gap: 25px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .chart-card h2 {
            margin-bottom: 20px;
            color: #111827;
            font-size: 20px;
        }

        .chart-box {
            position: relative;
            height: 320px;
        }


        /* =========================
           RESPONSIVE
           ========================= */

        @media (max-width: 768px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
            }

        }

    </style>

</head>

<body>


<!-- =========================
     SIDEBAR
     ========================= -->

<div class="sidebar">

    <h2>IT Resource System</h2>

    <a href="dashboard.php">
        🏠 Dashboard
    </a>

    <a href="resources.php">
        👨‍💻 Resources
    </a>

    <a href="skills.php">
        🛠 Skills
    </a>

    <a href="projects.php">
        📁 Projects
    </a>

    <a href="allocation.php">
        📊 Allocations
    </a>

    <a href="availability.php">
        📅 Availability
    </a>

    <a href="ai_matching.php">
        🤖 AI Resource Matching
    </a>

    <a href="index.php" class="logout">
        🚪 Logout
    </a>

</div>


<!-- =========================
     MAIN CONTENT
     ========================= -->

<div class="main">


    <!-- HEADER -->

    <div class="header">

        <h1>
            IT Project Resource Allocation System
        </h1>

        <p>
            Resource management, project allocation and
            AI-based resource matching
        </p>

    </div>


    <!-- =========================
         STATISTICS
         ========================= -->

    <div class="stats-container">


        <div class="stat-card">

            <h3>
                👨‍💻 Total Resources
            </h3>

            <p>
                <?php echo $total_resources; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>
                📁 Total Projects
            </h3>

            <p>
                <?php echo $total_projects; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>
                ✅ Available Resources
            </h3>

            <p>
                <?php echo $available_resources; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>
                📊 Active Allocations
            </h3>

            <p>
                <?php echo $active_allocations; ?>
            </p>

        </div>

    </div>


    <!-- =========================
         QUICK ACCESS
         ========================= -->

    <h2 class="menu-title">
        Quick Access
    </h2>


    <div class="menu-container">


        <a href="resources.php"
           class="menu-card">

            <h3>👨‍💻 Resources</h3>

            <p>
                Manage IT resources
            </p>

        </a>


        <a href="skills.php"
           class="menu-card">

            <h3>🛠 Skills</h3>

            <p>
                Manage resource skills
            </p>

        </a>


        <a href="projects.php"
           class="menu-card">

            <h3>📁 Projects</h3>

            <p>
                Manage projects
            </p>

        </a>


        <a href="allocation.php"
           class="menu-card">

            <h3>📊 Allocation</h3>

            <p>
                Allocate resources
            </p>

        </a>


        <a href="availability.php"
           class="menu-card">

            <h3>📅 Availability</h3>

            <p>
                Check availability
            </p>

        </a>


        <a href="ai_matching.php"
           class="menu-card">

            <h3>🤖 AI Matching</h3>

            <p>
                Find the best resource
            </p>
            </a>
<a href="er_diagram.php" class="quick-card">
    <div class="quick-icon">🗂️</div>
    <h3>ER Diagram</h3>
    <p>View Database ER Diagram</p>
</a>

    </div>


    <!-- =========================
         CHARTS
         ========================= -->

    <div class="charts-container">


        <!-- RESOURCE AVAILABILITY -->

        <div class="chart-card">

            <h2>
                Resource Availability
            </h2>

            <div class="chart-box">

                <canvas id="resourceChart"></canvas>

            </div>

        </div>


        <!-- PROJECT ALLOCATION -->

        <div class="chart-card">

            <h2>
                Project Allocation
            </h2>

            <div class="chart-box">

                <canvas id="projectChart"></canvas>

            </div>

        </div>


    </div>

</div>


<!-- =========================
     JAVASCRIPT CHARTS
     ========================= -->

<script>

    /* =========================
       RESOURCE AVAILABILITY
       ========================= */

    const resourceChart =
        document.getElementById('resourceChart');

    new Chart(resourceChart, {

        type: 'doughnut',

        data: {

            labels: [
                'Available',
                'Busy'
            ],

            datasets: [{

                data: [
                    <?php echo $available_resources; ?>,
                    <?php echo $busy_resources; ?>
                ]

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'bottom'

                }

            }

        }

    });


    /* =========================
       PROJECT ALLOCATION
       ========================= */

    const projectChart =
        document.getElementById('projectChart');

    new Chart(projectChart, {

        type: 'bar',

        data: {

            labels:
                <?php echo json_encode($project_names); ?>,

            datasets: [{

                label:
                    'Allocation Percentage',

                data:
                    <?php echo json_encode($project_allocations); ?>

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    title: {

                        display: true,

                        text: 'Allocation %'

                    }

                }

            },

            plugins: {

                legend: {

                    display: false

                }

            }

        }

    });

</script>


</body>

</html>