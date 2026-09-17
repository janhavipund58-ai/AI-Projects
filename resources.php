<?php

include "db.php";

/* ADD RESOURCE */
if (isset($_POST['add_resource'])) {

    $name = $_POST['resource_name'];
    $email = $_POST['email'];
    $designation = $_POST['designation'];
    $experience = $_POST['experience'];
    $status = $_POST['availability_status'];

    $sql = "INSERT INTO resources
            (resource_name, email, designation, experience, availability_status)
            VALUES
            ('$name', '$email', '$designation', '$experience', '$status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: resources.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


/* DELETE RESOURCE */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query(
        $conn,
        "DELETE FROM resources WHERE resource_id = $id"
    );

    header("Location: resources.php");
    exit();
}


/* GET RESOURCES */
$result = mysqli_query(
    $conn,
    "SELECT * FROM resources
     ORDER BY resource_id DESC"
);

?>

<!DOCTYPE html>

<html>

<head>

<title>Resources - IT Resource Allocation</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
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

    padding: 25px 15px;

    color: white;
}

.logo {
    text-align: center;
    margin-bottom: 30px;
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
}


/* TOPBAR */

.topbar {
    height: 75px;

    background: white;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 30px;

    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;
}

.page-header h2 {
    font-size: 25px;
}

.add-btn {
    background: #2563eb;

    color: white;

    border: none;

    padding: 12px 20px;

    border-radius: 8px;

    cursor: pointer;

    font-weight: bold;
}


/* FORM */

.form-box {
    display: none;

    background: white;

    padding: 25px;

    border-radius: 15px;

    margin-bottom: 25px;

    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.form-box h3 {
    margin-bottom: 20px;
}

.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 15px;
}

.form-group label {
    display: block;

    margin-bottom: 6px;

    font-weight: bold;

    font-size: 14px;
}

.form-group input,
.form-group select {
    width: 100%;

    padding: 11px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;
}

.submit-btn {
    margin-top: 20px;

    background: #16a34a;

    color: white;

    border: none;

    padding: 12px 25px;

    border-radius: 7px;

    cursor: pointer;

    font-weight: bold;
}


/* TABLE */

.table-box {
    background: white;

    padding: 25px;

    border-radius: 15px;

    box-shadow: 0 5px 15px rgba(0,0,0,0.08);

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
    padding: 13px;

    border-bottom: 1px solid #e2e8f0;
}

tr:hover {
    background: #f8fafc;
}


/* STATUS */

.status {
    padding: 6px 12px;

    border-radius: 15px;

    font-size: 12px;

    font-weight: bold;
}

.available {
    background: #dcfce7;

    color: #166534;
}

.busy {
    background: #fee2e2;

    color: #991b1b;
}


/* DELETE */

.delete-btn {
    background: #dc2626;

    color: white;

    text-decoration: none;

    padding: 7px 12px;

    border-radius: 6px;

    font-size: 12px;
}

.delete-btn:hover {
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


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">

        <div style="font-size:40px;">💻</div>

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
            <a href="resources.php" class="active">
                👨‍💻 &nbsp; Resources
            </a>
        </li>

        <li>
            <a href="skills.php">
                🛠️ &nbsp; Skills
            </a>
        </li>

        <li>
            <a href="projects.php">
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



<!-- MAIN -->

<div class="main">


    <div class="topbar">

        <h1>Resource Management</h1>

        <div class="admin">
            👤 Admin
        </div>

    </div>



    <div class="content">


        <!-- HEADER -->

        <div class="page-header">

            <h2>👨‍💻 IT Resources</h2>

            <button
                class="add-btn"
                onclick="showForm()">

                + Add Resource

            </button>

        </div>



        <!-- ADD FORM -->

        <div
            class="form-box"
            id="resourceForm">

            <h3>➕ Add New Resource</h3>


            <form method="POST">


                <div class="form-grid">


                    <div class="form-group">

                        <label>Resource Name</label>

                        <input
                            type="text"
                            name="resource_name"
                            required>

                    </div>



                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email">

                    </div>



                    <div class="form-group">

                        <label>Designation</label>

                        <input
                            type="text"
                            name="designation"
                            placeholder="Software Developer"
                            required>

                    </div>



                    <div class="form-group">

                        <label>Experience (Years)</label>

                        <input
                            type="number"
                            step="0.1"
                            name="experience"
                            min="0"
                            required>

                    </div>



                    <div class="form-group">

                        <label>Availability Status</label>

                        <select name="availability_status">

                            <option value="Available">
                                Available
                            </option>

                            <option value="Busy">
                                Busy
                            </option>

                        </select>

                    </div>


                </div>


                <button
                    type="submit"
                    name="add_resource"
                    class="submit-btn">

                    Save Resource

                </button>


            </form>

        </div>



        <!-- TABLE -->

        <div class="table-box">

            <table>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Designation</th>

                    <th>Experience</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>


                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                <tr>

                    <td>
                        <?php echo $row['resource_id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['resource_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['designation']); ?>
                    </td>

                    <td>
                        <?php echo $row['experience']; ?> years
                    </td>

                    <td>

                        <?php if ($row['availability_status'] == 'Available') { ?>

                            <span class="status available">
                                Available
                            </span>

                        <?php } else { ?>

                            <span class="status busy">
                                Busy
                            </span>

                        <?php } ?>

                    </td>

                    <td>

                        <a
                            class="delete-btn"
                            href="resources.php?delete=<?php echo $row['resource_id']; ?>"
                            onclick="return confirm('Delete this resource?');">

                            Delete

                        </a>

                    </td>

                </tr>

                <?php

                    }

                } else {

                ?>

                <tr>

                    <td colspan="7" style="text-align:center;">

                        No resources found.

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

function showForm() {

    const form =
        document.getElementById("resourceForm");

    if (form.style.display === "block") {

        form.style.display = "none";

    } else {

        form.style.display = "block";

    }

}

</script>


</body>

</html>