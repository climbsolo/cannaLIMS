<?php 

include "includes.php";

showheader();

?>

<style>

body {
    overflow-y: scroll;
}

a {
    /* text-shadow: .5px .5px 1px green; */
    color: blue;
}


hr {
    color: black;
    border-color: black;
}

#documents {
    display: none;
}

.container {
    width: 100% !important;
    margin: 0 !important;
    padding: 2em !important;
}

.centerform {    
    margin: 1em auto;
   /*
   background-color: white;
    border: 1px black solid;
    margin: auto;
    padding: 0 2em 2em 2em;
    -webkit-border-radius: 15px;
    -moz-border-radius: 15px;
    border-radius: 15px;
    overflow: hidden;
    display: none;
    opacity: 0.9;
    margin-top: 1.75em;
    */
}

.searchfor {
    display: none;
}

select {
    padding: 3px;
}

.form-group {
    margin: 0 25px 25px 0;
}

#managedistributionlistdiv input {
    padding: 2px;
    width: 100%;
    margin-bottom: .5em;
}    

</style>

<body>
<div class="container">

    <div class="row">
        <div class="col-lg-6" style="text-align:left;">
            <a href="http://www.cannasys.com/"><img src="images\logo.png" style="width:300px;" /></a>
        </div>
        <div class="col-lg-6" style="text-align:right;">           
            <a href="http://cannalims.com" target="_blank"><img src="..\common\images\poweredbylogo.png" style="width:200px;" /></a>
        </div>
    </div>
    
    <div id="login_div" style="text-align:center;max-width:60em;" class="centerform">
        <h2>Login</h2>
        <p>Please login to access your documents. If you do not have an account, please contact us to have one set up for you.</p>
        <form class="form-inline">
            <div class="form-group">
                <label for="loginlicensenumber">License Number:</label>
                <input type="text" class="form-control login" id="loginlicensenumber" placeholder="Enter License Number">
            </div>
            <div class="form-group">
                <label for="loginpassword">Password:</label>
                <input type="password" class="form-control login" id="loginpassword" placeholder="Enter password">
            </div>
            <div class="checkbox" style="display:none;">
                <label><input type="checkbox"> Remember me</label>
            </div>
            <button type="button" onclick="customerlogin()" id="btncustomerlogin" class="btn btn-default">Submit</button>
        </form>

    </div>


    <div id="documents">
    <div class="row">
    <div class="col-sm-10">
        <div id="searchoptionsdiv">
            Search Options: 
            <select id="searchoptions">
                <option value="all">All</option>
                <option value="metrcnumber">METRC Number</option>
                <option value="productname">Sample Name</option>
                <option value="sampleid">Sample ID</option>
                <option value="batchid">Batch Number</option>
                <option value="adatecheckedin">Check In Date</option>            
            </select> 
            <input type="text" id="searchfortext" class="searchfor"></input>
            <input type="text" id="searchfordate" class="searchfor datetimepicker"></input>            
            <button id="btnsearch" onclick="dosearch()">Search</button>
        </div>    
    </div>

    
    <div class="col-sm-2" style="text-align:right;">
        <button id="btncustomerlogout" onclick="customerlogout()" class="btn btn-default">Logout</button>
    </div>
    </div>
       <input id="currentcount" style="display:none;"></input>
        
        <div class="centerform" id="docdiv">
        
        <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#documentlist" aria-controls="documentlist" role="tab" data-toggle="tab" onclick="$('#searchoptionsdiv').show();">Reports</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" onclick="getdistributionlist(),$('#searchoptionsdiv').hide();">Profile</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="documentlist"></div>
    
    <div role="tabpanel" class="tab-pane" id="profile">
    
        <div class="row">
            <div class="col-lg-6">
            
                <h3 style="text-align:center;">Manage Distribution List <button id="btnadddistributionlistemail"class="btn btn-primary" style="font-size:80%;margin-left:1em;">Add New</button></h3>
                <div id="managedistributionlistdiv" style="display:none;">                
                    <fieldset class="fieldset" style="padding:1em;">
                        <legend class="legend">
                            Add New Member
                        </legend>
                
                
                        <label for="dlemail">Email Address: </label><br />
                        <input type="email" id="dlemail"></input>
                        <br />
                        <div style="float:right;">
                            <button id="btnapplydistributionlistemail" class="btn btn-primary">Apply</button>
                            <button id="btncanceladddistributionlistemail" class="btn btn-default">Cancel</button>
                        </div>
                    </fieldset>
                </div>
                
                <div id="distributionlist"></div>
            
            </div>
            
            <div class="col-lg-6">
            
            
            </div>
        
        </div>
    
    </div>
  </div>

</div>
      
       
    </div>
</div>
</body>
</html>


<?php showfooter(); ?>


<script>

$(document).ready(function() {
    
    showdivs();
    
    $("#btnadddistributionlistemail").click(function() {
        showadddistributionlistitem();
    });
    
    $("#btnapplydistributionlistemail").click(function() {
        applydistributionlistitem();       
    });
    
    $("#btncanceladddistributionlistemail").click(function() {
        hideadddistributionlistitem();         
    });
    
    $('.login').keypress(function(event) {
        if (event.keyCode == 13) {
            customerlogin();
            return false;
        }
    });
    
    $('#searchfortext').keyup(function(event) {
        if (event.keyCode == 13) {
            dosearch();
            return false;
        }
    });
    
    $("#searchoptions").change(function() {     
        $("#documentlist").html("");        
        $("#searchfortext").val("");
        $("#searchfordate").val("");
        $("#currentcount").val("");
        showsearchfor();
        if ($(this).val() == "all") {
            dosearch();
        }
    });
    
    $("#searchoptions").val("all");
    $("#documentlist").html("");        
    $("#searchfortext").val("");
    $("#searchfordate").val("");
    $("#currentcount").val(0);
   
    $(window).scroll(function() {
        if($(window).scrollTop() + window.innerHeight == getDocHeight()) {          
           getdocuments();
        }
    });
       
});

function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}

function customerlogin() {

    var licensenumber = $("#loginlicensenumber").val();
    if (licensenumber.length < 1) {
        showerrormessage("Please enter your License Number as it was provided to you.");
        return false;
    };
    
    var password = $("#loginpassword").val();
    if (password.length < 1) {
        showerrormessage("Please enter your Password.");
        return false;
    };

    $.post("customerlogin.php", {licensenumber: licensenumber, password: password}, 
    function(d) { 
      
        if (d != "success") {            
            showerrormessage("Invalid License Number or Password. Please try again");
        }
        else
        {
           $("#login_div input").val("");
           $("#login_div").hide();
           $("#documents").fadeIn(200);
           dosearch();
        }
        
    });             
    
}

function showdivs() {
    
    var cluid = "";
    
    <?php
        if (isset($_SESSION["cluid"])) {
     ?>    
        cluid = "<?php echo $_SESSION["cluid"] ?>";
    <?php
        }
    ?>
    
    if (cluid.length < 1) {
        $("#documents").hide();
        $("#login_div").show();
        $("#loginlicensenumber").focus();
    }
    else
    {
        $("#login_div").hide();
        $("#documents").show();
        dosearch();        
    }
}

function customerlogout() {
    $.get("customerlogout.php", function(){
        //showsuccessmessage ("Logout Successful!");
        //location.reload();
    });    
}

function getdocuments() {
    
     var currentcount = $("#currentcount").val();
     
     if (currentcount == "done") {
         return false;
     }
    
    var searchby = $("#searchoptions").val();
    
if (searchby == "adatecheckedin") {
    var searchfor = $("#searchfordate").val();
}
else
{
    var searchfor = $("#searchfortext").val();    
}
   
   if (searchby != "all" && searchfor.length < 1) {
       return false;
   };
       
    $.post("customergetdocuments.php", {searchby: searchby, searchfor: searchfor, currentcount: currentcount}, 
    function(data) {
                
        var d = data.split("###@@CANNALIMSSPLITTER@@###");
              
        $("#currentcount").val(d[0]);
        
        $("#documentlist").html($("#documentlist").html() + d[1]);
        $("#docdiv").fadeIn(1000);
    });   
}

function showsearchfor() {
    
    var searchoption = $("#searchoptions").val();
    
    if (searchoption == "all") {   
        $("#searchfortext").hide();
        $("#searchfordate").hide();
        $("#searchfortext").val("");
        $("#searchfordate").val("");     
        return false;
    }
    
    if (searchoption == "adatecheckedin") {        
        $("#searchfortext").hide();
        $("#searchfordate").show();
        $("#searchfortext").val("");
        $("#searchfordate").val("");
    }
    else
    {
        $("#searchfortext").show(); 
        $("#searchfordate").hide();
        $("#searchfortext").val("");
        $("#searchfordate").val("");        
    }
    
}


function dosearch() {
        
    $.when( $('#documentlist').html('') ).done(function(d) {        
        $.when( $('#currentcount').val(0)  ).done(function(d1) {
            getdocuments();
        });
    });
}

function getdistributionlist() {    
    $.post("customergetdistributionlist.php", {},
    function(data) {
        $("#distributionlist").html(data);      
    });   
}

function showadddistributionlistitem() {    
    $("#managedistributionlistdiv").fadeIn(500);
}

function hideadddistributionlistitem() {    
    $("#managedistributionlistdiv").fadeOut(250);
    $("#managedistributionlistdiv input").val("");    
} 

function applydistributionlistitem() {
    
    var email = $("#dlemail").val();
    if (email.length < 1) {
        showerrormessage ("Please provide an Email Address.");
        $("dlemail").focus();
        return false;
    }
    
    if (!validateEmail(email)) {
        showerrormessage ("Please provide a VALID Email Address.");
        $("dlemail").focus();
        return false;   
    }
    
     //waitingDialog.show();
    
    $.post("customeradddistributionlistitem.php", {email: email},
    function(data) {
        $("#distributionlist").html(data); 
        hideadddistributionlistitem();
        getdistributionlist();
        //waitingDialog.hide();        
    });   
}

function deletedistributionlistitem(id) {    
    var x = confirm("Are you SURE?");
    if (x) {
        $.post("customerdeletedistributionlistitem.php", {id: id},
        function(data) {
            getdistributionlist();      
        });
    }   
}

function validateEmail(email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( email );
}

</script>
