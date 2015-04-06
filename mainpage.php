<!DOCTYPE html>

<html>

<head>
    <title>Acromedia Auction</title>
    <link href="style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>

    <body>
        <nav id="nav02"></nav>
        <div id="main">
            <h1>Home</h1>
            <h2>Recent Auctions:</h2>
            Category: <select id="category" name="category" ></select><br><br>
            <div id="maindisplay"></div><br>
        </div>    
    </body>
    
    <script src="mainpage.js?<?php echo time(); ?>"></script>
    <script>
    var tableData;
    /* function to populate user's auctions for the main page*/
    //if (document.getElementById("maindisplay")){
    $("#category").change(function(){
        $.get("controller.php?command=allauctionsdata&category=" + $("#category option:selected").val(), function(data, status){
            tableData = data;
            if (data != 0){
            var i = 0;
            var out = "<table><tr><th></th><th>Category</th><th>User</th><th>Title</th><th>Price</th><th>Time Left</th></tr>";
            for (i = 0; i < data.length; i++){
                out += "<tr><td>" +
                "<button class='view'>View</button>" +
                "</td><td>" +
                data[i].category + 
                "</td><td>" +
                data[i].user +
                "</td><td>" +
                data[i].title +
                "</td><td>" +
                "$" + data[i].price +

                "</td><td>" +
                (data[i].auctiontime == "" ? "expired" : data[i].auctiontime) +
                "</td></tr>";

            }
            out += "</table>";

            $("#maindisplay").html(out);

            $(".view").click(function(){
                var i = this.parentNode.parentNode.rowIndex - 1;
                window.open("controller.php?command=auctionpage&id=" + tableData[i].id, "_self");
            });

            }
            else
                $("#maindisplay").html("There is nothing to display");

        });
    });

    // initial trigger of dropdown change event to load all data upon page load. Without this, page will not populate
    // results until page is category is changed
    $("#category").trigger("change");



    </script>

</html>
