<!DOCTYPE html>

<html>

<head>
    <title>Acromedia Auction</title>
    <link href="style.css?<?php echo time(); ?>" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>

    <body>
        <nav id="nav02"></nav>
        <div id="main">
        <h1>User: <?php echo $_SESSION['username']; ?></h1>
        <h2>My Auctions:</h2>
        <div id="display"></div>
        <h2>My Bids:</h2>
        <div id="biddisplay"></div><br>
        <input style="margin-top: 3px; margin-bottom: 3px; padding: 3px;" onclick="displaynew()" type="button" value="New Auction">
        <div id="newauction">
            <form id="newauctionform" method="post" action="controller.php" enctype="multipart/form-data">
                <input type="hidden" name="command" value="newauction">
                Title: <br><input id="title" type="text" name="title" required><br><br>
                Reserve Price ($): <br><input type="text" id="price" name="reserveprice" pattern="[0-9]*"><br><br>
                Category: <select id="category" name="category" required></select> <br><br>
                <!-- Auction time in hours. Minimum time is 1 hour, maximum time is 168 hours (1 week) --> 
                Auction Time (in hours): <br><input type="number" name="auctionhours" min="1" max="168"><br><br>
                <fieldset style="display: inline">
                    <legend>Image Upload</legend>
                    <input type="file" id="fileselect" name="fileselect" accept="image/x-png, image/gif, image/jpeg" 
                    onchange="readImg(this)"><br>
                    <img id="imgoutput"></img>
                </fieldset><br><br>
                Description: <br><textarea name="description" required></textarea><br><br>
                <input style="margin-top: 5px; padding: 3px;" onclick="auctionsubmit()" type="button" value="Submit">
            </form>
        </div>

        </div>    
    </body>
    
    <script src="mainpage.js?<?php echo time(); ?>"></script>
    <script src="userauctions.js?<?php echo time(); ?>"></script>

</html>
