
function displaynew(){
       $("#newauction").toggle(300);
} 

document.getElementById("price").onblur = function(){
    this.value = parseFloat(this.value.replace(/,/g, ""))
                    .toFixed(2)
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, "");
};

function readImg(input){
   if (input.files && input.files[0]){
       var reader = new FileReader();
       reader.onload = function(e){
           $("#imgoutput")
                .attr('src', e.target.result)
                .width(150)
                .height(200);
       }
       reader.readAsDataURL(input.files[0]);
   }
}

function auctionsubmit(){
    $("#newauctionform").submit(); 

}

/* variable to store data retrieved from controller */
var tableData;

/* variable to store user bids */
var bidData;


/* function to populate user's auctions and bids*/
window.onload = function(){
    $.get("controller.php?command=myauctionsdata", function(data, status){
        tableData = data;
        if (data != 0){
        var i = 0;
        var out = "<table><tr><th></th><th>Category</th><th>User</th><th>Title</th><th>Price</th><th>Time Left</th></tr>";
        for (i = 0; i < data.length; i++){
            out += "<tr><td>" +
            "<button class='view'>View</button> <button class='delete'>Delete</button>" +
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

        $("#display").html(out);

        $(".delete").click(function(){
            var i = this.parentNode.parentNode.rowIndex - 1;
            if (confirm("Are you sure you wish to delete auction?"))
                 window.open("controller.php?command=deleteauction&id=" + tableData[i].id, "_self");
        });
        
        $(".view").click(function(){
            var i = this.parentNode.parentNode.rowIndex - 1;
            window.open("controller.php?command=auctionpage&id=" + tableData[i].id, "_self");
        });

        }
        else
            $("#display").html("There is nothing to display");

    });

    $.get("controller.php?command=myauctionsbid", function(data, status){
        bidData = data;
        if (data != 0){
        var i = 0;
        var out = "<table><tr><th></th><th>Category</th><th>User</th><th>Title</th><th>Price</th><th>Time Left</th></tr>";
        for (i = 0; i < data.length; i++){
            out += "<tr><td>" +
            "<button class='bview'>View</button>" +
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

        $("#biddisplay").html(out);

        
        $(".bview").click(function(){
            var i = this.parentNode.parentNode.rowIndex - 1;
            window.open("controller.php?command=auctionpage&id=" + bidData[i].id, "_self");
        });

        }
        else
            $("#biddisplay").html("There is nothing to display");

    });



}


