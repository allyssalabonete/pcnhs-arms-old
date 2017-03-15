<?php
    session_start();

    if(!isset($_SESSION['logged_in']) && !isset($_SESSION['account_type'])){
      header('Location: ../../login.php');
    }
    // Session Timeout
    $time = time();
    $session_timeout = 1800; //seconds
    
    if(isset($_SESSION['last_activity']) && ($time - $_SESSION['last_activity']) > $session_timeout) {
      session_unset();
      session_destroy();
      session_start();
    }

    $_SESSION['last_activity'] = $time;
  ?>
<?php require_once "../../resources/config.php"; ?>
<?php $stud_id = $_GET['stud_id'] ?>
<!-- Update Database -->
<?php
	if(!$conn) {
		die();
	}
	$cred_id = $_POST['credential'];
	$request_type = $_POST['request_type'];
    $signatory = $_POST['signatory'];
	$personnel_id = $_SESSION['per_id'];
	$date = $_POST['date'];

	$statement1 = "INSERT INTO `pcnhsdb`.`requests` (`cred_id`, `stud_id`, `request_type`, `status`, `date_processed`, `sign_id`, `per_id`) VALUES ('$cred_id', '$stud_id', '$request_type', 'u', '$date', '$signatory', '$personnel_id');";

	$statement2 = "INSERT INTO `pcnhsdb`.`unclaimed` (`date_processed`) VALUES ('$date');";

	//mysqli_query($conn, $statement1);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
		
		<!-- Bootstrap -->
		<link href="../../resources/libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="../../resources/libraries/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		
		<!-- Datatables -->
		<link href="../../resources/libraries/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom Theme Style -->
		<link href="../../css/custom.min.css" rel="stylesheet">
		<link href="../../css/tstheme/style.css" rel="stylesheet">
		<link rel="stylesheet" href="../../css/form137print.css">

		<style type="text/css" media="print">
		   .no-print { display: none; }
		</style>
	</head>
	<body class="nav-md">
		
		<div class="right_col" role="main">

			<?php
				$DAmonth = $_POST['month'];
                $DAday = $_POST['day'];
                $DAyear = $_POST['year'];
				$admittedto = $_POST['admittedto'];
				$remarks = $_POST['remarks'];
			?>

             <?php
             $birth_date = "";
             $box1 = "SELECT *, concat(last_name, ', ', first_name, ' ', upper(left(mid_name, 1)), '.') as 'full_name' FROM students NATURAL JOIN parent NATURAL JOIN primaryschool WHERE students.stud_id = '$stud_id';";
             $result = $conn->query($box1);
             if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $name = $row['full_name'];
                    $province = $row['province'];
                    $barangay = $row['barangay'];
                    $towncity = $row['towncity'];
                    $pname = $row['pname'];
                    $occupation = $row['occupation'];
                    $address = $row['address'];
                    $total_elem_years = $row['total_elem_years'];
                    $psname = $row['psname'];
                    $pschool_year = $row['pschool_year'];
                    $stud_id = $row['stud_id'];
                    $birth_date = $row['birth_date'];
                    $gender = $row['gender'];
                }
             }

             $birthday = explode("/", $birth_date);

             $bday = $birthday[0];
             $bmonth = $birthday[1];
             $byear = substr($birthday[2], 0, 4);
            

             if($bmonth < 10) {
                $bmonth = substr($bmonth, 1, 1);
             }

              $month_array = array('January','February','March','April','May','June','July','August','September','October','November','December');
              $monthstr = $month_array[$bmonth-1];
             ?>

             <?php
             //regular or irregular
             if(strtoupper($gender) == 'MALE' || strtoupper($gender) == 'M') {
                $formgender = "he";
             }else {
                $formgender = "she";
             }
             ?>

             <?php 

             if(!$conn) {
                die();
             }

             $stat = "";
             $grstmt = "SELECT * FROM studentsubjects where stud_id = '$stud_id' and comment = 'failed'";

             $result = $conn->query($grstmt);

             if($result->num_rows>0) {
                $stat = "irregular";
             }else {
                $stat = "regular";
             }



             ?>

             <?php

             $statement = "SELECT * FROM personnel WHERE per_id='$personnel_id'";
             $result = $conn->query($statement);
             if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $registrar_id = $row['per_id'];
                        $registrar_name = $row['first_name'].' '.substr($row['mname'], 0, 1).'. '.$row['last_name'];
                        $registrar_name = strtoupper($registrar_name);
                        $position_reg = $row['position'];
                        $position_reg = strtolower($position_reg);
                        $position_reg = ucfirst($position_reg);
                    }
             }
             ?>

             <?php

             $statement = "SELECT * FROM signatories WHERE sign_id='$signatory'";
             $result = $conn->query($statement);
             if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $sign_id = $row['sign_id'];
                        $sign_name = $row['first_name'].' '.substr($row['mname'], 0, 1).'. '.$row['last_name'];
                        $sign_name = strtoupper($sign_name);
                        $position = $row['position'];
                        $position = strtolower($position);
                        $position = ucfirst($position);
                    }
             }
             ?>
			<!-- FORM 137 -->
			<div class="col-md-12">
			<div class = "container">
       			 <div class = "content">
            <div class = "main">
                    <img src="../../images/doe.png" id="img-1"></img>
                    <img src="../../images/p.jpg" id="img-2"></img>
                <div id = heading-1>
                    <!--DepEd logo and PCNHS logo-->
                    
                    <h4 class = "inst">Republic of the Philippines</h4>
                    <h4 class = "inst">Department of Education</h4>
                    <h4 class = "inst">Cordillera Administrative Region</h4>
                    <h4 class = "inst">PINES CITY NATIONAL HIGH SCHOOL</h4>
                    <h3 class = "info">Palma Street,     Baguio City</h3>
                    <h3 class = "info">Tel.Nos. (074) 445-5937 / (074) 304-1124</h3>
                </div>


                <p id = "formname">FORM 137-A (K-12 Curriculum)</p>

                <!--box-1-->
                <div id = "box-1">
                    
                    <p id = "b1-r1-p1">Name:</p>            
                        <div id = "b1-r1-d1" class="underline">
                           <?php echo $name; ?> 
                        </div>

                    <p id = "b1-r1-p2">Date of Birth:</p>

                    <p id="b1-r1-p3">Year:</p>
                        <div id ="b1-r1-d2" class="underline">
                            <?php echo $byear; ?>
                        </div>
                    
                    <p id="b1-r1-p4">Month:</p>
                        <div id="b1-r1-d3" class="underline">
                            <?php echo $monthstr; ?>
                        </div>

                    <p id="b1-r1-p5">Day:</p>
                        <div id="b1-r1-d4" class="underline">
                            <?php echo $bday; ?>
                        </div>
                   

                    <p id="b1-r2-p1">Place of Birth:</p>
                    

                    <p id="b1-r2-p2">Province:</p>
                        <div id="b1-r2-d1" class="underline">
                            <?php echo $province; ?>
                        </div>
                    

                    <p id="b1-r2-p3">Municipality/City:</p>
                        <div id="b1-r2-d2" class="underline">
                            <?php echo $towncity; ?>
                        </div>
                    

                    <p id="b1-r2-p4">Barangay:</p>
                        <div id="b1-r2-d3" class="underline">
                            <?php echo $barangay; ?>
                        </div>
                       
                    

                    <p id="b1-r3-p1">Parent/Guardian:</p>
                        <div id="b1-r3-d1" class="underline">
                            <?php echo $pname; ?>
                        </div>
                    

                    <p id="b1-r3-p2">Occupation:</p>
                        <div id="b1-r3-d2" class="underline">
                            <?php echo $occupation; ?>
                        </div>
                    

                    <p id="b1-r4-p1">Address of Parent/Guardian:</p>
                        <div id="b1-r4-d1" class="underline">
                            <?php echo $address; ?>
                        </div>
                    

                    <p id="b1-r5-p1">Elementary Course Completed:</p>
                        <div id="b1-r5-d1" class="underline">
                            <?php echo $total_elem_years; ?>
                        </div>
                    

                    <p id="b1-r5-p2"></p>
                        <div id="b1-r5-d2" class="underline">
                            <?php echo $psname; ?>
                        </div>
                    

                    <p id="b1-r5-p3">Year:</p>
                        <div id="b1-r5-d3" class="underline">
                            <?php echo $pschool_year; ?>
                        </div>
                    

                    <p id="b1-r6-p1">LRN:</p>
                        <div id="b1-r6-d1" class="underline">
                            <?php echo $stud_id; ?>
                        </div>
                    
                   </div>

                   <span></span>
                   <!--end of box-1-->
                   <!-- First Year -->
                    <?php

                            if(!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            }
                            $stud_id = $_GET['stud_id'];
                            $query = "SELECT distinct(schl_name) as 'schl_name', studentsubjects.yr_level, studentsubjects.schl_year FROM pcnhsdb.studentsubjects left join subjects on studentsubjects.subj_id = subjects.subj_id left join pcnhsdb.grades on studentsubjects.stud_id = grades.stud_id where studentsubjects.yr_level = 1 and studentsubjects.stud_id = '$stud_id';";
                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    $schl_name1 = $row['schl_name'];
                                    $yr_level1 = $row['yr_level'];
                                    $schl_year1 = $row['schl_year'];
                                }
                            }
                            

                        ?>

                   <!-- First Year -->
                   <!--box-2-->
                    <div id = "box-2" class="gr">

                            <div id="info">

                                <p id="b2-r1-p1">School:</p>
                                    <div id="b2-r1-d1" class="underline"><?php echo $schl_name1; ?></div>
                                

                                <p id="b2-r2-p1">Grade:</p>
                                    <div id="b2-r2-d1" class="underline"><?php echo $yr_level1; ?></div>
                                

                                <p id="b2-r2-p2">School Year:</p>
                                    <div id="b2-r2-d2" class="underline"><?php echo $schl_year1; ?></div>
                                

                            </div>


                            <div id="1st-yr">

                                <table id="1st-t">
                                
                                <thead>
                                    <tr id="b2-r3-head">  
                                    <th class="col1">SUBJECT</th>
                                    <th class="col2">Final Rating</th>
                                    <th class="col3">Action Taken</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php

                                    if(!$conn) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }
                                    $query = "SELECT subj_name, subj_level, fin_grade, unit, comment FROM pcnhsdb.studentsubjects left join subjects on studentsubjects.subj_id = subjects.subj_id where yr_level = 1 and stud_id = '$stud_id';";
                                    $result = $conn->query($query);
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while($row = $result->fetch_assoc()) {
                                            $subj_name1 = $row['subj_name'];
                                            $subj_level1 = $row['subj_level'];
                                            $fin_grade1 = $row['fin_grade'];
                                            $unit1 = $row['unit'];
                                            $comment1 = $row['comment'];

                                            echo <<<YR1
                                               
                                                <tr id="b2-r4">  
                                                    <td class="subj">$subj_name1</td> <!-- subject -->
                                                    <td class="fr">$fin_grade1</td> <!-- final rating -->
                                                    <td class="at">$comment1</td> <!-- Action Taken -->
                                                </tr>

YR1;
                                        }
                                    }
                                    

                                ?>
                            </tbody>
                                
                            </table>

                                <p id="b2-r18-p1">Days of School:</p>
                                <div id="b2-r18-d1" class="underline"></div>

                                <p id="b2-r18-p2">Days Present:</p>
                                <div id="b2-r18-d2" class="underline"></div>

                                <p id="b2-r19-p1">Total Number of Years in School:</p>
                                <div id="b2-r19-d1" class="underline"></div>
                        </div>

                    </div>
                    <?php

                            if(!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            }
                            $stud_id = $_GET['stud_id'];
                            $query = "SELECT distinct(schl_name) as 'schl_name', studentsubjects.yr_level, studentsubjects.schl_year FROM pcnhsdb.studentsubjects left join subjects on studentsubjects.subj_id = subjects.subj_id left join pcnhsdb.grades on studentsubjects.stud_id = grades.stud_id where studentsubjects.yr_level = 2 and studentsubjects.stud_id = '$stud_id';";
                            $result = $conn->query($query);
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    $schl_name2 = $row['schl_name'];
                                    $yr_level2 = $row['yr_level'];
                                    $schl_year2 = $row['schl_year'];
                                }
                            }
                            

                        ?>
                    <div id = "box-3" class="gr">

                            <div id="info">

                                <p id="b2-r1-p1">School:</p>
                                    <div id="b2-r1-d1" class="underline"><?php echo $schl_name2; ?></div>
                                

                                <p id="b2-r2-p1">Grade:</p>
                                    <div id="b2-r2-d1" class="underline"><?php echo $yr_level2; ?></div>
                                

                                <p id="b2-r2-p2">School Year:</p>
                                    <div id="b2-r2-d2" class="underline"><?php echo $schl_year2; ?></div>
                                

                            </div>
                            <div id="2nd-yr">

                                <table id="2nd-t">
                                <thead>
                                    <tr id="b3-r3-head">  
                                    <th class="col1">SUBJECT</th>
                                    <th class="col2">Final Rating</th>
                                    <th class="col3">Action Taken</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                               
                                <tr id="b3-r4">  
                                <td class="subj">sample</td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r5">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r6">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r7">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r8">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r9">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r10">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r11">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r12">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r13">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r14">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r15">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r16">  <!-- additional subject -->
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r17">  <!-- additional subject -->
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>
                                 </tbody>
                            </table>

                                <p id="b2-r18-p1">Days of School:</p>
                                <div id="b2-r18-d1" class="underline"></div>

                                <p id="b2-r18-p2">Days Present:</p>
                                <div id="b2-r18-d2" class="underline"></div>

                                <p id="b2-r19-p1">Total Number of Years in School:</p>
                                <div id="b2-r19-d1" class="underline"></div>
                        </div>

                        
                    </div>

                        <!--end of box-2-->

                     <!--box-3-->

                     <div id = "box-4-5">
                    <div id = "box-4" class="gr">

                            <div id="info">

                                <p id="b2-r1-p1">School:</p>
                                    <div id="b2-r1-d1" class="underline"></div>
                                

                                <p id="b2-r2-p1">Grade:</p>
                                    <div id="b2-r2-d1" class="underline"></div>
                                

                                <p id="b2-r2-p2">School Year:</p>
                                    <div id="b2-r2-d2" class="underline"></div>
                                

                            </div>
                            <div id="3rd-yr">

                                <table id="3rd-t">
                                
                                <tr id="b3-r3-head">  
                                <th class="col1">SUBJECT</th>
                                <th class="col2">Final Rating</th>
                                <th class="col3">Action Taken</th>
                                </tr>

                                <tr id="b3-r4">  
                                <td class="subj">sample</td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r5">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r6">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r7">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r8">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r9">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r10">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r11">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r12">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r13">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r14">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r15">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r16">  <!-- additional subject -->
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r17">  <!-- additional subject -->
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                            </table>

                                <p id="b2-r18-p1">Days of School:</p>
                                <div id="b2-r18-d1" class="underline"></div>

                                <p id="b2-r18-p2">Days Present:</p>
                                <div id="b2-r18-d2" class="underline"></div>

                                <p id="b2-r19-p1">Total Number of Years in School:</p>
                                <div id="b2-r19-d1" class="underline"></div>
                        </div>

                        
                    </div>

                    <div id = "box-5" class="gr">

                            <div id="info">

                                <p id="b2-r1-p1">School:</p>
                                    <div id="b2-r1-d1" class="underline"></div>
                                

                                <p id="b2-r2-p1">Grade:</p>
                                    <div id="b2-r2-d1" class="underline"></div>
                                

                                <p id="b2-r2-p2">School Year:</p>
                                    <div id="b2-r2-d2" class="underline"></div>
                                

                            </div>
                            <div id="4th-yr">

                                <table id="4th-t">
                                
                                <tr id="b3-r3-head">  
                                <th class="col1">SUBJECT</th>
                                <th class="col2">Final Rating</th>
                                <th class="col3">Action Taken</th>
                                </tr>

                                <tr id="b3-r4">  
                                <td class="subj">sample</td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r5">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r6">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r7">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r8">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r9">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r10">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r11">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r12">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r13">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r14">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r15">  
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r16">  <!-- additional subject -->
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                                <tr id="b3-r17">  <!-- additional subject -->
                                <td class="subj"></td> <!-- subject -->
                                <td class="fr"></td> <!-- final rating -->
                                <td class="at"></td> <!-- Action Taken -->
                                </tr>

                            </table>

                                <p id="b2-r18-p1">Days of School:</p>
                                <div id="b2-r18-d1" class="underline"></div>

                                <p id="b2-r18-p2">Days Present:</p>
                                <div id="b2-r18-d2" class="underline"></div>

                                <p id="b2-r19-p1">Total Number of Years in School:</p>
                                <div id="b2-r19-d1" class="underline"></div>
                        </div>

                        
                    </div>
                    </div>

                        <div id="box-6">

                            <p id="b6-r1-p1">SUMMER/REMEDIAL CLASS</p>

                            <p id="b6-r1-p2">School:</p>
                            <div id="b6-r1-d1"></div>

                            <p id="b6-r1-p3">School Year:</p>
                            <div id="b6-r1-d2"></div>

                            <!-- <p id="b6-r2-p1">Subject</p> 
                            <p id="b6-r2-p2">Final Rating</p> 
                            <p id="b6-r2-p3">Action Taken</p> -->

                            <table id="additional-t">
                                
                                <tr id="b6-r2-head">  
                                <th class="add-col1">SUBJECT</th>
                                <th class="add-col2">Final Rating</th>
                                <th class="add-col3">Action Taken</th>
                                </tr>

                                <tr id="b6-r3">  <!-- additional subject -->
                                <td class="add-subj"></td> <!-- subject -->
                                <td class="add-fr"></td> <!-- final rating -->
                                <td class="add-at"></td> <!-- Action Taken -->
                                </tr>

                            </table>

                            <!-- <div class="subject-name">
                                    
                                </div>

                                <div class="final-rating">
                                    
                                </div>

                                <div class="action-taken"> -->                           
                        
                            <p id="b6-r3-p4">Days of School:</p>
                                <div id="b6-r3-d1"></div>

                            <p id="b6-r3-p5">Days Present:</p>
                                <div id="b6-r3-d2"></div>
                            <!-- </div> -->

                        </div>

                        <div id="box-7">

                            <div id="cert">I certify that this is a true copy of the records of <div id="name-cert"> <?php echo $name; ?> </div> This student is eligible on</br> this <div id="day-cert"> <?php echo $DAday; ?> </div> day of <div id="month-cert"> <?php echo $DAmonth; ?> </div> <div id="year"> <?php echo $DAyear; ?> </div> for admission to <div id="grade-cert"> <?php echo $admittedto; ?> </div> as a <div id="reg-cert"> <?php echo $stat; ?> </div> student and <div id="gender-cert"> <?php echo $formgender; ?> </div> has no</br> property and/or money accountability in this school.</div>

                            <p id="b7-r1-p1">REMARKS:</p>
                            <div id="b7-r1-d1"></div>

                            <p id="b7-r1-p2">ISSUED TO: <div id="b7-r1-d2"><?PHP echo $remarks; ?></div></p>



                            <p id="b7-r2-p1">NOTE: A mark, erasure or alternation of any entry invalidates this form.</p>

                            <p id="b7-r4-p1">not valid without seal</p>




                            <div id="box-8">
                            <p id="b8-r1-p1">Prepared by:</p>
                            <div id="b8-r2-name"> <?php echo $registrar_name; ?></div>
                            <div id="b8-r3-pos"> <p> <?php echo $position_reg; ?> </p></div>
                            </div>

                            <div id="box-9">
                            <p id="b9-r1-p1">Checked &amp; Verified by:</p>
                            <div id="b9-r2-name"> <?php echo $sign_name; ?> </div>
                            <div id="b9-r3-pos"> <p> <?php echo $position; ?></p> </div>
                            </div>

                        </div>



                    </div>

                </div>
            </div>


			<!-- FORM 137 -->

			<div class="row no-print">
				<div class="col-xs-12">
					<button class="btn btn-success pull-right" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
				</div>
			</div>

		</div>
	
		<!-- Scripts -->
	</body>
</html>