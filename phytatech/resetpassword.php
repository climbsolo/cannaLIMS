<?php 
include "includes.php";
?>

<!DOCTYPE html>
<?php showheader() ?>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Create or Reset your Password</h1>
        <p>Please enter your desired password to continue.</p>
      </div>
    </div>

    <div class="container">    
        <div class="form-group">
            <div class="row">
                <div class="col-lg-8">
                <h4 id="resetpassword_message">Please provide your desired password.</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4" style="padding-top:20px;">
                    <label for="reset_password1">Type your New Password:</label>
                    <input class="cannashout_password form-control" type="password" id="reset_password1"></input>
                </div>
                <div class="col-lg-4" style="padding-top:20px;">
                    <label for="reset_password2">Re-type your New Password:</label>
                    <input class="cannashout_password form-control" type="password" id="reset_password2"></input>
                </div>
            </div>
            <div class="row">
            <div class="col-lg-4">
            </div>
                <div class="col-lg-4" style="text-align:right;padding-top:20px;">
                    <a class="btn btn-primary btn-lg" href="#" role="button" id="btnresetpassword">Reset Password &raquo;</a>
                </div>
            </div>
        </div>
        
    <?php showfooter() ?>
         
    </div>      
    </body>
</html>

<script>

$(document).ready(function() {
    
    $("#btnresetpassword").click(function() {
        resetpassword();
    });
    
    $('.cannashout_password').keypress(function(event) {
        if (event.keyCode == 13) {
            $("#btnresetpassword").click();
            return false;
        }
    });
    
});

function resetpassword() {
    
    var resetpasswordid = "<?php echo $_GET["id"] ?>";
    
    if (resetpasswordid.length < 1) {
        $("#resetpassword_message").html("We're sorry, but your request could not be processed. Please contact us for further support.");
        return false;
    }
    
    var pw1 = $("#reset_password1").val();
    var pw2 = $("#reset_password2").val();
    
    if (pw1.length < 1) {
        $("#resetpassword_message").html("Please provide a password.");
        $("#reset_password1").focus();
        return false;
        
    }
    
    if (pw1 != pw2) {
        $("#resetpassword_message").html("The passwords do not match.");
        $("#reset_password1").focus();
        return false;
    }   
    
        $.ajax({
        type: 'POST',
        url: "resetpasswordvalidate.php",
        data: {
            'resetpasswordid': resetpasswordid,
            'pw': pw1            
        },
        error: function(err) {
            alert("ERROR: " + JSON.stringify(err));
            alert("OOPS!  An error has occurred! Please, wait a moment and then give it another try... that usually works!");
            return false;
        },
        success: function(d){
            //alert(d);
            if (d === "success") {
                window.location = "index.php";                
            }
            else
            {
                $("#resetpassword_message").html(d);
                $("#reset_password1").focus();
            }
        }
        });    
    
}

</script>
