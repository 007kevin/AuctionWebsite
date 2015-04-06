$("#nav01").html(
        "<ul id='menu'>" +
            "<li><a href='controller.php'>Home</a></li>" +
            "<li><a href='controller.php?command=signinpage'>Sign In</a></li>" +
            "<li><a href='controller.php?command=registerpage'>Register</a></li>" +
        "</ul>");

function regsubmit(){
    if ($("#regform p").text() == " Available" && $("#pwd").val() != "" && $("#pwd").val() == $("#cpwd").val()){
      $("#regform").submit();  
      window.alert("User account successfully created. Please sign in");
    }
} 

function signsubmit(){
    $("#signform").submit(); 
}

$("#usr").keyup(function(){
    $.get("controller.php?command=namecheck&username=" + $("#usr").val(), function(data, status){
        if (data == "Available"){
           $("#regform p").css('color', 'green');
           $("#regform p").html(" Available");
        }
        else {
           $("#regform p").css('color', 'red');
           $("#regform p").html(" Username Taken");
        }
    }); 
});
