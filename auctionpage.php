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
        <h1>id#<?php echo $id; ?></h1>
        <h2><?php echo $title; ?></h2>
        <img style="max-width: 250px; max-height: 250px;" src="<?php echo $image; ?>">
        <p><?php echo $description; ?></p>
        <p>Time left for this auction: <?php echo $timeleft; ?></p>
        <p><b>Reserve price: $<?php echo $price; ?></b></p>
        <p id="hbid"><b>Highest bid: </b></p>
        <div id="bid">No bids made for this item</div>
        <br>
        <form method="get" action="controller.php"> 
            <input type="hidden" name="command" value="auctionbid">
            <input type="hidden" name="aid" value="<?php echo $id; ?>">
            Make Bid: $<input type="text" id="price" name="bid" pattern="[0-9]*">
            <input type="button" id="bidbutton" value="Bid"><p style="inline"></p>  
        </form>
        </div>    
    </body>
    
    <script src="mainpage.js?<?php echo time(); ?>"></script>
    <script>
        var id = <?php echo $id; ?>;
        // variable to store highest bid
        var topbid = 0;
        document.getElementById("price").onblur = function(){
        this.value = parseFloat(this.value.replace(/,/g, ""))
                    .toFixed(2)
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, "");
        };
        
        $("#bidbutton").click(function(){
            // user can only submit if bid is greater than highest bid
            if (parseFloat($("#price").val()) > topbid)
                $("form").submit();
        });

        window.onload = function(){
            $.get("controller.php?command=getbids&aid=" + id, function(data, status){
                if (data != 0){
            
                    var i;
                    var out = "Bid history:";
                    for (i = 0; i < data.length; i++){
                        // As the bid history output is being generated, the highest bid will be contained in the identifier topbid
                        out += "<br>$" + (parseFloat(data[i].bid) > topbid ? topbid = data[i].bid : data[i].bid) + " by " + data[i].user + " on " + data[i].datetime;
                        console.log(data[i].bid);
                    }
                    $("#bid").html(out);
                    
                    $("#hbid").html("<b>Higest bid: $" + topbid);
                }
                // If time left for auction has expired, disable bid input and button
                if ("<?php echo $timeleft; ?>" == "expired"){
                    $("#price").prop("readonly", true);
                    $("#bidbutton").prop("disabled", true);
                }

            });
        }
        
    </script>

</html>
