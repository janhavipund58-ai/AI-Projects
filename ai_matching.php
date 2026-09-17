<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $project_id = intval($_POST["project_id"]);

    $result = "AI Resource Matching is available. Project ID: " . $project_id;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Resource Matching</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f5f9;
            padding: 40px;
        }

        .box {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        h1 {
            text-align: center;
        }

        select, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            font-size: 16px;
        }

        button {
            background: #198754;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        pre {
            background: #f4f4f4;
            padding: 20px;
            margin-top: 20px;
            white-space: pre-wrap;
        }
    </style>
</head>

<body>

<div class="box">

    <h1>🤖 AI Resource Matching</h1>

    <form method="POST">

        <label>Select Project:</label>

        <select name="project_id" required>

            <option value="">-- Select Project --</option>
            <option value="1">AI Chatbot</option>
            <option value="2">E-Commerce Website</option>
            <option value="3">Cloud Migration</option>

        </select>

        <button type="submit">
            Find Best Resource
        </button>

    </form>

    <?php
    if (isset($result)) {
        echo "<h2>AI Recommendation</h2>";
        echo "<pre>";
        echo htmlspecialchars($result);
        echo "</pre>";
    }
    ?>

</div>

</body>
</html>