<?php
    include('../../config.php');
    include('../../utils.php');

    session_start();
    validateSession('student_id', '../../index.php');

    
    $student_id = $_SESSION['student_id'];
	$sql = "SELECT * FROM student WHERE student_id = '$student_id'";
	$result = mysqli_query($conn, $sql);
	if($result){
        $row = mysqli_fetch_assoc($result);
		if($row){
            $student_name= $row['student_name'];
			$student_ic = $row['student_ic'];
			$student_id = $row['student_id'];
			$student_email = $row['student_email'];
			$student_phone = $row['student_phone'];
			$student_address = $row['student_address'];
			$student_profilePic = $row['student_profilePic'];
		}
	}

    customHeader('Student Profile', '../../../public/css/style.css', '../../../public/icon/icon.png');
?>

    <body>
        <?php
            studentNavigation();
            popUp('student_profile.php')
        ?>

        <div class="profile-row">
            <div class="profile-left">
                <?php echo "<img src='../../../public/storage/profile/$student_profilePic' alt='Profile Picture'>";?> 
            </div>
            
            <div class="profile-right">
                <h1> My Profile </h1>
                <form action="student_profile_edit.php" method=POST enctype="multipart/form-data" id="profile-edit">
                    <table width="100%" class="table-profile" >
                        <tr>    
                            <th> Name </th>
                            <td class="fill">:</td>
                            <td><textarea rows="1" name="student_name"><?php echo"$student_name";?></textarea></td>
                        </tr>  
                        <tr>    
                            <th> Identity Card Number </th>
                            <td class="fill">:</td>
                            <td><textarea rows="1" name="student_ic"><?php echo"$student_ic";?></textarea></td>
                        </tr> 
                        <tr>    
                            <th> Matrics Number </th>
                            <td class="fill">:</td>
                            <td><?php echo"$student_id";?></td>
                        </tr> 
                        <tr>    
                            <th> E-mail </th>
                            <td class="fill">:</td>
                            <td><textarea rows="1"  name="student_email"><?php echo"$student_email";?></textarea></td>
                        </tr> 
                        <tr>    
                            <th> Phone Number </th>
                            <td class="fill">:</td>
                            <td><textarea rows="1"  name="student_phone"><?php echo"$student_phone";?></textarea></td>
                        </tr> 
                        <tr>    
                            <th> Address </th>
                            <td class="fill">:</td>
                            <td><textarea rows="4" name="student_address"><?php echo"$student_address";?></textarea></td>
                        </tr>  
                        <tr>    
                            <th> Profile Picture </th>
                            <td class="fill">:</td>
                            <td><input type="file" name="student_profilePic"></input></td>
                        </tr>  
                    </table>
                    <div>
                        <br><br>
                        <button class="normal-btn" type="button" onclick="location.href='student_profile.php'">Cancel</button>
                        <button class="normal-btn" type="reset" onclick="reset_form('profile-edit')">Reset</button>
                        <button class="accept-btn" type="submit">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <?php
        $targetdir = "../../../public/storage/profile/";
        $targetfile = "";
        $uploadstat = 0;
        $imgfiletype = "";
        $uploadfilename = "";

        $student_id = $_SESSION['student_id'];
        $sql_retrieve = "SELECT * FROM student WHERE student_id = '$student_id'";
        $retrieved_result = mysqli_query($conn, $sql_retrieve);
        if ($retrieved_result) {
            $row = mysqli_fetch_assoc($retrieved_result);
            if ($row) {
                $imagename = $row['student_profilePic'];
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $student_name = trim($_POST['student_name']);
            $student_ic = trim($_POST['student_ic']);
            $student_email = trim($_POST['student_email']);
            $student_phone = trim($_POST['student_phone']);
            $student_address = trim($_POST['student_address']);

            $uploadstat = 0;

            if (isset($_FILES["student_profilePic"]) && $_FILES["student_profilePic"]["name"] == "") {
                $sql = "UPDATE student SET student_name = '$student_name', student_ic = '$student_ic', student_id = '$student_id',
                        student_email = '$student_email', student_phone = '$student_phone', student_address = '$student_address' 
                        WHERE student_id = '$student_id'";
                $status = executeQuery($conn, $sql);

                if ($status) {
                    echo '<script>popup_page_stay("Your profile has been updated");</script>';
                } else {
                    echo '<script>popup_page_stay("There was an error updating your profile");</script>';
                }
            } else if (isset($_FILES["student_profilePic"]) && $_FILES["student_profilePic"]["error"] == UPLOAD_ERR_OK) {
                $imagetype = (pathinfo($imagename, PATHINFO_EXTENSION));
                $oldimage = $student_id . "." . $imagetype;
                $oldimagetarget = $targetdir . $oldimage;

                $filetemp = $_FILES["student_profilePic"];
                $uploadfilename = $filetemp["name"];

                $imgfiletype = strtolower(pathinfo($uploadfilename, PATHINFO_EXTENSION));
                $targetfile = $targetdir . $student_id . "." . $imgfiletype;
                $imgnewname = $student_id . "." . $imgfiletype;

                $oldFileSize = file_exists($oldimagetarget) ? filesize($oldimagetarget) : 0;
                $newFileSize = $_FILES["student_profilePic"]["size"];

                if ($newFileSize > 500000) {
                    echo '<script>popup_page_stay("The file is too large. Try resizing your image")</script>';
                    $uploadstat = 0;
                }
                if ($imgfiletype != "jpg" && $imgfiletype != "png" && $imgfiletype != "jpeg") {
                    echo '<script>popup_page_stay("Only JPG, JPEG, and PNG files are accepted")</script>';
                    $uploadstat = 0;
                }

                if (file_exists($targetfile)) {
                    unlink($targetfile);
                }

                if (file_exists($oldimagetarget) && $oldFileSize != $newFileSize) {
                    unlink($oldimagetarget);
                }

                $sql = "UPDATE student SET student_name = '$student_name', student_ic = '$student_ic', student_id = '$student_id', 
                        student_email = '$student_email', student_phone = '$student_phone', student_address = '$student_address', 
                        student_profilePic = '$imgnewname' WHERE student_id = '$student_id'";

                $status = executeQuery($conn, $sql);

                if ($status) {
                    if (move_uploaded_file($_FILES["student_profilePic"]["tmp_name"], $targetfile)) {
                        echo '<script>popup_page_stay("Your profile has been updated")</script>';
                    } else {
                        echo '<script>popup_page_stay("There was an error updating your profile")</script>';
                    }
                } else {
                    echo '<script>popup_page_stay("Connection to Database Failed")</script>';
                }
            }
        }

    $conn->close();
    ?>


</html>