<?php
session_start();

if(isset($_SESSION["loggedin"]) && ($_SESSION["role"]=="student")) {
  header("location: welcome-user-student2.php");
  return;
}else if(isset($_SESSION["loggedin"]) && ($_SESSION["role"]=="sa")) {
  header("location: welcome-sa.php");
  return;
}
else if(isset($_SESSION["loggedin"]) && ($_SESSION["role"]=="teacher")){
  header("location: welcome-user-teacher.php");
  return;
} else if(isset($_SESSION["loggedin"]) && ($_SESSION["role"]=="audit")){
  header("location: welcome-user.php");
  return;
}

require_once "config.php";

$username = $password = $role = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

  if(empty(trim($_POST["username"]))){
    $username_err = "Please enter username.";
  } else{
    $username = trim($_POST["username"]);
  }

  if(empty(trim($_POST["password"]))){
    $password_err = "Please enter your password.";
  } else{
    $password = trim($_POST["password"]);
  }

  if(empty($username_err) && empty($password_err)){
    $sql = "SELECT id, username, password,role,name FROM msuser where username = ?";
    if($stmt = mysqli_prepare($link, $sql)){
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      $param_username = $username;
      if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1){     
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password,$role,$name);
          if(mysqli_stmt_fetch($stmt)){
            if(password_verify($password, $hashed_password)){
              $_SESSION['initial_enomor'] = $initial;
              if($role == "student"){
                if($password=="bcabca"){
                  $_SESSION["changepw"] = 1;
                }else{
                  $_SESSION["changepw"] = 0;
                }
                session_start();

                // $_SESSION["nip"] = $nip;
                $_SESSION["status"] = '%';
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = 1;
                $_SESSION["name"] = $name;
                $_SESSION["unt"] = $unit_kerja;
                $_SESSION['HeadCluster'] = $headcluster;
                $_SESSION["mode"] = 1;
                $_SESSION["page"] = 0;
                $_SESSION['searchwaiting']=0;
                $_SESSION['page'] = 0;
                $_SESSION['spvtrack'] = $spvtrack;

                //form aktivitas view-form-ccs & view-form-bot
                $_SESSION['searchNamePT'] = "";
                $_SESSION['jenisformfilterviewformccs'] = "";
                $_SESSION['reqdatetofilterviewformccs'] = "";
                $_SESSION['reqdatefromfilterviewformccs'] = "";
                $_SESSION['ccspicfilterviewformccs'] = "";
                $_SESSION['statusformfilterviewformccs'] = "";

                //form aktivitas my-form-ccs & my-form-bot
                $_SESSION['searchNamePTMY'] = "";
                $_SESSION['jenisformfilterviewformccsMY'] = "";
                $_SESSION['reqdatetofilterviewformccsMY'] = "";
                $_SESSION['reqdatefromfilterviewformccsMY'] = "";
                $_SESSION['ccspicfilterviewformccsMY'] = "";
                $_SESSION['statusformfilterviewformccsMY'] = "";

                //form aktivitas my-form-ccs-backup & my-form-bot-backup
                $_SESSION['searchNamePTMYb'] = "";
                $_SESSION['jenisformfilterviewformccsMYb'] = "";
                $_SESSION['reqdatetofilterviewformccsMYb'] = "";
                $_SESSION['reqdatefromfilterviewformccsMYb'] = "";
                $_SESSION['ccspicfilterviewformccsMYb'] = "";
                $_SESSION['statusformfilterviewformccsMYb'] = "";

                //surkon-view-form-ccs
                $_SESSION['searchIDPTSurkonView'] = "";
                $_SESSION['searchNamePTSurkonView'] = "";
                $_SESSION['nosuratfilterSurkonView'] = "";
                $_SESSION['jenisformfilterSurkonView'] = "";
                $_SESSION['reqdatetofilterSurkonView'] = "";
                $_SESSION['reqdatefromfilterSurkonView'] = "";
                $_SESSION['ccspicfilterSurkonView'] = "";
                $_SESSION['spvpicfilterSurkonView'] = "";
                $_SESSION['statusformfilterSurkonView'] = "";

                //surkon-view-riwayat-form-ccs
                $_SESSION['searchIDPTSurkonViewRiwayat'] = "";
                $_SESSION['searchNamePTSurkonViewRiwayat'] = "";
                $_SESSION['nosuratfilterSurkonViewRiwayat'] = "";
                $_SESSION['jenisformfilterSurkonViewRiwayat'] = "";
                $_SESSION['reqdatetofilterSurkonViewRiwayat'] = "";
                $_SESSION['reqdatefromfilterSurkonViewRiwayat'] = "";
                $_SESSION['ccspicfilterSurkonViewRiwayat'] = "";
                $_SESSION['spvpicfilterSurkonViewRiwayat'] = "";
                $_SESSION['prosesdateSurkonViewRiwayat'] = "";

                //surkon-my-form-ccs
                $_SESSION['searchIDMY'] = "";
                $_SESSION['searchNamePTSurkonMy'] = "";
                $_SESSION['nosuratfilterSurkonMy'] = "";
                $_SESSION['jenisformfilterSurkonMy'] = "";
                $_SESSION['reqdatetofilterSurkonMy'] = "";
                $_SESSION['reqdatefromfilterSurkonMy'] = "";
                $_SESSION['ccspicfilterSurkonMy'] = "";
                $_SESSION['spvpicfilterSurkonMy'] = "";
                $_SESSION['statusformfilterSurkonMy'] = "";

                //surkon-authorize-form-ccs
                $_SESSION['searchIDMYb'] = "";
                $_SESSION['searchNamePTSurkonMyb'] = "";
                $_SESSION['nosuratfilterSurkonMyb'] = "";
                $_SESSION['jenisformfilterSurkonMyb'] = "";
                $_SESSION['reqdatetofilterSurkonMyb'] = "";
                $_SESSION['reqdatefromfilterSurkonMyb'] = "";
                $_SESSION['ccspicfilterSurkonMyb'] = "";
                $_SESSION['statusformfilterSurkonMyb'] = "";

                //surkon-pendebetan-ccs
                $_SESSION['surkonpendebetanID'] = "";
                $_SESSION['surkonpendebetanCost'] = "";
                
                //surkon-request-form-ccs
                $_SESSION['surkonreqjenisform'] = "";
                $_SESSION['surkonreqjenisformlain'] = "";
                $_SESSION['surkonreqperusahaanid'] = "";
                $_SESSION['surkonreqperusahaanname'] = "";
                $_SESSION['surkonreqcis'] = "";
                $_SESSION['surkonreqpicpembuatsurat'] = "";
                $_SESSION['surkonreqketerangan'] = "";

                //surkon-bukti-pendebetan-ccs
                $_SESSION['surkonbiayafilterbatch'] = "";
                $_SESSION['surkonbiayanamaPT'] = "";
                $_SESSION['surkonbiayanosurat'] = "";
                $_SESSION['surkonbiayajenisform'] = "";
                $_SESSION['surkonbiayareqid'] = "";
                $_SESSION['surkonbiayapembuatsurat'] = "";
                $_SESSION['surkonbiayaspv'] = "";

                //view-classroom
                $_SESSION['searchDayEditClass'] = "";
                $_SESSION['searchTeacherEditClass'] = "";
                $_SESSION['searchClassEditClass']= "";
                $_SESSION['searchStartTimeEditClass']= "";
                $_SESSION['searchEndTimeEditClass']= "";
                $_SESSION['searchSubjectNameEditClass'] = "";

                $_SESSION['surkonauthfilterbatch'] = "";
                
                $_SESSION['surkonpendebetanbatcher'] = "";
                $_SESSION['idd23'] = "";

                //view-attendance-student

                $_SESSION['filtersubjectsess'] = "";

                header("location: welcome-user-student2.php");
              }

              else if($role == "parent"){
                if($password=="bcabca"){
                  $_SESSION["changepw"] = 1;
                }else{
                  $_SESSION["changepw"] = 0;
                }
                session_start();

                // $_SESSION["nip"] = $nip;
                $_SESSION["status"] = '%';
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = 5;
                $_SESSION["name"] = $name;
                $_SESSION["unt"] = $unit_kerja;
                $_SESSION['HeadCluster'] = $headcluster;
                $_SESSION["mode"] = 1;
                $_SESSION["page"] = 0;
                $_SESSION['searchwaiting']=0;
                $_SESSION['page'] = 0;
                $_SESSION['spvtrack'] = $spvtrack;

                //form aktivitas view-form-ccs & view-form-bot
                $_SESSION['searchNamePT'] = "";
                $_SESSION['jenisformfilterviewformccs'] = "";
                $_SESSION['reqdatetofilterviewformccs'] = "";
                $_SESSION['reqdatefromfilterviewformccs'] = "";
                $_SESSION['ccspicfilterviewformccs'] = "";
                $_SESSION['statusformfilterviewformccs'] = "";

                //form aktivitas my-form-ccs & my-form-bot
                $_SESSION['searchNamePTMY'] = "";
                $_SESSION['jenisformfilterviewformccsMY'] = "";
                $_SESSION['reqdatetofilterviewformccsMY'] = "";
                $_SESSION['reqdatefromfilterviewformccsMY'] = "";
                $_SESSION['ccspicfilterviewformccsMY'] = "";
                $_SESSION['statusformfilterviewformccsMY'] = "";

                //form aktivitas my-form-ccs-backup & my-form-bot-backup
                $_SESSION['searchNamePTMYb'] = "";
                $_SESSION['jenisformfilterviewformccsMYb'] = "";
                $_SESSION['reqdatetofilterviewformccsMYb'] = "";
                $_SESSION['reqdatefromfilterviewformccsMYb'] = "";
                $_SESSION['ccspicfilterviewformccsMYb'] = "";
                $_SESSION['statusformfilterviewformccsMYb'] = "";

                //surkon-view-form-ccs
                $_SESSION['searchIDPTSurkonView'] = "";
                $_SESSION['searchNamePTSurkonView'] = "";
                $_SESSION['nosuratfilterSurkonView'] = "";
                $_SESSION['jenisformfilterSurkonView'] = "";
                $_SESSION['reqdatetofilterSurkonView'] = "";
                $_SESSION['reqdatefromfilterSurkonView'] = "";
                $_SESSION['ccspicfilterSurkonView'] = "";
                $_SESSION['spvpicfilterSurkonView'] = "";
                $_SESSION['statusformfilterSurkonView'] = "";

                //surkon-view-riwayat-form-ccs
                $_SESSION['searchIDPTSurkonViewRiwayat'] = "";
                $_SESSION['searchNamePTSurkonViewRiwayat'] = "";
                $_SESSION['nosuratfilterSurkonViewRiwayat'] = "";
                $_SESSION['jenisformfilterSurkonViewRiwayat'] = "";
                $_SESSION['reqdatetofilterSurkonViewRiwayat'] = "";
                $_SESSION['reqdatefromfilterSurkonViewRiwayat'] = "";
                $_SESSION['ccspicfilterSurkonViewRiwayat'] = "";
                $_SESSION['spvpicfilterSurkonViewRiwayat'] = "";
                $_SESSION['prosesdateSurkonViewRiwayat'] = "";

                //surkon-my-form-ccs
                $_SESSION['searchIDMY'] = "";
                $_SESSION['searchNamePTSurkonMy'] = "";
                $_SESSION['nosuratfilterSurkonMy'] = "";
                $_SESSION['jenisformfilterSurkonMy'] = "";
                $_SESSION['reqdatetofilterSurkonMy'] = "";
                $_SESSION['reqdatefromfilterSurkonMy'] = "";
                $_SESSION['ccspicfilterSurkonMy'] = "";
                $_SESSION['spvpicfilterSurkonMy'] = "";
                $_SESSION['statusformfilterSurkonMy'] = "";

                //surkon-authorize-form-ccs
                $_SESSION['searchIDMYb'] = "";
                $_SESSION['searchNamePTSurkonMyb'] = "";
                $_SESSION['nosuratfilterSurkonMyb'] = "";
                $_SESSION['jenisformfilterSurkonMyb'] = "";
                $_SESSION['reqdatetofilterSurkonMyb'] = "";
                $_SESSION['reqdatefromfilterSurkonMyb'] = "";
                $_SESSION['ccspicfilterSurkonMyb'] = "";
                $_SESSION['statusformfilterSurkonMyb'] = "";

                //surkon-pendebetan-ccs
                $_SESSION['surkonpendebetanID'] = "";
                $_SESSION['surkonpendebetanCost'] = "";
                
                //surkon-request-form-ccs
                $_SESSION['surkonreqjenisform'] = "";
                $_SESSION['surkonreqjenisformlain'] = "";
                $_SESSION['surkonreqperusahaanid'] = "";
                $_SESSION['surkonreqperusahaanname'] = "";
                $_SESSION['surkonreqcis'] = "";
                $_SESSION['surkonreqpicpembuatsurat'] = "";
                $_SESSION['surkonreqketerangan'] = "";

                //surkon-bukti-pendebetan-ccs
                $_SESSION['surkonbiayafilterbatch'] = "";
                $_SESSION['surkonbiayanamaPT'] = "";
                $_SESSION['surkonbiayanosurat'] = "";
                $_SESSION['surkonbiayajenisform'] = "";
                $_SESSION['surkonbiayareqid'] = "";
                $_SESSION['surkonbiayapembuatsurat'] = "";
                $_SESSION['surkonbiayaspv'] = "";

                //view-classroom
                $_SESSION['searchDayEditClass'] = "";
                $_SESSION['searchTeacherEditClass'] = "";
                $_SESSION['searchClassEditClass']= "";
                $_SESSION['searchStartTimeEditClass']= "";
                $_SESSION['searchEndTimeEditClass']= "";
                $_SESSION['searchSubjectNameEditClass'] = "";

                $_SESSION['surkonauthfilterbatch'] = "";
                
                $_SESSION['surkonpendebetanbatcher'] = "";
                $_SESSION['idd23'] = "";

                //view-attendance-student

                $_SESSION['filtersubjectsess'] = "";

                header("location: welcome-user-parent.php");
              }

              else if($role == "audit"){
                if($password=="bcabca"){
                  $_SESSION["changepw"] = 1;
                }else{
                  $_SESSION["changepw"] = 0;
                }
                session_start();
                $_SESSION["nip"] = $nip;
                $_SESSION["status"] = '%';
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = 5;
                $_SESSION["name"] = $name;
                $_SESSION["unt"] = $unit_kerja;
                $_SESSION['HeadCluster'] = $headcluster;
                $_SESSION["mode"] = 1;
                $_SESSION["page"] = 0;
                $_SESSION['searchwaiting']=0;
                $_SESSION['page'] = 0;
                $_SESSION['spvtrack'] = $spvtrack;

                //form aktivitas view-form-ccs & view-form-bot
                $_SESSION['searchNamePT'] = "";
                $_SESSION['jenisformfilterviewformccs'] = "";
                $_SESSION['reqdatetofilterviewformccs'] = "";
                $_SESSION['reqdatefromfilterviewformccs'] = "";
                $_SESSION['ccspicfilterviewformccs'] = "";
                $_SESSION['statusformfilterviewformccs'] = "";

                //form aktivitas my-form-ccs & my-form-bot
                $_SESSION['searchNamePTMY'] = "";
                $_SESSION['jenisformfilterviewformccsMY'] = "";
                $_SESSION['reqdatetofilterviewformccsMY'] = "";
                $_SESSION['reqdatefromfilterviewformccsMY'] = "";
                $_SESSION['ccspicfilterviewformccsMY'] = "";
                $_SESSION['statusformfilterviewformccsMY'] = "";

                //form aktivitas my-form-ccs-backup & my-form-bot-backup
                $_SESSION['searchNamePTMYb'] = "";
                $_SESSION['jenisformfilterviewformccsMYb'] = "";
                $_SESSION['reqdatetofilterviewformccsMYb'] = "";
                $_SESSION['reqdatefromfilterviewformccsMYb'] = "";
                $_SESSION['ccspicfilterviewformccsMYb'] = "";
                $_SESSION['statusformfilterviewformccsMYb'] = "";

                //surkon-view-form-ccs
                $_SESSION['searchIDPTSurkonView'] = "";
                $_SESSION['searchNamePTSurkonView'] = "";
                $_SESSION['nosuratfilterSurkonView'] = "";
                $_SESSION['jenisformfilterSurkonView'] = "";
                $_SESSION['reqdatetofilterSurkonView'] = "";
                $_SESSION['reqdatefromfilterSurkonView'] = "";
                $_SESSION['ccspicfilterSurkonView'] = "";
                $_SESSION['spvpicfilterSurkonView'] = "";
                $_SESSION['statusformfilterSurkonView'] = "";

                //surkon-view-riwayat-form-ccs
                $_SESSION['searchIDPTSurkonViewRiwayat'] = "";
                $_SESSION['searchNamePTSurkonViewRiwayat'] = "";
                $_SESSION['nosuratfilterSurkonViewRiwayat'] = "";
                $_SESSION['jenisformfilterSurkonViewRiwayat'] = "";
                $_SESSION['reqdatetofilterSurkonViewRiwayat'] = "";
                $_SESSION['reqdatefromfilterSurkonViewRiwayat'] = "";
                $_SESSION['ccspicfilterSurkonViewRiwayat'] = "";
                $_SESSION['spvpicfilterSurkonViewRiwayat'] = "";
                $_SESSION['prosesdateSurkonViewRiwayat'] = "";

                //surkon-my-form-ccs
                $_SESSION['searchIDMY'] = "";
                $_SESSION['searchNamePTSurkonMy'] = "";
                $_SESSION['nosuratfilterSurkonMy'] = "";
                $_SESSION['jenisformfilterSurkonMy'] = "";
                $_SESSION['reqdatetofilterSurkonMy'] = "";
                $_SESSION['reqdatefromfilterSurkonMy'] = "";
                $_SESSION['ccspicfilterSurkonMy'] = "";
                $_SESSION['spvpicfilterSurkonMy'] = "";
                $_SESSION['statusformfilterSurkonMy'] = "";

                //surkon-authorize-form-ccs
                $_SESSION['searchIDMYb'] = "";
                $_SESSION['searchNamePTSurkonMyb'] = "";
                $_SESSION['nosuratfilterSurkonMyb'] = "";
                $_SESSION['jenisformfilterSurkonMyb'] = "";
                $_SESSION['reqdatetofilterSurkonMyb'] = "";
                $_SESSION['reqdatefromfilterSurkonMyb'] = "";
                $_SESSION['ccspicfilterSurkonMyb'] = "";
                $_SESSION['statusformfilterSurkonMyb'] = "";

                //surkon-pendebetan-ccs
                $_SESSION['surkonpendebetanID'] = "";
                $_SESSION['surkonpendebetanCost'] = "";
                
                //surkon-request-form-ccs
                $_SESSION['surkonreqjenisform'] = "";
                $_SESSION['surkonreqjenisformlain'] = "";
                $_SESSION['surkonreqperusahaanid'] = "";
                $_SESSION['surkonreqperusahaanname'] = "";
                $_SESSION['surkonreqcis'] = "";
                $_SESSION['surkonreqpicpembuatsurat'] = "";
                $_SESSION['surkonreqketerangan'] = "";

                //surkon-bukti-pendebetan-ccs
                $_SESSION['surkonbiayafilterbatch'] = "";
                $_SESSION['surkonbiayanamaPT'] = "";
                $_SESSION['surkonbiayanosurat'] = "";
                $_SESSION['surkonbiayajenisform'] = "";
                $_SESSION['surkonbiayareqid'] = "";
                $_SESSION['surkonbiayapembuatsurat'] = "";
                $_SESSION['surkonbiayaspv'] = "";

                $_SESSION['surkonauthfilterbatch'] = "";
                
                $_SESSION['surkonpendebetanbatcher'] = "";
                $_SESSION['idd23'] = "";

                header("location: welcome-user-teacher.php");
              }

              else if($role == "teacher"){
                if($password=="bcabca"){
                  $_SESSION["changepw"] = 1;
                }else{
                  $_SESSION["changepw"] = 0;
                }
                session_start();
                $_SESSION["nip"] = $nip;
                $_SESSION["status"] = '%';
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = 2;
                $_SESSION["name"] = $name;
                $_SESSION["unt"] = $unit_kerja;
                $_SESSION["mode"] = 1;
                $_SESSION["page"] = 0;
                $_SESSION['searchwaiting']=0;
                $_SESSION['page'] = 0;
                $_SESSION['searchNamePT'] = "";
                $_SESSION['jenisformfilterviewformccs'] = "";
                $_SESSION['reqdatetofilterviewformccs'] = "";
                $_SESSION['reqdatefromfilterviewformccs'] = "";
                $_SESSION['ccspicfilterviewformccs'] = "";
                $_SESSION['statusformfilterviewformccs'] = "";
                $_SESSION['searchNamePTMY'] = "";
                $_SESSION['jenisformfilterviewformccsMY'] = "";
                $_SESSION['reqdatetofilterviewformccsMY'] = "";
                $_SESSION['reqdatefromfilterviewformccsMY'] = "";
                $_SESSION['ccspicfilterviewformccsMY'] = "";
                $_SESSION['statusformfilterviewformccsMY'] = "";
                
                $_SESSION['searchDayEditClass'] = "";
                $_SESSION['searchTeacherEditClass'] = "";
                $_SESSION['searchClassEditClass']= "";
                $_SESSION['searchStartTimeEditClass']= "";
                $_SESSION['searchEndTimeEditClass']= "";
                $_SESSION['searchSubjectNameEditClass'] = "";
                $_SESSION['filtersubjectsess'] = "";

                header("location: welcome-user-teacher.php");
              }

              //USER ADMIN
              else if($role == 0){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = 0;   
                $_SESSION["name"] = $name;
                $_SESSION["nip"] = $nip;
                $_SESSION['searchNameUser'] = "";
                $_SESSION['searchNameUnitKerja'] = "";
                $_SESSION['searchNameJenisForm'] = "";

                //edit-class

                $_SESSION['searchDayEditClass'] = "";
                $_SESSION['searchTeacherEditClass'] = "";
                $_SESSION['searchClassEditClass']= "";
                $_SESSION['searchStartTimeEditClass']= "";
                $_SESSION['searchEndTimeEditClass']= "";
                $_SESSION['searchSubjectNameEditClass'] = "";
                
                header("location: welcome-sa.php");
              }

            } else{
              $password_err = "The password you entered was not valid.";
            }
          }
        } else{
          $username_err = "No account found with that username.";
        }
      } else{
        echo "Oops! Something went wrong. Please try again later.";
      }
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="shortcut icon" href="../favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/login.css">
    <link
        rel="stylesheet"
        href="css/animate.min.css"
    />
</head>

<style>
  /* body {
    background-image: url('css/BCA Virtual Background A BCA.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  } */
</style>

<body>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="row">
      <div class="col-sm-4">
        <div class="container">
          
          <div class="col-md-12 offset-md-1 border shadow-lg p-3 mb-5 bg-light mt-4 p-4 rounded animate__animated animate__fadeInUp">
            <div class="row rounded" style="background-color:#0060AF">
              <h4 class="text-light">Welcome to SMS, Please Login!</h4>
            </div>  
            <br>
            <div class="row">
              <div class="login-form">
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row g-3">
                    <?php if($password_err <> "" || $username_err <> ""){ ?>
                    <b style="color:red;"><?php echo $password_err; echo $username_err; ?></b>
                    <?php } ?>
                    <div class="col-12">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-12">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <!--<div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe"> Remember me</label>
                        </div>
                    </div>-->
                    <p></p>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" role="button">Login</button>
                        <a href="../home.php" class="btn btn-danger" role="button">Back</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-8"></div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://www.markuptag.com/bootstrap/5/js/bootstrap.bundle.min.js"></script>
</body>
</html>