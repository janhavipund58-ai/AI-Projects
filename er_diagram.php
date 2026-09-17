<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ER Diagram - IT Project Resource Allocation System</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .header {
            background: #1e293b;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
        }

        .header p {
            margin-top: 8px;
            font-size: 17px;
        }

        .container {
            padding: 30px;
            text-align: center;
        }

        .diagram-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            max-width: 1200px;
            margin: auto;
        }

        .diagram-card h2 {
            color: #1e293b;
            margin-bottom: 25px;
        }

        .diagram-image {
            width: 100%;
            max-width: 1100px;
            height: auto;
            display: block;
            margin: auto;
            border: 2px solid #ddd;
            border-radius: 10px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 13px 28px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .back-btn:hover {
            background: #1d4ed8;
        }

    </style>

</head>

<body>

    <div class="header">

        <h1>IT Project Resource Allocation System</h1>

        <p>Entity Relationship Diagram</p>

    </div>


    <div class="container">

        <div class="diagram-card">

            <h2>🗂️ ER Diagram</h2>

            <img
                src="./er_diagram.png"
                alt="IT Resource Allocation ER Diagram"
                class="diagram-image"
            >

        </div>


        <a href="dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

    </div>

</body>

</html>