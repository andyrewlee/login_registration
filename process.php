<?php

session_start();
require_once("connection.php");

if(isset($_POST['action']) && $_POST['action'] == 'register')
{
    register_user($connection, $_POST);
}
elseif(isset($_POST['action']) && $_POST['action'] == 'login')
{
    login($connection, $_POST);
}
elseif(isset($_GET['logout']))
{
    logout();
}

function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}

function login($connection, $post)
{
    if(empty($post["email"]) || empty($post["password"]))
    {
        $_SESSION['errors'][] = "Email or Password cannot be blank";
    }
    else
    {
        $sql = "SELECT id, password FROM users WHERE email = '".$post['email']."'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        if(empty($row))
        {
            $_SESSION['errors'][] = "Could not find email in database, please register";
        }
        else
        {
            if(crypt($post["password"], $row["password"]) != $row['password'])
            {
                $_SESSION['errors'][] = "Incorrect password";
            }
            else
            {
                $_SESSION['user_id'] = $row['id'];
                header('Location: profile.php?id=' . $row['id']);
                exit;
            }
        }
    }
    header('Location: index.php');
    exit;
}

function register_user($connection, $post)
{
    $exploded_date = "";
    foreach($post as $key => $value)
    {
        if($key == "first_name")
        {
            if(empty($value))
            {
                $_SESSION["errors"][] = "Your first name cannot be blank";
            }
            if(preg_match("/[0-9]/", $value))
            {
                $_SESSION["errors"][] = "Your first name cannot contain numbers";
            }
        }
        if($key == "last_name")
        {
            if(empty($value))
            {
                $_SESSION["errors"][] = "Your last name cannot be blank";
            }
            if(preg_match("/[0-9]/", $value))
            {
                $_SESSION["errors"][] = "Your last name cannot contain numbers";
            }
        }
        if($key == "email")
        {
            if(empty($value))
            {
                $_SESSION["errors"][] = "Your email cannot be blank";
            }
            if(!filter_var($value, FILTER_VALIDATE_EMAIL))
            {
                $_SESSION["errors"][] = "Please enter a vaild email";
            }
        }
        if($key == "birthday")
        {
            if(empty($value))
            {
                $_SESSION["errors"][] = "Your birthday cannot be blank";
            }
            else
            {
                $exploded_date = explode("/", $value);
                if(!checkdate($exploded_date[0], $exploded_date[1], $exploded_date[2]))
                {
                    $_SESSION["errors"][] = "Please enter valid birthday";
                }
                if($value > date('Y/m/d'))
                {
                    $_SESSION["errors"][] = "Your birthday cannot be in the future";
                }
            }
        }
        if($key == "password")
        {
            if(empty($value))
            {
                $_SESSION["errors"][] = "Your password cannot be blank";
            }
            if(strlen($value) < 8)
            {
                $_SESSION["errors"][] = "Your password needs to be longer than 7 characters";
            }
        }
        if($key == "password_confirmation")
        {
            if($post["password"] !== $value)
            {
                $_SESSION["errors"][] = "Your passwords need to match";
            }
        }
    }
    if(isset($_SESSION["errors"]))
    {
        header('Location: index.php');
        die();
    }
    else
    {
        $_SESSION["success_message"] = "Congratulations you are now a member!";
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
        $hash = crypt($post['password'], $salt);
        $sql = "INSERT INTO users (first_name, last_name, email, birthday, password, created_at, updated_at) VALUES('".$post["first_name"]."', '".$post["last_name"]."', '".$post["email"]."', '".$exploded_date[2].'-'.$exploded_date[0].'-'.$exploded_date[1]."', '".$hash."', NOW(), NOW())";
        mysqli_query($connection, $sql);
        $user_id = mysqli_insert_id($connection);
        $_SESSION['user_id'] = $user_id;

        header('Location: profile.php?id=' . $user_id);
        exit;
    }
}

?>
