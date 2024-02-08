<?php
// Initialize the session
session_start();
$_SESSION['page'] = 0;

if(!isset($_SESSION["loggedin"])){
    header("location: login.php");
    return;
}

if($_SESSION["role"] != 0){
    header("location: login.php");
    return;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['searchbyname'])){
        $_SESSION['searchTeacherEditClass'] = $_POST['inputteachername'];
        $_SESSION['searchDayEditClass'] = $_POST['inputday'];
        $_SESSION['searchClassEditClass']= $_POST['inputclass'];
        $_SESSION['searchStartTimeEditClass']= $_POST['inputstarttime'];
        $_SESSION['searchEndTimeEditClass']= $_POST['inputendtime'];
        $_SESSION['searchSubjectNameEditClass'] = $_POST['inputsubjectname'];
        $_SESSION['page'] = 0;
        $_SESSION["searchNamePT"] = $_POST["searchNameTxt"]; 
        $_SESSION['jenisformfilterviewformccs'] = $_POST['filterjenisform'];
        // $_SESSION['keteranganlainfilterformccs'] = $_POST['keteranganlain'];
        $_SESSION['reqdatetofilterviewformccs'] = $_POST['reqdateto'];
        $_SESSION['reqdatefromfilterviewformccs'] = $_POST['reqdatefrom'];
        $_SESSION['ccspicfilterviewformccs'] = $_POST['ccspicfilter'];
        $_SESSION['statusformfilterviewformccs'] = $_POST['filterstatusform'];
        $_SESSION['searchIDMY'] = $_POST['IDTxt'];
        $testes = date("Y-m-d");

        //echo "<script type='text/javascript'>alert('$testes');window.location.href='edit-class.php';</script>";
        header("location: edit-class.php");
        return;
    }

    

    if(isset($_POST['takejob'])){;
        $getCurrID = $_POST['idd'];
        $idd = $_SESSION['id'];
        date_default_timezone_set("Asia/Jakarta");
        $today = date("y-m-d H:i:s");

        $conn = new mysqli("localhost", "root", "", "kckrctb");
        $sql = "UPDATE msclassroom set pic_ccs_id = '$idd', takendate = '$today' where id = '$getCurrID'";
        if ($conn->query($sql) === TRUE) {
	    $sql = "UPDATE msallorder set pic_ccs_id = '$idd' where real_id = '$getCurrID' AND ordertype LIKE '%Form Aktivitas%'";
	    if ($conn->query($sql) === TRUE) {
		echo "<script type='text/javascript'>alert('Form Taken!');window.location.href='edit-class.php';</script>";
	    }            
        }
    }

    if(isset($_POST['paging'])){
        $_SESSION['page'] = $_POST['currpage'];
    }
    if(isset($_POST['jumpnext'])){
        $_SESSION['page'] = $_POST['hiddenpage']+1;
    }
    if(isset($_POST['jumpback'])){
        $_SESSION['page'] = $_POST['hiddenpage']-1;
    }
    if(isset($_POST['jumpfirst'])){
        $_SESSION['page'] = 0;
    }
    if(isset($_POST['jumplast'])){
        $_SESSION['page'] = $_SESSION['pagetotall']-1;
    }

    if(isset($_POST['editpicform'])){
        $getCurrID = $_POST['idd'];
        $idpembuatsurat = $_POST['picpembuatsurat'];
        $conn = new mysqli("localhost", "root", "", "kckrctb");

        $sql = "UPDATE msclassroom set pic_ccs_id = '$idpembuatsurat' where id = '$getCurrID'";
        if ($conn->query($sql) === TRUE) {
            echo "<script type='text/javascript'>alert('PIC telah diedit!');window.location.href='edit-class.php';</script>";
        }
    }

    if(isset($_POST['updateclass'])){
        $getCurrID = $_POST['iddclass'];
        $class = $_POST['classinput'];
        $day = $_POST['editday'];
        $start_time = $_POST['editstarttime'];
        $end_time = $_POST['editendtime'];
        $teacher_id = $_POST['selectteacher'];
        // $cis = $_POST['cis'];
        // $rek = $_POST['rekening'];
        // $ctx = $_POST['ctx'];
        $subject = $_POST['jenisform'];

        $conn = new mysqli("localhost","root", "", "kckrctb");
        $sql = "SELECT * from `mssubject` WHERE name LIKE '$subject'";
        $result1 = $conn->query($sql);
        $subject_id= 0;
                        
        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                        //data perusahaan
                $subject_id = $row['id'];
            }                 
        
            $conn->close();

            // if($copyasli==1 && $kelengkapan==2){
            //     $conn = new mysqli("localhost", "root", "", "kckrctb");
            //     $sql = "UPDATE msformccs set `status` = 'PENDING' where id = '$getCurrID'";
            //     if ($conn->query($sql) === TRUE) {
            //     }
            // }

            // if($tanggapan!=""){
            //     $conn = new mysqli("localhost", "root", "", "kckrctb");
            //     $sql = "UPDATE msformccs set `status` = 'REPLIED BY BO' where id = '$getCurrID'";
            //     if ($conn->query($sql) === TRUE) {
            //     }
            // }

            $conn = new mysqli("localhost", "root", "", "kckrctb");
            $sql = "UPDATE msschedule set class = '$class', day = '$day', `start_time` = '$start_time', `end_time` = '$end_time', subject_name = '$subject',
            subject_id = '$subject_id', teacher_id = '$teacher_id' where id = '$getCurrID'";
            if ($conn->query($sql) === TRUE) {
                echo "<script type='text/javascript'>alert('Class updated!');window.location.href='edit-class.php';</script>";
            }
        }
    }

    if(isset($_POST['deleteclass'])){
        $getCurrID = $_POST['iddclass'];

        $conn = new mysqli("localhost", "root", "", "kckrctb");
        $sql = "DELETE FROM msschedule WHERE id LIKE '$getCurrID'";
        if ($conn->query($sql) === TRUE) {
            echo "<script type='text/javascript'>alert('Class Deleted!');window.location.href='edit-class.php';</script>";
        }
        
    }
}

$nowpage = $_SESSION['page'];
$viewperpage = '10';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Class Schedule</title>
    <link rel="shortcut icon" href="../favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/css.css" rel="stylesheet">
    <script src="css/js.js"></script>
    <!-- <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.min.js"></script> -->
</head>

<script>

    function myFunction() {
        var selectBox = document.getElementById("filterjenisform");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        var y = document.getElementById("keteranganlain");
        
        if(selectedValue=="Lain - lain"){
            y.setAttribute("type", "text");
            // y.required = true;
            // document.getElementById("rek").required = true;
            // document.getElementById("cis").required = true;
        }
    }
</script>

</head>
<body>
    <?php include('/nav/nav-sa.php');?>
    <br><br>

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h1>Edit Class</h1>
            <hr>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="f1" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group" style="width:100%;">
                            <input type="text" name="inputsubjectname" class="form-control" placeholder="Search Subject Name" value="<?php echo $_SESSION['searchSubjectNameEditClass'];?>">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="input-group" style="width:120%;">
                            <input type="text" name="inputclass" class="form-control" placeholder="Class" value="<?php echo $_SESSION['searchClassEditClass'];?>">
                        </div>
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <!-- <div class="col-md-2">
                        Subject: 
                        <select class="form-select" id="filterjenisform" name="filterjenisform" onchange="myFunction()">
                            <option value="" <?php if($_SESSION['jenisformfilterviewformccs'] == ""){ echo "selected";} ?>>All</option>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "kckrctb");
                            $sql123 = "select * FROM msjenisformccs";
                            $result123 = $conn->query($sql123);
                            if ($result123->num_rows > 0) {
                                while($row123 = $result123->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $row123['name']; ?>" <?php if($_SESSION['jenisformfilterviewformccs'] == $row123['name']){ echo "selected";} ?>><?php echo $row123['name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="Lain - lain">(Lain - lain)</option>
                        </select>
                        <br>
                        <input style="width:420px" <?php if($_SESSION['keteranganlainfilterformccs'] == "") {?> type="hidden" <?php }?>  id="keteranganlain" name="keteranganlain" class="form-control" placeholder = "<?php echo $_SESSION['keteranganlainfilterformccs']; ?>">
                    </div> -->

                    <div class="col-md-2">
                        Day:
                        <input type="text" name="inputday" class="form-control" placeholder="Search Day" value="<?php echo $_SESSION['searchDayEditClass'];?>">
                    </div>

                    <div class="col-md-2">
                        Time From:
                        <input type="time" value="<?php echo $_SESSION['searchStartTimeEditClass']; ?>" class="form-control" name="inputstarttime">
                    </div>

                    <div class="col-md-2">
                        Time To:
                        <input type="time" value="<?php echo $_SESSION['searchEndTimeEditClass']; ?>" class="form-control" name="inputendtime">
                    </div>

                    <div class="col-md-2">
                        Teacher:
                        <input type="text" name="inputteachername" class="form-control" placeholder="Search Teacher Name" value="<?php echo $_SESSION['searchTeacherEditClass'];?>">
                    </div>

                    <!-- <div class="col-md-2">
                        Teacher: 
                        <select name="ccspicfilter" class="form-select">
                            <option value="" <?php if($_SESSION['ccspicfilterviewformccs'] == ""){ echo "selected";} ?>>All</option>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "kckrctb");
                            $sql123 = "select mk.name AS unt, mu.id AS id, mu.name AS name FROM msuser mu, msunitkerja mk WHERE mu.unit_kerja = mk.id AND mk.name LIKE '%CCS%' ORDER BY mu.sort asc";
                            $result123 = $conn->query($sql123);
                            if ($result123->num_rows > 0) {
                                while($row123 = $result123->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $row123['id']; ?>" <?php if($_SESSION['ccspicfilterviewformccs'] == $row123['id']){ echo "selected";} ?>><?php echo $row123['name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                        
                    </div> -->

                    <div class="col-md-1">
                        <br>
                        <input type="submit" style="width:100%;" name="searchbyname" value="Search" class="btn btn-success">
                    </div>

                    <!-- <div class="row">
                        <div class="col-md-1">
                            <input type="reset" value="reset">
                        </div>
                    </div>                    -->
                    <br><br><br>
                </div>
            </form>

            <hr>
            <?php
            $conn = new mysqli("localhost", "root", "", "kckrctb");
            $idd = $_SESSION['id'];
            $teacherfilter = $_SESSION['searchTeacherEditClass'];
            $dayfilter = $_SESSION['searchDayEditClass'];
            $classfilter = $_SESSION['searchClassEditClass'];
            $starttimefilter =  $_SESSION['searchStartTimeEditClass'];
            $endtimefilter = $_SESSION['searchEndTimeEditClass'];
            $subjectnamefilter = $_SESSION['searchSubjectNameEditClass'];
            // $searchN = $_SESSION['searchNamePT'];
            // $jenisformfilter = $_SESSION['jenisformfilterviewformccs'];
            // // $formfilterlain = $_SESSION['keteranganlainfilterformccs'];
            // $reqdateto = $_SESSION['reqdatetofilterviewformccs'];
            // $reqdatefrom = $_SESSION['reqdatefromfilterviewformccs'];
            // $ccspicfilter = $_SESSION['ccspicfilterviewformccs'];
            // $statusfilter = $_SESSION['statusformfilterviewformccs'];
            // $IDFilter = $_SESSION['searchIDMY'];
            $flag = 0;

            // if($formfilterlain <> ""){
            //     $jenisformfilter = $formfilterlain;
            // }

            // if($statusfilter == "NOT TAKEN"){
            //     $flag = 1;
            //     $statusfilter = "";
            // }

            // if($statusfilter == "NOT TAKEN BY CCS"){
            //     $flag = 2;
            //     $statusfilter = "";
            // }

            // If ($reqdateto == ""){
            //     $reqdateto = date("Y-m-d")." 23:59:59.000000";
            // }else{
            //     $reqdateto = $reqdateto." 23:59:59.000000";
            // }

            // If ($reqdatefrom == ""){
            //     $reqdatefrom = "2000-01-01 00:00:00.000000";
            // }else{
            //     $reqdatefrom = $reqdatefrom. " 00:00:00.000000";
            // }

            // $sql= "select * FROM msstudent where user_id LIKE '$idd'";
            // $class = "";

            // $result = $conn->query($sql);
            // if ($result->num_rows > 0) {
            //     while($row = $result->fetch_assoc()) {
            //         $class = $row['class'];
            //     }
            // }

            // $sql = "SELECT count(id) as rowtot from msschedule
            //         WHERE namaperusahaan LIKE '%".$searchN."%'
            //         AND jenisform LIKE '%".$jenisformfilter."%'
            //         AND status LIKE '%".$statusfilter."%'
            //         AND requestdate >= '$reqdatefrom'
            //         AND requestdate <= '$reqdateto'";\

            if($starttimefilter == ""){
                $starttimefilter = "00:00";
            }

            if($endtimefilter == ""){
                $endtimefilter = "23:59";
            }

            $sql = "SELECT count(a.id) as rowtot FROM `msschedule` a 
            LEFT JOIN mssubject b ON a.subject_id = b.id 
            LEFT JOIN msteacher c ON a.teacher_id = c.id
            WHERE a.class LIKE '%".$classfilter."%'
            AND
                a.start_time >= '$starttimefilter'
                AND a.end_time <= '$endtimefilter'
            
            AND b.name LIKE '%".$subjectnamefilter."%'
            AND c.name LIKE '%".$teacherfilter."%'
            AND a.day LIKE '%".$dayfilter."%'";

            // if($statusfilter == "PENDING"){
            //     $sql = "SELECT count(id) as rowtot from msclassroom 
            //         WHERE namaperusahaan LIKE '%".$searchN."%'
            //         AND jenisform LIKE '%".$jenisformfilter."%'
            //         AND (status LIKE '%PENDING%' OR status LIKE '%REPLIED BY BO%' OR status LIKE '%REPLIED BY CCS%')
            //         AND requestdate >= '$reqdatefrom'
            //         AND requestdate <= '$reqdateto'";
            // }

            // if($flag == 1){
            //     $sql = $sql." AND pic_nonccs_id IS NULL AND status NOT LIKE 'CANCELLED'";
            // }

            // if($flag == 2){
            //     $sql = $sql." AND pic_ccs_id IS NULL AND status NOT LIKE 'CANCELLED'";
            // }
            
            // if($ccspicfilter <> "" && $flag <> 2){
            //     $sql = $sql." AND pic_ccs_id = '$ccspicfilter'";
            // }

            // if($IDFilter <> ""){
            //     $sql = $sql. " AND id = '$IDFilter'";
            // }

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rowtotal = $row['rowtot'];
                }
            }
            $pagetotal = $rowtotal/$viewperpage;
            $pagetotal = ceil($pagetotal);
            $_SESSION['pagetotall'] = $pagetotal;
            $_SESSION['rowtotal'] = $rowtotal;
            $rangeoffset = $nowpage * $viewperpage;
            $conn->close();

            $conn = new mysqli("localhost", "root", "", "kckrctb");

            $sql = "SELECT a.id AS id, b.id AS subject_id, c.id AS teacher_id, b.name AS subject_name,
                    c.name AS teacher_name, a.day AS day, a.start_time AS start_time, a.end_time AS end_time, a.class AS class
                    FROM `msschedule` a 
                    LEFT JOIN mssubject b ON a.subject_id = b.id 
                    LEFT JOIN msteacher c ON a.teacher_id = c.id
                    WHERE a.class LIKE '%".$classfilter."%'
                    AND a.start_time >= '$starttimefilter'
                    AND a.end_time <= '$endtimefilter'
                    AND b.name LIKE '%".$subjectnamefilter."%'
                    AND c.name LIKE '%".$teacherfilter."%'
                    AND a.day LIKE '%".$dayfilter."%' ORDER BY id ASC";
            
            // $sql = "select * FROM msclassroom WHERE namaperusahaan LIKE '%".$searchN."%' 
            //         AND jenisform LIKE '%".$jenisformfilter."%'
            //         AND status LIKE '%".$statusfilter."%'
            //         AND requestdate >= '$reqdatefrom'
            //         AND requestdate <= '$reqdateto'";

            // if($statusfilter == "PENDING"){
            //     $sql = "select * FROM msclassroom WHERE namaperusahaan LIKE '%".$searchN."%' 
            //         AND jenisform LIKE '%".$jenisformfilter."%'
            //         AND (status LIKE '%PENDING%' OR status LIKE '%REPLIED BY BO%' OR status LIKE '%REPLIED BY CCS%')
            //         AND requestdate >= '$reqdatefrom'
            //         AND requestdate <= '$reqdateto'";
            // }

            // if($flag == 1){
            //     $sql = $sql." AND pic_nonccs_id IS NULL AND status NOT LIKE 'CANCELLED'";
            // }

            // if($flag == 2){
            //     $sql = $sql." AND pic_ccs_id IS NULL AND status NOT LIKE 'CANCELLED'";
            // }

            // if($ccspicfilter <> "" && $flag <> 2){
            //     $sql = $sql." AND pic_ccs_id = '$ccspicfilter'";
            // }

            // if($IDFilter <> ""){
            //     $sql = $sql. " AND id = '$IDFilter'";
            // }

            // $sql = $sql." ORDER BY requestdate DESC";

            // $sql = $sql." limit $viewperpage offset $rangeoffset";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                ?>
                
                <table class="table table-hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="col-md-1" style="width:6%;">No.</th>
                            <th class="col-md-2" style="width:10%;">Day</th>
                            <th class="col-md-1" style="width:10%;">Time</th>
                            <th class="col-md-1" style="width:9%;">Subject Name</th>
                            <th class="col-md-1" style="width:9%;">Teacher</th>
                            <th class="col-md-1" style="width:10%;">Action</th>
                            <!-- <th class="col-md-1" style="width:15%;">Class</th>
                            <th class="col-md-2" style="width:27%;">Action</th> -->
                            <!-- <th class="col-md-2" style="width:2%;">Urgent</th> -->
                        </tr>
                    </thead>
                    <?php
                    while($row = $result->fetch_assoc()) {
                        // $cis = $row['cis'];
                        // $rek = $row['rekening'];
                        // $requestdate = date("d-m-Y", strtotime($row['requestdate']));
                        // $copyasli = $row['copyasli'];
                        $idd = $row['id'];
                        $day = $row['day'];
                        $starttime = $row['start_time'];
                        $endtime = $row['end_time'];
                        $subject_id = $row['subject_id'];
                        $subject_name = $row['subject_name'];
                        $teacher_id = $row['teacher_id'];
                        $class = $row['class'];

                        // if ($copyasli == 1){
                        //     $copyasli = "Copy";
                        // } else if ($copyasli == 2) {
                        //     $copyasli = "Asli";
                        // } else if ($copyasli == 3){
                        //     $copyasli = "No Form";
                        // } else if ($copyasli == 4){
                        //     $copyasli = "Asli di cabang";
                        // }

                        // $kelengkapan = $row['kelengkapandokumen'];

                        // if ($kelengkapan == 1){
                        //     $kelengkapan = "Lengkap";
                        // } else if($kelengkapan == 2) {
                        //     $kelengkapan = "Tidak Lengkap";
                        // } else if($kelengkapan == 3){
                        //     $kelengkapan = "No Form";
                        // }

                        // $name = $row['namaperusahaan'];
                        // $jenisform = $row['jenisform'];
                        // $sistembca = $row['sistem_bca'];
                        // $sistemdonedate = $row['sistem_done_date'];
                        // $idd = $row['id'];
                        // $idpic = $row['pic_ccs_id'];
                        // $idpicnonccs = $row['pic_nonccs_id'];
                        // $status = $row['status'];
                        // $picctx = $row['pic_ctx'];
                        // // $requestedby = $row['requestedby'];
                        // $pic = "";
                        // $picnonccs = "";
                        // $finalizer = "";
                        // $finalizerid = $row['finalized_by'];
                        // $dir = $row['folder'];
                        // $takendate = $row['takendate'];
                        // $findate = $row['finalizedate'];
                        // $keterangantambahan = $row['keterangan_tambahan'];
                        // $tanggapan = $row['tanggapan_keterangan_tambahan'];
                        // // $tanggapanccs = $row['tanggapan_keterangan_tambahan_ccs'];
                        // $keteranganrejected = $row['keterangan_reject'];
                        // $urgent = $row['urgent'];

                        // $kekurangancount = 0;
                        
                        // if($takendate <> 0){
                        //     $takendate = date("d-m-Y", strtotime($takendate));
                        // } else{
                        //     $takendate = "-";
                        // }

                        // if($findate <> 0){
                        //     $findate = date("d-m-Y", strtotime($findate));
                        // } else{
                        //     $findate = "-";
                        // }

                        // if($sistemdonedate <> 0){
                        //     $sistemdonedate = date("d-m-Y", strtotime($sistemdonedate));
                        // } else{
                        //     $sistemdonedate = "-";
                        // }

                        // if(($idpicnonccs == "" || $idpic == "") && $status !="CANCELLED"){
                        //     $status = "NOT TAKEN";
                        // }

                        $conn = new mysqli("localhost", "root", "", "kckrctb");
                        $sql1 = "select * FROM msteacher where id LIKE '$teacher_id'";
                        $result1 = $conn->query($sql1);
                        $name = "";
			
                        if ($result1->num_rows > 0) {
                            while($row1 = $result1->fetch_assoc()) {
                                $name = $row1['name'];
                            }
                        }

                        ?>
                        <tr style="text-align: left;">
                            <td style="vertical-align:middle;"><?php echo $idd;?></td>
                            <td style="vertical-align:middle;"><?php echo $day;?></td>
                            <td style="vertical-align:middle;"><?php echo $starttime;?> - <?php echo $endtime;?></td>
                            <td style="vertical-align:middle;"><?php echo $subject_name;?></td>
                            <td style="vertical-align:middle;"><?php echo $name;?></td>
                            <td style="vertical-align:middle;">
                                <div style="display: inline-block;">
                                    <a type="button" class="btn btn-primary" style="color: White;" data-bs-toggle="modal" data-bs-target="#a<?php echo $idd;?>">
                                        Edit Details
                                        <!-- <?php if($copyasli <> "") { ?>
                                        <svg xmlns="css/check-circle-fill.svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                        <?php } else{ ?>
                                        <svg xmlns="css/info-circle.svg" width="20" height="20" fill="Red" class="bi bi-info-circle border-0" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                        </svg>
                                        <?php } ?> -->
                                    </a>

                                    <!-- <a type="button" class="btn btn-info" style="color: White;" data-bs-toggle="modal" data-bs-target="#b<?php echo $idd;?>">
                                        File
                                        <span class="badge rounded-pill bg-danger"><?php echo sizeof($links) + sizeof($links2); ?></span>
                                    </a>

                                    <a type="button" class="btn btn-warning" style="color: White;" data-bs-toggle="modal" data-bs-target="#c<?php echo $idd;?>">
                                        FU
                                        <span class="badge rounded-pill bg-danger"><?php echo $countfu; ?></span>
                                    </a> -->
                                </div>

                                <div id="a<?php echo $idd;?>" class="modal fade">
                                    <div class="modal-dialog" style="max-width:465px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"><strong>Class Details</strong></h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                                    <!-- <div class="row">
                                                        <div class="col-sm-5">PIC CCS</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $pic; ?></strong></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-5">PIC Layanan Operasi</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $picnonccs; ?></strong></div>
                                                    </div>

                                                    <?php
                                                        $requestdate = date("d/m/Y H:i:s", strtotime($row['requestdate']));
                                                        if($takendate <> "-") $takendate = date("d/m/Y H:i:s", strtotime($row['takendate']));
                                                        if($findate <> "-") $findate = date("d/m/Y H:i:s", strtotime($row['finalizedate']));
                                                        if($sistemdonedate <> "-") $sistemdonedate = date("d/m/Y H:i:s", strtotime($row['sistem_done_date']));
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-5">Request Date</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $requestdate; ?></strong></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-5">Taken Date</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $takendate; ?></strong></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-5">Finalize Date</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $findate; ?></strong></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-5">Finalized By</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $finalizer; ?></strong></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-5">Finalize Sistem BCA Date</div>
                                                        <div class="col-sm-1">:</div>
                                                        <div class="col-sm-6"><strong><?php echo $sistemdonedate; ?></strong></div>
                                                    </div> -->

                                                    <!-- <hr> -->

                                                    <label>Class:</label>
                                                    <input  class="form-control" style="width:420px;" name="classinput" value="<?php echo $class; ?>">
                                                    <br>

                                                    <label>Day:</label>
                                                    <input  class="form-control" style="width:420px;" name="editday" value="<?php echo $day; ?>">
                                                    <br>

                                                    <label>Start Time:</label>
                                                    <input  class="form-control" name="editstarttime" style="width:420px" value="<?php echo $starttime; ?>">
                                                    <br>

                                                    <label>End Time:</label>
                                                    <input  class="form-control" name="editendtime" style="width:420px" value="<?php echo $endtime; ?>">
                                                    <br>

                                                    <label>Subject:</label>
                                                    <select id="jenisform" name="jenisform" class="form-select" style="width:420px" onchange="myFunction()" required>
                                                        <option value="" disabled selected>Select your option</option>
                                                    <?php
                                                    $conn = new mysqli("localhost","root", "", "kckrctb");
                                                    $sql = "SELECT * from `mssubject` ORDER BY name";
                                                    $result1 = $conn->query($sql);
                                                                    
                                                    if ($result1->num_rows > 0) {
                                                        while ($row = $result1->fetch_assoc()) {
                                                                    //data perusahaan
                                                            $id12 = $row['id'];
                                                            $name12= $row['name'];
                                                        ?>
                                                        
                                                            <option value="<?php echo $name12?>" <?php if($subject_id == $id12) { echo "selected";}?>><?php echo $name12?></option>
                                                        <?php }
                                                        }                 
                                                    
                                                    $conn->close();
                                                    ?>
                                                        <!-- <option value="Lain - lain">Lain - lain</option> -->
                                                    </select>
                                                    <br>

                                                    <input type="hidden" name="iddclass" value="<?php echo $idd;?>">

                                                    <label>Teacher:</label>
                                                    <select id="jenisform" name="selectteacher" class="form-select" style="width:420px" onchange="myFunction()" required>
                                                        <!-- <option value="" disabled selected>Select your option</option> -->
                                                    <?php
                                                    $conn = new mysqli("localhost","root", "", "kckrctb");
                                                    $sql = "SELECT * from `msteacher` ORDER BY name";
                                                    $result1 = $conn->query($sql);
                                                                    
                                                    if ($result1->num_rows > 0) {
                                                        while ($row = $result1->fetch_assoc()) {
                                                                    //data perusahaan
                                                            $id12 = $row['id'];
                                                            $name12= $row['name'];
                                                        ?>
                                                        
                                                            <option value="<?php echo $id12?>"  <?php if($teacher_id == $id12) { echo "selected";}?>><?php echo $name12?></option>
                                                        <?php }
                                                        }                 
                                                    
                                                    $conn->close();
                                                    ?>
                                                        
                                                    </select>
                                                    <br>

                                                    <!-- <label>Req. Date:</label>
                                                    <input  class="form-control" name="reqdate" style="width:420px" value="<?php echo $requestdate; ?>">
                                                    <br>

                                                    <label>Copy/Asli:</label>
                                                    <input  class="form-control" name="copyasli" style="width:420px" value="<?php echo $copyasli; ?>">
                                                    <br> -->

                                                    <!-- <label>Kelengkapan Dokumen:</label>
                                                    <input  class="form-control" name="kelengkapan" style="width:420px" value="<?php echo $kelengkapan; ?>" disabled>
                                                    <br>

                                                    <?php if($keterangantambahan != ""){ ?>
                                                    <label>Keterangan:</label>
                                                    <textarea class="form-control" type="text" name="keterangantambahan" style="width:420px" disabled><?php echo $keterangantambahan; ?></textarea>
                                                    <br> -->

                                                    <!-- <label>Tanggapan BO:</label>
                                                    <textarea class="form-control" type="text" name="tanggapan" style="width:420px" disabled><?php echo $tanggapan; ?></textarea>
                                                    <br>
                                                    <?php } ?>

                                                    <?php if($tanggapanccs != ""){ ?>
                                                    <label>Tanggapan CCS:</label>
                                                    <textarea class="form-control" type="text" name="tanggapan" style="width:420px" disabled><?php echo $tanggapanccs; ?></textarea>
                                                    <br>
                                                    <?php } ?> -->

                                                    <!-- <?php if($keterangantambahan == ""){ ?>
                                                    <label>Kekurangan Dokumen:</label>
                                                    <div class="card text-white shadow" style="width:420px; background-color:#B5D0F5">
                                                        <div class="card-body">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Keterangan</th>
                                                                    <th>Status</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                
                                                                <?php
                                                                    $conn = new mysqli("localhost", "root", "", "kckrctb");
                                                                    $sqlkekurangan = "SELECT * FROM `mskekurangandok` WHERE `msformccs_id` = '$idd'";
                                                                    $resultkekurangan = $conn->query($sqlkekurangan);
                                                                    $i = 0;
                                                                    if ($resultkekurangan->num_rows > 0) {
                                                                        while($rowkd = $resultkekurangan->fetch_assoc()) {
                                                                            $i++;
                                                                            if($rowkd['status'] == "DONE"){
                                                                                $kekurangancount++;
                                                                            }
                                                                            $adderkekuranganid = $rowkd['adder_id'];
                                                                            $finalizerkekuranganid = $rowkd['finalizer_id'];
                                                                            $adderkekurangan = "";
                                                                            $finalizerkekurangan = "";
                                                                            $sqlcariuser = "SELECT * FROM `msuser`";
                                                                            $result1222 = $conn->query($sqlcariuser);
                                                                            
                                                                            if ($result1222->num_rows > 0) {
                                                                                while($rowkd12 = $result1222->fetch_assoc()) {
                                                                                    if($rowkd12['id'] == $adderkekuranganid){
                                                                                        $adderkekurangan = $rowkd12['name'];
                                                                                    }
                                                                                    if($rowkd12['id'] == $finalizerkekuranganid){
                                                                                        $finalizerkekurangan = $rowkd12['name'];
                                                                                    }
                                                                                }
                                                                            }
                                                                ?>  
                                                                    <tr>
                                                                        <td class="align-middle" style="width: 7%;"><?php echo $i; ?>.</td>
                                                                        <td class="align-middle" style="width: 40%"><?php echo $rowkd['keterangan']; ?></td>
                                                                        <td class="align-middle">
                                                                        <?php 
                                                                            if($rowkd['status'] == "PENDING"){
                                                                        ?>
                                                                            <b style="color:white"><span class="badge bg-danger"><?php echo $rowkd['status']; ?></span></b>
                                                                        <?php
                                                                            } elseif($rowkd['status'] == "DONE"){
                                                                        ?>
                                                                            <b style="color:white"><span class="badge bg-success"><?php echo $rowkd['status']; ?></span></b>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                        </td>
                                                                        <td class="align-middle"> 
                                                                        <?php if($rowkd['status'] == "DONE"){ ?>
                                                                            <button type="button" class="btn btn-primary rounded-pill btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                                                Details
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li><a class="dropdown-item" href="#">Added: <?php echo date("d/m/Y (H:i:s)", strtotime($rowkd['added_date']))." by ".$adderkekurangan;?></a></li>
                                                                                <li><a class="dropdown-item" href="#">Done: <?php echo date("d/m/Y (H:i:s)", strtotime($rowkd['done_date']))." by ".$finalizerkekurangan;?></a></li>
                                                                            </ul>
                                                                        <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                        }
                                                                    }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <?php } ?> -->

                                                    <button class="btn btn-primary" onclick="return confirm('Update?');" name="updateclass">Update</button>

                                                    <button class="btn btn-danger" onclick="return confirm('delete?');" name="deleteclass">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
            else{
                ?>
                <br>
                <br>
                <h5 class="text-center  "> There is no request. </h5>
                <?php
            }
            
            ?>
        </div>
        <div class="col-md-1"></div>
    </div>
    <br>

    <?php if($_SESSION['rowtotal'] > 0){ ?>
    <div class = "row">
        <div class = "col-sm-4"></div>
        <div class = "col-sm-4 text-center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="hiddenpage" value="<?php echo $nowpage;?>">
                <?php
                if($nowpage!=$pagetotal-1){
                    ?>
                    <p>Showing 10 of <?php echo $_SESSION['rowtotal']; ?> Data </p>
                    <?php
                }else{
                    ?>
                    <p>Showing <?php if($_SESSION['rowtotal']%10=='0'){echo '10';}else{echo $_SESSION['rowtotal']%10;} ?> of <?php echo $_SESSION['rowtotal']; ?> Data </p>
                    <?php
                }
                ?>
                <div class="container-fluid input-group justify-content-center" style="max-width: 100%">
                <?php
                if($nowpage!=0){
                    ?>
                    <input type="submit" name="jumpback" value="Previous" class="btn btn-dark">
                    <?php
                }
                ?>
                    <select class="form-select" name="currpage" style="max-width: 75px">   
                <?php
                for($i = 0 ;$i < $pagetotal;$i++){
                    $x = $i+1;
                    ?>
                        <option value="<?php echo $i;?>" <?php if($nowpage==$i) echo "selected";?>><?php echo $x; ?></option>
                    <?php
                }
                ?>
                    </select>
                    <input type="submit" name="paging" value="Jump" class="btn btn-dark">
                
                <?php
                if($nowpage!=$pagetotal-1){
                    ?>
                    <input type="submit" name="jumpnext" value="Next" class="btn btn-dark">
                    <?php
                }
                ?>
                </div>
            </form>
        </div>
        <div class = "col-sm-4"></div>
    </div>
    <?php } ?>
<br>
</body>
</html>