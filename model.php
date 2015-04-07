<?php
    include_once('../databaseinfo.php');
    $conn = mysqli_connect($host, $usn, $pwd, $db);
    if (mysqli_connect_errno())
        echo "Failed to connect to database: " . mysqli_connect_error();

    function closeConn(){
        global $conn;
        mysqli_close($conn);
        unset($conn);
    }

    function checkDuplicateUser($username){
        global $conn;
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        closeConn();
        if (mysqli_num_rows($result) == 0)
            return false;
        else
            return true;
    }

    function createUser($username, $password){
        global $conn;
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $sql)){
            closeConn();
            return 0;
        }
        else {
            echo "error: " . mysqli_error($conn);
            closeConn();
            return 1;
        }
    }
    
    function checkValidity($username, $password){
        global $conn;
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        closeConn();
        if (mysqli_num_rows($result) == 0)
            return false;
        else
            return true;
    }

    function newAuction($title, $price, $datetime, $auctiontime, $user, $image, $description, $category){
        global $conn;
        $sql = "INSERT INTO auctions (title, price, datetime, auctiontime, user, image, description, category)
                    VALUES ('$title', '$price', '$datetime', '$auctiontime', '$user', '$image', '$description', '$category')";
        if (mysqli_query($conn, $sql)){
            closeConn();
            return 0;
        }
        else {
            echo "error: " .mysqli_error($conn);
            closeConn();
            return 1;
        }
    }

    function getAllAuctions($category){
        global $conn;
        if ($category != "all")
            $sql = "SELECT * FROM auctions WHERE category='$category' ORDER BY datetime";
        else
            $sql = "SELECT * FROM auctions ORDER BY datetime";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $json = array();
            while ($row = mysqli_fetch_assoc($result)){
                /* Calculate time left for the auction */
                $auctime = new DateTime($row['auctiontime']);
                $nowtime = new DateTime();
                $timeleft = "";
                /* Calculate time left for auction if auction is not in the past */
                if ($auctime > $nowtime){
                    $aucleft = $nowtime->diff($auctime);
                    $timeleft = sprintf("%dd, %dh, %dm", $aucleft->d, $aucleft->h, $aucleft->i);
                }
               
                $json[] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'price' => $row['price'],
                    'datetime' => $row['datetime'],
                   
                    'auctiontime' => $timeleft,
                    'user' => $row['user'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'category' => $row['category']
                );

            }
            closeConn();
            return json_encode($json);
        }
        else{
            closeConn();
            return 0;
        }

    }

    function getUserAuctions($username){
        global $conn;
        $sql = "SELECT * FROM auctions WHERE user = '$username' ORDER BY datetime";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $json = array();
            while ($row = mysqli_fetch_assoc($result)){
                /* Calculate time left for the auction */
                $auctime = new DateTime($row['auctiontime']);
                $nowtime = new DateTime();
                $timeleft = "";
                /* Calculate time left for auction if auction is not in the past */
                if ($auctime > $nowtime){
                    $aucleft = $nowtime->diff($auctime);
                    $timeleft = sprintf("%dd, %dh, %dm", $aucleft->d, $aucleft->h, $aucleft->i);
                }
               
                $json[] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'price' => $row['price'],
                    'datetime' => $row['datetime'],
                   
                    'auctiontime' => $timeleft,
                    'user' => $row['user'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'category' => $row['category']
                );

            }
            closeConn();
            return json_encode($json);
        }
        else{
            closeConn();
            return 0;
        }
    } 

    function getUserBidAuctions($username){
        global $conn;
        $sql = "SELECT DISTINCT aid FROM bids WHERE user='$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $bids = array();
            while ($row = mysqli_fetch_assoc($result)){
                error_log($row['aid']);
                $bids[] = getAuction($row['aid']);
            }
            closeConn();
            return json_encode($bids);

        }
        else{
            closeConn();
            return 0;
        }

    }

    function getAuction($id){
        global $conn;
        $sql = "SELECT * FROM auctions WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
                /* Calculate time left for the auction */
                $auctime = new DateTime($row['auctiontime']);
                $nowtime = new DateTime();
                $timeleft = "expired";
                /* Calculate time left for auction if auction is not in the past */
                if ($auctime > $nowtime){
                    $aucleft = $nowtime->diff($auctime);
                    $timeleft = sprintf("%dd, %dh, %dm", $aucleft->d, $aucleft->h, $aucleft->i);
                }
            $auction = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'price' => $row['price'],
                    'datetime' => $row['datetime'],
                   
                    'auctiontime' => $timeleft,
                    'user' => $row['user'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'category' => $row['category']
                );
            return $auction;
        }
        else{
            return 0;
        }
    }

    function insertBid($aid, $user, $amount){
        global $conn;
        $datetime = date("Y-m-d H:i:s", time());
        $sql = "INSERT INTO bids (aid, user, bid, datetime) VALUES ('$aid', '$user', '$amount', '$datetime')";
        if (mysqli_query($conn, $sql)){
            closeConn();
            return 0;
        }
        else{ 
            echo "error: " . mysqli_error($conn);
            closeConn();
            return 1;
        }
    }

    function getBids($aid){
        global $conn;
        $sql = "SELECT * FROM bids WHERE aid='$aid' ORDER BY datetime DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $data = array();
            while ($row = mysqli_fetch_assoc($result)){
                $data[] = array(
                    'id' => $row['id'],
                    'aid' => $row['aid'],
                    'user' => $row['user'],
                    'datetime' => $row['datetime'],
                    'bid' => $row['bid']
                );
            }
            closeConn();
            return json_encode($data);
        }
        else{
            closeConn();
            return 0;
        }
    }

    function deleteAuction($id){
        global $conn;
        $sql = "SELECT image FROM auctions WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $image = $row['image'];
        if ($image != ""){
            if (!unlink($image))
                error_log("error: unable to delete " . $image);
        }



        $sql = "DELETE FROM auctions WHERE id='$id'";
        $errflag;
        if (mysqli_query($conn, $sql))
            $errflag = 0;
        else{
            error_log("error: " . mysqli_error($conn));
            $errflag = 1;
        }
         $sql = "DELETE FROM bids WHERE aid='$id'";
        if (mysqli_query($conn, $sql))
            $errflag = 0;
        else{
            error_log("error: " . mysqli_error($conn));
            $errflag = 1;
        }
        closeConn();
        return $errflag;
    }
    
?>
