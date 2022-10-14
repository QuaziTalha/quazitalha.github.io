<?php
require 'phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host='smtp.gmail.com';
$mail->Port='587';
$mail->SMTPAuth=true;
$mail->SMTPSecure='tls';

$mail->Username='accforswiftmailer@gmail.com';
$mail->Password='qtkrapnlygqwglkl';

$servername = "sg2nlmysql3plsk.secureserver.net:3306";
$username = "newsletteradmin";
$password = "Ghxc81*7";
$dbname = "newsletter_emails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
 }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["name"])) {
      $response = ['success' => false, 'message' => 'Name Is Required'];
      header('Content-type: application/json');
      echo json_encode( $response );
      return false;
   } else {
     $name = test_input($_POST["name"]);
   }
   if (empty($_POST["email"])) {
      $response = ['success' => false, 'message' => 'Email Is Required'];
      header('Content-type: application/json');
      echo json_encode( $response );
      return false;
   } else {
     $email = test_input($_POST["email"]);
   }

   $emailcheck2 = "SELECT email FROM email WHERE email='$email' AND source='3srefrigrationsolution.in'";
   $result2 = $conn->query($emailcheck2);

   if ($result2->num_rows > 0) {
      $response = ['success' => false, 'message' => 'This Email is Already Registered'];
      header('Content-type: application/json');
      echo json_encode( $response );
      return false;
   }
   $emailcheck = "SELECT email FROM email WHERE email='$email';";
   $result = $conn->query($emailcheck);

   if ($result->num_rows == 0) {
      $sql = "INSERT INTO `email` (`name`, `email`, `source`) VALUES ('$name', '$email', '3srefrigrationsolution.in')";
      if ($conn->query($sql) === TRUE) {
         // echo "<script>console.log('New record created successfully')</script>";
        } else {
         $response = ['success' => false, 'message' => 'A Connection Error Occured'];
           header('Content-type: application/json');
           echo json_encode( $response );
        }
   }
      /* Set the mail sender. */
      $mail->setFrom('Ahmed2horizon@gmail.com', 'Tahzeeb Abaya');

      /* Add a recipient. */
      $mail->addAddress('Ahmed2horizon@gmail.com', 'Tahzeeb Abaya');

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Newsletter Subscription Notification';
      $mail->Body    = "<h1>A User by the name of ".$name." has Subscribed to recieve a Newsletter when our Website launches</h1><p>There Email id is ".$email.".</p>";
      $mail->AltBody = "A User by the name of ".$name." has Subscribed to recieve a Newsletter when our Website launchesThere Email id is ".$email;

      /* Finally send the mail. */
      $mail->send();
      if(!$mail->send()){
         $response = ['success' => false, 'message' => 'An Error Occured'];
         header('Content-type: application/json');
         echo json_encode( $response );
      }
      else{
         $response = ['success' => true, 'message' => 'Mail Sent Successfully'];
         header('Content-type: application/json');
         echo json_encode( $response );
      }
   }