<?php include "includes.php" ?>

<html>

<?php showheader(); ?>

<!-- blue theme stylesheet with additional css styles added in v2.0.17 -->
<link rel="stylesheet" href="css/theme.blue.css">
<link rel="stylesheet" href="css/styles.css">

<body style="display:none;">

<div style="padding:0 1em 0 1em;">
    <h1>Workflow</h1>
</div>    

<div id="workflow_list_div" class="centerform listdiv">    
    <div class="wrapper">
        <table class="tablesorter zebra" id="workflowlist">
            <thead>
                <tr>
                    <th>Sample ID</th>
                    <th>Date Accepted</th>
                    <th>License</th>
                    <th>Product Name</th>
                    <th>Tests</th>
                    <th>Unused Product Mass</th>                    
                    <th>METRC No</th>
                    <th>Test Completion</th>
                    <th>Report Generation</th>
                    <th>Report Approval</th>
                    <th>METRC Entry</th>                  
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
    </div>
</div>

</body>

<?php showfooter(); ?>

<script>

$(document).ready(function() {
    
    $("body").show();
    
    $("#workflowlist").fadeIn(250);
        
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
			if (running < 1){         
				waitingDialog.hide();
                $("#managesample_sample_image").prop("src", "uploads/" + sample_id + "/" + fileName);
                $("#managesample_sample_image_path").val("uploads/" + sample_id + "/" + fileName);
                $("#managesample_sample_image_div").fadeIn(250);
			}						
		}
	});
}

function dosearch() {
    getsamples();
}

function getsamples() { 
    
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
    
    $.getJSON("managesamplesfunctions.php?qtype=samplelist&sb=" + searchby + "&sf=" + searchfor, 
    function(d){     
        $("#samplelist").find('tbody')
            .empty()
            .append(d.tds);
        var resort = true;        
        $("#samplelist_div").fadeIn(250);
        $(".tablesorter").trigger("update", [resort]);        
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

</script>