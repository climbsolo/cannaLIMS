<?php

include "includes.php";

showheader();

$licenseoptions = "";
$sql = "select tblclients.business_name as business_name, tbllicenses.license_number as license_number from tblclients inner join tbllicenses on tblclients.client_id = tbllicenses.client_id where (tbllicenses.active is null or tbllicenses.active = '' or tbllicenses.active <> 'false') and (tblclients.active is null or tblclients.active = '' or tblclients.active <> 'false') order by tblclients.business_name, tbllicenses.license_number asc";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {        
        $business_name = $row["business_name"];
        $license_number = $row["license_number"];
        $licenseoptions .= "<option value=\"$license_number\">$business_name: $license_number</option>";         
    }
}

?>
<!-- blue theme stylesheet with additional css styles added in v2.0.17 -->
<link rel="stylesheet" href="css/theme.blue.css">
<link rel="stylesheet" href="css/styles.css">

<style>

body, html {
    font-size: 90%;    
}

.legend {
    font-size: 120% !important; 
    color: gray !important;
}

input {
    padding: 2px 5px 2px 5px;
}

.individualdiv fieldset {  
    padding: 1em;
}

select {
    padding:3px;
}

.tablesorter input[type="text"] {
    background: transparent;
    border: none;
    width: 000%;
    min-width: 1%;
    font-size: 85%;
}


.tablesorter span {
    width: 1%;
    display: none;
}


</style>
<html>

<body>

<ul class="nav nav-tabs" role="tablist">    
    <li role="presentation" class="active">
        <a href="#chain_of_custody_admin" aria-controls="reports_admin" role="tab" data-toggle="tab">Chain of Custody</a>
    </li>
    <li role="presentation">
        <a href="#main_admin" aria-controls="main_admin" role="tab" data-toggle="tab">Configurations</a>
    </li>
    <li role="presentation">
        <a href="#customer_portal" aria-controls="main_admin" role="tab" data-toggle="tab">Customer Portal</a>
    </li>
    <li role="presentation">
        <a href="#invoicing_details" aria-controls="invoicing_details" role="tab" data-toggle="tab">Invoicing Details</a>
    </li>
  </ul>

  <!-- Tab panes -->
<div class="tab-content">
<div role="tabpanel" class="tab-pane" id="main_admin"> 

<div class="container-fluid" style="width:50%;min-width:40em;float:left;">
    <div class="centerform listdiv" style="text-align:right;">    
        <fieldset class="fieldset"  style="text-align:left;">
            <legend class="legend">
                Customer Report Views
            </legend>
            <div class="wrapper">
                <div style="float:left;width:38%;padding-left:1%;">                
                    <select id="reports_view_searchby" onchange="showreportsviewsearchfor($(this))" style="width:70%;">
                        <option value=""></option>
                        <option value="sample_id">Sample ID</option>                        
                        <option value="license_number">Client</option>
                    </select>                    
                </div>
                <div style="float:right;text-align:right;width:60%;padding-right:1%;"> 
                    <div style="text-align:left;">
                        <input type="text" id="reports_view_searchfor_sample_id"  class="reports_view_search" style="width:70%;display:none;"></input>
                        <select id="reports_view_searchfor_client" class="reports_view_search" style="width:70%;display:none;" onchange="showreportsviewsearchfor($('#reports_view_searchby'))">
                            <option value=""></option>
                            <?php echo $licenseoptions ?>
                        </select> 
                        <button class="reports_view_search" id="view_reports_btngo" style="display:none;" onclick="getreportsviews()">Go</button>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <div style="margin-top:3em;padding:.5em;">
                    <table class="tablesorter" style="display:none;" id="reports_view_list">                        
                        <thead>
                        
                            <th>Sample ID</th>
                            <th>Potency</th>
                            <th>Residual Solvents</th>
                            <th>Terpenes</th>
                        
                        </thead>                       
                        <tbody>
                        </tbody>                       
                    </table>                   
                </div>
            </div>
        </fieldset>    
    </div>
</div>


<div class="container-fluid" style="width:50%;min-width:30em;float:left;">

    <div style="float:left;margin-right:20px;" class="individualdiv">
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
   
    <div style="float:left;margin-right:20px;" class="individualdiv">
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
    
    <div style="float:left;" class="individualdiv">
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
    
    <div class="individualdiv">
    <div style="float:left;">
    <fieldset class="fieldset">
        <legend class="legend">
            PTHC
        </legend>
        <div style="margin-top:-1em;float:left;">
        <label for="pthcinstrument">Instrument</label>
        <br />
        <select id="pthcinstrument" style="margin-right:.5em;padding:3px;width:9.75em;">
            <option value=""></option>
            <option value="HPLC 1050">HPLC 1050</option>
            <option value="HPLC 1100">HPLC 1100</option>
            <option value="GC 5890">GC 5890</option>
            <option value="GC 6890">GC 6890</option>                    
        </select>
        </div>
        <div style="margin-top:-1em;float:right;">        
        <label for="pthc">Value</label>
        <br />
        <input type="text" style="width:4em;" id="pthc"></input>            
        </div>
        <div style="clear:both;"></div>
    </fieldset>
    </div>
    
    <div style="float:left;">
    <fieldset class="fieldset">
        <legend class="legend">
            Weighboat
        </legend>
        <div style="margin-top:-1em;width:5em">
            <label for="weighboat">Value</label>
            <input type="text" style="width:100%;" id="weighboat"></input>            
        </div>
    </fieldset>
    </div>
    
    </div>
    
    <div style="clear:both;"></div>
    
    <div style="float:left;" class="individualdiv">
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
                    <input type="password" id="customerpassword" style="width:100%;"></input>
                </div>
                <div class="col-sm-6">
                    <label for="customerpassword2">Retype Password</label><br />
                    <input type="password" id="customerpassword2" style="width:100%;"></input>
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
    </fieldset>
    </div>
    
    
    <div style="float:left;width:30em;padding-bottom:4em;" class="individualdiv">
    <fieldset class="fieldset">
        <legend class="legend">
            User Logins
        </legend>
        <div class="row" style="margin-top:-1em;">
            <div class="col-sm-8">
                <label for="userlogin">User Name</label>
                <br />
                <select style="width:100%;" id="userlogin"></select>
                <input type="text" id="newusername" style="display:none;width:100%;"></input>
            </div>
            <div class="col-sm-4" style="margin-top: 1.5em;">
                <div id="userbtns1">
                    <button onclick="newuser()">New</button>
                    <button onclick="deleteuser()">Del</button>
                </div>
                <div id="userbtns2" style="display:none;">
                    <button onclick="cancelnewuser('')">Cancel</button>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:1.5em;">
            <div class="col-sm-6">
                <div style="margin-bottom:1em;">
                    <label for="userfirstname">First Name</label><br />
                    <input type="text" id="userfirstname" class="userdata" style="width:100%;"></input>
                </div>
            </div>
            <div class="col-sm-6">            
                <div style="margin-bottom:1em;">
                   <label for="userlastname">Last Name</label><br />
                    <input type="text" id="userlastname" class="userdata" style="width:100%;"></input>
                </div>
            </div>    
        </div>
        <div class="row">
            <div class="col-sm-8">
                <label for="useremail">Email</label><br />
                <input type="email" id="useremail" class="userdata" style="width:100%;"></input>
            </div>
            <div class="col-sm-4">
                <label for="userrole">Role</label><br />
                <select id="userrole" class="userdata" style="width:100%;">
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
        </div>
        <div class="row" style="margin-top:.5em;">
            <div class="col-sm-8"> 
                <button id="userpassword_btn" onclick="resetpassword()" style="display:none;" onclick="">Reset Password</button>
                <div id="userpassword_div" style="display:none;">
                    <label for="userpassword">Password</label><br />
                    <input type="password" id="userpassword" class="userdata" style="width:100%;margin-bottom:5px;"></input>
                    <br />
                    <button onclick="cancelresetpassword()" id="canceluserpassword_btn">Cancel</button>
                </div>
            </div>           
            <div class="col-sm-4" style="text-align:right;">
                <button id="userloginsapply">Apply</button>
            </div>
        </div>
            
        </div>       
    </fieldset>
</div>    

</div>
  


    <div role="tabpanel" class="tab-pane active" id="chain_of_custody_admin">
 
    <div style="padding:2em;" class="export_csv_div"> 

        <div class="row">
            <div class="col-sm-6" style="padding-top:.5em;">
                <div>
                Search Options: 
                    <select id="searchoptions" onchange="$('#samplelist_div').hide();$('#searchfortext').val('');$('#searchfordate').val('');">
                        <option value="all">All</option>
                        <option value="sample_id">Sample ID</option>
                        <option value="product_name">Product Name</option>                
                        <option value="batch_id">Batch Number</option>
                        <option value="date_accepted">Check In Date</option>            
                    </select>    
               
                    <input type="text" id="searchfortext" class="searchfor"></input>
                    <input type="text" id="searchfordate" class="searchfor datepicker"></input>
                    
                    <button id="btnsearch" onclick="dosearch(1, $('#pagination_limit').val())" class="btn btn-default">Search</button>
                </div>                           
            </div>
            <div class="col-sm-6" style="text-align:right;margin-top:-1.5em;">                
                <div class="pagination"></div>
                <div class="limithtml" style="margin-top:-1.5em;"></div>   
            </div>
        </div>
        <div class="row"> 
        <div class="sm-col-12">        
            <table class="tablesorter zebra" id="samplelist">
                <thead>
                    <tr>
                        <th>Sample ID</th>
                        <th>Product Name</th>                        
                        <th>Location</th>
                        <th>Used Mass</th>
                        <th>Unused Mass</th>
                        <th>Accepted</th>                    
                        <th>Tested</th>                   
                        <th>Reported</th>                    
                        <th>Report Approved</th>                    
                        <th>METRC Entry </th>
                    </tr>
                </thead>
                <tbody> 
                </tbody>
            </table> 
        </div> 
        
        
    </div> 
</div>
</div> 
  
    <div role="tabpanel" class="tab-pane" id="customer_portal">
    
        <iframe id="frame_customerportal" src="customerportal.php" style=";width:100%;height:100%;border:none;"></iframe>     
    </div>    

    <div role="tabpanel" class="tab-pane" id="invoicing_details">
        <div style="padding:2em;">
            <div style="float:left;">
                <label for="invoicing_details_start_date">Start Date: </label>
                <input id="invoicing_details_start_date" class="datepicker" style="margin-right:2em;"></input>            
         
                <label for="invoicing_details_end_date">End Date: </label> 
                <input id="invoicing_details_end_date" class="datepicker" style="margin-right:2em;"></input>

                <button id ="invoicing_details_btnfind" class="btn btn-primary">Find</button>            
            </div>
            <div style="float:right;">
                <a href="#" class="export btn btn-primary">Export Table</a> 
            </div> 
        <div id="invoicing_details_results" class="dvData" style="padding:1em;margin-top:2em;"></div>        
    </div>

</div>

</body>

<?php showfooter() ?>

</html>

<script>

$(document).ready(function() {
    getrfcompounds("allrfcompounds");
    getslopecompounds("allslopecompounds");
    getpthcvalue();
    getweighboatvalue("weighboat");
    getusers("");  

    $(".centerform").fadeIn(250);    

    $("#main_nav", window.parent.document).fadeIn(250);    
    
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
        $.get("adminfunctions.php?calltype=updatepthc&value=" + $("#pthc").val() + "&instrument=" + $("#pthcinstrument").val()); 
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
    
    $("#userlogin").change(function() {
        getuserdata();
    });
    
    $("#userloginsapply").click(function() {
        updateuser();        
    });
    
    $("#searchoptions").change(function() {
        showsearchfor();
    });
    
    
    $("#pthcinstrument").change(function() {
        getpthcvalue();
    });
    
    dosearch(1,25);
    
    $("#userpassword").val("");
    
    $("#invoicing_details_btnfind").click(function() {
        
        $("#invoicing_details_results").html();
       
        var startdate = $("#invoicing_details_start_date").val();
        if (startdate.length < 1) {
            showerrormessage ("Please provide a Start Date.");
            return false;
        }
        var enddate = $("#invoicing_details_end_date").val();
        if (enddate.length < 1) {
            showerrormessage ("Please provide a End Date.");
            return false;
        }
        
        $.get("getinvoicingdetails.php?startdate=" + startdate + "&enddate=" + enddate, 
        function(data) {
            var resort = true; 
            $("#invoicing_details_results").html(data);          
            $(function() {
                $('#invoicing_details_results_table').tablesorter({
                theme : 'blue',
                // initialize zebra striping and resizable widgets on the table
                widgets: [ 'reorder', 'resizable', 'stickyHeaders', 'zebra', 'filter' ],
                widgetOptions: {
                    stickyHeaders : 'tablesorter-stickyHeader',
                    zebra : [ "odd", "even" ],  
                    resizable: true,
                    resizable_addLastColumn : true,
                    reorder_axis        : 'x', // 'x' or 'xy'
                    reorder_delay       : 00,
                    reorder_helperClass : 'tablesorter-reorder-helper',
                    reorder_helperBar   : 'tablesorter-reorder-helper-bar',
                   // reorder_noReorder   : 'reorder-false',
                    reorder_blocked     : 'reorder-block-left reorder-block-end',
                    reorder_complete    : null // callback
                  //widthFixed: false,
                  // These are the default column widths which are used when the table is
                  // initialized or resizing is reset
                  //resizable_widths : [ '10%' ]
                }
                });
            });           
        });
       
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

function getpthcvalue() {
    var instrument = $("#pthcinstrument").val();
    $("#pthc").val("");
    $.get("adminfunctions.php?calltype=getpthc&instrument="+instrument, function(data) {
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
    
    if (licensenumber.length < 1) {
        showerrormessage("Please select a Customer from the dropdown list.");
        return false;
    }
    
    if (pw1.length < 1) {
        showerrormessage("Please provide a Customer Login password");
        return false;
    }
    
    if (pw1 != pw2) {
        showerrormessage("Sorry, the passwords don't match. Please try again");
        $("#customerpassword").val("");
        $("#customerpassword2").val("");
        return false;
    }
    
    
    $.get("adminupdatecustomer.php?licensenumber=" + licensenumber + "&password=" + pw1,
        function(d){
                     
            $("#customerpassword").val("");
            $("#customerpassword2").val("");
            $("#customerlogin").val("");
             
            showsuccessmessage(d);
        });

}

function getusers(user_id) {
    $("#userlogin").empty();
    $("#userlogin").append($('<option>').text(""));    
    $.get("adminfunctions.php?calltype=getusers",
    function(data) {
        if (data.length > 0) {
            var obj = jQuery.parseJSON(data);
            $.each(obj, function(index, item) {   
                $("#userlogin").append(function() {                          
                    return '<option value=' + index + '>' + item.user_name + '</option>';
                });               
            });
            
            $("#userlogin").val(user_id);
            
            getuserdata();
        }
    });   
}

function getuserdata() {
    var user_id = $("#userlogin").val();
    $("#userpassword_div").hide();
    $("#userpassword_btn").hide(); 
        
    if (user_id.length > 0) {
        $("#userpassword_btn").fadeIn(250);
    }
    
    $(".userdata").val("");
    $.get("adminfunctions.php?calltype=getuserdata&user_id="+user_id,
    function(data) {
        //console.log(data);
        if (data.length > 0) {
            var obj = jQuery.parseJSON(data);
            $.each(obj, function(index, item) { 
                $("#" + index).val(item);
            });
        }        
    });
}

function newuser() {
    $("#userlogin").hide();
    $("#userbtns1").hide();
    $(".userdata").val("");
    $("#userpassword_btn").hide();
    $("#canceluserpassword_btn").hide();
    $("#userbtns2").fadeIn(250);
    $("#newusername").fadeIn(250);
    $("#userpassword_div").fadeIn(250);   
}

function cancelnewuser(uid) {   
    $("#userbtns2").hide();
    $("#newusername").hide();
    $("#userpassword_div").hide();
    var user_id = uid;
    if (user_id.length < 1) {
        user_id = $("#userlogin").val();
    }        
    $(".userdata").val("");
    $("#userlogin").fadeIn(250);
    $("#userbtns1").fadeIn(250); 
    getusers(user_id);    
}

function updateuser() {
    
    var user_id = "";
    var user_name = $("#newusername").val();
    
    if ( $("#userlogin").is(":visible") ) {
        user_id = $("#userlogin").val(); 
        user_name = $("#userlogin option:selected").text();      
    }
    
    if (user_name.length < 1) {
        showerrormessage("Please provide a User Name.");        
        return false;
    }
    
    
    var first_name = $("#userfirstname").val();
    if (first_name.length < 1) {
        showerrormessage("Please provide a First Name.");        
        return false;
    }
    
    var last_name = $("#userlastname").val();
    if (last_name.length < 1) {
        showerrormessage("Please provide a Last Name.");        
        return false;
    }
    
    var email = $("#useremail").val();
    if (email.length < 1) {
        showerrormessage("Please provide an Email Address.");        
        return false;
    }
    
    var password = $("#userpassword").val();    
    
    var role = $("#userrole").val();
    if (role.length < 1) {
        showerrormessage("Please provide a User Role.");        
        return false;
    }
            
    $.get("adminfunctions.php?calltype=updateuser&user_id="+user_id+"&first_name="+first_name+"&last_name="+last_name+"&email="+email+"&user_name="+user_name+"&role="+role+"&password="+password,
    function(uid) {
        
        if (uid.indexOf("Sorry") > -1) {
            showerrormessage(uid);
            return false;            
        }
        else
        {
            
            cancelnewuser(uid);
        }
        
        
    });
    
}

function deleteuser() {
    
    showconfirmationmessage("Really Delete this User?", "deleteuserexecute");    
    
}

function deleteuserexecute() {
    $("#confirmation_div").modal('hide'); 
    var user_id = $("#userlogin").val();
    
    if (user_id.length < 1) {        
        return false;
    }
    
    $.get("adminfunctions.php?calltype=deleteuser&user_id="+user_id,
    function() {
        showsuccessmessage("User Removed.");
        cancelnewuser("");
    });   
}
    

function getreportsviews() {

    var searchby = $("#reports_view_searchby").val() + "";
    var searchfor = "";
    if (searchby == "sample_id") {
        searchfor = $("#reports_view_searchfor_sample_id").val() + "";
    }
    
    if (searchby == "license_number") {
        searchfor = $("#reports_view_searchfor_client").val() + "";   
    } 

    if (searchfor.length < 1) {
        return false;
    }
    
    $("#reports_view_list").find('tbody').empty(); 
    $("#reports_view_list").hide();

    
    $.get("adminfunctions.php?calltype=reportsviews&searchfor="+searchfor+"&searchby="+searchby,
    function(d) {  
   
        if (d.length > 0) {
            $("#reports_view_list").find('tbody')
                .empty()
                .append(d);
            var resort = true;           
            $("#reports_view_list").fadeIn(250); 
            $(".tablesorter").trigger("update", [resort]);
        }
    });
}  

function showreportsviewsearchfor(obj) {
    
    $(".reports_view_search").hide();
    $("#reports_view_list").find('tbody').empty();
    $("#reports_view_list").hide();
    
    if (obj.val() == "sample_id") {
        $("#reports_view_searchfor_sample_id").show();
        $("#view_reports_btngo").show();
    }
    
     if (obj.val() == "license_number") {
        $("#reports_view_searchfor_client").show();
        $("#view_reports_btngo").click();
    }
  
}

function getsamples(p, l) {
        
    var searchby = $("#searchoptions").val();
    var searchfor = "";
        
    if (searchby != "all") {
        if (searchby == 'date_accepted') {
            searchfor =  $("#searchfordate").val();
            if (searchfor.length < 1) {
                //showerrormessage("Please provide a Check In Date.");
                return false;                
            }
        }
        else
        {
            searchfor =  $("#searchfortext").val();
            if (searchfor.length < 1) {
                //showerrormessage("Please provide a Search Value.");
                return false;                
            }            
        }
        
        
    }
    
    var limit = l; // the letter "l"
    
    if (limit == null) {
       limit = 25; 
    }
    
    if (limit.length < 1) {
        limit = 25;
    }
    
    var page = p;
    
    if (p == null) {
        p = 1;
    }
    
    if (page.length < 1) {
         page = 1;
    }
        
    var ellimit = $("#pagination_limit"); 
        
    if (ellimit.length) {
        limit = ellimit.val();
    }
    
    //alert(page + " - " + limit);
    
    waitingDialog.show();
    
    $.getJSON("adminfunctions.php?calltype=samplelist&sb=" + searchby + "&sf=" + searchfor + "&page=" + page + "&limit=" + limit, 
    function(d){ 
    
    
    //console.log(d.p_pagination);

        $(".pagination").html(d.pagination);       
        $(".p_pagination", window.parent.document).html(d.p_pagination);
        $(".limithtml").html(d.limithtml);
      
        $("#samplelist").find('tbody')        
            .empty()
            .append(d.tds);
        var resort = true;
         $(".tablesorter span").show();
        $("#pagination_limit").val(limit);
        waitingDialog.hide();
        $(".pagination").fadeIn(250);
        $(".p_pagination", window.parent.document).fadeIn(250);
        $(".limithtml").fadeIn(250);
        $("#samplelist_div").fadeIn(250);
        $(".tablesorter").trigger("update", [resort]);        
    });
    
}

function dosearch(p, l) {
    
    //alert(p + " - " + l);
    getsamples(p, l);
}

function showsearchfor() {
    var searchby = $("#searchoptions").val();
    
    $("#searchfortext").hide();
    $("#searchfordate").hide();
    $(".pagination").hide();
    $(".limithtml").hide();
    
    if (searchby == "all") {
        //$("#btnsearch").hide();
        dosearch($(".currentpage").html(), $('#pagination_limit').val());
        return false;
    }
    
    if (searchby.indexOf("date") > -1) {
        $("#searchfordate").show();
        //$("#btnsearch").fadeIn(250);
        $('#searchfordate').focus();
    }
    else
    {
        $("#searchfortext").show();
        //$("#btnsearch").fadeIn(250);
        $('#searchfortext').focus();
    }

}

function resetpassword() {
    
    $("#userpassword").val("");
    $("#userpassword_btn").hide();
    $("#userpassword_div").fadeIn(250);
    $("#canceluserpassword_btn").fadeIn(250);
}

function cancelresetpassword() {
    
    $("#userpassword").val("");
    $("#userpassword_div").hide();
    $("#canceluserpassword_btn").hide();
    $("#userpassword_btn").fadeIn(250);

}
  
</script>