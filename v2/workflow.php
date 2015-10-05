<?php include "includes.php";

$page = 1;
$limit = 25;

if (isset($_GET["page"])) {
   $page = $_GET["page"];    
}

if (isset($_GET["limit"])) {
   $limit = $_GET["limit"];    
}

 ?>

<html>

<?php showheader(); ?>

<!-- blue theme stylesheet with additional css styles added in v2.0.17 -->
<link rel="stylesheet" href="css/theme.blue.css">
<link rel="stylesheet" href="css/styles.css">

<body>

    <input id="currentcount" style="display:none;"></input>
     
    <div id="controlsdiv" style="padding:0 1em 0 1em;">
        <div class="row">
            <div class="col-sm-12">
                <h1 style="margin-top:-.1em;">Workflow Management</h1>
            </div>           
        </div> 
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
                    <input type="date" id="searchfordate" class="searchfor datetimepicker"></input>
                    
                    <button id="btnsearch" onclick="dosearch(1, $('#pagination_limit').val())" class="btn btn-default">Search</button>
                </div>
                <div style="font-size:85%;margin-top:3px;">
                    <input class="hover" type="checkbox" id="show_completed" onchange="dosearch(1, $('#pagination_limit').val())">
                    <label for="show_completed" class="hover" style="font-weight:normal;margin-left:3px;vertical-align: middle;"> Completed Samples</label></input>
                </div>                         
            </div>
            <div class="col-sm-6" style="text-align:right;margin-top:-1.5em;">                
                <div class="pagination"></div>
                <div class="limithtml" style="margin-top:-1.5em;"></div>   
            </div>
        </div>    
    </div>    

<div id="samplelist_div" class="centerform listdiv">    
    <div class="dvData"> 
    <div style="text-align:right;">    
        <a href="#" class="export btn btn-primary">Export Table</a>         

    </div>        
        <table class="tablesorter zebra" id="samplelist">
            <thead>
                <tr>                    
                    <th>Sample ID</th>
                    <th>Date Accepted</th>
                    <th>License</th>
                    <th>Product Name</th>
                    <th>Tests</th>
                    <th>Used Product Mass</th> 
                    <th>Unused Product Mass</th>                    
                    <th>METRC Number</th>
                    <th>Test Completion</th>
                    <th>Report Generation</th>
                    <th>Report Approval</th>
                    <th>METRC Entry</th>
                    <th>Reports</th>
                </tr>
            </thead>
            <tbody id="samplelisthtml"> 
            </tbody>
        </table>
    </div>
    <div style="clear:both;"></div>
</div>

   

</body>

<?php showfooter(); ?>

<script>

$(document).ready(function() {
    
    $("body").show();
    
    $("#samplelist").fadeIn(250);
         
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

    $("#main_nav", window.parent.document).fadeIn(250);
    $("#footer", window.parent.document).fadeIn(250);


});

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
        
    
    if (ellimit) {
        if (ellimit.length) {
            limit = ellimit.val();
        }
    }
             
    return $.getJSON("workflowfunctions.php?qtype=samplelist&sb=" + searchby + "&sf=" + searchfor + "&page=" + page + "&limit=" + limit + "&completed=" + completed, 
    function(d){ 
    
      $(".pagination").html(d.pagination); 
      
        $(".p_pagination", window.parent.document).html(d.p_pagination);       

        $(".limithtml").html(d.limithtml);

        $("#samplelist").find('tbody').empty();

        document.getElementById("samplelisthtml").innerHTML = d.tds;
                  
        var resort = true;
        $("#pagination_limit").val(limit);
        $(".pagination").fadeIn(250);
        $(".p_pagination", window.parent.document).fadeIn(250);
        $(".limithtml").fadeIn(250);
        $("#samplelist_div").fadeIn(250);      
        $(".tablesorter").trigger("update", [resort]);
        

        $('.datepicker').mouseover(function(){ 
            $(this).datetimepicker({format:'m/d/Y', dayOfWeekStart: 1, timepicker: false, lang:'en'}); 
        });
    
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
        $("#controlsdiv").fadeIn(250); 
        $("#samplelist_div").fadeIn(250);
        $("#main_nav", window.parent.document).fadeIn(250);
        $("#footer", window.parent.document).fadeIn(250);
}


function updateworkflow(sample_id, obj) {
    
    var tcol = obj.prop("id");
    var tval = obj.val();
   
   if (tval == null || tval.length < 1) {
       return false;
   }
   
    $.post("workflowfunctions.php?qtype=updatesample", {tval: tval, tcol: tcol, sample_id: sample_id},
    function(d){
       
        //$.when( dosearch($(".currentpage").html(), $('#pagination_limit').val()) ).done(function() {
            //$(".datepicker").hide();
     

        //});
        
    });
 
}

function showinmanagesamples(sampleid) {
       
    $(parent.window.document).find(".index_frame_div").hide(); 
    $(parent.window.document).find(".index_frame").hide();     
    
     $.get("managesamplesfunctions.php?qtype=setmanagesamplesorworkflow&sampleid=" + sampleid, function() {       
        $(parent.window.document).find('#frame_managesamples')[0].contentWindow.editsample(sampleid, 'workflow');
        $(parent.window.document).find('#frame_managesamples').show();
        $(parent.window.document).find('.index_frame_div').fadeIn(250);
         
        return;
    });
     

}



</script>