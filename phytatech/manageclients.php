<?php include "includes.php" ?>

<html>

<?php showheader(); ?>

<!-- blue theme stylesheet with additional css styles added in v2.0.17 -->
<link rel="stylesheet" href="css/theme.blue.css">
<link rel="stylesheet" href="css/styles.css">

<body style="display:none;">

    <input id="currentcount" style="display:none;"></input>
     
    <div id="controlsdiv" style="padding:0 1em 0 1em;">
        <div class="row">
            <div class="col-sm-10">
                Search Options: 
                <select id="searchoptions" onchange="$('#clientlist_div').hide();$('#searchfortext').val('');$('#searchfordate').val('');">
                    <option value="all">All</option>
                    <option value="business_name">Business Name</option>
                    <option value="email">Email Address</option>
                </select>    
           
                <input type="text" id="searchfortext" class="searchfor"></input>
                <input type="text" id="searchfordate" class="searchfor datetimepicker"></input>
                
                <button id="btnsearch" onclick="dosearch()" style="display:none;" class="btn btn-default">Search</button>
               
            </div> 
                        
            <div class="col-sm-2" style="text-align:right;margin-top:.5em;">
                <button id="btnewclient" onclick="editclient('')" class="btn btn-primary">New Client</button>
            </div>
        </div>                 
        <h1>Manage Clients</h1> 
    </div>   

    
    <div id="controlsdiv2" style="padding:0 5em 0 5em;display:none;">
        <div class="row">
            <div class="col-sm-2">
                <button id="btnclientdelete" onclick="showdeleteclient()" class="btn btn-danger admin_restricted mainbtn" style="margin-bottom:.5em;">Delete Record</button>
            </div>        
            <div class="col-sm-8">
                
            </div>       
            <div class="col-sm-2" style="text-align:right;">               
                <button id="btclientapply" onclick="updateclient()" class="btn btn-primary mainbtn" style="margin: 0 7em .5em 2em;">Done</button>               
            </div>
        </div>   
    </div>
    

<div id="clientlist_div" class="centerform listdiv">    
    <div>
        <table class="tablesorter" id="clientlist">
            <thead>
                <tr>                    
                    <th>Business Name</th>
                    <th>Business Address</th>
                    <th>Email Address</th>
                    <th>Business Phone</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
    </div>
</div>

<div id="manageclient_div" class="centerform">    
    <input type="text" style="display:none" id="manageclientid"></input> 
    
        <div class="row manage_client_div">            
            <div class="col-sm-4">               
                <label for="manageclient_business_name">*Business Name: </label>
                <br />
                <input type="text" id="manageclient_business_name"  class="form-control clientdata required"></input>
            </div>
            <div class="col-sm-4">
                <label for="manageclient_email">Email Address: </label>
                <br />
                <input type="text" id="manageclient_email" class="form-control clientdata"></input>
            </div>
            <div class="col-sm-4">
                <label for="manageclient_business_phone">Phone Number: </label>
                <br />
                <input type="text" id="manageclient_business_phone" class="form-control clientdata phone_us"></input>
            </div>
        </div>  
        
        <div style="clear:both;margin-top:2em;"></div>
        
        
        
        <div class="row manage_client_div">            
            <div class="col-sm-4"> 
                <label for="manageclient_business_address1">Address: </label>
                <br />
                <input type="text" id="manageclient_business_address1" class="form-control clientdata"></input>
            </div>
            <div class="col-sm-1"> 
                <label for="manageclient_business_address2">Ste/Apt: </label>
                <br />
                <input type="text" id="manageclient_business_address2" class="form-control clientdata"></input>
            </div>
             <div class="col-sm-3"> 
                <label for="manageclient_business_city">City: </label>
                <br />
                <input type="text" id="manageclient_business_city" class="form-control clientdata"></input>
            </div>
             <div class="col-sm-2"> 
                <label for="manageclient_business_state">State: </label>
                <br />
                <?php echo statelist("manageclient_business_state") ?>                
            </div>
             <div class="col-sm-2"> 
                <label for="manageclient_business_zip">ZIP: </label>
                <br />
                <input type="text" id="manageclient_business_zip" class="form-control clientdata cep"></input>
            </div>           
        </div>
        
        <div class="row"> 
            <div class="col-sm-6" id="managelicenses_div">        
            <fieldset class="fieldset" style="margin: 2em 0;">
                <legend class="legend">
                    Licenses
                </legend>                                
                    <div class="col-sm-12" style="margin-top:-1.5em;">                                 
                        <div id="licenselist_div">
                            <table class="tablesorter" id="licenselist">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Type</th>
                                        <th>Expiration</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody> 
                                </tbody>
                            </table>
                        </div>
                        <div id="addlicense_div" style="display:none;">
                            <div class="row">
                                <div class="col-sm-6" style="padding:1em;margin-top:2.5em;">                                    
                                    <label for="managelicense_license_type">License Type:</label>
                                    <br />
                                    <select id="managelicense_license_type" class="form-control licensedata">
                                        <option value="Grower">Grower</option>
                                        <option value="MIPS">MIPS</option>
                                        <option value="Retailer">Retailer</option>                                    
                                    </select>
                                    <br />
                                    <label for="managelicense_license_number">License Number:</label>
                                    <br />
                                    <input type="text" id="managelicense_license_number" class="form-control licensedata"></input>
                                    <br />
                                    <label for="managelicense_date_expiration">Expiration Date:</label>
                                    <br />
                                    <input type="text" id="managelicense_date_expiration" class="datepicker form-control licensedata"></input>
                                    <br />
                                    <label for="managelicense_hide_total_potential_cannabinoids_on_reports">Hide Total Potential Cannabinoids on Reports:</label>
                                    <select id="managelicense_hide_total_potential_cannabinoids_on_reports" class="form-control licensedata">
                                        <option value=""></option>
                                        <option value="True">True</option>                                    
                                    </select>
                                </div>
                                <div class="col-sm-6" style="padding:1em;">
                                <div style="text-align:right;padding-right:2em;margin-top:0em">
                                    <button id="btnapplylicense" style="display:none;" class="btn btn-primary">Apply</button>
                                    <button id="btncancellicense" style="display:none;" class="btn btn-default">Cancel</button>
                                </div>
                                
                                <div id="pricing_div">
                                
                                
                                </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>               
                    <div class="row" style="text-align:right;padding-right:2em;margin-top:-2em">
                        <button id="btnnewlicense" class="btn btn-primary">New</button>
                       
                    </div>
                
            </fieldset>
            </div>
             
            <div id="managelicense_license_image_div" class="col-sm-6" style="display:none;text-align:center;padding:1em;">           
            
                <div id="btnupload" style="text-align:center;">
                    <div id="fileuploader" class="bcbutton" style="margin: 2em auto 1em auto;width:10em;float:left;"></div>
                    <div style="float:right;margin-left:2em;">
                        <button id="managelicense_btn_clear_license_image" style="margin: 2em auto 1em auto;" class="btn btn-default">Clear Image</button>
                    </div>
                </div>
                <div style="clear:both;"></div>
               
                
               
                    <img id="managelicense_license_image" src="" style="width:100%;border:none;"></img>
                
                   
            
            </div>
        </div>
        
</div>

</body>

<?php showfooter(); ?>

<script>

$(document).ready(function() {    
    $("#searchoptions").change(function() {
        showsearchfor();
    });

    dosearch();
       
    $('#searchfortext').keypress(function(event) {
        if (event.keyCode == 13) {
            $("#btnsearch").click();
            return false;
        }
    });
    
    $("#btnnewlicense").click(function() {
        $("#licenselist_div").hide();        
        $(".licensedata").val("");
        $(".pricingdata").val("");
        $(".mainbtn").hide();
        $(".manage_client_div").hide();
        
        $.getJSON("managelicensesfunctions.php?qtype=editlicense&license_id=",
        function(data1) { 
        
            $.each( data1, function( key, val ) {
                
               if (key == "strtests") {
                    $("#pricing_div").html(val);               
                }
            });
        });
        $("#addlicense_div").show();
        $("#managelicense_license_image").prop("src", "");
        $("#managelicense_license_image_div").show();
        $(this).hide();
        getlicenses($("#manageclientid").val());
        $("#btnapplylicense").show();
        $("#btncancellicense").show();       
    });
    
    $("#btnapplylicense").click(function() {
        addlicense();        
    });
    
    $("#btncancellicense").click(function() {
        $("#addlicense_div").hide();
        $("#managelicense_license_image").prop("src", "");
        $("#managelicense_license_image_div").hide();
        $(".licensedata").val("");
        $("#licenselist_div").show();
        $("#btnapplylicense").hide();
        $("#btncancellicense").hide();
        $("#btnnewlicense").show();
        $(".mainbtn").show();
        $(".manage_client_div").fadeIn(250);
    });
    
    $("#managelicense_btn_clear_license_image").click(function() {
        $("#managelicense_license_image").removeAttr('src');
    });
    
    
    
    if ( $("#manageclient_div").is(":visible") ) {
        $("#main_nav", window.parent.document).hide();      
    }
    else
    {
        $("#main_nav", window.parent.document).fadeIn(250);
    }
    
    fileuploader();
        
});

function fileuploader(license_id) {
    
	var running = 0;  
	var uploader = new qq.FileUploader({
		element: document.getElementById('fileuploader'),
		action: 'fileupload.php',
        data: {license_id:license_id},
		debug: true,
		allowedExtensions: ['jpg', 'bmp', 'png', 'tif'],
		sizeLimit: 50000000, 
		onSubmit: function(id, fileName){            
			running++; 	
            waitingDialog.show();
            uploader.setParams({
                license_id: license_id
            });            
		},
		onComplete: function(id, fileName, responseJSON){
            running--;
			if(running < 1){         
				waitingDialog.hide();
                              
                $("#managelicense_license_image").prop("src", "uploads/" + license_id + "/" + fileName);
                $("#managelicense_license_image_path").val("uploads/" + license_id + "/" + fileName);
                $("#managelicense_license_image_div").fadeIn(250);
			}
						
		}
	});
}

function dosearch() {
    getclients();
}

function getclients() {
       
    var searchby = $("#searchoptions").val();
    var searchfor = "";
    
    if (searchby != "all") {       
        searchfor = $("#searchfortext").val();
        if (searchfor.length < 1) {
            showerrormessage("Please provide a Search Value.");
            return false;                
        }
    }  
    
    $.getJSON("manageclientsfunctions.php?qtype=clientlist&sb=" + searchby + "&sf=" + searchfor, function(d){       
        $("#clientlist").find('tbody')
            .empty()
            .append(d.tds);
        var resort = true;
        $("#clientlist_div").fadeIn(250); 
        $("#clientlist").trigger("update", [resort]);         
                
    });

}
  
function showsearchfor() {
    var searchby = $("#searchoptions").val();
    
    $("#searchfortext").hide();
    $("#searchfordate").hide();
    
    if (searchby == "all") {
        $("#btnsearch").hide();
        dosearch();
        return false;
    }
    
    if (searchby.indexOf("date") > -1) {
        $("#searchfordate").show();
        $("#btnsearch").fadeIn(250);
        $('#searchfordate').focus();
    }
    else
    {
        $("#searchfortext").show();
        $("#btnsearch").fadeIn(250);
        $('#searchfortext').focus();
    }

}

function showupdateclient(x) {
    
    $("#btnclientdelete").hide();
    
    if ($("#manageclientid").val().length > 0) {
        $("#btnclientdelete").show();
    }    
    
    if (x == 'true') {
        $("#controlsdiv2").hide();  
        $("#manageclient_div").hide();
        clearclientdata();
        $("#btncancellicense").trigger("click");        
        $("#controlsdiv").fadeIn(250); 
        $("#clientlist_div").fadeIn(250);
        $("#main_nav", window.parent.document).fadeIn(250);        
    }
    else
    {
        $("#controlsdiv").hide(); 
        $("#clientlist_div").hide();        
        $("#controlsdiv2").fadeIn(250);
        $("#manageclient_div").fadeIn(250);
        $("#main_nav", window.parent.document).hide();       
    }

}

function editclient(id) {
    
    //$("#manageclientid").val(id);
     
    /*
    if (id.length > 0 ) { 
        $.getJSON("manageclientsfunctions.php?qtype=getclientdata&client_id=" + id, 
        function(data) {
            
            $.each( data, function( key, val ) {
                $("#manageclient_" + key).val(val);
            });
            getlicenses(id);
            showupdateclient();  
        });
    }
    else
    {
        showupdateclient();
    }
    */
    
    var client_id = id;
    
    if (client_id.length < 1) {
        $.getJSON("manageclientsfunctions.php?qtype=newclient", 
        function(data) {            
            $.each( data, function( key, val ) {               
                if (key == "client_id") {
                   client_id = val; 
                }               
            });  
            $("#manageclientid").val(client_id);
            getlicenses(client_id);
            showupdateclient();          
        });
    }
    else
    {   
        $("#manageclientid").val(client_id);  
        $.getJSON("manageclientsfunctions.php?qtype=getclientdata&client_id=" + client_id, 
        function(data) {          
            $.each( data, function( key, val ) {
                $("#manageclient_" + key).val(val);
            });
            getlicenses(client_id);
            showupdateclient();  
        }); 
    }
   
}

function updateclient() {
    
    item = {};
    var id = "";
    var val = "";
    $(".required").removeClass("errorstyle");
    
    if ($("#manageclient_business_name").val().length < 1) {
        showerrormessage("Please provide a Business Name");        
        $("#manageclient_business_name").addClass("errorstyle");
        return false;       
               
    }
    
    if ($('#licenselist tbody tr').length < 1) {
        showerrormessage("At least one License is required");
        return false;
    }
   
    item ["client_id"] = $("#manageclientid").val();
    item ["business_state"] = $("#manageclient_business_state").val();
   
    $(".clientdata").each(function() {    
        id = $(this).prop("id");
        id = id.replace("manageclient_", "");
        val = $(this).val();        
        item[id] = val;       
    });
      
    var json =  encodeURIComponent(JSON.stringify(item)); 
            
    $.get("manageclientsfunctions.php?qtype=updateclient&json=" + json,
    function(d) {    
        //console.log(d);
        getclients();
    });
    
    
    showupdateclient('true');
}

function clearclientdata() {
    $("#manageclientid").val("");
    $("#manageclient_business_state").val("CO");
    $(".clientdata").val("");
}

function showdeleteclient() {
    showconfirmationmessage("Delete this Client?", 'deleteclient');
    
}

function deleteclient() {
    
    $("#confirmation_div").modal('hide'); 
    
    var id = $("#manageclientid").val();
        
    $.get("manageclientsfunctions.php?qtype=deleteclient&client_id=" + id,
    function() {
        getclients();
        showupdateclient('true'); 
    });
    
}

function getlicenses(client_id) {
    
    $.getJSON("managelicensesfunctions.php?qtype=getlicenses&client_id=" + client_id,
    function(d) {
        
        var html = d.tds;
        
        if (html.length < 1) {
            $("#licenselist").hide(); 
        }
        else
        {
            $("#licenselist").find('tbody')
                .empty()
                .append(d.tds);
            var resort = true;
            $("#licenselist").trigger("update", [resort]); 
            $("#licenselist").show();
        }
        
    });
}

function addlicense() {
    
    var id = $("#manageclientid").val();    
    
    var license_type = $("#managelicense_license_type").val() + "";
      
    if (license_type.length < 1 || license_type == "null") {
        showerrormessage ("Please provide a License Type.");
        return false;
    }
    
    var license_number = $("#managelicense_license_number").val();
    if (license_number.length < 1) {
        showerrormessage ("Please provide a License Number.");
        return false;
    }    
    
    var date_expiration = $("#managelicense_date_expiration").val();
    
    var pricingfail = "";
    
    $(".pricingdata").each(function() {
        if ($(this).val().length < 1) { 
           pricingfail = $(this).prop("id");
        }
        
    });   
        
    if (pricingfail != "") {
        showerrormessage ("ALL Test Costs are REQUIRED.<br /><br />Set the value to 0 (zero) if the cost does not apply to this customer (WARNING: That would mean that all tests of that type for this license would be invoiced for 0 (zero) dollars!)");
        return false;
    }
    
    var $hide_total_potential_cannabinoids_on_reports = $("#managelicense_hide_total_potential_cannabinoids_on_reports").val();
    
    var tests = [];
    
    $(".pricingdata").each(function() {
        
        product_id = $(this).data("product_id");
        //alert(product_id);
        dom_element_id = $(this).prop("id");
        price = $(this).val();
        
        tests.push({product_id: product_id, dom_element_id: dom_element_id, price: price});
        
        //tests["dom_element_id] = price;
        
      
        
    });
    
    //tests = JSON.stringify(tests);
    
    //console.log("tests: " + tests);
    
    item = [];

    var val = "";
    
    //item ["tests"] = tests;
    item.push({tests: tests});

    //item ["client_id"] = $("#manageclientid").val();
    item.push({colname: "client_id", val: $("#manageclientid").val()});

    $(".licensedata").each(function() {    
        id = $(this).prop("id");
        id = id.replace("managelicense_", "");
        val = $(this).val();        
        //item[id] = val;
        item.push({colname: id, val: val});
        
    });
    
    item.push({colname: "license_image_path", val: $("#managelicense_license_image").prop("src")});

    var json = JSON.stringify(item); 
    
    console.log("json: " + json);
   
   
   $.get("managelicensesfunctions.php?qtype=addlicense&json=" + json,
   function(d) {
       
        if (d.length > 0) {
            //console.log(d);
            showerrormessage(d);
            return false;
        }
        
        $("#addlicense_div").hide();
        $("#managelicense_license_image").prop("src", "");
        $("#managelicense_license_image_div").hide();
        $(".licensedata").val("");
        $("#licenselist_div").show();
        $("#btnapplylicense").hide();
        $("#btncancellicense").hide();
        $("#btnnewlicense").show();
        $(".mainbtn").show();
        $(".manage_client_div").fadeIn(250);
        getlicenses($("#manageclientid").val());
       
   });
    
}

function deletelicense(license_number) {
    
    event.stopPropagation();
    
    var x = confirm("Are you SURE?");
    
    if (!x) {
        return false;
    }
   
   $.get("managelicensesfunctions.php?qtype=deletelicense&license_number=" + license_number,
   function() {
        var client_id = $("#manageclientid").val();
        getlicenses(client_id);
   }); 
    
}

function showlicense(license_number) {    
        
    $("#managelicense_license_image").prop("src", "");
    $("#managelicense_license_image_div").hide();
       
    $.getJSON("managelicensesfunctions.php?qtype=editlicense&license_number=" + license_number,
    function(data) { 
   
    //console.log(data);
    var license_id = "";
    
        $.each( data, function( key, val ) {
            
            var y = "";

            if (key == "strtests") {
                $("#pricing_div").html(val);               
            }
                      
            if (key == "license_id") {
                license_id = val; 
            }
                          
            if (key == "license_image_path") {
                if(val != null) {
                    if (val.length > 0) {                        
                        $("#managelicense_license_image").prop("src", val);
                        $("#managelicense_license_image_div").show();                               
                    }
                }                
            }            
           
            $("#managelicense_" + key).val(val);
                            
        });   

    $("#licenselist_div").hide();        
    $("#addlicense_div").show();
    $(".mainbtn").hide();
    $(".manage_client_div").hide();
    $("#btnnewlicense").hide();
    $("#btnapplylicense").show();
    $("#btncancellicense").show();    
    fileuploader(license_id); 
    $("#managelicense_license_image_div").fadeIn(250);  
            
    $.getJSON("managelicensesfunctions.php?qtype=getprices&license_id=" + license_id,
        function(data1) {             
            $.each( data1, function( key, val ) {                
                 $("input[data-product_id='" + key + "']").val(val);
            });
        });
        
 
    
   });
   
   
    
}


</script>