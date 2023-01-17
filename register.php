<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>user reggister</title>
</head>
<body>
<?php
include'../conn.php';
if(isset($_POST["adduser"])){
  function abc($abcd){
    $abcd=trim($abcd);
    $abcd=htmlspecialchars($abcd);
    $abcd=stripslashes($abcd);
    return $abcd;
    }
$role=2;
$name=abc(mysqli_real_escape_string($conn,$_POST["name"]));
$email=abc(mysqli_real_escape_string($conn,$_POST["email"]));
$pass=abc(mysqli_real_escape_string($conn,$_POST["pass"]));
$rpass=abc(mysqli_real_escape_string($conn,$_POST["rpass"]));
$uimage=abc(mysqli_real_escape_string($conn,$_FILES["uimage"]["name"]));


$passhash=password_hash($pass,PASSWORD_BCRYPT);

$allowedexten=array('jpeg','png','jpg');
$filename=$_FILES["uimage"]["name"];
$fileexten=pathinfo($filename,PATHINFO_EXTENSION);


if(empty($name)){
$errorn="name must be required";
}
elseif(!preg_match("/^[a-zA-Z0-9]+$/",$name)){
$errorn="alpha numeric only";
}


elseif(empty($email)){
    $errorn="email must be required";
    }

    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $errorn="email is invalid";
    }


    elseif(empty($pass)){
    $errorn="password must be required";
}

elseif(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",$pass)){
  $errorn="Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character:";
  }
  

elseif($pass!=$rpass){
    $errorn="password and confirm password are not match";
}

elseif(empty($uimage)){
$errorn="image must be required";
}

elseif(!in_array($fileexten,$allowedexten)){
  $errorn="png ,jpeg ang jpg allowed only.";
  }
  elseif($_FILES['uimage']['size']> 3500000){
  $errorn="not allowed more than 1 mb";
  }

  
/* elseif(strlen($name) <5 || strlen($name)>10){
$errorn="user name between 5 to 10 characters";
} */

/* elseif(strlen($pass) <3 || strlen($pass)>80){
  $errorn="user pass between 3 to 8 characters";
  } */




    else{
      if(file_exists("userimages/".$_FILES['uimage']['name'])){
      $filename=$_FILES['uimage']['name'];
      $errorn="image already exists";
      }
      else{
    $select=mysqli_query($conn,"select * from users where u_email='$email' ");
    $count=mysqli_num_rows($select);
    if($count>0){
    $errorn="already exist";
    }
    else{
    
        move_uploaded_file($_FILES['uimage']['tmp_name'],'userimages/'.$uimage);
        $insert=mysqli_query($conn,"insert into users values(null,'$name','$email','$$passhash' ,'$uimage')"); 
        $insertrole=mysqli_query($conn,"insert into role values(null,'$role')");
         
         if(@$insert && $insertrole){
            $errorn="data has added";
        }
        else{
            echo'
            <script>
            alert("dat has  not added");
             </script>
            ';
            header('location:register.php?res=data has not added');
        }

      }
    }
    }
}
?>








<section class="vh-100 bg-image"
  style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-5">Create an account</h2>

  
  
   
              <?php
if(isset($errorn)){
echo"
<div class='alert alert-dark rounded-pill fs-5'>
$errorn
</div>";
}
  ?>


  <form method="post" enctype="multipart/form-data">

                <div class="form-outline mb-4">
                  <input name="name" type="text" value=" <?php echo @$name ?>" id="form3Example1cg" class="form-control rounded-pill form-control-lg" />
                  <label class="form-label" for="form3Example1cg">Your Name</label>
                  
  

                </div>

                <div class="form-outline mb-4">
                  <input type="email" value=" <?php echo @$email ?>" name="email" id="form3Example3cg" class="form-control rounded-pill form-control-lg" />
                  <label class="form-label" for="form3Example3cg">Your Email</label>
     </div>

                <div class="form-outline mb-4">
                  <input type="password" name="pass"   id="form3Example4cg" class="form-control rounded-pill form-control-lg" />
                  <label class="form-label" for="form3Example4cg">Password</label>
                  <br>
                  

                </div>

                <div class="form-outline mb-4">
                  <input type="password" name="rpass" id="form3Example4cdg" class="form-control rounded-pill form-control-lg" />
                  <label class="form-label" for="form3Example4cdg">Repeat your password</label><br>
  
                </div>



                
                <input type="file" name="uimage" class="form-control rounded-pill form-control-lg"/>
                
<br>

               <br> 
                <div class="d-flex justify-content-center">
                  <button type="submit" name="adduser" class="form-control rounded-pill form-control-lg btn btn-primary"/>Register</button>
                </div>

                <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="#!"
                    class="fw-bold  text-body"><u>Login here</u></a></p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>

