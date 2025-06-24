<?php
include("../include/dbConnection.php");
// include('../smtp/PHPMailerAutoload.php');

if(isset($_REQUEST["functionName"]))
{
	$functionName = $_REQUEST["functionName"];
	$functionName($connection);
}

//Save Function
function save($connection, $action, $tableName, $data, $condition = array())
{	
	$query = $action == "INSERT" ? "INSERT INTO " : ($action == "UPDATE" ? "UPDATE " : "");
	$query .= "`" . $tableName . "` SET ";
	$queryData = array();
	foreach($data as $key => $value)
	{
		$queryData[] = "`" . $key . "` = '" . $value . "'";
	}
	$query .= implode(", ", $queryData);
	if(count($condition) > 0)
	{
		$query .= " WHERE ";
		$queryCondition = array();
		foreach($condition as $key => $value)
		{
			$queryCondition[] = "`" . $key . "` = '" . $value . "'";
		}
		$query .= implode(" AND ", $queryCondition);
	}
	return mysqli_query($connection, $query);	
}
//Get State
function get_state($connection)
{
	$data = array();
	$q = isset($_REQUEST["q"]) ? trim($_REQUEST["q"]) : "";
	$countryId = isset($_REQUEST['countryId']) ? $_REQUEST['countryId'] : "";

    $stateResult = mysqli_query($connection,"SELECT * FROM `state` WHERE `countryId` = '".$countryId."' AND `state` LIKE '%" . $q . "%' AND `delete` = '0'");
    while($stateRow = mysqli_fetch_array($stateResult))
    {
        $data[] = array("id" => $stateRow["id"], "text" => $stateRow["state"]);
    }
	echo json_encode($data);
}
//Get Fleet
function get_fleet($connection)
{
	$data = array();
	$q = isset($_REQUEST["q"]) ? trim($_REQUEST["q"]) : "";
	$modelId = isset($_REQUEST['modelId']) ? $_REQUEST['modelId'] : "";

    $fleetResult = mysqli_query($connection,"SELECT `vehicleNumber`,`id` FROM `fleet` WHERE `modelId` = '".$modelId."' AND `vehicleNumber` LIKE '%" . $q . "%' AND `delete` = '0'");
    while($fleetRow = mysqli_fetch_array($fleetResult))
    {
        $data[] = array("id" => $fleetRow["id"], "text" => $fleetRow["vehicleNumber"]);
    }
	echo json_encode($data);
}
//get_garage_city
function get_garage_city($connection)
{
    $data = array();
	$q = isset($_REQUEST["q"]) ? trim($_REQUEST["q"]) : "";
	$vendorId = isset($_REQUEST['vendorId']) ? $_REQUEST['vendorId'] : "";
    $cityArray = array();
    $garageResult = mysqli_query($connection,"SELECT `cityId` FROM `vendorgaragelocation` WHERE `vendorId` = '".$vendorId."' AND `delete` = '0'");
    while($garageRow = mysqli_fetch_array($garageResult))
    {
        $array = array_push($cityArray,$garageRow['cityId']);
    }
    $cityQuery = mysqli_query($connection,"SELECT `city`,`id` FROM `city` WHERE `id` IN (".implode(',',$cityArray).")  AND `delete` = '0'");
    while($cityRow = mysqli_fetch_array($cityQuery))
    {
        $data[] = array("id" => $cityRow["id"], "text" => $cityRow["city"]);
    }
	echo json_encode($data);
}
//Get Garage List
function get_garage($connection)
{
    $data = array();
	$q = isset($_REQUEST["q"]) ? trim($_REQUEST["q"]) : "";
	$vendorId = isset($_REQUEST['vendorId']) ? $_REQUEST['vendorId'] : "";
    $cityId = isset($_REQUEST['cityId']) ? $_REQUEST['cityId'] : "";

    $locationResult = mysqli_query($connection,"SELECT * FROM `vendorgaragelocation` WHERE `vendorId` = '".$vendorId."' AND `cityId` = '".$cityId."' AND `area` LIKE '%" . $q . "%' AND `delete` = '0'");
    while($locationRow = mysqli_fetch_array($locationResult))
    {
        $text = $locationRow["area"]." ".$locationRow["landMark"]."-".$locationRow["zipCode"];
        $data[] = array("id" => $locationRow["id"], "text" => $text);
    }
	echo json_encode($data);
}
//Get Garage detail
function garage_detail($connection)
{
    $garageId = isset($_REQUEST['garageId']) ? $_REQUEST['garageId'] : "";
    $garageQuery = mysqli_query($connection,"SELECT * FROM `vendorgaragelocation` WHERE `id` = '".$garageId."' AND `delete` = '0'");
    $garageRow = mysqli_fetch_array($garageQuery);
    echo json_encode(array("cityId"=>$garageRow['cityId'],"area"=>$garageRow['area'],"landMark"=>$garageRow['landMark'],"zipCode"=>$garageRow['zipCode'],"concernedPerson"=>$garageRow['concernedPerson'],"garagePhone"=>$garageRow['garagePhone'],"garageemail"=>$garageRow['garageemail'],"alternatePerson"=>$garageRow['alternatePerson'],"alternatePhone"=>$garageRow['alternatePhone'],"alternateEmail"=>$garageRow['alternateEmail']));
}
//Add Garage Location
function garage_add($connection)
{
    $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : "";
    $count = $length+1;
    $content = '';
    $content .='<div class="row garagelocation">
                    <div class="col-lg-12 col-md-12 mt-4 mb-4">
                        <h5 style="color:red;">GARAGE LOCATION '.$count.'</h5>
                        <button class="btn btn-danger text-white remove_node_btn_frm_field">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">City:</label>
                            <select name="locationcityId[]" class="form-control select2 w-90" data-placeholder="Select City" >
                            <option value="">Select City</option>
                            ';
                            $garagecityQuery = mysqli_query($connection,"SELECT * FROM `city` WHERE `delete` = '0'");
                            while($garagecityRow = mysqli_fetch_array($garagecityQuery))
                            {
                                $content .= "<option value=\"".$garagecityRow['id']."\">".$garagecityRow['city']."</option>";
                            }
    $content .= '           </select>
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Area<span class="error">*</span>:</label>
                            <input type="text" class="form-control" name="area[]" placeholder="Enter Area">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Land Mark<span class="error">*</span>:</label>
                            <input type="text" class="form-control" name="landMark[]" placeholder="Enter Land Mark">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Zip Code<span class="error">*</span>:</label>
                            <input type="text" class="form-control" name="zipCode[]" placeholder="Enter Zip Code">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Concerned Person<span class="error">*</span>:</label>
                            <input type="text" class="form-control" name="concernedPerson[]" placeholder="Enter Concerned Person">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Garage Phone Number<span class="error">*</span>:</label>
                            <input type="text" class="form-control" name="garagePhone[]" placeholder="Enter Garage Phone Number">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Garage Email:</label>
                            <input type="text" class="form-control" name="garageemail[]" placeholder="Enter Garage Email">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Alternate Person:</label>
                            <input type="text" class="form-control" name="alternatePerson[]" placeholder="Enter Alternate Person">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Alternate Phone Number:</label>
                            <input type="text" class="form-control" name="alternatePhone[]" placeholder="Enter Alternate Phone Number">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="exampleInputname">Alternate Email:</label>
                            <input type="text" class="form-control" name="alternateEmail[]" placeholder="Enter Alternate Email">
                            <span class="locationerror error"></span>
                        </div>
                    </div>
                </div>';

                echo $content;

}
//Category
function category($connection)
{
    $id = isset($_REQUEST['id']) ? mysqli_real_escape_string($connection,$_REQUEST['id']) : "";
    $search = isset($_REQUEST['searchTerm']) ? $_REQUEST['searchTerm'] : "";   

    if(!isset($_POST['searchTerm'])){ 
        $query = mysqli_query($connection,"SELECT `categoryId`,`category` FROM `category` WHERE `delete` = '0'");
      }else{   
        $query = mysqli_query($connection,"SELECT `categoryId`,`category` FROM `category` WHERE `category` like '%".$search."%' AND `delete` = '0'");
      }

    while($row = mysqli_fetch_array($query))
    {
        if($row['categoryId'] == $id)
        {
            $data[] = array("id"=>$row['categoryId'], "text"=>$row['category'],"selected" => "selected");
        }
        else 
        {
            $data[] = array("id"=>$row['categoryId'], "text"=>$row['category']);
        }
    }

    echo json_encode($data);
}
//service
function service($connection)
{
    $id = isset($_REQUEST['id']) ? mysqli_real_escape_string($connection,$_REQUEST['id']) : "";
    $search = isset($_REQUEST['searchTerm']) ? $_REQUEST['searchTerm'] : "";   

    if(!isset($_POST['searchTerm'])){ 
        $query = mysqli_query($connection,"SELECT `id`,`serviceName` FROM `service` WHERE `delete` = '0'");
      }else{   
        $query = mysqli_query($connection,"SELECT `id`,`serviceName` FROM `service` WHERE `serviceName` like '%".$search."%' AND `delete` = '0'");
      }

    while($row = mysqli_fetch_array($query))
    {
        if($row['id'] == $id)
        {
            $data[] = array("id"=>$row['id'], "text"=>$row['serviceName']);
        }
        else 
        {
            $data[] = array("id"=>$row['id'], "text"=>$row['serviceName']);
        }
    }

    echo json_encode($data);
}
//Delete Function
function delete($connection,$tableName,$columnName,$id)
{
	return mysqli_query($connection, "UPDATE `" . $tableName . "` SET  `delete`='1'  WHERE `" . $columnName . "` = '" . $id . "'");
		
}
//get_servicedetail
function get_servicedetail($connection)
{
    $data = array();
	$q = isset($_REQUEST["q"]) ? trim($_REQUEST["q"]) : "";
	$serviceId = isset($_REQUEST['serviceId']) ? $_REQUEST['serviceId'] : "";

    $subserviceResult = mysqli_query($connection,"SELECT * FROM `servicedetail` WHERE `serviceId` = '".$serviceId."' AND `title` LIKE '%" . $q . "%' AND `delete` = '0'");
    while($subserviceRow = mysqli_fetch_array($subserviceResult))
    {
        $data[] = array("id" => $subserviceRow["id"], "text" => $subserviceRow["title"]);
    }
	echo json_encode($data);
}
//number to word
function number_to_word($num)
{
	$num    = ( string ) ( ( int ) $num );
   
    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );
       
        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
       
        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');
       
        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');
       
        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');
       
        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );
       
        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
           
            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
       
        $commas = count( $words );
       
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }
       
        $words  = implode( ', ' , $words );
       
        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , ( ucwords( $words ) ) ) , ', ' );
        if( $commas )
        {
            $words  = str_replace( ',' , ' and' , $words );
        }
       
        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}
//Pending Amount
function pendingamount($connection,$customerId,$amount,$pendingAmount,$recivedAmount)
{
    $query = "UPDATE `customer` SET
    `amount` = '".$amount."',
    `pendingAmount` = '".$pendingAmount."',
    `recivedAmount` = '".$recivedAmount."'
    WHERE `id` = '".$customerId."'
    ";

    return mysqli_query($connection, $query);
}
//Send Email
function smtp_mailer($to, $subject, $msg,$file = "")
{
	$mail = new PHPMailer();
	//$mail->SMTPDebug  = 3;
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Host = "mail.bookyourwheels.in";
	$mail->Port = 587;
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Username = "support@bookyourwheels.in";
	$mail->Password = "support@2022";
	$mail->SetFrom("support@bookyourwheels.in","BookYourWheels Luxury");
	$mail->addCC("rafid@bookyourwheels.in");
	$mail->addCC("rayid@bookyourwheels.in");
	$mail->addCC("faizan@bookyourwheels.in");
	
	$mail->Subject = $subject;
	$mail->Body = $msg;
	$mail->AddAddress($to);
    if($file != "")
    {
        $mail->addAttachment($file);
    }
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => false
	));
	if ($mail->Send())
    {}
	// } else
    // {
	// 	return $mail->ErrorInfo;;
	// }
}
//Find Total Day between Two Date
function day_interval($pickup_date,$drop_date)
{
    // Creates DateTime objects
    // $date1 = DateTime::createFromFormat('m-d-Y', $pickup_date);
    // $date2 = DateTime::createFromFormat('m-d-Y', $drop_date);
    
    //  $datetime1 = new DateTime($date1);
    // $datetime2 = new DateTime($date2);
    //echo date_format($datetime2, 'Y-m-d');
    $datetime1 = date_create(date('Y-m-d',strtotime(str_replace("/", "-", $pickup_date))));
  $datetime2 = date_create(date('Y-m-d',strtotime(str_replace("/", "-", $drop_date))));
    
    // Calculates the difference between DateTime objects
    $interval = date_diff($datetime1, $datetime2);
   // Display the result
   return $interval->format('%a');
}
//find number of between two day
function number_of_day($pickup_date,$pickupTime,$drop_date,$dropTime)
{
    // Creates DateTime objects
    // $date1 = DateTime::createFromFormat('m-d-Y H:i', $pickup_date." ".$pickupTime);
    // $date2 = DateTime::createFromFormat('m-d-Y H:i', $drop_date." ".$dropTime);
    // $d1 = new DateTime($pickup_date." ".$pickupTime);
    // $d2 = new DateTime($drop_date." ".$dropTime);

    $d1 = date_create(date('d-m-Y H:i',strtotime(str_replace("/", "-", $pickup_date)." ".$pickupTime)));
    $d2 = date_create(date('d-m-Y H:i',strtotime(str_replace("/", "-", $drop_date)." ".$dropTime)));
    $interval = $d1->diff($d2);
    $diffInSeconds = $interval->s; //45.
    $diffInMinutes = $interval->i; //23.
    $diffInHours = $interval->h; //8.
    $diffInDays = $interval->d; //21.

    return json_encode(array($diffInDays,$diffInHours));
} 
//Find Driver Bhtta
function getdriverbhattaapplicable($pickup_date,$pickup_time,$drop_date,$drop_time)
{
    $datee = date("Y-m-d",strtotime(strtr($pickup_date,"/", "-")));
    $newDateTimee = date('Y-m-d H:i A',strtotime($datee.$pickup_time));
    //$newDateTimee = date('Y-m-d H:i A', strtotime($currentDateTime));
    $enddatee =date("Y-m-d",strtotime(strtr($drop_date,"/", "-")));
    $endtripdatetimee = date('Y-m-d H:i A', strtotime($enddatee.$drop_time));
    //$endtripdatetimee = date('Y-m-d H:i A', strtotime($endtripDateTime));
    //next day 6 am time
    $currentDateTime_a = $datee.'22:00:00';
    $newDateTime_a = date('Y-m-d H:i A', strtotime($currentDateTime_a));
    $nxt = date('Y-m-d', strtotime($datee. ' + 1 days'));
    $currentDateTime_b = $nxt.'06:00:00';
    $newDateTime_b = date('Y-m-d H:i A', strtotime($currentDateTime_b));
    
    // echo "<pre>";
    // echo $newDateTimee."<br>";
    // echo $endtripdatetimee."<br>";
    // echo $newDateTime_a."<br>";
    // echo $newDateTime_b."<br>";
    // echo "</pre>";
    // exit;
    if(($newDateTimee >= $newDateTime_a) && ($newDateTimee <= $newDateTime_b) || ($endtripdatetimee >= $newDateTime_a) && ($endtripdatetimee <= $newDateTime_b))
    {
        return "yes";
    }
    else
    {
        return "no";
    }
}

function getDriverBhatta($pickup_date,$pickup_time,$drop_date,$drop_time){
   $startTime = strtotime($pickup_time);
   $endTime = strtotime($drop_time);

   
    // check is start and end is same date
    $checkDateSame = false;
    if($pickup_date == $drop_date){
        $checkDateSame = true;
    }

    /* get bteween days night hours */
    $night_Hours = 0;
    if(!$checkDateSame)
    {
        $btw_Days =  day_interval($pickup_date,$drop_date) - 1;
        $night_Hours = $btw_Days * 8;
    }

    // if same date, Check start time is between night hours
    if($checkDateSame){
        $checkStart = false;
        if(1666895460 <= $startTime){
    
        }
    }

    $first_day_night_hours = '';
    $last_day_night_hours = '';

    // echo strtotime($pickup_time);
    // echo date("H:i", strtotime("10:00 PM"));
    //echo strtotime('00:01');
// 10PM -> 1666974660
// 1666974600

    // echo die;
}
?>