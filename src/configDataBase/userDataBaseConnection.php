<?php
$servername = "localhost";
$username = "AmirSamer-MostWanted";
$password = "MostWanted_2025";
$dbname = "MostWanted";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>

<?php
class ClearData
{
  public function cleanInput($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    return $data;
  }
}
?>