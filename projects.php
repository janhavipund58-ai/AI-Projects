<?php

include "db.php";

/* =========================
   ADD PROJECT
========================= */

if (isset($_POST['add_project'])) {

    $project_name = mysqli_real_escape_string(
        $conn,
        trim($_POST['project_name'])
    );

    $client_name = mysqli_real_escape_string(
        $conn,
        trim($_POST['client_name'])
    );

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $priority = $_POST['priority'];

    $sql = "INSERT INTO projects
            (project_name, client_name, start_date, end_date, priority)
            VALUES
            ('$project_name',
             '$client_name',
             '$start_date',
             '$end_date',
             '$priority')";

    if (mysqli_query($conn, $sql)) {

        header("Location: projects.php");
        exit();

    } else {

        $message = "Error: " . mysqli_error($conn);
    }
}


/* =========================
   DELETE PROJECT
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $sql = "DELETE FROM projects
            WHERE project_id = $id";

    if (mysqli_query($conn, $sql)) {

        header("Location: projects.php");
        exit();

    } else {

        $message = "Cannot delete this project because it is being used.";
    }
}


/* =========================
   GET PROJECTS
========================= */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM projects
     ORDER BY project_id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Projects | IT Resource Allocation</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f1f5f9;
    color: #172554;
}


/* SIDEBAR */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;

    width: 250px;
    height: 100vh;

    background: #172554;

    color: white;

    padding: 25px 15px;
}

.logo {
    text-align: center;
    margin-bottom: 30px;
}

.logo-icon {
    font-size: 40px;
}

.logo h2 {
    margin-top: 8px;
}

.logo p {
    font-size: 11px;
    color: #cbd5e1;
    margin-top: 5px;
}

.menu {
    list-style: none;
}

.menu li {
    margin: 8px 0;
}

.menu a {
    display: block;

    padding: 13px 15px;

    color: white;

    text-decoration: none;

    border-radius: 8px;

    transition: 0.3s;
}

.menu a:hover {
    background: #2563eb;
}

.menu a.active {
    background: white;
    color: #172554;
    font-weight: bold;
}


/* MAIN */

.main {
    margin-left: 250px;
    min-height: 100vh;
}


/* TOPBAR */

.topbar {
    height: 75px;

    background: white;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 30px;

    box-shadow:
        0 2px 10px rgba(0,0,0,0.08);
}

.topbar h1 {
    font-size: 24px;
}

.admin {
    background: #e0e7ff;

    color: #1e3a8a;

    padding: 10px 18px;

    border-radius: 20px;

    font-weight: bold;
}


/* CONTENT */

.content {
    padding: 30px;
}


/* HEADER */

.page-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 25px;
}

.page-header h2 {
    font-size: 25px;
}

.add-button {
    background: #2563eb;

    color: white;

    border: none;

    padding: 12px 20px;

    border-radius: 8px;

    cursor: pointer;

    font-weight: bold;

    font-size: 14px;
}

.add-button:hover {
    background: #1d4ed8;
}


/* MESSAGE */

.message {
    background: #fee2e2;

    color: #991b1b;

    padding: 12px 15px;

    border-radius: 8px;

    margin-bottom: 20px;

    font-weight: bold;
}


/* FORM */

.form-box {
    display: none;

    background: white;

    padding: 25px;

    border-radius: 15px;

    margin-bottom: 25px;

    box-shadow:
        0 5px 15px rgba(0,0,0,0.08);
}

.form-box h3 {
    margin-bottom: 20px;
}

.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 18px;
}

.form-group label {
    display: block;

    margin-bottom: 7px;

    font-size: 14px;

    font-weight: bold;
}

.form-group input,
.form-group select {
    width: 100%;

    padding: 11px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    outline: none;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #2563eb;
}

.save-button {
    margin-top: 20px;

    background: #16a34a;

    color: white;

    border: none;

    padding: 12px 25px;

    border-radius: 7px;

    cursor: pointer;

    font-weight: bold;
}

.save-button:hover {
    background: #15803d;
}


/* TABLE */

.table-box {
    background: white;

    padding: 25px;

    border-radius: 15px;

    box-shadow:
        0 5px 15px rgba(0,0,0,0.08);

    overflow-x: auto;
}

table {
    width: 100%;

    border-collapse: collapse;
}

th {
    background: #172554;

    color: white;

    padding: 14px;

    text-align: left;
}

td {
    padding: 14px;

    border-bottom:
        1px solid #e2e8f0;
}

tr:hover {
    background: #f8fafc;
}


/* PRIORITY */

.priority {
    display: inline-block;

    padding: 6px 12px;

    border-radius: 15px;

    font-size: 12px;

    font-weight: bold;
}

.high {
    background: #fee2e2;
    color: #991b1b;
}

.medium {
    background: #fef3c7;
    color: #92400e;
}

.low {
    background: #dcfce7;
    color: #166534;
}


/* DELETE */

.delete-button {
    background: #dc2626;

    color: white;

    text-decoration: none;

    padding: 7px 12px;

    border-radius: 6px;

    font-size: 12px;

    font-weight: bold;
}

.delete-button:hover {
    background: #b91c1c;
}


/* RESPONSIVE */

@media(max-width: 900px) {

    .sidebar {
        width: 200px;
    }

    .main {
        margin-left: 200px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }
}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <div class="logo">

        <div class="logo-icon">
            💻
        </div>

        <h2>IT Resource</h2>

        <p>ALLOCATION SYSTEM</p>

    </div>


    <ul class="menu">

        <li>
            <a href="dashboard.php">
                🏠 &nbsp; Dashboard
            </a>
        </li>

        <li>
            <a href="resources.php">
                👨‍💻 &nbsp; Resources
            </a>
        </li>

        <li>
            <a href="skills.php">
                🛠️ &nbsp; Skills
            </a>
        </li>

        <li>
            <a href="projects.php"
               class="active">
                📁 &nbsp; Projects
            </a>
        </li>

        <li>
            <a href="allocations.php">
                📊 &nbsp; Allocations
            </a>
        </li>

        <li>
            <a href="availability.php">
                📅 &nbsp; Availability
            </a>
        </li>

        <li>
            <a href="reports.php">
                📈 &nbsp; Reports
            </a>
        </li>

        <li>
            <a href="ai_matching.php">
                🤖 &nbsp; AI Matching
            </a>
        </li>

        <li>
            <a href="logout.php">
                🚪 &nbsp; Logout
            </a>
        </li>

    </ul>

</div>


<!-- =========================
     MAIN
========================= -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <h1>Project Management</h1>

        <div class="admin">
            👤 Admin
        </div>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <!-- PAGE HEADER -->

        <div class="page-header">

            <h2>
                📁 Projects
            </h2>

            <button
                class="add-button"
                onclick="showProjectForm()">

                + Add Project

            </button>

        </div>


        <!-- MESSAGE -->

        <?php

        if (isset($message)) {

            echo "<div class='message'>";
            echo htmlspecialchars($message);
            echo "</div>";

        }

        ?>


        <!-- ADD PROJECT FORM -->

        <div
            class="form-box"
            id="projectForm">

            <h3>
                ➕ Add New Project
            </h3>


            <form method="POST">


                <div class="form-grid">


                    <div class="form-group">

                        <label>
                            Project Name
                        </label>

                        <input
                            type="text"
                            name="project_name"
                            placeholder="Enter project name"
                            required>

                    </div>


                    <div class="form-group">

                        <label>
                            Client Name
                        </label>

                        <input
                            type="text"
                            name="client_name"
                            placeholder="Enter client name"
                            required>

                    </div>


                    <div class="form-group">

                        <label>
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            required>

                    </div>


                    <div class="form-group">

                        <label>
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            required>

                    </div>


                    <div class="form-group">

                        <label>
                            Priority
                        </label>

                        <select name="priority"
                                required>

                            <option value="">
                                Select Priority
                            </option>

                            <option value="High">
                                High
                            </option>

                            <option value="Medium">
                                Medium
                            </option>

                            <option value="Low">
                                Low
                            </option>

                        </select>

                    </div>


                </div>


                <button
                    type="submit"
                    name="add_project"
                    class="save-button">

                    Save Project

                </button>

            </form>

        </div>


        <!-- PROJECT TABLE -->

        <div class="table-box">

            <table>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Project Name
                    </th>

                    <th>
                        Client
                    </th>

                    <th>
                        Start Date
                    </th>

                    <th>
                        End Date
                    </th>

                    <th>
                        Priority
                    </th>

                    <th>
                        Action
                    </th>

                </tr>


                <?php

                if (mysqli_num_rows($result) > 0) {

                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {

                ?>

                <tr>

                    <td>
                        <?php
                        echo $row['project_id'];
                        ?>
                    </td>


                    <td>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $row['project_name']
                            );
                            ?>
                        </strong>

                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['client_name']
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo $row['start_date'];
                        ?>
                    </td>


                    <td>
                        <?php
                        echo $row['end_date'];
                        ?>
                    </td>


                    <td>

                        <?php

                        $priority =
                            strtolower(
                                $row['priority']
                            );

                        ?>

                        <span
                            class="priority
                            <?php
                            echo $priority;
                            ?>">

                            <?php
                            echo htmlspecialchars(
                                $row['priority']
                            );
                            ?>

                        </span>

                    </td>


                    <td>

                        <a
                            href="projects.php?delete=<?php
                            echo $row['project_id'];
                            ?>"
                            class="delete-button"
                            onclick="
                            return confirm(
                            'Delete this project?'
                            );">

                            Delete

                        </a>

                    </td>

                </tr>

                <?php

                    }

                } else {

                ?>

                <tr>

                    <td
                        colspan="7"
                        style="text-align:center;">

                        No projects found.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>


    </div>

</div>


<script>

function showProjectForm() {

    const form =
        document.getElementById("projectForm");

    if (form.style.display === "block") {

        form.style.display = "none";

    } else {

        form.style.display = "block";

    }

}

</script>


</body>

</html>