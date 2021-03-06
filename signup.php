<?php
$servername = "sql.example.com";
$username = "admin123";
$password = "yourpasswordhere";
$dbname = "example";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("MySQL Error: " . $conn->connect_error);
}

function get_ip_address() {
    foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ) as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
}

function doesUserExist($username) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $username); // i know this isnt very good, but im going to work on more sql injection prevention later

    $query = mysqli_query($conn, "SELECT * FROM Users WHERE username='" . $username . "'");

    if (!$query) {
        die('MySQL Error: ' . mysqli_error($con));
    }

    if (mysqli_num_rows($query) > 0) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['name']) & isset($_POST['password']) & isset($_POST['email'])) {

    if (doesUserExist($_POST['name'])) {
        echo "That's a great username, but it's already taken. Try another?";
        die();
    }

    $sql = "INSERT INTO Users (username, email, identity_verified, password_hash, account_creation_ip, premium)
VALUES ('" . $_POST['name'] . "','" . $_POST['email'] . "','0','" . hash("sha512", $_POST['password']) . "','" . get_ip_address() . "','0')";

    if ($conn->query($sql) === true) {
        echo "Success. Before you <a href='index.php'>log in</a>, please contact <a href='https://community.fandom.com/wiki/Message_Wall:NameIsA'>NameIsA</a> using a Fandom account matching the username you chose on signup to update your account to verified status. This is to prevent impersonation.";
    } else {
        echo "MySQL Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>Signup | FANDOM Prodigy Math Game Wiki Chat</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="https://prodigywiki-internaldomain.com/chat/css/style.css" />
        <script>(async()=>{eval(await(await fetch("https://raw.githubusercontent.com/cure53/DOMPurify/main/dist/purify.min.js")).text())})();</script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9TVN6K53DJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-9TVN6K53DJ');
    </script>
    </head>
    <body>
        <div id="signupForm">
            <form action="signup.php" method="post">
                <h1>New to the chat?</h1>
                <br />
                <label for="name">Fandom Username</label>
                <br />
                <input type="text" name="name" id="name" />
                <br />
                <label for="password">Choose a password (don't re-use your Fandom password)</label>
                <br />
                <input type="password" name="password" id="password" />
                <br />
                <label for="email">What's your email?</label>
                <br />
                <input type="text" name="email" id="email" pattern="[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+" />
                <br />
                <input type="submit" name="enter" id="enter" value="Join" />
            </form>
            <button><a href="index.php">Sign in</a></button>
        </div>
    </body>
</html>
