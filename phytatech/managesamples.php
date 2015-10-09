<?php include "includes.php";

$page = 1;
$limit = 25;

if (isset($_GET["page"])) {
   $page = $_GET["page"];    
}

if (isset($_GET["limit"])) {
   $limit = $_GET["limit"];    
}

$sampleid = "";

/*
if (isset($_SESSION["showmanagesamplesorworkflowsampleid"])) {
   $sampleid = $_SESSION["showmanagesamplesorworkflowsampleid"];    
}
*/

if (isset($_GET["sampleid"])) {
   $sampleid = $_GET["sampleid"];    
}

 ?>
 
 <style>
 body {
     overflow-x:hidden;
 }

 
 </style>

<html>

<?php showheader(); ?>

<!-- blue theme stylesheet with additional css styles added in v2.0.17 -->
<link rel="stylesheet" href="css/theme.blue.css">
<link rel="stylesheet" href="css/styles.css">

<body onscroll="showcolumns()">

<div id="test"></div>

    <input id="currentcount" style="display:none;"></input>
     
    <div id="controlsdiv" style="padding:0 1em 0 1em;">
        <div class="row">
            <div class="col-sm-10">
                <h1 style="margin-top:-.1em;">Manage Samples</h1>
            </div>       
            <div class="col-sm-2" style="text-align:right;padding-top:.5em;">
                <button id="btnewsample" onclick="editsample('')" class="btn btn-primary">New Samples</button>               
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-6" style="padding-top:.5em;">
                <div>
                Search Options: 
                    <select id="searchoptions" onchange="$('#samplelist_div').hide();$('#searchfortext').val('');$('#searchfordate').val('');">
                        <option value="all">All</option>
                        <option value="sample_id">Sample ID</option>
                        <option value="client_id">Client Name</option> 
                        <option value="product_name">Product Name</option>                
                        <option value="batch_id">Batch Number</option>
                        <option value="date_accepted">Check In Date</option>            
                    </select>    
               
                    <input type="text" id="searchfortext" class="searchfor"></input>
                    <input type="text" id="searchfordate" class="searchfor datepicker"></input>
                    <select id="searchforclientlist" class="searchfor"></select>
                    
                    <button id="btnsearch" onclick="dosearch(1, $('#pagination_limit').val())" class="btn btn-default">Search</button>
                </div>
                <div style="font-size:85%;margin-top:3px;">
                    <input class="hover" type="checkbox" id="show_completed" onchange="dosearch(1, $('#pagination_limit').val())">
                    <label for="show_completed" class="hover" style="font-weight:normal;margin-left:3px;vertical-align: middle;"> Completed</label></input>
                
                    <input class="hover" type="checkbox" id="manage_flower_masses" onchange="dosearch(1, $('#pagination_limit').val())" style="margin-left:2em;">
                    <label for="manage_flower_masses" class="hover" style="font-weight:normal;margin-left:3px;vertical-align: middle;"> Flower</label></input>
                </div>                 
            </div>
            <div class="col-sm-6" style="text-align:right;margin-top:-1.5em;">                
                <div class="pagination"></div>
                <div class="limithtml" style="margin-top:-1.5em;"></div>   
            </div>
        </div>    
    </div> 
    
    <div id="controlsdiv2" style="padding:2em 1em 0 1em;display:none;">
        <div class="row">                
            <div class="col-sm-2">
                <button class="btn btn-info nextprev" onclick="navigatesample('previous')">&lt; Previous</button>
                <button class="btn btn-info nextprev" onclick="navigatesample('next')">Next &gt;</button>
            </div>
            <div class="col-sm-8"></div>      
            <div class="col-sm-2" style="text-align:right;">                
                <button id="btsampleapply" onclick="updatesample('', '')" class="btn btn-primary" style="margin: 0 0 .5em 0;">Done</button>
                <input type="number" style="width:4em;margin-right:1em;display:none;" value="0" id="create_sample_count"></input>
                <br />                
                <button id="btnsampledelete" onclick="showdeletesample()" class="btn btn-danger admin_restricted" style="margin-bottom:.5em;">Delete Record</button>                
            </div>
        </div>   
    </div>

<div id="samplelist_div" class="centerform listdiv">
<div style="text-align:right;">  
    <button onclick="exportcsv('tblsamples', 'samplelist', 'view')">Export Samples</button> 
    <button onclick="exportcompounds('all_compounds', 'samplelist')">Export Compounds</button>     
    <!--<input type="checkbox" class="hover" id="export_all"><label for="export_all" class="hover" style="font-weight:normal;margin-left:3px;vertical-align: middle;font-size:85%;"> Export ALL SAMPLES</label></input>-->
</div>
    <div class="export_csv_div">    
        <table class="tablesorter zebra" id="samplelist">
            <thead>
                <tr>
                    <th>Sample ID</th>
                    <th>License</th>
                    <th>Product Name</th>
                    <th>Product Type</th>
                    <th>Batch Number</th>
                    <th>METRC Number</th>
                    <th>Tests</th>
                    <th>Rush Order</th>
                    <th>Tocopherol Area</th>
                    <th>Dichloromethane Area</th>
                    <th>Wet Mass</th>
                    <th>Dry Mass</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
    </div>
    <div style="clear:both;"></div>
</div>

<div id="managesample_div" class="centerform"> 

<input type="text" style="display:none;" id="returnpage"></input>

    <div style="width:80%;float:left;">
    
    <div class="row">            
        <div class="col-sm-3"> 
            <label for="managesample_sample_id">Sample ID:</label>
            <br />
            <input id="managesample_sample_id" class="sampledata form-control" readonly></input>
        </div>
        <div class="col-sm-3">
            <label for="managesample_client_id">*Client:</label>
            <br />
            <select id="managesample_client_id" class="required sampledata form-control" data-label="Client"></select>
        </div>
        <div class="col-sm-3"> 
            <label for="managesample_license_number">License:</label>
            <br />
            <select id="managesample_license_number" class="sampledata form-control" data-label="License"></select>
        </div>
        <div class="col-sm-3"> 
            <label for="managesample_metrc_number">METRC Number:</label>
            <br />
            <input id="managesample_metrc_number" class="sampledata form-control" data-label="METRC Number"></input>
        </div>
    </div>
    
    <div class="row" style="margin-top:1em;">
         
        <div class="col-sm-3">
            <label for="managesample_date_accepted">Date Accepted:</label>
            <br />
            <input type="text" id="managesample_date_accepted" class="sampledata form-control datepicker" data-label="Date Accepted"></input>    
        </div>
        <div class="col-sm-3"> 
            <label for="managesample_manifest_id">Manifest ID:</label>
            <br />
            <input id="managesample_manifest_id" class="sampledata form-control"></input>
        </div>
        <div class="col-sm-3"> 
            <label for="managesample_batch_id">Batch ID:</label>
            <br />
            <input id="managesample_batch_id" class="sampledata form-control"></input>
        </div>
        <div class="col-sm-3">
            <div class="row">
                <div class="col-sm-6">
                    <label for="managesample_storage_location">Storage Location:</label>
                    <br />
                    <input id="managesample_storage_location" class="sampledata form-control"></input>
                </div>
                <div class="col-sm-6">
                    <label for="managesample_rush_order">Rush Order:</label>
                    <br />
                    <select id="managesample_rush_order" class="sampledata form-control">
                        <option value=""></option>
                        <option value="True">True</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row" style="margin-top:1em;"> 
        <div class="col-sm-3"> 
            <label for="managesample_sample_mass">Sample Mass:</label>
            <br />
            <input id="managesample_sample_mass" class="sampledata form-control" data-label="Sample Mass"></input>  
        </div>     
        <div class="col-sm-3"> 
            <label for="managesample_product_name">Product Name:</label>
            <br />
            <input id="managesample_product_name" class="sampledata form-control" data-label="Product Name"></input>
        </div>
        <div class="col-sm-3">
            <label for="managesample_product_type">Product Type:</label>
            <br />
            <select id="managesample_product_type" class="sampledata form-control" data-label="Product Type"></select>
        </div>
        <div class="col-sm-3">
            <div class="row" id="managesample_flower_masses" style="display:none;"> 
                <div class="col-sm-6">
                    <label for="managesample_wet_mass">Wet Mass:</label>
                    <br />
                    <input id="managesample_wet_mass" class="sampledata form-control"></input>
                </div>
                <div class="col-sm-6">
                    <label for="managesample_dry_mass">Dry Mass:</label>
                    <br />
                    <input id="managesample_dry_mass" class="sampledata form-control"></input>
                </div>
            </div> 
            <div class="row" id="managesample_package_amount_div" style="display:none;">
                <div class="col-sm-12"> 
                    <label for="managesample_package_amount">Package Amount:</label>
                    <br />
                    <input id="managesample_package_amount" class="sampledata form-control"></input>
                </div>
            </div>
        </div>               
    </div>
    
    <div class="row" style="margin-top:1em;"> 
        <div class="col-sm-3">
            <fieldset class="fieldset" id="tests_fieldset" style="margin: 1em 0;">
                <legend class="legend">
                    Tests to Perform
                </legend>
                <div class="col-sm-12" style="margin-top:-1em;">
                <input type="checkbox" id="managesample_potency_checkbox" value="Potency" class="sampledatatests"><label for="managesample_potency_checkbox" style="margin-left:1em;font-weight:normal;">Potency</label></input>
                <br />
                <input type="checkbox" id="managesample_homogeneity_checkbox" value="Homogeneity" class="sampledatatests"><label for="managesample_homogeneity_checkbox" style="margin-left:1em;font-weight:normal;">Homogeneity</label></input>
                <br />               
                <input type="checkbox" id="managesample_residual_solvents_checkbox" value="Residual Solvents" class="sampledatatests"><label for="managesample_residual_solvents_checkbox" style="margin-left:1em;font-weight:normal;">Residual Solvents</label></input>
                <br />
                <input type="checkbox" id="managesample_terpenes_checkbox" value="Terpenes" class="sampledatatests"><label for="managesample_terpenes_checkbox" style="margin-left:1em;font-weight:normal;">Terpenes</label></input>
                <br />
                <input type="checkbox" id="managesample_microbial_checkbox" value="Microbial" class="sampledatatests"><label for="managesample_microbial_checkbox" style="margin-left:1em;font-weight:normal;">Microbial</label></input>
                <br />
                <input type="checkbox" id="managesample_heavy_metals_checkbox" value="Heavy Metals" class="sampledatatests"><label for="managesample_heavy_metals_checkbox" style="margin-left:1em;font-weight:normal;">Heavy Metals</label></input>
                <br />
                <input type="checkbox" id="managesample_pesticides_checkbox" value="Pesticides" class="sampledatatests"><label for="managesample_pesticides_checkbox" style="margin-left:1em;font-weight:normal;">Pesticides</label></input>
                <br />
                <input type="checkbox" id="managesample_potterp_checkbox" value="Potency/Terpenes" class="sampledatatests"><label for="managesample_potterp_checkbox" style="margin-left:1em;font-weight:normal;">Potency/Terpenes</label></input>
                <br />
                <input type="checkbox" id="managesample_rush_checkbox" value="Rush Order" class="sampledatatests"><label for="managesample_rush_checkbox" style="margin-left:1em;font-weight:normal;">Rush Order</label></input>
                </div>
            </fieldset>
        </div>
        
        <div class="col-sm-3">
            <fieldset class="fieldset" style="margin: 1em 0;">
            <legend class="legend">
                Sample Image
            </legend>
                <div id="managesample_sample_image_div" style="display:none;text-align:center;margin-bottom:2.5em;">
                    <img id="managesample_sample_image" style="max-width:98%;" src=""></img>
                    <input type="text" style="display:none;" class="sampledata" id="managesample_sample_image_path"></input>
                </div>                
                 <div id="btnupload" style="text-align:center;">
                    <div id="fileuploader" class="bcbutton" style="margin: 0 auto 1em auto;width:10em;"></div>
                </div>           
            </fieldset>
        </div>
        
        <div class="col-sm-3">
            <fieldset class="fieldset" style="margin: 1em 0;">
            <legend class="legend" style="margin:0;">
                Reports
            </legend>
                <div id="managesample_reports_div">
                   
                </div>
            </fieldset>
        </div>
        
        <div class="col-sm-3">
            <fieldset class="fieldset" style="margin: 1em 0;">
            <legend class="legend">
                Notes
            </legend>
                <textarea id="managesample_notes"  class="sampledata" style="border:none;width:100%;resize:vertical"></textarea>
            </fieldset>
        </div>
    </div>
    
    </div>
    
    <div style="width:18%;float:right;text-align:right;font-size:85%;">
    
     <fieldset class="fieldset" style="padding:0 3em 2em 3em;text-align:left;min-height:20em;">
        <legend class="legend">Workflow</legend>
        <div class="row" style="margin-top:-1em;">
            <div class="col-sm-12" style="padding:1em 0 1em 0;">
                <label for="managesample_date_test_completion">Test Completion</label>
                <br />
                <input type="text" class="form-control sampledata datepicker" id="managesample_date_test_completion_workflow"></input>
            </div>
            <div class="col-sm-12" style="padding:1em 0 1em 0;">
                <label for="managesample_date_report_generation">Report Generation</label>
                <br />
                <input type="text" class="form-control sampledata datepicker" id="managesample_date_report_generation_workflow"></input>
            </div>
            <div class="col-sm-12" style="padding:1em 0 1em 0;">
                <label for="managesample_date_report_approval">Report Approval</label>
                <br />
                <input type="text" class="form-control sampledata datepicker" id="managesample_date_report_approval_workflow"></input>
            </div>
            <div class="col-sm-12" style="padding:1em 0 2em 0;border-bottom: 1px solid gray;">
                <label for="managesample_date_data_input_into_metrc">METRC Entry</label>
                <br />
                <input type="text" class="form-control sampledata datepicker" id="managesample_date_data_input_into_metrc_workflow"></input>
                  
            </div>        
            
                <div class="col-sm-12" style="padding:2em 0 1em 0;">
                    <label for="managesample_date_destroyed">Date Destroyed</label>
                    <br />
                    <input type="text" class="form-control sampledata datepicker" id="managesample_date_destroyed"></input>
                </div>
                <div class="col-sm-12" style="padding:2em 0 1em 0;">
                    <label for="managesample_destroyed_mass">Mass Destroyed</label>
                    <br />
                    <input type="text" class="form-control sampledata" id="managesample_destroyed_mass"></input>
                </div>
            
        </div>
        <div class="row" style="padding:2em 0 1em 0;">
            <div class="col-sm-6">
                <label for="managesample_used_mass">Used<br />Mass</label>
                <br />
                <input type="text" class="form-control sampledata" id="managesample_used_mass" readonly></input>
            </div>
            <div class="col-sm-6">
                <label for="managesample_unused_mass">Unused<br />Mass</label>
                <br />
                <input type="text" class="form-control sampledata" id="managesample_unused_mass" readonly></input>
            </div>
        </div>
            
        
        
        </div>
    </fieldset>
    </div>
    
    <div style="clear:both;"></div>
    
</div>

</body>

<?php showfooter(); ?>

<script>

$(document).ready(function() {
    
    $("#samplelist").fadeIn(250);
    
    $("body").show();
    
    var getsampleid = '<?php echo $sampleid ?>';        
    if (getsampleid.length < 1) { 
    
        $("#samplelist").fadeIn(250);
         
        fileuploader(); 
        
        $("#searchoptions").change(function() {
            showsearchfor();
        });
        
        dosearch(1, 25);
      
        $('#searchfortext').keypress(function(event) {
            if (event.keyCode == 13) {
                $("#btnsearch").click();
                return false;
            }
        });       
        
        if ( $("#managesample_div").is(":visible") ) {
        $("#main_nav", window.parent.document).hide();
        $("#footer", window.parent.document).hide();            
        }
        else
        {
            $("#main_nav", window.parent.document).fadeIn(250);
            $("#footer", window.parent.document).fadeIn(250);
        }
    
    }
    
    $("#managesample_client_id").change(function() {
         licenselist($("#managesample_license_number"), '');        
    });
    
    $("#managesample_product_type").change(function() {
         showflowermasses();        
    });
    
    
    
    $(function () {
        $(".nobubble").click(function(event) {
        event.preventDefault();
        event.stopPropagation();       
        });
    });
    
           
});

function fileuploader(sample_id) {
    
	var running = 0;  
	var uploader = new qq.FileUploader({
		element: document.getElementById('fileuploader'),
		action: 'fileupload.php',
        data: {sample_id:sample_id},
		debug: true,
		allowedExtensions: ['jpg', 'bmp', 'png', 'tif'],
		sizeLimit: 50000000, 
		onSubmit: function(id, fileName){            
			running++; 	
            waitingDialog.show();
            uploader.setParams({
                sample_id: sample_id
            });            
		},
		onComplete: function(id, fileName, responseJSON){
            running--;
			if(running < 1){         
				waitingDialog.hide();
                $("#managesample_sample_image").prop("src", "uploads/" + sample_id + "/" + fileName);
                $("#managesample_sample_image_path").val("uploads/" + sample_id + "/" + fileName);
                $("#managesample_sample_image_div").fadeIn(250);
			}
						
		}
	});
}

function dosearch(p, l) {
    waitingDialog.show();    
    $.when( getsamples(p, l) ).done(function() {
         waitingDialog.hide();
    });
}

function getsamples(p, l) {
        
    var searchby = $("#searchoptions").val();
    var searchfor = "";
    var completed = $("#show_completed").prop("checked");
    var flower = $("#manage_flower_masses").prop("checked");
        
  
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
    if (limit == null || limit.length < 1) {
        limit = 25;
    }
    
    var page = p;
    if (page == null || page.length < 1) {
         page = 1;
    }
        
    var ellimit = $("#pagination_limit"); 
        
    if (ellimit.length) {
        limit = ellimit.val();
    }
    
    return $.getJSON("managesamplesfunctions.php?qtype=samplelist&sb=" + searchby + "&sf=" + searchfor + "&page=" + page + "&limit=" + limit + "&completed=" + completed + "&flower=" + flower, 
    function(d){ 
    
    //console.log(d.p_pagination);

        //showcolumns();
    
        $(".pagination").html(d.pagination);       
        $(".p_pagination", window.parent.document).html(d.p_pagination);
        $(".limithtml").html(d.limithtml);      
  
        $("#samplelist").find('tbody')
            .empty()
            .append(d.tds);
        var resort = true;
        $("#pagination_limit").val(limit);
        $(".pagination").fadeIn(250);
        $(".p_pagination", window.parent.document).fadeIn(250);
        $(".limithtml").fadeIn(250);
        $("#samplelist_div").fadeIn(250);
        $(".tablesorter").trigger("update", [resort]);        
    });
    
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

function showupdatesample(x) {
    
    $("#btsampledelete").hide();
           
    if ($("#managesample_sample_id").val().length > 0) {
        $("#btnsampledelete").show();
    }    
    
    if (x == 'true') {
        $("#controlsdiv2").hide();  
        $("#managesample_div").hide();
        clearsampledata();
        //alert($(".currentpage").html() + " - " + $('#pagination_limit').val());
        dosearch($(".currentpage").html(), $('#pagination_limit').val());        
        $("#controlsdiv").fadeIn(250); 
        $("#samplelist_div").fadeIn(250);
        $("#main_nav", window.parent.document).fadeIn(250);
        $("#footer", window.parent.document).fadeIn(250); 
        
    }
    else
    {
        $("#controlsdiv").hide(); 
        $("#samplelist_div").hide();        
        $("#controlsdiv2").fadeIn(250);
        $("#managesample_div").fadeIn(250);
        fileuploader($("#managesample_sample_id").val());
        $("#main_nav", window.parent.document).hide(); 
        $("#footer", window.parent.document).hide();         
    }

}

function editsample(id, returnpage) {
        
    var sample_id = id;
    
    $("#returnpage").val(returnpage);
                      
    if (sample_id != null && sample_id.length < 1) {  
    
        clientlist($("#managesample_client_id"), '');
        producttypelist($("#managesample_product_type"));
        
         
        return $.getJSON("managesamplesfunctions.php?qtype=newsample", 
        function(data) {
       
            $.each( data, function( key, val ) {               
                if (key == "sample_id") {
                   sample_id = val; 
                }
                               
            });  
                      
            $("#managesample_sample_id").val(sample_id); 
            
           
            $("#create_sample_count").show();
            showflowermasses();
            showupdatesample('');          
        });
    }
    else
    {   
        $("#managesample_sample_id").val(sample_id); 

        
        $.when( clientlist($("#managesample_client_id"), sample_id) ).done(function() {       
            $.when( producttypelist($("#managesample_product_type")) ).done(function() {  

              
                return $.getJSON("managesamplesfunctions.php?qtype=getsampledata&sample_id=" + sample_id, 
                function(data) {
                    
                   // console.log (JSON.stringify(data));
                    
                    $.each( data, function( key, val ) {
                        
                      /*
                      if (key == "product_type") {
                            producttypelist($("#managesample_product_type"));
                            $("#managesample_product_type").val(val);          
                        }
                      */  
                         
                       
                        if (key == "license_number") {
                            var license_number = val;
                            $.when( licenselist($("#managesample_license_number"), license_number) ).done(function(){                    
                                return;
                            });
                        }
                        
                        if (key == "sample_image_path") {
                            $("#managesample_sample_image").prop("src", "");
                            $("#managesample_sample_image_div").hide();     
                            if(val != null) {
                                if (val.length > 0) {                        
                                    $("#managesample_sample_image").prop("src", val);
                                    $("#managesample_sample_image_div").show();                               
                                }
                            }
                            
                        }                        

                       if (key == "tests_to_perform") {                                            
                            if(val != null) {
                                if (val.length > 0) {  
                              
                                    var tests_json = $.parseJSON(val);
                                    $.each(tests_json, function(key1, val1) {                                        
                                        $("#"+key1).prop("checked", true);
                                    });
                                }
                            }                            
                        }                        

                        if (key == "managesample_reports_div") {
                            $("#managesample_reports_div").html(val);                            
                        }
                        
                        if (key != "license_number" && key != "managesample_reports_div") {
                            $("#managesample_" + key).val(val);
                        }
                        
                        
                               

                        
                        if (key == 'date_report_approval_workflow' || key == 'date_data_input_into_metrc_workflow' || key == 'date_destroyed') {
                             $("#managesample_" + key).prop("readonly", false); 
                             if (val) {
                                if (val.length > 0) {
                                    $("#managesample_" + key).prop("readonly", true);
                                }                               
                            }
                        } 

       //console.log(key + " - " + val);                        

                    });                    
                   
                   $("#create_sample_count").val('');
                   $("#create_sample_count").hide();
                    showflowermasses();             
                           
                    showupdatesample('');  
                    
                });
            });
        });      
    }   
}

function updatesample(nav) {
    
    item = {};
    var id = "";
    var val = "";
    var error = "";
    var obj;
    $(".required").removeClass("errorstyle");
    $("#tests_fieldset").removeClass("errorstyle");
    
    $(".required").each(function() {
        if ($(this).val() == null || $(this).val().length < 1) {
            error = "Please provide a value for " + $(this).data("label");
            obj = $(this);
            return false;            
        }
    });
    
    
    if (error.length < 1) {
    
        var tests = {};    
        
        $(".sampledatatests").each(function() {        
            if ($(this).prop('checked') == true) {
                tests[$(this).prop("id")] = $(this).prop("value");
            }       
        });
            
       // if (JSON.stringify(tests) == "{}") {
       //     error = "Please provide at least one Test to Perform.";
       //     obj = $("#tests_fieldset");
       // }
    };
    
    
    if (error.length > 0) {
        showerrormessage(error);        
        obj.addClass("errorstyle");
        return false;       
    }
       
    item["sample_id"] = $("#managesample_sample_id").val();
    item["tests_to_perform"] = JSON.stringify(tests);
   
    $(".sampledata").each(function() {    
        id = $(this).prop("id");
        //console.log(id);
        id = id.replace("managesample_", "");
        val = $(this).val();        
        item[id] = val;       
    });    
    
    if ($("#managesample_product_type").val() != "Flower") {
        item["wet_mass"] = null;
        item["dry_mass"] = null;        
    }
    
    if ( $("#managesample_product_type").val().indexOf( "Edible" ) == -1 ) {
        item["package_amount"] = null;    
    }
   
    var json = encodeURIComponent(JSON.stringify(item)); 
    
    var sample_count = $("#create_sample_count").val();
        
    //console.log(json);

   $.get("managesamplesfunctions.php?qtype=updatesample&json=" + json + "&samplecount="+sample_count,
    function(d) { 
    
    $("#create_sample_count").val('');
    if (d == "success") {    
        $(".sampledatatests").prop('checked', false);
        
        var returnpage = $("#returnpage").val();
        
        if (nav != 'true') {           
            if (returnpage == 'workflow') {
                $(parent.window.document).find(".index_frame").hide(); 
                $(parent.window.document).find('#frame_workflow').fadeIn(250);
                $("#returnpage").val('');
                //return false;                
            }
        }
        
        if (nav != 'true') {
            showupdatesample('true');
        }
        
    }        
    else
    {
        showerrormessage(d);
    }
    });  
}

function clearsampledata() {   
    $(".sampledata").val("");
    $("#managesample_product_type").empty();
    $("#managesample_sample_image").prop("src", "");
}

function showdeletesample() {
    showconfirmationmessage("Delete this Sample?", 'deletesample');
    
}

function deletesample() {
    
    $("#confirmation_div").modal('hide'); 
    
    var id = $("#managesample_sample_id").val();
        
    $.get("managesamplesfunctions.php?qtype=deletesample&sample_id=" + id,
    function() {
        dosearch($(".currentpage").html(), $('#pagination_limit').val());
        showupdatesample('true'); 
    });
    
}

function clientlist(obj, sample_id) {
    
    obj.empty();
    obj.append($('<option>').text(""));

    return $.getJSON("managesamplesfunctions.php?qtype=clientdropdown&sample_id=" + sample_id, 
    function(data) {
        
        console.log(data);
        
        $.each( data, function( key, val ) { 
            obj.append($('<option>', { val : key }).text(val));           
        }); 

        var sel = obj;
        var opts_list = sel.find('option');
        opts_list.sort(function(a, b) { return $(a).text().toLowerCase() > $(b).text().toLowerCase() ? 1 : -1; });
        sel.html('').append(opts_list);        
       
    });    
}

function licenselist(obj, license_number) {
    obj.empty();
    obj.append($('<option>').text(""));
    var client_id = $("#managesample_client_id").val();
    
    $.getJSON("managesamplesfunctions.php?qtype=licensedropdown&client_id=" + client_id, 
    function(data) {  

    
        $.each( data, function( key, val ) { 
            obj
            .append($('<option>', { val : key })
            .text(val));
           
        });
        
        obj.val(license_number);
    });    
}

function producttypelist(obj){    
    obj.empty();
    obj.append($('<option>').text(""));
    return $.getJSON("managesamplesfunctions.php?qtype=producttypelist", 
    function(data ) {
        $.each( data, function( key, val ) { 
            obj.append($('<option>', { val : key }).text(val));           
        });
    });    
}

function showflowermasses() {
    
    $("#managesample_flower_masses").hide();
    $("#managesample_package_amount_div").hide();
    
    if ($("#managesample_product_type").val() == "Flower") {
        $("#managesample_package_amount_div").hide();
        $("#managesample_flower_masses").show();        
        return false;
    }
        
   if ($("#managesample_product_type").val() == "Liquid Edible" || $("#managesample_product_type").val() == "Solid Edible") {
        $("#managesample_flower_masses").hide();
        $("#managesample_package_amount_div").show();
        return false;
    }
    
}

function navigatesample(direction) {
        
    var sample_id = $("#managesample_sample_id").val();
    var completed = $("#show_completed").prop("checked");
    
    if (sample_id.length < 1) {
        return false;
    }
    
    $(".nextprev").prop('disabled', true);
    
    $.get("managesamplesfunctions.php?qtype=navigate&sample_id=" + sample_id + "&direction=" + direction + "&completed=" + completed, 
    function(d) {  
       
        if (d.length < 1) {
            $(".nextprev").prop('disabled', false);
            return false;
        }
        
        updatesample('true', '');
        editsample(d);
        $(".nextprev").prop('disabled', false);          
    });
    
    
}

function exportcsv(tbl, id, xtype) {
    
    var exporttype = xtype;
    
    //var all = $("#export_all").prop("checked");
        
    if (exporttype == 'all') {
        exportTableToCSV('all', 'tblsamples', 'samplelist');       
    }
    else
    {
        exportTableToCSV('view', 'tblsamples', 'samplelist');
    }   
}

function exportcompounds(tbl, id) {
    exportTableToCSV('view', 'all_compounds', 'samplelist');
}

function updateflowermass(wetordry, sampleid, val) {
    
    $.get("managesamplesfunctions.php?qtype=updateflowermass&wetordry="+wetordry+"&sampleid="+sampleid+"&val="+val,
    function(){
        
    });
    
}

function showcolumns() {
    
    return false;
    
    var completed = $("#show_completed").prop("checked");
    var flower = $("#manage_flower_masses").prop("checked");
         
    
    /*
    $('#samplelist td:nth-child(9), #samplelist th:nth-child(11)').hide();
    $('#samplelist td:nth-child(10), #samplelist th:nth-child(12)').hide();
    $('#samplelist td:nth-child(11), #samplelist th:nth-child(9)').hide();
    $('#samplelist td:nth-child(12), #samplelist th:nth-child(10)').hide();
    
    if (completed == true && flower == false){
        $('#samplelist tr td:nth-child(9), #samplelist th:nth-child(11)').hide();
        $('#samplelist tr td:nth-child(10), #samplelist th:nth-child(12)').hide();
        $('#samplelist tr td:nth-child(11), #samplelist th:nth-child(9)').show();
        $('#samplelist tr td:nth-child(12), #samplelist th:nth-child(10)').show();       
    }
    
    if (completed == false && flower == true){
        $('#samplelist td:nth-child(9), #samplelist th:nth-child(9)').hide();
        $('#samplelist td:nth-child(10), #samplelist th:nth-child(10)').hide();
        $('#samplelist td:nth-child(11), #samplelist th:nth-child(11)').show();
        $('#samplelist td:nth-child(12), #samplelist th:nth-child(12)').show();
    }

    if (completed == true && flower == true){
        $('#samplelist td:nth-child(9), #samplelist th:nth-child(9)').show();
        $('#samplelist td:nth-child(10), #samplelist th:nth-child(10)').show();
        $('#samplelist td:nth-child(11), #samplelist th:nth-child(11)').show();
        $('#samplelist td:nth-child(12), #samplelist th:nth-child(12)').show();
    } 
*/
        
        $('#samplelist td:nth-child(9), #samplelist th:nth-child(9)').hide();
        $('#samplelist td:nth-child(10), #samplelist th:nth-child(10)').hide();
        $('#samplelist td:nth-child(11), #samplelist th:nth-child(11)').hide();
        $('#samplelist td:nth-child(12), #samplelist th:nth-child(12)').hide();

        if (completed == true && flower == false){
        $('#samplelist tr td:nth-child(11), #samplelist th:nth-child(11)').hide();
        $('#samplelist tr td:nth-child(12), #samplelist th:nth-child(12)').hide();
        $('#samplelist tr td:nth-child(9), #samplelist th:nth-child(9)').show();
        $('#samplelist tr td:nth-child(10), #samplelist th:nth-child(10)').show();       
        }

        if (completed == false && flower == true){
        $('#samplelist td:nth-child(9), #samplelist th:nth-child(9)').hide();
        $('#samplelist td:nth-child(10), #samplelist th:nth-child(10)').hide();
        $('#samplelist td:nth-child(11), #samplelist th:nth-child(11)').show();
        $('#samplelist td:nth-child(12), #samplelist th:nth-child(12)').show();
        }

        if (completed == true && flower == true){
        $('#samplelist td:nth-child(9), #samplelist th:nth-child(9)').show();
        $('#samplelist td:nth-child(10), #samplelist th:nth-child(10)').show();
        $('#samplelist td:nth-child(11), #samplelist th:nth-child(11)').show();
        $('#samplelist td:nth-child(12), #samplelist th:nth-child(12)').show();
        } 

       
}


</script>