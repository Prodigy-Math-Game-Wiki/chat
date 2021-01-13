<?php
header("Content-Security-Policy: frame-ancestors 'self' https://prodigy-math-game.fandom.com;");

session_set_cookie_params(0);
session_start();

$servername = "sql.example.com";
$username = "admin123";
$password = "yourpasswordhere";
$dbname = "example";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("MySQL Error: " . $conn->connect_error);
}

function loginWithCredentials($username, $password) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $username); // i know this isnt very good, but im going to work on more sql injection prevention later

    $password_hash = hash("sha512", $password);

    $query = mysqli_query($conn, "SELECT id FROM Users WHERE username = '$username' and password_hash = '$password_hash'");
   
    if (!$query) {
        die('MySQL Error: ' . mysqli_error($conn));
    }

    $query2 = mysqli_query($conn, "SELECT id, identity_verified FROM Users WHERE identity_verified = '1' and username = '$username'");

    if(!$query2) {
        die("MySQL Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($query) > 0) {
        if(mysqli_num_rows($query2) > 0) {
            $_SESSION['password_hash'] = $password_hash;
            $_SESSION['name'] = $username;
            echo "Logged in. Please wait a moment.";
        } else {
            echo "Please request your account to be verified.";
        }

    } else {
         echo "Invalid username or password. Try another?";
    }
}

function shouldShowAds() {
    if($_SESSION['isMobile'] = 1) {
        return true;
    } else {
        return false;
    }
}

if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])
) {
    //header("Location: https://prodigywiki-internaldomain.com/chat/loadmobile.php");
}

function loginForm()
{
    echo '
    <div id="loginform" style="display: flex;">
    <div>
    <iframe data-aa="1540550" src="//ad.a-ads.com/1540550?size=120x600" scrolling="no" style="width:120px; height:600px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>
    </div>
    <div style="width: max-content;">
    <iframe data-aa="1540553" src="//ad.a-ads.com/1540553?size=970x90" scrolling="no" style="width:970px; height:90px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>
    <form action="index.php" method="post">
        <p>Welcome to the Unnoficial FANDOM Chat. Please sign up or log in below.</p>
        <label for="name">Username:</label>
        <input type="text" name="name" id="name" />
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"/>
        <input type="submit" name="enter" id="enter" value="Login" />
    </form>
    <button><a href="signup.php">Sign up for the Unnoficial FANDOM Chat.</a></button> | <button><a href="#">I forgot my password.</a></button><br/>Password reset is a W.I.P, contact NameIsA if you need help accessing your account.
    <br/><hr/>
    <sub>Sponsored by the <a href="https://prodigy-math-game.fandom.com">Prodigy Math Game Wiki</a>. | Developed by <a href="https://prodigy-math-game.fandom.com/wiki/User:NameIsA">NameIsA</a>. | This chat is <a href="https://github.com/Prodigy-Math-Game-Wiki/chat">open source</a>.</sub>
    </div>
    <div>
    <iframe data-aa="1539829" src="//ad.a-ads.com/1539829?size=160x600" scrolling="no" style="width:160px; height:600px; border:0px; padding:0; overflowa:hidden" allowtransparency="true"></iframe>
</div>
    </div>
    ';
}

if (isset($_POST['enter']) & isset($_POST['password']) & isset($_POST['name']))
{
    loginWithCredentials($_POST['name'], $_POST['password']);
}

if (isset($_GET['logout']))
{
    session_destroy();
    header("Location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" lang="en">
<head>
    <title>Prodigy Math Game Wiki | Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css"/>
    <script>(async() => {eval(await (await fetch("https://raw.githubusercontent.com/cure53/DOMPurify/main/dist/purify.min.js")).text())})()</script>

    <?php
    /**********************************************
    * Removing ads on mobile for design purposes. *
    ***********************************************/
    ?>
    <?php if(!shouldShowAds()) { echo '<style>'; } ?> <?php if(!shouldShowAds()) {
        echo 'iframe[src^="//ad.a-ads"] { display: none!important; }';
    }
    ?> <?php if(!shouldShowAds()) { echo'</style>'; } ?>
</head>

<body>

<?php
if (!(isset($_SESSION['password_hash'])))
{
    loginForm();
}
else
{
?>
<div id="wrapper" style="display: flex;">
<div><iframe data-aa="1540550" src="//ad.a-ads.com/1540550?size=120x600" scrolling="no" style="width:120px; height:600px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe></div>
<div>
    <div id="menu">
        <p class="welcome">
            Welcome, <b><?php echo $_SESSION['name']; ?></b>
        </p>
      
        <div class="nav"><a id="exit" href="./index.php?logout">Sign out</a></div>
        <br/>
        <hr/>
            <?php if(shouldShowAds()) { echo '<iframe data-aa="1540553" src="//ad.a-ads.com/1540553?size=970x90" scrolling="no" style="width:970px; height:90px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>'; } ?>
        <div style="clear: both;"></div>
    </div>  
    
    <div id="chatbox" style="list-style: none;"><ul style="list-style: none;"><?php
    if (file_exists("log.html") && filesize("log.html") > 0)
    {
        $handle = fopen("log.html", "r");
        $contents = fread($handle, filesize("log.html"));
        fclose($handle);

        echo $contents;
    } ?></ul></div>


    <form name="message" action="">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
    </form>
</div>
<div><iframe data-aa="1540550" src="//ad.a-ads.com/1540550?size=120x600" scrolling="no" style="width:120px; height:600px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe></div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
    $("#submitmsg").click(function() {	
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		return false;
	});

	loadLog = () => {		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
        
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html) {	
                html = DOMPurify.sanitize(html);

				$("#chatbox").html(html);

				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
				if(newscrollHeight > oldscrollHeight) {
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
				}				
		  	}
		});
	}

    keepAliveCall = () => {
        fetch("./keepalive.php");
    }

    setInterval(loadLog, 1500);

    setInterval(keepAliveCall, 20000);
</script>
<?php
}
?>
</admin123om
