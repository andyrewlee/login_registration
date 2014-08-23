<?php
session_start();
require_once("connection.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="container">
<?php
        $sql = "SELECT first_name, last_name, email, birthday FROM users WHERE id =" . $_GET["id"];
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id'])
        { ?>
            <div id="header">
                <h1>Welcome <?= $row["first_name"] ?>!</h1>
                <a href="process.php?logout=<?= $_SESSION["user_id"] ?>">Log Out</a>
            </div>
            <div id="main-content">
                <h2>Profile Information</h2>
                <p><?= $row["first_name"] ?></p>
                <p><?= $row["last_name"] ?></p>
                <p><?= $row["email"] ?></p>
                <p><?= date('M d, Y', strtotime($row["birthday"])) ?></p>
            </div>
<?php   } ?>
        </div>
    </body>
</html>
