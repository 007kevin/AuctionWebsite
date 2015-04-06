<!DOCTYPE html>

<html>

<head>
    <title>Acromedia Auction</title>
    <link href="style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>

    <body>
        <nav id="nav01"></nav>
        <div id="main">
            <h1>Create your personal account</h1>
            <h2>Registration:</h2>
            <form id="regform" method="post" action="controller.php">
                <input type="hidden" name="command" value="register">
                Username: <br><input id="usr" type="text" name="username" required><p style="display: inline;"></p><br>
                Password: <br><input id="pwd" type="password" name="password" required><br>
                Confirm password: <br><input id="cpwd" type="password" name="confirmpassword" required><br>
                <input style="margin-top: 5px; padding: 3px;" onclick="regsubmit()" type="button" value="Submit">
            </form>
        </div>    
    </body>
    
<script src="startpage.js?<?php echo time(); ?>"></script>

</html>
