<?php
include "db.php";

$message = "";

/* =========================
   ADD ALLOCATION
   ========================= */

if (isset($_POST['add_allocation'])) {

    $resource_id = intval($_POST['resource_id']);
    $project_id = intval($_POST['project_id']);
    $allocation_percentage = intval($_POST['allocation_percentage']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO allocations
            (resource_id, project_id, allocation_percentage,
             start_date, end_date, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiisss",
        $resource_id,
        $project_id,
        $allocation_percentage,
        $start_date,
        $end_date,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {

        /* Make resource Busy for Active allocation */
        if ($status == "Active") {

            mysqli_query(
                $conn,
                "UPDATE resources
                 SET availability_status='Busy'
                 WHERE resource_id=$resource_id"
            );
        }

        $message = "Allocation added successfully!";

    } else {

        $message = "Error: " . mysqli_error($conn);
    }
}


/* =========================
   DELETE ALLOCATION
   ========================= */

if (isset($_GET['delete'])) {

    $allocation_id = intval($_GET['delete']);

    /* Find resource before deleting */
    $check = mysqli_query(
        $conn,
        "SELECT resource_id
         FROM allocations
         WHERE allocation_id=$allocation_id"
    );

    if ($check && mysqli_num_rows($check) > 0) {

        $allocation = mysqli_fetch_assoc($check);

        $resource_id = $allocation['resource_id'];

        mysqli_query(
            $conn,
            "DELETE FROM allocations
             WHERE allocation_id=$allocation_id"
        );

        /* Check whether resource still has an active allocation */
        $active = mysqli_query(
            $conn,
            "SELECT COUNT(*) AS total
             FROM allocations
             WHERE resource_id=$resource_id
             AND status='Active'"
        );

        $active_row = mysqli_fetch_assoc($active);

        if ($active_row['total'] == 0) {

            mysqli_query(
                $conn,
                "UPDATE resources
                 SET availability_status='Available'
                 WHERE resource_id=$resource_id"
            );
        }

        $message = "Allocation deleted successfully!";
    }
}


/* =========================
   GET RESOURCES
   ========================= */

$resources = mysqli_query(
    $conn,
    "SELECT resource_id, resource_name
     FROM resources
     ORDER BY resource_name"
);


/* =========================
   GET PROJECTS
   ========================= */

$projects = mysqli_query(
    $conn,
    "SELECT project_id, project_name
     FROM projects
     ORDER BY project_name"
);


/* =========================
   GET ALLOCATIONS
   ========================= */

$allocations = mysqli_query(
    $conn,
    "SELECT
        a.allocation_id,
        r.resource_name,
        p.project_name,
        a.allocation_percentage,
        a.start_date,
        a.end_date,
        a.status
     FROM allocations a
     INNER JOIN resources r
        ON a.resource_id = r.resource_id
     INNER JOIN projects p
        ON a.project_id = p.project_id
     ORDER BY a.allocation_id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Resource Allocation</title>

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
            color: white;
            padding: 25px 15px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 13px 15px;
            margin: 7px 0;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #374151;
        }

        .logout {
            background: #dc2626;
        }

        /* =========================
           MAIN
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
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
        }

        /* =========================
           MESSAGE
           ========================= */

        .message {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* =========================
           FORM
           ========================= */

        .form-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .form-box h2 {
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));

            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
        }

        .btn {
            margin-top: 20px;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            background: #1f2937;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #111827;
        }

        /* =========================
           TABLE
           ========================= */

        .table-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .table-box h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .delete {
            color: white;
            background: #dc2626;
            padding: 7px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        .delete:hover {
            background: #b91c1c;
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
        📊 Allocation
    </a>

    <a href="availability.php">
        📅 Availability
    </a>

    <a href="ai_matching.php">
        🤖 AI Matching
    </a>

    <a href="reports.php">
        📈 Reports
    </a>

    <a href="logout.php" class="logout">
        🚪 Logout
    </a>

</div>


<!-- =========================
     MAIN CONTENT
     ========================= -->

<div class="main">

    <div class="header">

        <h1>📊 Resource Allocation</h1>

        <p>
            Allocate IT resources to projects
        </p>

    </div>


    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>


    <!-- =========================
         ADD ALLOCATION FORM
         ========================= -->

    <div class="form-box">

        <h2>➕ Add New Allocation</h2>

        <form method="POST">

            <div class="form-grid">


                <div class="form-group">

                    <label>Resource</label>

                    <select name="resource_id" required>

                        <option value="">
                            Select Resource
                        </option>

                        <?php
                        while ($resource = mysqli_fetch_assoc($resources)) {
                        ?>

                            <option value="<?php echo $resource['resource_id']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $resource['resource_name']
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Project</label>

                    <select name="project_id" required>

                        <option value="">
                            Select Project
                        </option>

                        <?php
                        while ($project = mysqli_fetch_assoc($projects)) {
                        ?>

                            <option value="<?php echo $project['project_id']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $project['project_name']
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Allocation Percentage</label>

                    <input
                        type="number"
                        name="allocation_percentage"
                        min="1"
                        max="100"
                        placeholder="Example: 50"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Start Date</label>

                    <input
                        type="date"
                        name="start_date"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>End Date</label>

                    <input
                        type="date"
                        name="end_date"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Status</label>

                    <select name="status">

                        <option value="Active">
                            Active
                        </option>

                        <option value="Completed">
                            Completed
                        </option>

                    </select>

                </div>


            </div>


            <button
                type="submit"
                name="add_allocation"
                class="btn"
            >
                Add Allocation
            </button>

        </form>

    </div>


    <!-- =========================
         ALLOCATION TABLE
         ========================= -->

    <div class="table-box">

        <h2>📋 Allocation Records</h2>

        <table>

            <tr>

                <th>ID</th>

                <th>Resource</th>

                <th>Project</th>

                <th>Allocation %</th>

                <th>Start Date</th>

                <th>End Date</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php while ($row = mysqli_fetch_assoc($allocations)) { ?>

            <tr>

                <td>
                    <?php echo $row['allocation_id']; ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $row['resource_name']
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $row['project_name']
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo $row['allocation_percentage'];
                    ?>%
                </td>

                <td>
                    <?php echo $row['start_date']; ?>
                </td>

                <td>
                    <?php echo $row['end_date']; ?>
                </td>

                <td>
                    <?php echo $row['status']; ?>
                </td>

                <td>

                    <a
                        href="allocation.php?delete=<?php echo $row['allocation_id']; ?>"
                        class="delete"
                        onclick="return confirm('Are you sure you want to delete this allocation?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>