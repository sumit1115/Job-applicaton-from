<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// DATABASE CONNECTION
$servername = "localhost";
$username = "sm";
$password = "123";
$dbname = "jobapplications";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// RETRIEVING FORM DATA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect value of input field
    $name = isset($_POST['mname']) ? $_POST['mname'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $experience = isset($_POST['experience']) ? $_POST['experience'] : '';
    $degree = isset($_POST['degree']) ? $_POST['degree'] : '';
    $jobposition = isset($_POST['jobposition']) ? $_POST['jobposition'] : '';
    $summary = isset($_POST['summary']) ? $_POST['summary'] : '';
    
    // Checkbox values
    $html = isset($_POST['html']) ? 'HTML' : '';
    $css = isset($_POST['css']) ? 'CSS' : '';
    $javascript = isset($_POST['javascript']) ? 'JavaScript' : '';
    $angular = isset($_POST['angular']) ? 'Angular' : '';
    $bootstrap = isset($_POST['bootstrap']) ? 'Bootstrap' : '';
    $reactJS = isset($_POST['reactJS']) ? 'ReactJS' : '';

    // Construct the skills string
    $skills = [];
    if ($html) {
        $skills[] = 'HTML';
    }
    if ($css) {
        $skills[] = 'CSS';
    }
    if ($javascript) {
        $skills[] = 'JavaScript';
    }
    if ($angular) {
        $skills[] = 'Angular';
    }
    if ($bootstrap) {
        $skills[] = 'Bootstrap';
    }
    if ($reactJS) {
        $skills[] = 'ReactJS';
    }
    $skillsString = implode(', ', $skills);

    // Insert applicant data
    $sqlquery = "INSERT INTO applicants (name, phone, city, email, gender, experience, degree, jobposition, skills, summary) 
    VALUES ('$name', '$phone', '$city', '$email', '$gender', '$experience', '$degree', '$jobposition', '$skillsString', '$summary')";
    
    if ($conn->query($sqlquery) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }

    // Send email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sumitmane607@gmail.com'; // Update with your email address
        $mail->Password   = 'gbqeoxhaxumnxwmj'; // Update with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('sumitmane607@gmail.com'); // Update with your email address
        $mail->addAddress('sumitmane361@gmail.com'); // Update with recipient email address

        // Attachments
       

        // Validate and sanitize form input data
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $city = isset($_POST['city']) ? $_POST['city'] : '';
        $jobPosition = isset($_POST['jobPosition']) ? $_POST['jobPosition'] : '';
        $skills = isset($_POST['skills']) ? $_POST['skills'] :'';

        // Construct the message
        $message = "
            <p><strong>Name:</strong> $name</p>
            <p><strong>Phone Number:</strong> $phone</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>City:</strong> $city</p>
            <p><strong>Gender:</strong> $gender</p>
            <p><strong>Experience:</strong> $experience</p>
            <p><strong>Degree:</strong> $degree</p>
            <p><strong>jobposition:</strong> $jobposition</p>
            <p><strong>Summary:</strong> $summary</p>
            <p><strong>Skills:</strong> $skillsString</p>";

        $mail->isHTML(true);
        $mail->Subject = 'Job Application from ' . $name;
        $mail->Body    = $message;

        $mail->send();

        echo 'Your application has been sent successfully. We will review your application and update you shortly!';
    } catch (Exception $e) {
        echo "Message could not be sent. Error: {$mail->ErrorInfo}";
    }
}

$conn->close();
?>
