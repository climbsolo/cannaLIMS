<!DOCTYPE html>
<html>
<head>

<?php 

$reporttype = "cannabinoids";

if ($reporttype == "cannabinoids") {
    
?>    

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

// Create the data table.
var data = new google.visualization.DataTable();
data.addColumn('string', 'Substance');
data.addColumn('number', 'Value');
data.addRows([
  ['THC', 0.04],
  ['CBD', .07],
  ['CBC', .009],
  ['CBG', 0],
  ['CBN', 0],
  ['THCV', 0],
  ['CBDVA', 0]  
]);

// Set chart options
var options = { legend: 'none', //{ position: 'labeled' },
                pieSliceText: 'percentage',
               'height':300,
               'width':300,
                colors:['#F04E37', '#F79552', '#FAB49B', '#91D9F8', '#C8DC69', '#2BB673', '#1D9AD6' ],
                pieStartAngle: 90
               };
               
// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
chart.draw(data, options);
}

</script>

<?php 
}
?>
<style>

body {
    padding: 2em 3em 0 3em;
    font-family: Arial, sans-serif;
    width: 680px;
}

#headerimage {
    float: left;
    width: 45%;
}

#headerbar1 {
    background-color: #1A411D;
    width: 100%;
    height: 1.3em;
    padding: .2em .5em 0 .5em;
    color: white;
    margin: 1em 0 0 0;
    font-weight: bold;
    font-size: 1.2em;
}

#headerbar2 {
    background-color: #C7BA7E;
    width: 100%;
    height: .5em;
    padding: 0 .5em 0 .5em;
     filter:alpha(opacity=88);
    -webkit-box-shadow: 0 2px 0px 0px gray;
    box-shadow: 0 2px 20px 0px gray;
}

br {
    clear:both;
}

#sampledata1 {
    float: left;
    width: 55%;
}

#sampledata2 {
    float: right;
    width: 43%;
}

#sampledata {
    font-size: .9em;
}

#sampledata label, #labnotes label {
    width: 36%;
    float: left;
    margin: .1em 0 .9em 0;
    font-weight: normal;
    font-family: "Franklin Gothic Medium";
}

#sampledata input {
    width: 62%;
    float: right;
    margin-bottom: .9em;
    font-family: Courier;
    border: none;
}

.separator {
    height: 2px;
    border-top: 2px solid lightgray;
    border-bottom: 2px solid lightgray;
    width: 100%;
    margin-top: .5em;
}

#results1 {
    float: left;
    width: 65%;
}

 #results2 {
    float: right;
    width: 33%;
}

#resultstable tr td {
    width: 16%;
    line-height: 1.75em;
}

#resultstable tr td:nth-child(odd) {
    font-weight: bold;
}

#resultstable tr td:nth-child(even) {
    font-family: Courier;
}

.sup {
  position: relative;
  bottom: 1ex; 
  font-size: 80%;
}

#labnotes {
    width:100%;
    min-height:5em;
}

</style>

</head>
<body>

<div id="header">
   <img id="headerimage" src="images/report_logo.png"/>
</div>

<br />

<div style="text-align:center;">
    <div id="headerbar1"><span style="float:left;">CANNABINOID <span style="color:#75AA48;">LABORATORY REPORT</span></span></div>
    <div id="headerbar2"></div>
</div>

<br />

<div id="sampledata">   
    <div id="sampledata1">
        <label for="customer">Customer:</label>
        <input type="text" id="customer" value="Cannasys, LLC."></input>
        <br />
        <label for="strain">Strain:</label>
        <input type="text" id="strain" value="G3xG7L7 #8"></input>
        <br />
        <label for="salesrepresentative">Sales Representative:</label>
        <input type="text" id="salesrepresentative" value="Rob Howland"></input>
        <br />
        <label for="sampleid">Sample ID:</label>
        <input type="text" id="sampleid" value="625-58-95"></input>
        <br />
        <label for="batchnumber">Batch Number:</label>
        <input type="text" id="batchnumber"value="95-asda1"></input>
        <br />
        <label for="watercontent">Water Content:</label>
        <input type="text" id="watercontent" value=".5%"></input>
        <br />
    </div>
    <div id="sampledata2">
        <label for="datesampled">Date Sampled:</label>
        <input type="text" id="datesampled" value="4/15/2015"></input>
        <br />
        <label for="datereported">Date Reported:</label>
        <input type="text" id="datereported" value="4/16/2015"></input>
        <br />
        <label for="dateoflabtest">Date of Lab Test:</label>
        <input type="text" id="dateoflabtest" value="4/15/2015"></input>
        <br />
        <label for="instrumentation">Instrumentation:</label>
        <input type="text" id="instrumentation" value="HPLC/DAD"></input>
        <br />
        <label for="calibration">Calibration:</label>
        <input type="text" id="calibration" value="4/2/2015"></input>
        <br />
    </div>
</div>  

<br />

<div class="separator"></div> 

<br />

<div id="results">  

<?php 
if ($reporttype == "cannabinoids") {
?>    
    <div id="results1">
    <table style="font-size:.7em;background-color:#DCDDDE;padding:.5em;margin-bottom:1em;">
        <tr>
            <td style="width:32%;">ACIDIC<br />COMPOUND</td>
            <td style="width:32%;">NEUTRAL<br />COMPOUND</td>
            <td style="width:32%;text-align:right;"><div style="font-weight:bold;text-align:left;">TOTAL POTENTIAL CANNABINOIDS<span class="sup">2</span></div></td>
        </tr>
    </table>   
        <table id="resultstable">           
            <tr>
                <td>THCA</td><td><span id="thca">0.05</span></td>
                <td>THC</td><td><span id="thc">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#F04E37;margin-right:5px;float:left;"></div>THC</td><td><span id="thc_tpc">0.04</span></td>
            </tr>
            <tr>
                <td>CBDA</td><td><span id="cbda">0.07</span></td>
                <td>CBD</td><td><span id="cbd">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#F79552;margin-right:5px;float:left;"></div>CBD</td><td><span id="cbd_tpc">0.04</span></td>
            </tr>
            <tr>
                <td>CBCA</td><td><span id="cbca">0.009</span></td>
                <td>CBC</td><td><span id="cbd">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#FAB49B;margin-right:5px;float:left;"></div>CBC</td><td><span id="cbd_tpc">0.008</span></td>
            </tr>
            <tr>
                <td>CBGA</td><td><span id="cbga">NR*</span></td>
                <td>CBG</td><td><span id="cbg">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#C8DC69;margin-right:5px;float:left;"></div>CBG</td><td><span id="cbg_tpc">NR*</span></td>
            </tr>
            <tr>
                <td>CBNA</td><td><span id="cbna">NR*</span></td>
                <td>CBN</td><td><span id="cbn">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#2BB673;margin-right:5px;float:left;"></div>CBN</td><td><span id="cbn_tpc">NR*</span></td>
            </tr>
            <tr>
                <td>THCVA</td><td><span id="thcva">NR*</span></td>
                <td>THCV</td><td><span id="thcv">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#1D9AD6;margin-right:5px;float:left;"></div>THCV</td><td><span id="thcv_tpc">NR*</span></td>
            </tr>
            <tr>
                <td>CBDVA</td><td><span id="cbdva">NR*</span></td>
                <td>CBDV</td><td><span id="cbdv">NR*</span></td>
                <td><div style="width:1.2em;height:1.5em;background-color:#91D9F8;margin-right:5px;float:left;"></div>CBDV</td><td><span id="cbdv_tpc">NR*</span></td>
            </tr>
        
        </table>
    
    </div>
    
    <div id="results2">
         <div id="chart_div"></div>
    </div>

<?php 
}
?>
    
</div>

<br />

<div class="separator"></div> 

<br />

<div id="labnotes">
<span id="labnoteslabel">Lab Notes:</span>
<br />
<br />
Phytatech - 879 Federal Blvd - Denver CO 80204 - (303)-946-8843

</div>


<br />

<div>

    <div style="width:68%;float:left;border-top:2px solid lightgray;font-size:.7em;">
        <p><span class="sup">1</span> Product samples are tested as sold to patients, with no adjustment for water content.</p>
        <p><span class="sup">2</span> The sum of acidic and neutral values does not equal total potential content of a compound.</p>
        <p>To account for incomplete conversion of acidic to neutral compounds, the acidic value is
reduced by a standard formula i.e., (THC-acid x 0.88) + delta9-THC = Total Potential THC</p>
        <p>
        Ingredients found in trace quantities, below physiologically significant levels, are not reported.
        </p>
    </div>
    <div style="width:30%;float:right;">
        <img src="images/noelpalmersig.png" />
    </div>

    <br />
    <div style="font-size:.6em;">PhytaTech is a service mark of PalliaTech, Inc., an independent company dedicated to advancing the science of palliative care. &copy;2013 PalliaTech, Inc. All Rights Reserved</div>

</div> 

<br />

</body>

</html>







