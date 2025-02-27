<?php
    include('../../config.php');
    include('../../utils.php');

    session_start();
    validateSession('admin_id', '../../index.php');

    customHeader('Admin PMFKI Account', '../../../public/css/style.css', '../../../public/icon/icon.png');
?>

    <body>
        <?php
            adminNavigation();
            popUp('pmfki.php');
        ?>

        <!-- Form Popup -->
        <div id="popup_form" class="popup-form">
            <div class="popup-content">
                <form action="pmfki.php" method="POST" enctype="multipart/form-data">
                    <h2>Create New Account For PMFKI</h2>
                    <table>
                        <tr>
                            <th>Name</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_name" required></td>
                        </tr>
                        <tr>
                            <th>Identity Card Number</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_ic" required></td>
                        </tr>
                        <tr>
                            <th>Matrics Number</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_id" required></td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td class="fill">:</td>
                            <td><input type="password" name="pmfki_pwd" required></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_phone" required></td>
                        </tr>
                    </table>
                    <br>
                    <button class="normal-btn" type="button" value="" onclick="location.href='pmfki.php'">Back</button>
                    <button class="normal-btn" type="submit" name="confirm">Confirm</button>
                </form>
            </div>
        </div>

        <div class="table-list">
            <h1>PMFKI</h1>
            <div class=middle-button>
                <button class="normal-btn" onclick="popup_form()">Add New PMKFI Account</button>
            </div>
            <table border="1" width="100%" class="event-list-table"> <!-- id="event-list-table" --> 
                <tr>
                    <th colspan="13">LIST OF PMFKI</th>
                </tr>
                <tr>
                    <td width="2%">No</td>
                    <td width="30%">Name</td>
                    <td width="10%">Matrics Number</td>
                    <td width="10%">Identity Card Number</td>
                    <td width="10%">Phone Number</td>
                    <td width="10%">Action</td>
                </tr>
                <?php
                    $sql = "SELECT * FROM pmfki";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $numrow=1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td>";
                            echo "<td>". $row["pmfki_name"] . "</td>";
                            echo '<td>' . $row["pmfki_id"] . '</td>';
                            echo '<td>' . $row["pmfki_ic"] . '</td>';
                            echo "<td>" . $row["pmfki_phone"] . "</td>";
                            echo '<td><button class="normal-btn" onclick="location.href=\'pmfki_edit.php?id=' . $row["pmfki_id"] . '\'">Edit</button>';
                            echo '<button class="decline-btn" onclick="location.href=\'pmfki_delete.php?id=' . $row["pmfki_id"] . '\'">Delete</button></td>';
                            echo "</tr>" . "\n\t\t";
                            $numrow++;
                        }
                    } else {
                        echo '<tr><td colspan="7">0 results</td></tr>';
                    } 
                ?>
            </table>
        </div>
    </body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $pmfki_name = strtoupper(trim($_POST["pmfki_name"]));
            $pmfki_id = strtoupper(trim($_POST["pmfki_id"]));
            $pmfki_pwd = strtoupper(trim($_POST["pmfki_pwd"]));
            $pmfki_ic = trim($_POST["pmfki_ic"]);
            $pmfki_phone = trim($_POST["pmfki_phone"]);
            $pwd_hash = trim(password_hash($_POST["pmfki_pwd"], PASSWORD_DEFAULT));

            if(isset($_POST["confirm"])){
                $sql = "INSERT INTO pmfki (pmfki_name, pmfki_ic, pmfki_id, pmfki_phone, pmfki_pwd)
                        VALUES ('$pmfki_name', '$pmfki_ic', '$pmfki_id', '$pmfki_phone', '$pwd_hash')";
                $status = executeQuery($conn, $sql);
    
                if($status){
                    echo '<script>popup_page_stay("New PMFKI account has been created");</script>';
                }
                else{
                    echo '<script>popup_page_stay("There was an error creating a new account");</script>';
                }
            }
        }
    ?>
</html>
