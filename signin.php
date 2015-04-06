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
            <h1>Sign in to your account</h1>
            <h2>Login:</h2>
            <form id="signform" method="post" action="controller.php">
                <input type="hidden" name="command" value="signin">
                Username: <br><input type="text" name="username" required><p style="display: inline;"></p><br>
                Password: <br><input id="pwd" type="password" name="password" required><br>
                <input style="margin-top: 5px; padding: 3px;" onclick="signsubmit()" type="button" value="Submit">
            <footer id="foot01"></footer>
        </div>    
    </body>
    
<script src="startpage.js?<?php echo time(); ?>"></script>

</html>
