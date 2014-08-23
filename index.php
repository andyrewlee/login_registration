<?php

session_start();
require_once("connection.php");

// $_SESSION = array();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Log In and Registration</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="container">

            <div id="form-wrapper">
<?php
            if(isset($_SESSION["errors"]))
            {
                foreach($_SESSION["errors"] as $value)
                {  ?>
                <p class="error"><?= $value ?></p>
<?php
                }
                unset($_SESSION["errors"]);
            }  ?>
                <form action="process.php" method="post">
                    <h2>Register Now</h2>

                    <p>
                        <label>First Name:</label>
                        <input type="text" name="first_name" placeholder="First name">
                    </p>

                    <p>
                        <label>Last Name:</label>
                        <input type="text" name="last_name" placeholder="Last name">
                    </p>

                    <p>
                        <label>Email:</label>
                        <input type="text" name="email" placeholder="Email">
                    </p>

                    <p>
                        <label>Birthday:</label>
                        <input type="date" name="birthday" placeholder="mm/dd/yyyy">
                    </p>

                    <p>
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="Password">
                    </p>

                    <p>
                        <label>Confirm Password:</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm password">
                    </p>

                    <input type="hidden" name="action" value="register">
                    <input type="submit" value="Register">

                </form>
                <form action="process.php" method="post">
                    <h2>Log in</h2>

                    <p>
                        <label>Email:</label>
                        <input type="text" name="email" placeholder="Email">
                    </p>

                    <p>
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="Password">
                    </p>

                    <input type="hidden" name="action" value="login">
                    <input type="submit" value="Log in">
                </form>

            </div>

        </div>
    </body>
</html>
