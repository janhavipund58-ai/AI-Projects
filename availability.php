<?php
include "db.php";

$message = "";

/* ADD AVAILABILITY */
if (isset($_POST['add_availability'])) {

    $resource_id = intval($_POST['resource_id']);
    $available_from = $_POST['available_from'];
    $available_to = $_POST['available_to'];
    $allocation_percentage = intval($_POST['allocation_percentage']);

    if ($available_to < $available_from) {

        $message = "Available To date cannot be before Available From date.";

    } else {

        $sql = "INSERT INTO availability
                (resource_id, available_from, available_to, allocation_percentage)
                VALUES
                ($resource_id, '$available_from', '$available_to',
                 $allocation_percentage)";

        if (mysqli_query($conn, $sql)) {
            $message = "Availability added successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}


/* DELETE AVAILABILITY */
if (isset($_GET['delete'])) {

    $availability_id = intval($_GET['delete']);

    mysqli_query(
        $conn,
        "DELETE FROM availability
         WHERE availability_id = $availability_id"
    );

    $message = "Availability deleted successfully!";
}


/* GET RESOURCES */
$resources = mysqli_query(
    $conn,
    "SELECT resource_id, resource_name
     FROM resources
     ORDER BY resource_name"
);


/* GET AVAILABILITY */
$availability = mysqli_query(
    $conn,
    "SELECT
        a.availability_id,
        r.resource_name,
        a.available_from,
        a.available_to,
        a.allocation_percentage
     FROM availability a
     INNER JOIN resources r
        ON a.resource_id = r.resource_id
     ORDER BY a.availability_id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Resource Availability</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.header {
    background: #1e3a5f;
    color: white;
    padding: 20px 30px;
    font-size: 27px;
    font-weight: bold;
}

.container {
    width: 92%;
    margin: 30px auto;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.10);
}

h2 {
    color: #1e3a5f;
    margin-top: 0;
}

.message {
    background: #d4edda;
    color: #155724;
    padding: 13px;
    border-radius: 7px;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
}

select,
input {
    width: 100%;
    padding: 12px;
    margin-top: 7px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
}

button {
    background: #1e3a5f;
    color: white;
    padding: 12px 22px;
    border: none;
    border-radius: 7px;
    font-size: 15px;
    cursor: pointer;
}

button:hover {
    background: #142942;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #1e3a5f;
    color: white;
    padding: 13px;
}

td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.delete {
    color: #dc3545;
    text-decoration: none;
    font-weight: bold;
}

.delete:hover {
    text-decoration: underline;
}

.back {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    color: #1e3a5f;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="header">
    📅 Resource Availability
</div>

<div class="container">

<?php if ($message != "") { ?>

<div class="message">
    <?php echo htmlspecialchars($message); ?>
</div>

<?php } ?>


<!-- ADD AVAILABILITY -->

<div class="card">

<h2>➕ Add Resource Availability</h2>

<form method="POST">

<label>Select Resource</label>

<select name="resource_id" required>

<option value="">
    -- Select Resource --
</option>

<?php

while ($row = mysqli_fetch_assoc($resources)) {

?>

<option value="<?php echo $row['resource_id']; ?>">

<?php
echo htmlspecialchars($row['resource_name']);
?>

</option>

<?php } ?>

</select>


<label>Available From</label>

<input
    type="date"
    name="available_from"
    required
>


<label>Available To</label>

<input
    type="date"
    name="available_to"
    required
>


<label>Current Allocation Percentage</label>

<input
    type="number"
    name="allocation_percentage"
    min="0"
    max="100"
    value="0"
    required
>


<button type="submit" name="add_availability">

    ➕ Add Availability

</button>

</form>

</div>


<!-- AVAILABILITY TABLE -->

<div class="card">

<h2>📋 Availability Details</h2>

<div class="table-container">

<table>

<tr>
    <th>ID</th>
    <th>Resource</th>
    <th>Available From</th>
    <th>Available To</th>
    <th>Allocation %</th>
    <th>Action</th>
</tr>

<?php

if (mysqli_num_rows($availability) > 0) {

while ($row = mysqli_fetch_assoc($availability)) {

?>

<tr>

<td>
    <?php echo $row['availability_id']; ?>
</td>

<td>
    <?php echo htmlspecialchars($row['resource_name']); ?>
</td>

<td>
    <?php echo $row['available_from']; ?>
</td>

<td>
    <?php echo $row['available_to']; ?>
</td>

<td>
    <?php echo $row['allocation_percentage']; ?>%
</td>

<td>

<a
    class="delete"
    href="availability.php?delete=<?php echo $row['availability_id']; ?>"
    onclick="return confirm('Delete this availability record?');"
>
    🗑 Delete
</a>

</td>

</tr>

<?php

}

} else {

?>

<tr>

<td colspan="6">
    No availability records found.
</td>

</tr>

<?php } ?>

</table>

</div>

<a href="dashboard.php" class="back">
    ← Back to Dashboard
</a>

</div>

</div>

</body>

</html>