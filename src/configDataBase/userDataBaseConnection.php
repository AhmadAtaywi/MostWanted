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
class ClearData{
    public function cleanInput($data)
    {
        $data = trim($data); // Remove whitespace from the beginning and end of a string
        $data = stripslashes($data); // Remove backslashes from a string
        $data = strip_tags($data); // Remove HTML and PHP tags from a string
        return $data;
    }
}
?>