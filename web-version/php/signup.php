<?php
session_start();
include_once 'db.php';

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = md5($_POST['pass']);
$cpassword = md5($_POST['cpass']);
$Role = 'user';
$verification_status = '0';

//checking field are not empty
if(!empty($fname) && !empty($lname) && !empty($email) && !empty($phone) && !empty($password) && !empty($cpassword)) {

    //if email is valid
    if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
        //cheching email already exits
        $sql = mysqli_query($conn,"SELECT email FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            echo "$email ~ Already exits";
        }
        else {
            //checking passwords
            if($password == $cpassword) {
                //checking user uploaded image or not
                if(isset($_FILES['image'])) {
                    $img_name = $_FILES['image']['name'];
                    $img_typ = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    $img_explode = explode('.',$img_name);
                    $img_extension = end($img_explode);
                    $extensions = ['png','jpeg','jpg'];

                    if(in_array($img_extension,$extensions) === true) {
                        $time = time();
                        $newimagename = $time . $img_name;
                        if(move_uploaded_file($tmp_name, "../Images/" . $newimagename))
                        {
                            $random_id = rand(time(),10000000); //creating a user unique id
                            $otp = mt_rand(1111,9999); //creating 4 digits otp


                            //Insert data into table
                            $sq12 = mysqli_query($conn,"INSERT INTO users (unique_id, fname, lname, email, phone, password, image, otp, verification_status, Role)
                            VALIES ({$random_id}), '{$fname}', '{$lname}', '{$email}', '{$phone}', '{$password}', '{$newimagename}', '{$otp}', '{$verification_status}', '{$Role}')");

                            if($sq12){
                                $sq13 = mysqli_query($conn,"SELECT * FROM users WHERE email = '{$email}'");
                                if(mysqli_num_rows($sq13)>0) {
                                    $row = mysqli_fetch_assoc($sq13);   //fetching data

                                    $_SESSION['unique_id'] = $row['unique_id'];
                                    $_SESSION['email'] = $row['email'];
                                    $_SESSION['otp'] = $row['otp'];


                                    //lets start the mail function
                                    if($otp){
                                        $reciever = $email;
                                        $subject = "From: $fname $lname <$email>";
                                        $body = "Name "." $fname $lname \n Email "." $email \n ". " $otp";
                                        $sender = "From: 348kanavsvjc@gmail.com"; 
                                        
                                        if(mail($reciever,$subject,$body,$sender)){
                                            echo "success";
                                        }
                                        else {
                                            echo "email problem" . mysqli_error($conn);
                                        }
                                    }
                                    //mail function end

                                }
                            }
                        }
                    }
                    else {
                        echo "Please select a Profile Image- JPG, PNG, JPEG"
                    }


                }
                else {
                    echo "Please select a Profile Image";
                }

            }
            else {
                echo "Password don't match";
            }
        }

    }
    else {
        echo "$email ~ This is not a valid email";
    }
}


?>