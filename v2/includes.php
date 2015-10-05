<?php 

include "../common/master_includes.php";

$session_timeout = 600; // 10 minutes in seconds
$session_timeout_check_interval = 10000; // 10 seconds in milliseconds

if (isset($_SESSION["luid"])) {
    if ($_SESSION["luid"] == '3') { 
        $session_timeout = 600; // 1 minutes in seconds
        $session_timeout_check_interval = 10000; // 10 seconds in milliseconds    
    }
}

// The QuickBooks Online app anme. Used in showinvoice.php.
$qbo_appname = 'cannalims_demo';


$now = time();
if (((isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) && isset($_SESSION['luid']))) { 
    header('Location: logout.php');
}
else
{
    $_SESSION['discard_after'] = $now + $session_timeout;
}

$homeurl = "http://cannalims.com/v2";

$labaddress = "1720 S. Bellaire 325 Denver, CO 80204";
$labphone = "(800) 420-1338";

date_default_timezone_set('America/Denver');

// Using PDO for DB Connections
$dbconn = new PDO('mysql:host=localhost;dbname=cannalims_v2', 'cl_phytatech', ' kjhKJH8756&^&%47yfuJHg', array(PDO::ATTR_PERSISTENT => true));
$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$salt = 'jkhaaslfjgjGIGiaguer89yhvkjbclkjlkj8734420'; //DO NOT LOSE OR ALTER or you will lose ALL USER PASSWORDS and have to reset them ALL!!!!

function sendemail($subject, $message, $recipient) {

$mail = new PHPMailer;

$mail->IsSMTP();
$mail->Host = "smtpout.secureserver.net"; 
$mail->SMTPAuth = true;
$mail->Username = "phytatechreports@phytatech.com";
$mail->Password = "reports";
$mail->SMTPSecure = "ssl";
$mail->Port = "465";
$mail->From = "phytatechreports@phytatech.com";
$mail->FromName = "Phytatech Notifications";
$mail->AddAddress($recipient);
//$mail->AddCC("");
$mail->AddBCC("mike@cannasys.com");
//$mail->AddBCC("sgoldman@phytatech.com");
$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $subject;
$mail->Body = $message;
$mail->Send();


//error_log("sendemail: $subject, $message, $recipient");

}

function getpagination($tablename, $orderby, $searchstring, $qtype, $page, $filename, $plimit) {
    
    global $dbconn;
    
    $arr = array();
    
    $arr["test"] = $page;
    
    if ($page == 0 || strlen($page) < 1) {
        $page = 1;
    }
    
    $limit = 0;

    try {

        // Find out how many items are in the table
        $total = $dbconn->query("
            SELECT
                COUNT(*)
            FROM
                $tablename 
            WHERE 
                (active = '' or active is null or active <> 'false') $searchstring
            
        ")->fetchColumn();

if ($tablename == 'tblchangehistory') {
    $total = $dbconn->query("
            SELECT
                COUNT(DISTINCT sample_id)
            FROM
                $tablename 
            WHERE 
                (active = '' or active is null or active <> 'false') $searchstring            
        ")->fetchColumn();
}    
      

        // How many items to list per page
        $limit += $plimit;

        // How many pages will there be
        $pages = ceil($total / $limit);

        // What page are we currently on?
        /*
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(            
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));
        */

        // Calculate the offset for the query
        $offset = ($page - 1)  * $limit;
        
        //if ($offest > $total) {
        //    $offset = $total;
       // }

        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);
        
         // The frame "back" link
        $pagedown = $page - 1;
        $prevlink = ($page > 1) ? '<a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="dosearch(1, ' . $plimit . ')" title="First page">&laquo;</a> <a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="dosearch(' . $pagedown . ', ' . $plimit . ')" title="Previous page">&lsaquo;</a>' : '<span class="disabled" style="font-size:160%;font-weight:bold;color:lightgray;" >&laquo;</span> <span class="disabled" style="font-size:160%;font-weight:bold;color:lightgray;">&lsaquo;</span>';

        // The frame "forward" link
        $pageup = $page + 1;
        $nextlink = ($page < $pages) ? '<a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="dosearch(' . $pageup . ', ' . $plimit . ')" title="Next page">&rsaquo;</a> <a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="dosearch(' . $pages . ', ' . $plimit . ')" title="Last page">&raquo;</a>' : '<span style="font-size:160%;font-weight:bold;color:lightgray;" class="disabled">&rsaquo;</span> <span style="font-size:160%;font-weight:bold;color:lightgray;" class="disabled">&raquo;</span>';
        
          // The parent "back" link
        $pagedown = $page - 1;
        $p_prevlink = ($page > 1) ? '<a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="$(\'iframe:visible\')[0].contentWindow.dosearch(1, ' . $plimit . ')" title="First page">&laquo;</a> <a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="$(\'iframe:visible\')[0].contentWindow.dosearch(' . $pagedown . ', ' . $plimit . ')" title="Previous page">&lsaquo;</a>' : '<span class="disabled" style="font-size:160%;font-weight:bold;color:lightgray;" >&laquo;</span> <span class="disabled" style="font-size:160%;font-weight:bold;color:lightgray;">&lsaquo;</span>';

        // The parent "forward" link
        $pageup = $page + 1;
        $p_nextlink = ($page < $pages) ? '<a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="$(\'iframe:visible\')[0].contentWindow.dosearch(' . $pageup . ', ' . $plimit . ')" title="Next page">&rsaquo;</a> <a style="font-size:160%;font-weight:bold;" href="javascript:void(0);" onclick="$(\'iframe:visible\')[0].contentWindow.dosearch(' . $pages . ', ' . $plimit . ')" title="Last page">&raquo;</a>' : '<span style="font-size:160%;font-weight:bold;color:lightgray;" class="disabled">&rsaquo;</span> <span style="font-size:160%;font-weight:bold;color:lightgray;" class="disabled">&raquo;</span>';
        
         // Display the frame paging information
        $arr["p_pagination"] = '<div id="paging">' . $p_prevlink . ' Page <span class="currentpage">' . $page . '</span> of ' . $pages . ' pages, displaying ' . $start . '-' . $end . ' of ' . $total . ' results ' . $p_nextlink . ' </div>';

        
        // Display the frame paging information
        $arr["pagination"] = '<div id="paging">' . $prevlink . ' Page <span class="currentpage">' . $page . '</span> of ' . $pages . ' pages, displaying ' . $start . '-' . $end . ' of ' . $total . ' results ' . $nextlink . ' </div>';
        
        $arr["limithtml"] = '<div style="font-size:85%;text-align:right">Results per page: <select id="pagination_limit" onchange="dosearch(1, $(this).val())"><option value="2">2</option><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="250">250</option><option value="500">500</option><option value="1000">1000</option></select></div>';

        // Prepare the paged query
        $stmt = $dbconn->prepare("
            SELECT
                *
            FROM
                $tablename
            WHERE
                (active = '' or active is null or active <> 'false') $searchstring
            ORDER BY
                $orderby
            LIMIT
                :limit
            OFFSET
                :offset
        ");
        

        // Bind the query params
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
         $arr["data"] = array();

        // Do we have any results?
        //if ($stmt->rowCount() > 0) {
            // Define how we want to fetch the results
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $iterator = new IteratorIterator($stmt);         

            // Display the results
            foreach ($iterator as $row) {
               array_push($arr["data"], $row);
            }         
            
            return json_encode($arr);
            
             $arr["data"] = array();

        //} else {
        //    $arr["error"] = '<p>No results could be displayed.</p>';
        //}

    } catch (Exception $e) {
        $arr["error"] = '<p>' . $e->getMessage() . '</p>';
    }
}

function logincheck() {    
    if (!isset($_SESSION['luid'])) {
        header('Location: logout.php');
        return false;
    }
    else
    {
        return true;
    }
}

function logaction($field_name, $value, $sample_id, $table_name, $test_type) {
    
    global $dbconn;
    global $aDateTimeGlobal;
    global $nDateTimeGlobalStandard;
    global $luid; 
    
    $sql = "update tblchangehistory set active = 'false' where field_name = :field_name and sample_id = :sample_id and table_name = :table_name";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':field_name'=>$field_name, ':sample_id'=>$sample_id, ':table_name'=>$table_name)); 
    
    $sql = "insert into tblchangehistory (field_name, value, datetime, ndatetime, sample_id, user_id, table_name, test_type) values ('$field_name','$value','$aDateTimeGlobal','$nDateTimeGlobalStandard','$sample_id', '$luid', '$table_name', '$test_type')";               
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(); 

}
?>