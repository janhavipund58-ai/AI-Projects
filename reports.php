<?php
include "db.php";

/* Total Resources */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM resources");
$total_resources = mysqli_fetch_assoc($result)['total'];

/* Available Resources */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM resources
     WHERE availability_status='Available'"
);
$available_resources = mysqli_fetch_assoc($result)['total'];

/* Busy Resources */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM resources
     WHERE availability_status='Busy'"
);
$busy_resources = mysqli_fetch_assoc($result)['total'];

/* Total Projects */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM projects");
$total_projects = mysqli_fetch_assoc($result)['total'];

/* Total Allocations */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM allocations");
$total_allocations = mysqli_fetch_assoc($result)['total'];

/* Active Allocations */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM allocations
     WHERE status='Active'"
);
$active_allocations = mysqli_fetch_assoc($result)['total'];

/* Project Allocation Report */
$project_report = mysqli_query(
    $conn,
    "SELECT
        p.project_name,
        COUNT(a.allocation_id) AS total_allocations,
        COALESCE(SUM(a.allocation_percentage),0) AS total_percentage
     FROM projects p
     LEFT JOIN allocations a
        ON p.project_id = a.project_id
     GROUP BY p.project_id, p.project_name
     ORDER BY p.project_id"
);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Reports - IT Resource Allocation</title>

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

        .sidebar {
            position: fixed;
            width: 240px;
            height: 100vh;
            background: #1f2937;
            padding: 25px 15px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin: 7px 0;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #374151;
        }

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
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
        }

        .cards {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            color: #666;
            margin-bottom: 12px;
        }

        .card p {
            font-size: 32px;
            font-weight: bold;
        }

        .report-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        .logout {
            background: #dc2626;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <h2>IT Resource System</h2>

    <a href="dashboard.php">🏠 Dashboard</a>

    <a href="resources.php">👨‍💻 Resources</a>

    <a href="skills.php">🛠 Skills</a>

    <a href="projects.php">📁 Projects</a>

    <a href="allocation.php">📊 Allocations</a>

    <a href="availability.php">📅 Availability</a>

    <a href="ai_matching.php">🤖 AI Matching</a>

    <a href="reports.php">📈 Reports</a>

    <a href="logout.php" class="logout">🚪 Logout</a>

</div>


<div class="main">

    <div class="header">

        <h1>📈 Resource Allocation Reports</h1>

        <p>
            Summary of resources, projects and allocations
        </p>

    </div>


    <div class="cards">

        <div class="card">
            <h3>Total Resources</h3>
            <p><?php echo $total_resources; ?></p>
        </div>

        <div class="card">
            <h3>Available</h3>
            <p><?php echo $available_resources; ?></p>
        </div>

        <div class="card">
            <h3>Busy</h3>
            <p><?php echo $busy_resources; ?></p>
        </div>

        <div class="card">
            <h3>Total Projects</h3>
            <p><?php echo $total_projects; ?></p>
        </div>

        <div class="card">
            <h3>Total Allocations</h3>
            <p><?php echo $total_allocations; ?></p>
        </div>

        <div class="card">
            <h3>Active Allocations</h3>
            <p><?php echo $active_allocations; ?></p>
        </div>

    </div>


    <div class="report-box">

        <h2>Project Allocation Report</h2>

        <table>

            <tr>
                <th>Project Name</th>
                <th>Total Allocations</th>
                <th>Total Allocation %</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($project_report)) { ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($row['project_name']); ?>
                </td>

                <td>
                    <?php echo $row['total_allocations']; ?>
                </td>

                <td>
                    <?php echo $row['total_percentage']; ?>%
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>