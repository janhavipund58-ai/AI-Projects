<?php
session_start();
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username == "" || $password == "") {
        $message = "Please enter username and password.";
    } else {

        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {

                $_SESSION["username"] = $username;

                header("Location: dashboard.php");
                exit();

            } else {
                $message = "Invalid username or password.";
            }

            mysqli_stmt_close($stmt);

        } else {
            $message = "Database query error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>IT Project Resource Allocation System - Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            width: 350px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            text-align: center;
        }

        h2 {
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>IT Project Resource Allocation System</h2>

    <div class="subtitle">Login to continue</div>

    <?php if ($message != "") { ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST" action="">

        <input
            type="text"
            name="username"
            placeholder="Enter Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Enter Password"
            required
        >

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>