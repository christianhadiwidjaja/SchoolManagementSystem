<?php
// Initialize the session
session_start();

if(!isset($_SESSION["loggedin"])){
    header("location: login.php");
    return;
}
// if($_SESSION["role"] == 0){
//     header("location: login.php");
//     return;
// }
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['addnewform'])){
        $class = $_POST['classinput'];
        $day = $_POST['selectdate'];
        $start_time = $_POST['starttime'];
        $end_time = $_POST['endtime'];
        $teacher_id = $_POST['selectteacher'];
        // $cis = $_POST['cis'];
        // $rek = $_POST['rekening'];
        // $ctx = $_POST['ctx'];
        $subject = $_POST['jenisform'];
        // $keterangantambahan = addslashes($_POST['keterangantambahan']);
        $kekurangandok = array();

        // for($i = 1; $i <= 10; $i++){
        //     if($_POST["ket".$i] <> ""){
        //         array_push($kekurangandok, addslashes($_POST["ket".$i]));
        //     }
        // }

        if ($subject == "Lain - lain"){
            $subject = $_POST['keteranganlain'];
        }

        // $namaperusahaan = $_POST['namaperusahaan'];
        // $copyasli = $_POST['copyasli'];
        // $kelengkapan = $_POST['kelengkapandokumen'];
        $id = $_SESSION['id'];
        // $isS6 = $_SESSION['is_s6'];
        // $subjectkodeaktivasi = strtolower($subject);

        

        

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

        // if(count($kekurangandok) > 0){
        //     $status = "PENDING";
        // } else{
        //     $status = "IN PROGRESS";
        // }

        date_default_timezone_set("Asia/Jakarta");
        $requestdate = date("y-m-d H:i:s");

        $conn = new mysqli("localhost","root","","kckrctb");

        

        
            $sql = "INSERT INTO `msschedule` (
                `day`, `start_time`, `end_time`, `subject_id`, `subject_name`, `teacher_id`, `class`
            ) VALUES (
                '$day', '$start_time', '$end_time', '$subject_id', '$subject', '$teacher_id', '$class'
            )";

            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;

                $link = "Uploads/Class/".$last_id."/";

                mkdir($link, 0700);

                $sql = "UPDATE `msschedule` SET folder = '$link' WHERE id = '$last_id'";
                if ($conn->query($sql) === TRUE) {
                }
            
                if (($_FILES['filedocument']['name']!="")){
                    // Where the file is going to be stored
                    $file = $_FILES['filedocument']['name'];
                    $path = pathinfo($file);
                    $filename = $path['filename'];
                    $ext = $path['extension'];
                    $temp_name = $_FILES['filedocument']['tmp_name'];
                    $path_filename_ext = $link.$filename.".".$ext;
                    $fulldirectory = $directory.$file;

                    // Check if file already exists
                    if (file_exists($path_filename_ext)) {
                        echo "<script type='text/javascript'>alert('Sudah ada file dengan identitas yang sama! Tolong re-name file!');window.location.href='add-class.php';</script>";
                    }
                    else{
                        move_uploaded_file($temp_name,$path_filename_ext);
                    }
                }

                // $conn = new mysqli("localhost","root","","kckrctb");

                // foreach($kekurangandok as $kekurangan){
                //     $sql2 = "INSERT INTO `mskekurangandok` (
                //         `msschedule_id`, `keterangan`, `status`, `added_date`, `adder_id`
                //     ) VALUES (
                //         '$last_id', '$kekurangan', '$status', '$requestdate', '$id'
                //     )";

                //     if ($conn->query($sql2) === TRUE) {

                //     }
                // }

                // $sql = "SELECT * FROM msschedule ORDER BY id DESC LIMIT 1";

                // $real_id = "";

                // $result = $conn->query($sql);
                // if ($result->num_rows > 0) {
                //     while($row = $result->fetch_assoc()) {
                //         $real_id = $row['id'];
                //     }
                // }

                // $sql = "INSERT INTO `msallorder` (
                //     `namaperusahaan`, `requestdate`, `jenisform`, `pic_ccs_id`, `status`, `ordertype`, `real_id`
                // ) VALUES (
                //     '$namaperusahaan','$requestdate','$subject', '$id', '$status', 'Form Aktivitas', '$real_id'
                // )";

                // if ($conn->query($sql) === TRUE) {
                // }

                echo "<script type='text/javascript'>alert('Form telah berhasil disubmit!');window.location.href='add-class.php';</script>";
                $conn->close();
            }
        
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Class</title>
    <link rel="shortcut icon" href="../favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/css.css" rel="stylesheet">
    <script src="css/js.js"></script>
</head>

<style>
    ul[hidden] {
    display: none
    }

    a { 
        color: inherit; 
    } 
</style>

<script>
    function addnewket() {
        var x = Number(document.getElementById("countket").innerHTML);
        if (x<10){
            x = x + 1;
            document.getElementById("ketgroup" + x).hidden = false;
        }
        document.getElementById("countket").innerHTML = x;
    }

    function deleteket(i) {
        var x = Number(document.getElementById("countket").innerHTML);
        

        for(let curr = i; curr < x; curr++){
            document.getElementById("ket" + curr).value = document.getElementById("ket" + (curr + 1)).value;
        }

        document.getElementById("ket" + x).value = "";
        document.getElementById("ketgroup" + x).hidden = true;
        document.getElementById("countket").innerHTML = x - 1;
    }

    function myFunction() {
        var selectBox = document.getElementById("jenisform");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        var y = document.getElementById("keteranganlain");
        
        if(selectedValue=="Lain - lain"){
            y.setAttribute("type", "text");
            y.required = true;
            document.getElementById("rek").required = true;
            document.getElementById("cis").required = true;
        }
        else if(selectedValue=="Pembukaan Rekening New CIS"){
            y.setAttribute("type", "hidden");
            y.required = false;
            document.getElementById("rek").required = false;
            document.getElementById("cis").required = false;
        }
        else if(selectedValue=="Pembukaan Rekening"){
            y.setAttribute("type", "hidden");
            y.required = false;
            document.getElementById("rek").required = false;
        }
        else{
            y.setAttribute("type", "hidden");
            y.required = false;
            document.getElementById("rek").required = true;
            document.getElementById("cis").required = true;
        }
    }

    function myFunction2() {
        document.getElementById("cis").disabled = false;   
    }

    function myFunction1() {
        var selectBox = document.getElementById("jenisform");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        var y = document.getElementById("keteranganlain");

        for(let i = 1; i <= 10; i++){
            document.getElementById("ket" + i).value = "";
            document.getElementById("ketgroup" + i).hidden = true;
        }

        document.getElementById("countket").innerHTML = 0;

        y.setAttribute("type", "hidden");
        y.required = false;
        document.getElementById("rek").required = true;
        document.getElementById("cis").required = true;
        window.scrollTo(0,0); 
    }

    function browseperusahaan() {
        document.getElementById("cis").value = "";
        var input, filter, ul, li, a, i, txtValue, inputvalue;
        input = document.getElementById("namaperusahaan");
        inputvalue = document.getElementById("namaperusahaan").value.length;
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        
        if (inputvalue == 0) {
            ul.hidden = true;
        }
        else{
            ul.hidden = false;
        }
        
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    function convert(str, cis, size){
        cis = cis.toString();
        while (cis.length < size) cis = "0" + cis;
        document.getElementById("namaperusahaan").value = str;
        document.getElementById("cis").value = cis;
        ul = document.getElementById("myUL");
        ul.hidden = true;
        document.getElementById("cis").disabled = true; 
    }

</script>

</head>
<body>
    <?php 
    if($_SESSION['role'] == 1){ 
        include('/nav/nav-ccs.php');
    }
    else if($_SESSION['role'] == 2){
        include('/nav/nav-bo.php');
    }else if($_SESSION['role'] == 3){
        include('/nav/nav-b2b.php');
    }else if($_SESSION['role'] == 4){
        include('/nav/nav-apk.php');
    }else if($_SESSION['role'] == 0){
        include('/nav/nav-sa.php');
    }
    ?>
    <br><br>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2">
            <br><br><br><br><br><br><br><br><br><br><br>
            <p style="color: red; text-align: right;"><b><i>Please Fill Out The Class Subject Based on the Class Grade</i></b></p>
        </div>
        <div class="col-sm-4">
            <div class="card-body">
                <div class="wrapper">
                    <h1> <strong>Add Class</strong></h1>
                    <p>Please fill this form.</p>
                    <hr style="width: 420px;">
                    <form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

                        <div id="keterangantambahan">
                            <label>Class</label>
                            <input style="width:420px" type="text" name="classinput" class="form-control"></input>
                            <br>
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
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
                                
                                    <option value="<?php echo $name12?>"><?php echo $name12?></option>
                                <?php }
                                }                 
                            
                            $conn->close();
                            ?>
                                <option value="Lain - lain">Lain - lain</option>
                            </select>
                        </div> 
                        
                        <input style="width:420px" type="hidden" id="keteranganlain" name="keteranganlain" class="form-control" maxlength = "32" placeholder = "isi sesuai nama subjectnya">
                        <br>

                        <!-- <label>Nama Perusahaan</label>
                        <input autocomplete="off" style="width:420px" type="text" id="namaperusahaan" name="namaperusahaan" onkeyup="browseperusahaan()" class="form-control" required>
                        <div id="myUL" class="list-group" hidden style="width:420px">
                            <?php 
                                $conn = new mysqli("localhost","root", "", "kckdatanasabah");
                                $sql1 = "SELECT * from `msperusahaan` ORDER BY name";
                                $result2 = $conn->query($sql1);

                                if ($result2->num_rows > 0) {
                                    while ($row1 = $result2->fetch_assoc()) {
                                        $name12= $row1['name'];
                                        $cis12 = $row1['nomorCIS'];
                                    ?>
                                    <li href="#" onclick="convert(this.innerHTML, '<?php echo $cis12; ?>', <?php echo strlen($cis12); ?>)" class="list-group-item list-group-item-action"><?php echo $name12; ?></li>
                                    <?php
                                    }                 
                                }
                                $conn->close();
                            ?>
                        </div>
                        <br> -->

                        <label>Day</label>
                        <select id="jenisform" name="selectdate" class="form-select" style="width:420px" required>
                            <option value="">Select</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>  
                        <br>

                        <label>Start Time</label>
                        <input style="width:420px" type="time" id="rek" name="starttime" class="form-control">
                        <br>

                        <label>End Time</label>
                        <input style="width:420px" type="time" id="rek" name="endtime" class="form-control">
                        <br>

                        <!-- <label>Nama PIC CTX / Divisi Lain</label>
                        <input style="width:420px" type="text" name="ctx" class="form-control" required>
                        <br> -->

                        <div class="form-group">
                            <label>Select Teacher</label>
                            <select id="jenisform" name="selectteacher" class="form-select" style="width:420px" onchange="myFunction()" required>
                                <option value="" disabled selected>Select your option</option>
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
                                
                                    <option value="<?php echo $id12?>"><?php echo $name12?></option>
                                <?php }
                                }                 
                            
                            $conn->close();
                            ?>
                                <option value="Lain - lain">Lain - lain</option>
                            </select>
                        </div>
                        <br>

                        <!-- <div class="form-group">
                            <label>Copy/Asli</label>
                            <select name="copyasli" class="form-select" style="width:420px" required>
                                <option value="" disabled selected>Select your option</option>
                                <option value="1">Copy</option>
                                <option value="2">Asli</option>
                                <option value="4">Asli di Cabang</option>
                                <option value="3">No Form</option>
                            </select>
                        </div> 
                        <br>

                        <div class="form-group">
                            <label>Kelengkapan Dokumen</label>
                            <select name="kelengkapandokumen" class="form-select" style="width:420px" required>
                                <option value="" disabled selected>Select your option</option>
                                <option value="1">Lengkap</option>
                                <option value="2">Tidak Lengkap</option>
                                <option value="3">No Form</option>
                            </select>
                        </div> 
                        <br>

                        <div id="keterangantambahan">
                            <label>Keterangan</label>
                            <textarea style="width:420px" type="text" name="keterangantambahan" class="form-control"></textarea>
                            <br>
                        </div>
                        
                        <div id="kekurangandokumen">
                            <label>Keterangan tidak lengkap (max. 10)</label>
                            <div class="card text-white shadow" style="width:420px; background-color:#B5D0F5">
                                <div class="card-body">
                                    <button type="button" class="text-white btn btn-sm border-0 shadow" style="background-color:#6980FF;" onclick="addnewket();">Add New +</button>
                                    <label id="countket" hidden>0</label>
                                    <label id="selected" hidden>0</label>

                                    <p></p>

                                    <?php for($i = 1; $i <= 10; $i++){ ?>
                                    <div id="ketgroup<?php echo $i; ?>" hidden>
                                        <div class="input-group" style="width:360px;">
                                            <label for="ket<?php echo $i; ?>" style="width:8%; color:black;"><?php echo $i."."; ?></label>
                                            <input style="width:82%; border-top-left-radius: 3px; border-bottom-left-radius: 3px;" type="text" id="ket<?php echo $i; ?>" name="ket<?php echo $i; ?>" class="form-control form-control-sm border-0">
                                            <button type="button" style="width:10%; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:#BA0000;" class="text-white btn btn-sm border-0" onclick="deleteket(<?php echo $i;?>)" id="deleteket<?php echo $i; ?>">X</button>
                                        </div> 
                                        <p></p>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <br>
                        </div> -->
                        
                        <label for="formFile" class="form-label">Upload Dokumen</label>
                        <input style="width:420px" class="form-control" type="file" name="filedocument">
                        <br>

                        <div class="form-group">
                            <button class="btn btn-success" name="addnewform" onclick="myFunction2();">Submit</button>
                            <input type="reset" id="" class="btn btn-dark" value="Reset" onclick="myFunction1();">
                        </div>
                        <br><br>
                    </form>
                </div>
            </div>  
        </div>

        <div class="col-sm-2">
            
        </div>  
        <div class="col-sm-2"></div>
    </div>  
</body>
</html>