<?php
session_start();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check credentials
  if ($username === 'admin-san' && $password === 'FeetSkyClamp') {
    $_SESSION['admin_logged_in'] = true;
    header('Location: admin.php');
    exit;
  } else {
    $error = "Invalid username or password";
  }
}

// Handle logout
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: admin.php');
  exit;
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - HiGA Tutor Portal</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <h1 class="title">Admin Login - <span>HiGA Tutor Portal</span></h1>
    <?php if (isset($error)): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <div class="body">
      <form id="admin-login-form" method="POST" action="admin.php">
        <input type="text" id="username" name="username" placeholder="Username" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <input id="login-btn" type="submit" value="Login">
      </form>
    </div>
    <p class="go-back"><a href="./index.php">Go Back</a></p>
  </body>

  </html>
<?php
  exit;
}

// Fetch all users
require('db.php');
$query = "SELECT id, sex, name, email, subjects, preferred_language, university_choice, 'tutor' as user_type FROM tutors UNION SELECT id, sex, name, email, subjects, preferred_language, university_choice, 'student' as user_type FROM students";
$result = $conn->query($query);

if ($result === false) {
  die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
  $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
  $users = [];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - HiGA Tutor Portal</title>
  <link rel="stylesheet" href="style.css">
</head>

<body id="admin">
  <h1 class="title">Admin Panel - <span>HiGA Tutor Portal</span></h1>
  <div class="body">
    <div class="user">
      <h2>All Users</h2>
      <table>
        <thead>
          <tr>
            <th>User Type</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Subjects</th>
            <th>Preferred Language</th>
            <th>University Choice</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?php echo htmlspecialchars($user['user_type']); ?></td>
              <td><?php echo htmlspecialchars($user['name']); ?></td>
              <td><?php echo htmlspecialchars($user['sex']); ?></td>
              <td><?php echo htmlspecialchars($user['subjects']); ?></td>
              <td><?php echo htmlspecialchars($user['preferred_language']); ?></td>
              <td><?php echo htmlspecialchars($user['university_choice']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
    // Fetch all tutors
    $tutorQuery = "SELECT * FROM tutors";
    $tutorResult = $conn->query($tutorQuery);
    $tutors = $tutorResult->num_rows > 0 ? $tutorResult->fetch_all(MYSQLI_ASSOC) : [];
    // Fetch all students
    $studentQuery = "SELECT * FROM students";
    $studentResult = $conn->query($studentQuery);
    $students = $studentResult->num_rows > 0 ? $studentResult->fetch_all(MYSQLI_ASSOC) : [];
    // Find matching pairs
    $matchingPairs = [];
     foreach ($tutors as $tutor) {
    $tutorSubjects = array_map('trim', explode(',', $tutor['subjects']));

    // For tutors with HL subjects, add corresponding SL subjects
    foreach ($tutorSubjects as $subject) {
      if (substr($subject, -3) === '-hl') {
        $slSubject = substr($subject, 0, -3) . '-sl';
        if (!in_array($slSubject, $tutorSubjects)) {
          $tutorSubjects[] = $slSubject;
        }
      }
    }

    foreach ($students as $student) {
      $studentSubjects = array_map('trim', explode(',', $student['subjects']));
      $commonSubjects = array_intersect($tutorSubjects, $studentSubjects);

      // Check for language compatibility
      $languageMatch = ($tutor['preferred_language'] === 'both' || $student['preferred_language'] === 'both') ||
        ($tutor['preferred_language'] === $student['preferred_language']);

      // Check for university compatibility
      $universityMatch = ($tutor['university_choice'] === 'both' || $student['university_choice'] === 'both') ||
        ($tutor['university_choice'] === $student['university_choice']);

      if (
        !empty($commonSubjects) &&
        $languageMatch &&
        $universityMatch
      ) {
          $matchingPairs[] = [
            'tutor_name' => $tutor['name'] . "\n(" . ucfirst($tutor['sex']) . ")",
            'student_name' => $student['name'] . "\n(" . ucfirst($student['sex']) . ")",
            'student_grade' => $student['grade'],
            'common_subjects' => implode(', ', $commonSubjects),
            'preferred_language' => $tutor['preferred_language'] === 'both' ? $student['preferred_language'] : $tutor['preferred_language'],
            'university_choice' => $tutor['university_choice'] === 'both' ? $student['university_choice'] : $tutor['university_choice']
          ];
        }
      }
    }
    ?>
    <div class="match">
      <h2>Matching Tutor-Student Pairs</h2>
      <table>
        <thead>
          <tr>
            <th>Tutor Name</th>
            <th>Student Name</th>
            <th>Student Grade</th>
            <th>Teachable Subjects</th>
            <th>Language</th>
            <th>University Choice</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($matchingPairs)): ?>
            <?php foreach ($matchingPairs as $pair): ?>
              <tr>
                <td><?php echo htmlspecialchars($pair['tutor_name']); ?></td>
                <td><?php echo htmlspecialchars($pair['student_name']); ?></td>
                <td><?php echo htmlspecialchars($pair['student_grade']); ?></td>
                <td><?php echo htmlspecialchars($pair['common_subjects']); ?></td>
                <td><?php echo htmlspecialchars($pair['preferred_language']); ?></td>
                <td><?php echo htmlspecialchars($pair['university_choice']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7">No matching pairs found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <p class="go-back"><a href="./login.php">Go Back</a></p>
</body>

</html>