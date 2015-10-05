<?php 

include "includes.php";

$hidelogoutbutton = ' style="display:none;"';

$loggedin = '';
$role = '';

if (isset($_SESSION["luid"])) {
    if (strlen($_SESSION["luid"]) > 0) {
        $loggedin = 'true';
        $hidelogoutbutton = "";
        $role = $_SESSION["role"];
    }
}

?>

<style>

.btn-warning {
    margin-bottom:1em !important;
    min-width: 10em;
}

html, body {
    overflow-y: hidden;
}

.hidden {
    display: none;
}

</style>

<html>

    <?php showheader(); ?>
    
    <!-- blue theme stylesheet with additional css styles added in v2.0.17 -->
    <link rel="stylesheet" href="css/theme.blue.css">
    <link rel="stylesheet" href="css/styles.css"> 
    
    <body>
   
    <div class="row" style="min-height:10%;">      
        <div class="col-sm-8" style="text-align:left;">
            <div id="main_nav" style="display:none;">               
                <button onclick="$('.p_pagination').fadeIn(250);changeframe('managesamples.php');" class="btn btn-warning">Manage Samples</button>
                <button onclick="changeframe('manageclients.php');$('.p_pagination').hide();" class="btn btn-warning">Manage Clients</button>
                <button onclick="changeframe('importcsv.php');$('.p_pagination').hide();" class="btn btn-warning">Import CSV</button>                
                <button onclick="changeframe('workflow.php');$('.p_pagination').hide();" class="btn btn-warning admin_restricted">Workflow</button>
                <button onclick="changeframe('admin.php');$('.p_pagination').hide();" class="btn btn-warning admin_restricted">Admin</button>
            </div>
        </div>
        <div class="col-sm-4" style="text-align:right;">           
            <button id="btnuserlogout" onclick="logout()" class="btn btn-default" <?php echo $hidelogoutbutton ?>>Logout</button>
            <img src="images\logo.png" style="width:200px;margin:.5em .5em 0 1em;" />          
        </div>

    </div>
        
       <?php 
        if ($loggedin == "true") {
    ?>
    
    <script>  
    
    if (window.top.location.href !='<?php echo $_SESSION["url"] ?>') {
        window.top.location.href = 'logout.php';
    }
    
   setInterval(function(){ 
    
    $.get("gettimeoutvalue.php", function(data) {
    var discard_after = data;
    var now = Math.floor(Date.now() / 1000)
    var diff = discard_after - now;
        if (diff < 1) {
            window.top.location.href = 'logout.php';
        }
    });
    }, <?php echo $session_timeout_check_interval ?>);

   
    </script>
    
   
    <div style="height:80%;">

        <iframe id="frame" src="managesamples.php" style="width:100%;height:100%;border:none;padding-bottom:0em;"></iframe>
        
        <div id="footer" style="height:50px;">
            <div class="row">
                <div class="col-sm-6">           
                    <a href="http://www.cannasys.com/"><img src="..\common\images\poweredbylogo.png" style="height:50px;" /></a>
                </div>
                <div class="col-sm-6" style="text-align:right;">
                    <div class="p_pagination"></div>
                </div>
            </div>
        </div>        
    </div>
   
    
    <?php showfooter() ?>

    <?php        
        }
        else
        {
    ?>
    
    <div id="login_div" style="text-align:center;max-width:60em;" class="centerform">
        <h2>Please Login</h2>
        <p>If you do not have an account, or cannot recall your username or password, please contact the Lab Director.</p>
        <form class="form-inline">
            <div class="form-group">
                <label for="user_name">User Name:</label>
                <input type="text" class="form-control login" id="user_name" placeholder="User Name" autocomplete="off" autofocus="on">
            </div>
            <div class="form-group">
                <label for="loginpassword">Password:</label>
                <input type="password" class="form-control login" id="password" placeholder="Password">
            </div>            
            <button type="button" onclick="login()" id="btnlogin" class="btn btn-default">Submit</button>
            
        </form>

    </div>
    
    <?php showfooter() ?> 
    
    <script>
    
    $(document).ready(function() {
       $("#login_div").fadeIn(200);
       
       $('.login').keypress(function(event) {
        if (event.keyCode == 13) {
            login();
            return false;
        }
    });
       
    });
    </script>
            
            
    <?php     
        }
    ?>
    
   

    </body>
</html>

<script>

$(window).load(function() {
        
    var role = "<?php echo $role ?>";    
    if (role != "Admin") {
        $(".admin_restricted").hide();        
        $("#frame").contents().find(".admin_restricted").addClass("hidden");      
    }
    
});

function login() {
    var un = $("#user_name").val();
    if (un.length < 1) {
        showerrormessage("Please provide your Username.");
        return false;
    }
    
    var pw = $("#password").val();
    if (pw.length < 1) {
        showerrormessage("Please provide your Password.");
        return false;
    }
    
    var url = window.location.href;
    
    waitingDialog.show();
    
    setTimeout(function() {
    
   
    $.post("login.php", {username: un, password: pw, url: url}, 
    function(data) {    
        if (data == "failure") {
            showerrormessage("An error has occurred. Please try again.");
            waitingDialog.hide();
            return false;
        }
        
        $("#main_nav button").each(function() {
            $.when( $(this).click() ).done(function() {
                // Don't do a damn thing... just let all the AJAX stuff occur as if the user had already clicked all the Nav buttons... this way, it's all zippy once they are logged in. We make it last AT LEASY one second in order to ensure the "Please Wait" dialog is displayed... this way, it is consistently visible to the User, whether the request is fast or slow.
            });
        });
        
        location.reload();
    });
    
    }, 1000);
    
}

function logout() {
    window.top.location.href = "logout.php";
}

</script>