<?php
    session_start();

    include_once('model.php');
    //if no command, default to opening mainpage if already signed in, else go to startpage
    if (empty($_POST['command']) && empty($_GET['command'])){
        if (isset($_SESSION['signedin']))
            include('mainpage.php');
        else{
            include('startpage.php');
        }
        exit();
    }

    //to allow commands received via get or post
    if (isset($_POST['command']))
        $command = $_POST['command'];
    if (isset($_GET['command']))
        $command = $_GET['command'];

    //commands for when user is not signed in
    if (!isset($_SESSION['signedin'])){
            switch($command){
            // open sign in page
            case 'signinpage':
                include('signin.php');
                exit();

            // open register page
            case 'registerpage':
                include('register.php');
                exit();

            // ajax to check name availability on register page
            case 'namecheck':
                $username = $_GET['username'];
                if (checkDuplicateUser($username))
                    echo("Unavailable");
                else
                    echo("Available");
                exit();

            // create new user
            case 'register':
                $username = $_POST['username'];
                $password = $_POST['password'];
                createUser($username, $password); 
                include('signin.php');
                exit();

            // sign in check
            case 'signin':
                $username = $_POST['username'];
                $password = $_POST['password'];
                if (checkValidity($username, $password)){
                    $_SESSION['signedin'] = 'YES';
                    $_SESSION['username'] = $username;
                    include('mainpage.php');
                }
                else{
                    include('signin.php');
                    echo '<script type = "text/javascript">'
                            ,'$("#signform p").html(" incorrect username/password");'
                            ,'$("#signform p").css("color", "red")'
                            ,'</script>';
                }
                exit();
            }
    }
    // these commands are only accessible if user is signed in
    else{

        switch($command){

            // open user's auctions
            case 'myauctions':
                include('userauctions.php');
                exit();

            // command for creating new auction
            case 'newauction':
                $image_location = "";
                // Check if image is uploaded by user
                if (file_exists($_FILES['fileselect']['tmp_name']) && is_uploaded_file($_FILES['fileselect']['tmp_name'])){
                    $target_dir = "uploaded_images/"; // directory to hold uploaded images
                    $target_file = $target_dir . basename($_FILES['fileselect']['name']);
                    $id = 0;
                    // Check if there are duplicate names. Will append incrementing integer values to file name until 
                    // duplicate name is no longer found
                    while (file_exists($target_file)){
                        $temp = explode(".", $_FILES["fileselect"]["name"]);
                        $target_file = $target_dir . $temp[0] . ++$id . "." . $temp[1];
                    }
                    move_uploaded_file($_FILES['fileselect']['tmp_name'], $target_file);
                    $image_location = $target_file;
                }
                $user = $_SESSION['username'];
                $title = $_POST['title'];
                $price = $_POST['reserveprice'];
                $category = $_POST['category'];
                $description = $_POST['description'];
                $datetime = date("Y-m-d H:i:s", time());

                //calcuate auction time by adding the specifed hours to the current time
                $hours = $_POST['auctionhours'];
                $time = new DateTime($datetime);
                $time->modify("+$hours hours");
                $auctiontime = $time->format("Y-m-d H:i:s");

                newAuction($title, $price, $datetime, $auctiontime, $user, $image_location, $description, $category);
                header("Location: controller.php?command=myauctions");
                exit();

            //command to get user's auctions
            case 'myauctionsdata':
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: application/json; charset=UTF-8");
                $user = $_SESSION['username'];
                echo(getUserAuctions($user));
                exit();

            //command to get auctions where user placed a bid
            case 'myauctionsbid':
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: application/json; charset=UTF-8");
                $user = $_SESSION['username'];
                echo(getUserBidAuctions($user));
                exit();

            //command to delete auction
            case 'deleteauction':    
                deleteAuction($_GET['id']);
                header("Location: controller.php?command=myauctions");
                exit();

            //command to displayed individual auctions
            case 'auctionpage':
                $auction = getAuction($_GET['id']);
                $id = $auction['id'];
                $title = $auction['title'];
                $image = $auction['image'];
                $description = $auction['description'];
                $timeleft = $auction['auctiontime'];
                $price = $auction['price'];
                include('auctionpage.php');
                exit();

            //command to place bid on auction
            case 'auctionbid':
                $aid = $_GET['aid']; 
                $user = $_SESSION['username'];
                $amount = $_GET['bid']; 
                insertBid($aid, $user, $amount);
                header("Location: controller.php?command=auctionpage&id=" . $aid);
                exit();

            //get bids to be displayed in the my auctions page
            case 'getbids':
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: application/json; charset=UTF-8");
                $aid = $_GET['aid'];
                echo(getBids($aid)); 
                exit();

            //home page to display all auctions
            case 'allauctionsdata':
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: application/json; charset=UTF-8");
                error_log($_GET['category']);
                echo(getAllAuctions($_GET['category']));
                exit();

            }
    }
?>
            
