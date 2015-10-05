<?php

include "includes.php";

showheader();

 $licenseoptions = "";
$sql = "select tblclients.business_name as business_name, tbllicenses.license_number as license_number from tblclients inner join tbllicenses on tblclients.client_id = tbllicenses.client_id where (tbllicenses.active is null or tbllicenses.active = '' or tbllicenses.active <> 'false') order by tblclients.business_name, tbllicenses.license_number asc";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {        
        $business_name = $row["business_name"];
        $license_number = $row["license_number"];
        $licenseoptions .= "<option value=\"$license_number\">$business_name $license_number</option>";         
    }
}


       



?>

<style>

body, html {
    font-size: 100%;    
}

input {
    padding: 0 5px 0 5px;
}

.individiualdiv fieldset {  
    padding: 1em;
}


</style>
<html>

<body>
<div class="container-fluid">

    <div style="float:left;margin-right:20px;" class="individiualdiv">
    <fieldset class="fieldset">
            <legend class="legend">
                Response Factors
            </legend>
            <div style="margin-top:-1em;width:20em;">
            <div style="float:left;">            
                <label for="rfcompound">Compound</label>
                <br />
                <select id="rfcompound" style="padding:3px;width:8em;">
                   
                </select>
                <input type="text" id="rfaddcompound" style="width:8em;display:none;"></input>
                <button id="btnaddrfcompound">New</button>
                <button id="btnapplyrfcompound" style="display:none;">Add</button>
                <button id="btndeleterfcompound" style="margin-left:.5em;">Del</button>
                <button id="btncancelrfcompound" style="margin-left:.5em;display:none;">Cancel</button>
                
            </div>
           
            <div style="float:right;">
                <label for="rfcompoundvalue">Value</label>
                <br />
                <input type="text" id="rfcompoundvalue" style="width:3em;"></input>
            </div>
            </div>
            
            <div style="clear:both;"></div>
                           
        </fieldset>
    </div>
   
    <div style="float:left;margin-right:20px;" class="individiualdiv">
        <fieldset class="fieldset">
            <legend class="legend">
                Slopes
            </legend>
            <div style="margin-top:-1em;width:30em;">
            <div style="float:left;">
                <label for="slopecompoundinstrument">Instrument</label>
                <br />
                <select id="slopecompoundinstrument" style="margin-right:.5em;padding:3px;width:9.75em;">
                    <option value=""></option>
                    <option value="GC 5890">GC 5890</option>
                    <option value="GC 6890">GC 6890</option>                    
                </select>
            </div>
            <div style="float:left;">
                <label for="slopecompound">Compound</label>
                <br />
                <select id="slopecompound" style="padding:3px;width:6em;">
                   
                </select>
                <input type="text" id="slopeaddcompound" style="width:6em;display:none;"></input>
                
                <button id="btnaddslopecompound">New</button>
                <button id="btnapplyslopecompound" style="display:none;">Add</button>
                <button id="btndeleteslopecompound" style="margin-left:.5em;">Del</button>
                <button id="btncancelslopecompound" style="margin-left:.5em;display:none;">Cancel</button>
               
            </div>
            
            <div style="float:right;">
                <label for="slopecompoundvalue">Value</label>
                <br />
                <input type="text" id="slopecompoundvalue" style="width:5em;"></input>    
            </div>
            </div>
            
            <div style="clear:both;"></div>
                           
        </fieldset>
    </div>
    
    <div style="float:left;" class="individiualdiv">
    <fieldset class="fieldset">
        <legend class="legend">
            Dilutions
        </legend>
        <div style="margin-top:-1em;width:14em;">
            <div style="float:left;">
            <label for="dilutionsampletype">Sample Type</label>
            <br />
            <select id="dilutionsampletype" style="margin-right:.5em;padding:3px;width:9.75em;">
                <option value=""></option>
                <option value="Concentrate">Concentrate</option>
                <option value="Flower">Flower</option>
                <option value="Solid Edible">Solid Edible</option>
                <option value="Liquid Edible">Liquid Edible</option>
            </select>
            </div>
            <div style="float:right;">
                <label for="dilutionvalue">Value</label>
                <br />
                <input type="text" style="width:3em;" id="dilutionvalue"></input>  
            </div>            
        </div>
    </fieldset>
    </div>
    
    <div class="individiualdiv">
    <div style="float:left;">
    <fieldset class="fieldset">
        <legend class="legend">
            PTHC
        </legend>
        <div style="margin-top:-1em;width:4em;">
            <label for="pthc">Value</label>
            <input type="text" style="width:4em;" id="pthc"></input>            
        </div>
    </fieldset>
    </div>
    
    <div style="float:left;">
    <fieldset class="fieldset">
        <legend class="legend">
            Weighboat
        </legend>
        <div style="margin-top:-1em;width:4em;">
            <label for="weighboat">Value</label>
            <input type="text" style="width:4em;" id="weighboat"></input>            
        </div>
    </fieldset>
    </div>
    
    </div>
    
    <div style="clear:both;"></div>
    
    <div style="float:left;" class="individiualdiv">
    <fieldset class="fieldset">
        <legend class="legend">
            Customer Logins
        </legend>
        <div style="margin-top:-1em;">
            <label for="customerlogin">Customer</label><br />
            <select style="width:100%;" id="customerlogin">
                <option value=""></option>
                <?php echo $licenseoptions ?>
            </select> 
            <br /><br />
            <div class="row">
                <div class="col-sm-6">
                    <label for="customerpassword">Password</label><br />
                    <input type="password" id="customerpassword" style="width:100%;" ></input>
                </div>
                <div class="col-sm-6">
                    <label for="customerpassword2">Retype Password</label><br />
                    <input type="password" id="customerpassword2" style="width:100%;" ></input>
                </div>
            </div>
            <div class="row" style="margin-top:.5em;">
                <div class="col-sm-8">                
                </div>
                <div class="col-sm-4" style="text-align:right;">
                    <button id="customerloginsapply">Apply</button>
                </div>
            </div>
            
            </div>
        </div>
    </fieldset>
    </div>
    
</div>

</body>

<?php showfooter() ?>

</html>

<script>

$(document).ready(function() {
    getrfcompounds("allrfcompounds");
    getslopecompounds("allslopecompounds");
    getpthcvalue("pthc");
    getweighboatvalue("weighboat");
    $("#rfcompound").change(function() {
        getrfvalue("rfcompounds", $(this).val());
        
    });
    $("#slopecompound").change(function() {
        getslopevalue("slopecompounds", $(this).val());
        
    });
    $("#slopecompoundinstrument").change(function() {
        getslopecompounds("allslopecompounds");
    });
    
    $("#dilutionsampletype").change(function() {
        getdilutionvalue("getdilutionvalue", $("#dilutionsampletype").val());
    });
    
    $("#rfcompoundvalue").change(function() {        
        if (($("#rfcompound").val().length) > 0) {           
            $.get("adminfunctions.php?calltype=updaterfcompound&searchfor=" + $("#rfcompound").val() + "&value=" + $("#rfcompoundvalue").val());
        }        
    });
    
    $("#pthc").change(function() {
        $.get("adminfunctions.php?calltype=updatepthc&value=" + $("#pthc").val()); 
    });
    
    $("#weighboat").change(function() {
        $.get("adminfunctions.php?calltype=updateweighboat&value=" + $("#weighboat").val()); 
    });
    
    $("#dilutionvalue").change(function() {
        if ($("#dilutionsampletype").val().length > 0) {
        $.get("adminfunctions.php?calltype=updatedilution&searchfor=" + $("#dilutionsampletype").val() + "&value=" + $("#dilutionvalue").val()); 
        }
    });
    
    $("#slopecompoundvalue").change(function() {
        var instrument = $("#slopecompoundinstrument").val();
        if (($("#slopecompound").val().length) > 0) {
            $.get("adminfunctions.php?calltype=updateslopecompound&searchfor=" + $("#slopecompound").val() + "&value=" + $("#slopecompoundvalue").val() + "&instrument=" + instrument);
        }
    });
    
    $("#btnaddrfcompound").click(function() {
        $("#rfaddcompound").val("");
        $("#rfcompoundvalue").val("");
        $(this).hide();
        $("#rfcompound").hide();
        $("#btndeleterfcompound").hide();
        $("#rfaddcompound").show();
        $("#btnapplyrfcompound").show();
        $("#btncancelrfcompound").show();
        
    });
    
    $("#btncancelrfcompound").click(function() {
        getrfcompounds("allrfcompounds");        
    });
    
    $("#btnapplyrfcompound").click(function() {
        applyrfcompound();
    });
    
    $("#btndeleterfcompound").click(function() {
        var c = confirm("Are you SURE?!");    
        if (c) {
            $.get("adminfunctions.php?calltype=deleterfcompound&searchfor=" + $('#rfcompound').val(), function() {
            getrfcompounds("allrfcompounds");
            });
        }
    });
    
     $("#btnaddslopecompound").click(function() {
         $("#slopecompoundvalue").val("");
        $("#slopeaddcompound").val("");
        $(this).hide();
        $("#slopecompound").hide();
        $("#btndeleteslopecompound").hide();
        $("#slopeaddcompound").show();
        $("#btnapplyslopecompound").show();
        $("#btncancelslopecompound").show();       
    });
    
    $("#btncancelslopecompound").click(function() {
        getslopecompounds("allslopecompounds");        
    });
    
    $("#btnapplyslopecompound").click(function() { 
        applyslopecompound();
    });
    
    $("#btndeleteslopecompound").click(function() {
        var c = confirm("Are you SURE?!");    
        if (c) {
            $.get("adminfunctions.php?calltype=deleteslopecompound&searchfor=" + $('#slopecompound').val() + "&instrument=" + instrument, function() {
            getslopecompounds("allslopecompounds");
            });
        }
    });
    
    $("#customerloginsapply").click(function() {
        customerloginsapply();
    });
    
});       
        
function getrfcompounds(calltype) {
    $("#rfcompound").empty();
    $.get("adminfunctions.php?calltype=" + calltype, function(data) {
         if (data.length > 0) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {            
            $("#rfcompound").append(function() {                          
                return '<option value=' + item.val + '>' + item.text + '</option>';
            });
        });
        $("#rfcompoundvalue").val(getrfvalue('rfcompounds', $("#rfcompound").val())); 
        }        
        cancelnewrf();        
    });
} 

function getrfvalue(calltype, searchfor) {
    $.get("adminfunctions.php?calltype=" + calltype + "&searchfor=" + searchfor, function(data) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {
            $("#rfcompoundvalue").val(item);            
        });        
    });
}  

function getpthcvalue(calltype) {
    $.get("adminfunctions.php?calltype=" + calltype, function(data) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {
            $("#pthc").val(item);            
        });        
    });
} 

function getweighboatvalue(calltype) {
    $.get("adminfunctions.php?calltype=" + calltype, function(data) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {
            $("#weighboat").val(item);            
        });        
    });
}   

function getslopecompounds(calltype) {    
    $("#slopecompound").empty();
    var instrument = $("#slopecompoundinstrument").val();    
    if (instrument.length < 1) {
        cancelnewslope();
        return false;
    }
    $.get("adminfunctions.php?calltype=" + calltype + "&instrument=" + instrument, function(data) {
       if (data.length > 0) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {          
            $("#slopecompound").append(function() {                          
                return '<option value=' + item.val + '>' + item.text + '</option>';
            });
        });
        $("#slopecompoundvalue").val(getslopevalue('slopecompounds', $("#slopecompound").val()));
       }
        cancelnewslope();         
    });
} 

function getslopevalue(calltype, searchfor) {
    var instrument = $("#slopecompoundinstrument").val();
    $.get("adminfunctions.php?calltype=" + calltype + "&searchfor=" + searchfor + "&instrument=" + instrument, function(data) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {
            $("#slopecompoundvalue").val(item);            
        });        
    });
}

function getdilutionvalue(calltype, searchfor) {
    var sampletype = $("#dilutionsampletype").val();
    $.get("adminfunctions.php?calltype=" + calltype + "&searchfor=" + searchfor  + "&sampletype=" + sampletype, function(data) {
        var obj = jQuery.parseJSON(data);
        $.each(obj, function(index, item) {
            $("#dilutionvalue").val(item);            
        });        
    });
}
 
$("#btnaddrfcompound").click(function() {
    $(this).hide();
    $("#rfcompound").hide();
    $("#rfcompound").val("");
    $("#btndeleterfcompound").hide();
    $("#rfaddcompound").show();
    $("#btnapplyrfcompound").show();
    $("#btncancelrfcompound").show();
});

$("#btnaddslopecompound").click(function() {
    $(this).hide();
    $("#slopecompound").hide();
    $("#slopecompound").val("");
    $("#btndeleteslopecompound").hide();
    $("#slopeaddcompound").show();
    $("#btnapplyslopecompound").show();
    $("#btncancelslopecompound").show();
});
 
function cancelnewrf() {    
     $("#rfaddcompound").hide();
     $("#btnapplyrfcompound").hide();
     $("#btncancelrfcompound").hide();
     $("#btnaddrfcompound").show();
     $("#rfcompound").show();
     $("#btndeleterfcompound").show();
}

function cancelnewslope() {    
     $("#slopeaddcompound").hide();
     $("#btnapplyslopecompound").hide();
     $("#btncancelslopecompound").hide();
     $("#btnaddslopecompound").show();
     $("#slopecompound").show();
     $("#btndeleteslopecompound").show();
}

function applyrfcompound() {               
    $.get("adminfunctions.php?calltype=updaterfcompound&searchfor=" + $('#rfaddcompound').val() + "&value=" + $("#rfcompoundvalue").val(),
    function() {
    getrfcompounds("allrfcompounds");
    });
}

function applyslopecompound() {
    var instrument = $("#slopecompoundinstrument").val();      
    $.get("adminfunctions.php?calltype=updateslopecompound&searchfor=" + $('#slopeaddcompound').val() + "&value=" + $("#slopecompoundvalue").val() +"&instrument=" + instrument,
    function() {
    getslopecompounds("allslopecompounds");
    });
}

function customerloginsapply() {
    var pw1 = $("#customerpassword").val();
    var pw2 = $("#customerpassword2").val();
    var licensenumber = $("#customerlogin").val();
    var displayname = $("#customerlogin :selected").text();
    
    if (licensenumber.length < 1) {
        alert("Please select a Customer from the dropdown list.");
        return false;
    }
    
    if (pw1.length < 1) {
        alert("Please provide a Customer Login password");
        return false;
    }
    
    if (pw1 != pw2) {
        alert("Sorry, the passwords don't match. Please try again");
        $("#customerpassword").val("");
        $("#customerpassword2").val("");
        return false;
    }
    
    $.get("adminupdatecustomer.php?licensenumber=" + licensenumber + "&password=" + pw1 + "&displayname="+displayname,
        function(d){
            $("#customerpassword").val("");
            $("#customerpassword2").val("");
            $("#customerlogin").val("");
             
            showsuccessmessage(d);
        });

}
</script>