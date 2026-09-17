<?php

include "db.php";

/* =========================
   ADD SKILL
========================= */

if (isset($_POST['add_skill'])) {

    $skill_name = trim($_POST['skill_name']);

    if ($skill_name != "") {

        $skill_name = mysqli_real_escape_string($conn, $skill_name);

        $check = mysqli_query(
            $conn,
            "SELECT skill_id FROM skills
             WHERE skill_name = '$skill_name'"
        );

        if (mysqli_num_rows($check) > 0) {

            $message = "Skill already exists!";

        } else {

            $sql = "INSERT INTO skills (skill_name)
                    VALUES ('$skill_name')";

            if (mysqli_query($conn, $sql)) {

                header("Location: skills.php");
                exit();

            } else {

                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}


/* =========================
   DELETE SKILL
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $sql = "DELETE FROM skills
            WHERE skill_id = $id";

    if (mysqli_query($conn, $sql)) {

        header("Location: skills.php");
        exit();

    } else {

        $message = "Cannot delete this skill because it is being used.";
    }
}


/* =========================
   GET SKILLS
========================= */

$result = mysqli_query(
    $conn,
    "SELECT * FROM skills
     ORDER BY skill_id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Skills | IT Resource Allocation</title>


<style>

/* =========================
   GENERAL
========================= */

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


/* =========================
   SIDEBAR
========================= */

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

    margin-bottom: 8px;
}

.logo h2 {

    font-size: 21px;
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


/* =========================
   MAIN
========================= */

.main {

    margin-left: 250px;

    min-height: 100vh;
}


/* =========================
   TOP BAR
========================= */

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

    color: #172554;
}

.admin {

    background: #e0e7ff;

    color: #1e3a8a;

    padding: 10px 18px;

    border-radius: 20px;

    font-weight: bold;
}


/* =========================
   CONTENT
========================= */

.content {

    padding: 30px;
}


/* =========================
   PAGE HEADER
========================= */

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


/* =========================
   MESSAGE
========================= */

.message {

    background: #fee2e2;

    color: #991b1b;

    padding: 12px 15px;

    border-radius: 8px;

    margin-bottom: 20px;

    font-weight: bold;
}


/* =========================
   FORM
========================= */

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

    margin-bottom: 18px;

    color: #172554;
}

.form-box input {

    width: 100%;

    padding: 12px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    outline: none;

    font-size: 14px;
}

.form-box input:focus {

    border-color: #2563eb;
}

.save-button {

    margin-top: 15px;

    background: #16a34a;

    color: white;

    border: none;

    padding: 12px 22px;

    border-radius: 7px;

    cursor: pointer;

    font-weight: bold;
}

.save-button:hover {

    background: #15803d;
}


/* =========================
   TABLE
========================= */

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


/* =========================
   SKILL BADGE
========================= */

.skill-badge {

    display: inline-block;

    background: #dbeafe;

    color: #1e40af;

    padding: 7px 14px;

    border-radius: 20px;

    font-weight: bold;

    font-size: 13px;
}


/* =========================
   DELETE BUTTON
========================= */

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


/* =========================
   RESPONSIVE
========================= */

@media(max-width: 800px) {

    .sidebar {

        width: 200px;
    }

    .main {

        margin-left: 200px;
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
            <a href="skills.php"
               class="active">
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



<!-- =========================
     MAIN
========================= -->

<div class="main">


    <!-- TOP BAR -->

    <div class="topbar">

        <h1>Skills Management</h1>

        <div class="admin">
            👤 Admin
        </div>

    </div>



    <!-- CONTENT -->

    <div class="content">


        <!-- PAGE HEADER -->

        <div class="page-header">

            <h2>
                🛠️ Skills
            </h2>

            <button
                class="add-button"
                onclick="showSkillForm()">

                + Add Skill

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



        <!-- ADD SKILL FORM -->

        <div
            class="form-box"
            id="skillForm">

            <h3>
                ➕ Add New Skill
            </h3>


            <form method="POST">

                <input
                    type="text"
                    name="skill_name"
                    placeholder="Enter skill name e.g. Python"
                    required>


                <button
                    type="submit"
                    name="add_skill"
                    class="save-button">

                    Save Skill

                </button>

            </form>

        </div>



        <!-- SKILL TABLE -->

        <div class="table-box">

            <table>

                <tr>

                    <th>
                        Skill ID
                    </th>

                    <th>
                        Skill Name
                    </th>

                    <th>
                        Action
                    </th>

                </tr>


                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row =
                        mysqli_fetch_assoc($result)) {

                ?>

                <tr>

                    <td>
                        <?php
                        echo $row['skill_id'];
                        ?>
                    </td>

                    <td>

                        <span class="skill-badge">

                            <?php
                            echo htmlspecialchars(
                                $row['skill_name']
                            );
                            ?>

                        </span>

                    </td>

                    <td>

                        <a
                            href="skills.php?delete=<?php
                            echo $row['skill_id'];
                            ?>"
                            class="delete-button"
                            onclick="
                            return confirm(
                            'Delete this skill?'
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
                        colspan="3"
                        style="text-align:center;">

                        No skills found.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>


    </div>

</div>



<!-- =========================
     JAVASCRIPT
========================= -->

<script>

function showSkillForm() {

    let form =
        document.getElementById("skillForm");

    if (form.style.display === "block") {

        form.style.display = "none";

    } else {

        form.style.display = "block";

    }

}

</script>


</body>

</html>