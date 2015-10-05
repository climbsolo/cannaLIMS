<!DOCTYPE html>

<?php 

include "includes.php";

?>


<!--
*************************************************************************
CannaLIMS
Created by SwishDaddy - CannaSys, Inc.
Last Updated: May 2015

-->

<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<?php showheader() ?>

<style>
select {
    padding: 5px;
}
</style>

<body>

<div class="col-sm-12">
    <div class="col-sm-2" style="width:14em;">
        <fieldset class="fieldset">
            <legend class="legend">
                Import CSV
            </legend>
            
                <div style="padding: 0 20px 10px 20px;margin-top:-1em;text-align:center;">                   
                       
                    <button id="btnclearall" class="btn btn-default" style="width:100px;font-size:90%;">Reset</button>
                    
                    <br style="clear:both;" />                       
                   
                    <div id="btnupload" style="text-align:center;">
                        <div id="fileuploader" class="bcbutton" style="margin-top:1.25em;"></div>
                    </div>
                                        
                    <div id="controlbuttons" style="text-align:center;display:none;"></div>    

                 </div>
                 
                 <div style="clear:both;"></div>
            
        </fieldset> 
        
        <div id="residualsolventsblanksdiv" style="display:none;">
        <fieldset class="fieldset"> 
        <legend class="legend">
            Blanks Values
        </legend>
            <div style="padding:0 2% 2% 2%;text-align:center;">
            <select id="residualsolventsblanklist"  style="width:66%;">
                <option value="Butane">Butane</option>
                <option value="Isobutane">Isobutane</option>
                <option value="Hexane">Hexane</option>
                <option value="Benzene">Benzene</option>
                <option value="Heptane">Heptane</option>
                <option value="Toluene">Toluene</option>
                <option value="Xylene">Xylene</option>
            </select>
            <input id="residualsolventsblankvalue" style="margin:0 0 0 5%;width:25%;padding:3px;"></input>
            <div style="text-align:right;margin-top:1em;">
                <button class="bcbutton" id="residualsolventsblankbtn" onclick="applyresidualsolventsblanks()">Apply</button>
            </div>  
            
            </div>
        </fieldset>
        </div>
    
    </div>
        <div class="col-sm-10"> 
            <div id="retentiontimewindows">
            <fieldset class="fieldset" style="padding:0 1em 1em 1em;margin-bottom:1em;min-width:1100px;">
                <legend class="legend">Retention Time Windows</legend> 
                <div class="row" style="min-width:1100px;">
                    <div class="col-lg-2"> 
                        <label id="rtinstrumentlbl" for="rtinstrument">Instrument</label>
                        <select id="rtinstrument" style="width:100%;" onchange="rtinstrument()"> 
                        <option value="HPLC 1100">HPLC 1100</option><option value="HPLC 1050">HPLC 1050</option><option value="GC 5890">GC 5890</option><option value="GC 6890">GC 6890</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                    <label id="rtcompoundtypeslbl" for="rtcompoundtypes">Compound Type</label>
                        <select id="rtcompoundtypes" style="width:100%;" onchange="rtinstrument()"><option value="cannabinoids">Cannabinoids</option><option value="residualsolvents">Residual Solvents</option><option value="terpenes">Terpenes</option></select>            
                    </div> 
                    <div class="col-lg-2">
                        <label id="rtaddcompoundnamelbl" for="rtaddcompoundname" style="display:none;">New Compound</label>
                        <input id="rtaddcompoundname" type="text" style="width:100%;display:none;padding:.2em;">
                        <label id="rtcompoundnameslbl" for="rtcompoundnames">Compound</label>
                        <select id="rtcompoundnames" style="width:100%;" onchange="rttimes()"></select>                        
                    </div>
                    <div class="col-lg-6">
                        <div class="col-lg-3">
                            <label for="rtmintime">Min Time</label><br /><input style="width:5em;padding:.2em;" type="text" id="rtmintime"></input>
                        </div>
                        <div style="text-align:right;" class="col-lg-3">
                            <label for="rtmaxtime">Max Time&nbsp;</label><br /><input type="text" style="width:5em;padding:.2em;" id="rtmaxtime"></input>
                        </div>
                        <div class="col-lg-2" style="padding-top:1.8em;"><button id="rtbtnupdate" onclick="rtupdatetimes()">Update</button></div>
                        <div style="text-align:right;padding-top:1.8em;" class="col-lg-4">
                            <button id="rtbtnadd" onclick="rtadd()">Add</button><button id="rtbtnapply" onclick="rtapply()" style="display:none;margin-bottom:.5em;">Apply</button><button id="rtbtncancel" onclick="rtcancel()" style="display:none;margin-left:1em;">Cancel</button><button id="rtbtndel" style="margin-left:1em;" onclick="rtdel()">Del</button>
                        </div>            
                    </div>
                </div>
            </fieldset>
            </div>
            
        <div id="results" style="margin-top:1em;"></div>
        </div>
    
</div>

</body>

<?php showfooter() ?>

</html>

<script>

$(document).ready(function() {   
  fileuploader();  
  
  $("#main_nav", window.parent.document).fadeIn(250);
  
  $("#btnclearall").click(function() {
    $("#testtype").val("");
    $("#residualsolventsblanksdiv").hide();
    showuploadbutton();
    $("#retentiontimewindows").fadeIn(250);
  });
    rtinstrument();
});

function fileuploader() {
	var running = 0;  
	var uploader = new qq.FileUploader({
		element: document.getElementById('fileuploader'),
		action: 'fileupload.php',
		debug: true,
		allowedExtensions: ['csv'],
		sizeLimit: 50000000, 
		onSubmit: function(id, fileName){            
			running++; 	
            waitingDialog.show();
            uploader.setParams({
                sample_id: '<?php echo $_SESSION["luid"] ?>'
            });
		},
		onComplete: function(id, fileName, responseJSON){
            running--;
			if(running < 1){										
				parcecsv(fileName)
			}
						
		}
	});
}


function parcecsv(filename) {
    var parser = "";
    
    if (filename.indexOf(".") < 0) {
        waitingDialog.hide();
        showerrormessage("Invalid filename. Please make sure the file extension exisits (i.e. \".csv\").");
        return false;
    } 

    if (filename.indexOf("_") < 0) {
        waitingDialog.hide();
        showerrormessage("Invalid filename. Please make sure the file name is formatted correctly (i.e. \"20150618_6890_rs.csv\").");
        return false;
    } 
    
    var arrfilename = filename.split(".");
    arrfilename = arrfilename[0].split("_");
    var instrumentname = arrfilename[1];
    var testtype = arrfilename[2].toLowerCase();
        
    if (testtype == "pot") {
        testtype = "cannabinoids";
    }
    else
    {
        if (testtype == "rs") {
            testtype = "residualsolvents";
        }
        else
        {
            if (testtype == "t") {
                testtype = "terpenes";
            }
            else
            {
                waitingDialog.hide();
                showerrormessage("Invalid Test Type in filename. Please make sure the file name is formatted correctly and has a legitimate test type (i.e. \"20150618_6890_<span style=\"font-weight:bold;\">rs</span>.csv\"). Valid  test type values are \"POT\" for Potency (Cannabinoids), \"RS\" for Residual Solvents, and \"T\" for Terpines.");
                return false;
            }
        }
    }   
    
    if (testtype == "cannabinoids") {    
        parser = "parsecsv_cannabinoids.php";
    }
    
    if (testtype == "residualsolvents") {    
        parser = "parsecsv_residualsolvents.php";
    }
    
    if (testtype == "terpenes") {    
        parser = "parsecsv_terpenes.php";
    }    
        
    $.post(parser, {filename: filename, instrumentname: instrumentname},
    function(d) {
        
        if (d.indexOf("The import process has been aborted") > 0) {
            $("#btnupload").hide();
            $("#controlbuttons").hide(); 
            $('#results').html(d);           
            waitingDialog.hide();
            return false;
        }
        
        $('#results').html(d);
        $("#btnupload").hide();      
        
        var errorcheck = d.substring(0,6);
                
        if (errorcheck == "ERROR:" || d.dlength < 1) {        
            $("#controlbuttons").hide();
        }
        else
        {
            $("#controlbuttons").show();
            if (testtype == "residualsolvents") {        
                $("#residualsolventsblanksdiv").show();
            }
            else
            {            
                $("#residualsolventsblanksdiv").hide();
            }
        }           
           
        waitingDialog.hide();
    });
}

function showuploadbutton(testtype) { 
    
    $("#results").html("");
    $("#residualsolventsblanksdiv").hide();
    
    $("#btnupload").fadeIn(200);
    $("#controlbuttons").hide();
    
}
  
function applyresidualsolventsblanks() {
    
    var target = $("#residualsolventsblanklist").val();
    var val = $("#residualsolventsblankvalue").val();
    var id = "";
    var compound = "";
    
    $(".formula").each(function() {
        id = $(this).prop("id");
        painputid = id + "_area";
        compound = $(this).data("compound");
        
        if (target == compound) {
            $("#" + painputid).val($("#" + painputid).val() - val);
            calculatepercentage(id, "residualsolvents")
        }
        
    });
    $("#residualsolventsblankvalue").val("");
    
}

function rtinstrument() {
    var instrumentname = $("#rtinstrument").val();
    var compoundtype = $("#rtcompoundtypes").val();
    $("#rtcompoundnames").empty();
    $("#rtmintime").val("");
    $("#rtmaxtime").val("");
        
    $.getJSON('getretentiontimedata.php?i='+instrumentname+'&ctv='+compoundtype, function(data){

        $.each(data, function (key, val) {                         
            $("#rtcompoundnames").append(
            $('<option>', {
                value: val,
                text: val
            }, '<option/>')
        );
        rtcancel();            
        });
    });
}

function rttimes()  {
    $("#rtmintime").val("");
    $("#rtmaxtime").val("");
        
    var instrumentname = $("#rtinstrument").val();
    var compoundname = $("#rtcompoundnames").val();
    $.getJSON('getretentiontimedata.php?i='+instrumentname+'&c='+compoundname+'&gt=true', function(data){
        $.each(data, function (index, item) {
            $("#"+index).val(item);
        });
    });

} 

function rtadd() { 
        $("#rtcompoundnames").hide();
        $("#rtcompoundnameslbl").hide();
        $("#rtaddcompoundname").show();
        $("#rtaddcompoundnamelbl").show();
        $("#rtbtnadd").hide();
        $("#rtbtndel").hide();
        $("#rtbtnupdate").hide();
        $("#rtbtncancel").show();
        $("#rtbtnapply").show();
        $("#rtmintime").val("");
        $("#rtmaxtime").val("");
}  

function rtcancel() {
   $("#rtbtncancel").hide();
   $("#rtbtnapply").hide();
   $("#rtaddcompoundname").val("");
   $("#rtaddcompoundname").hide();
   $("#rtaddcompoundnamelbl").hide();
   $("#rtbtnadd").show();
   $("#rtbtndel").show();
   $("#rtbtnupdate").show();
   $("#rtcompoundnames").show();
   $("#rtcompoundnameslbl").show();
   rttimes();
} 

function rtapply() {
    var instrumentname = $("#rtinstrument").val();
    var compoundname = $("#rtaddcompoundname").val();
    var compoundtype = $("#rtcompoundtypes").val();
    var mintime = $("#rtmintime").val();
    var maxtime = $("#rtmaxtime").val();
        
    if (compoundname.length < 1) {
        showerrormessage ("Please provide a name for the new Compound");
        return false;
    } 

    if (!isNumber(mintime)) {
        showerrormessage ("Sorry, that is an invalid Min Time value.");
        return false;
    }
    
    if (!isNumber(maxtime)) {
        showerrormessage ("Sorry, that is an invalid Max Time value.");
        return false;
    }
        
    $.get('getretentiontimedata.php?i='+instrumentname+'&c='+compoundname+'&ct='+compoundtype+'&min='+mintime+'&max='+maxtime+'&add=true', 
    function(data){
        rtcancel();
        rtinstrument();
    });
} 
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function rtdel() {
    var compoundname = $("#rtcompoundnames").val();
    
    if (compoundname == "TerpTHC") {
        alert ("Sorry, you cannot delete this value.");
        return false;
    }
    
    
    var x = confirm("Are you SURE?");
    
    if (x) {
        var instrumentname = $("#rtinstrument").val();
        
        $.get('getretentiontimedata.php?i='+instrumentname+'&c='+compoundname+'&del=true', 
        function(data){
            rtcancel();
            rtinstrument();
        });
        }

}  

function rtupdatetimes() {
    
    var instrumentname = $("#rtinstrument").val();
    var compoundname = $("#rtcompoundnames").val();
    var min = $("#rtmintime").val();
    var max = $("#rtmaxtime").val();
        
    $.get('getretentiontimedata.php?i='+instrumentname+'&c='+compoundname+'&min='+min+'&max='+max+'&u=true', 
    function(data){
        rtcancel();
        rtinstrument();
    });
    
    
}    

</script>