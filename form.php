<?php
// Database configuration
$host = 'localhost';
$db   = 'MARKS_MANAGEMENT';
$user = 'root'; 
$pass = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize variables to store form data
$regno = $ass = $exam = $class = '';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $regno = $_POST['regno'];
    $ass = $_POST['ass'];
    $exam = $_POST['exam'];
    $class = $_POST['class'];

    // Save the marks to the database
    try {
        $stmt = $pdo->prepare("INSERT INTO marks (regno, ass, exam, class) VALUES (?, ?, ?, ?)");
        $stmt->execute([$regno, $ass, $exam, $class]);

        // Clear the input values
        $regno = $ass = $exam = $class = '';
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

// Retrieve student data from the database
try {
    $stmt = $pdo->query("SELECT * FROM marks");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Management System - Enter Marks</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        form>div {
            width: 30%;
            display: flex;
            justify-content: space-between;
            margin: .3rem;
        }

        form>div>label {
            font-size: 18px;
            font-weight: 600;
            width: 50%;
        }
    </style>
</head>

<body>
    <h1>Enter the marks of student</h1>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div>
            <label for="regno">RegNo</label>
            <input type="text" name="regno" id="regno" value="<?php echo $regno; ?>" required><br>
        </div>

        <div><label for="ass">CAT</label>
            <input type="number" name="ass" id="ass" value="<?php echo $ass; ?>" required><br>
        </div>

        <div>
            <label for="exam">Exam</label>
            <input type="number" name="exam" id="exam" value="<?php echo $exam; ?>" required><br>
        </div>

        <div>
            <label for="class">Class</label>
            <input type="text" name="class" id="class" value="<?php echo $class; ?>" required><br>
        </div>

        <button type="submit" name="submit">Save Marks</button>
    </form>

    <?php if (!empty($students)) : ?>
        <h2>The entered student marks are:</h2>

        <table>
            <thead>
                <tr>
                    <th>Registration Number</th>
                    <th>Assignment Marks</th>
                    <th>Exam Marks</th>
                    <th>Class</th>
                    <th>Total Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) : ?>
                    <tr>
                        <td><?php echo $student['regno']; ?></td>
                        <td><?php echo $student['ass']; ?></td>
                        <td><?php echo $student['exam']; ?></td>
                        <td><?php echo $student['class']; ?></td>
                        <td><?php echo $student['ass'] + $student['exam']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        // Clear input values after form submission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>