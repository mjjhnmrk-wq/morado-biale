<?php
$show = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$result = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($show == "age") {
        // Get complete birthdate
        $birthdate = $_POST["birthdate"];
        
        // Calculate age from full date
        $birth = new DateTime($birthdate);
        $today = new DateTime();
        $age = $birth->diff($today);
        
        $result = "Your age is <strong>" . $age->y . " years, " . $age->m . " months, " . $age->d . " days</strong><br>
                   Born on: " . $birth->format('F j, Y') . " (" . $birth->format('l') . ")<br>
                   Total days lived: " . $age->days;
        
    } elseif ($show == "grade") {
        $prelim = $_POST["prelim"];
        $midterm = $_POST["midterm"];
        $final = $_POST["final"];
        $avg = ($prelim + $midterm + $final) / 3;
        $status = ($avg >= 75) ? "Passed" : "Failed";
        $result = "Average Grade: <strong>" . round($avg, 2) . "</strong> ($status)";
        
    } elseif ($show == "bmi") {
        $weight = $_POST["weight"];
        $height = $_POST["height"] / 100;
        $bmi = $weight / ($height * $height);

        if ($bmi < 18.5) $category = "Underweight";
        elseif ($bmi < 24.9) $category = "Normal weight";
        elseif ($bmi < 29.9) $category = "Overweight";
        else $category = "Obese";

        $result = "BMI: <strong>" . round($bmi,2) . "</strong> ($category)";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .box {
            background: #667eea;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .box:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        
        .box a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            width: 100%;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        label {
            font-weight: bold;
            color: #555;
            margin-top: 10px;
        }
        
        input {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus {
            border-color: #667eea;
            outline: none;
        }
        
        button {
            background: #667eea;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            background: #764ba2;
        }
        
        .result {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        
        .result strong {
            color: #e74c3c;
        }
        
        a.back-link {
            display: inline-block;
            margin-top: 15px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 15px;
            border: 2px solid #667eea;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        a.back-link:hover {
            background: #667eea;
            color: white;
        }
        
        .date-input {
            position: relative;
        }
        
        .date-input::after {
            content: "📅";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            pointer-events: none;
        }
        
        .info-text {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .bmi-explanation {
            margin-top: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">

    <?php if ($show == "dashboard"): ?>
        <h2>📊 Student Calculator Dashboard</h2>
        <div class="box"><a href="?page=age">🎂 Age Calculator</a></div>
        <div class="box"><a href="?page=grade">📚 Course Grade Calculator</a></div>
        <div class="box"><a href="?page=bmi">⚖️ BMI Calculator</a></div>

    <?php elseif ($show == "age"): ?>
        <h2>🎂 Age Calculator</h2>
        <form method="post">
            <label>Birth Date (Complete Date)</label>
            <p class="info-text">Select your complete birthdate including day, month, and year</p>
            <div class="date-input">
                <input type="date" name="birthdate" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit">Calculate Age</button>
        </form>
        
        <?php if ($result): ?>
            <div class="result"><?= $result ?></div>
        <?php endif; ?>
        
        <a href="?page=dashboard" class="back-link">⬅ Back to Dashboard</a>

    <?php elseif ($show == "grade"): ?>
        <h2>📚 Course Grade Calculator</h2>
        <form method="post">
            <label>Prelim Grade (0-100)</label>
            <input type="number" name="prelim" min="0" max="100" step="0.1" required>
            
            <label>Midterm Grade (0-100)</label>
            <input type="number" name="midterm" min="0" max="100" step="0.1" required>
            
            <label>Final Exam Grade (0-100)</label>
            <input type="number" name="final" min="0" max="100" step="0.1" required>
            
            <button type="submit">Calculate Average</button>
        </form>
        
        <?php if ($result): ?>
            <div class="result"><?= $result ?></div>
        <?php endif; ?>
        
        <a href="?page=dashboard" class="back-link">⬅ Back to Dashboard</a>

    <?php elseif ($show == "bmi"): ?>
        <h2>⚖️ BMI Calculator</h2>
        <form method="post">
            <label>Weight (kg)</label>
            <input type="number" name="weight" min="1" max="300" step="0.1" required>
            
            <label>Height (cm)</label>
            <p class="info-text">Example: 175 cm for 5'9" height</p>
            <input type="number" name="height" min="50" max="250" step="0.1" required>
            
            <button type="submit">Calculate BMI</button>
        </form>
        
        <?php if ($result): ?>
            <div class="result"><?= $result ?></div>
        <?php endif; ?>
        
        <div class="bmi-explanation">
            <strong>BMI Categories:</strong><br>
            • Underweight: BMI less than 18.5<br>
            • Normal weight: BMI 18.5 to 24.9<br>
            • Overweight: BMI 25 to 29.9<br>
            • Obese: BMI 30 or greater
        </div>
        
        <a href="?page=dashboard" class="back-link">⬅ Back to Dashboard</a>
    <?php endif; ?>

</div>

</body>
</html>