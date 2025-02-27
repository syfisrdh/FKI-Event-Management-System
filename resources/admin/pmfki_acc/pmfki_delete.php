<?php
    include('../../config.php');
    include('../../utils.php');

    session_start();
    validateSession('admin_id', '../../index.php');

    customHeader('Admin PMFKI Account', '../../../public/css/style.css', '../../../public/icon/icon.png');
?>

    <body onload="auto_open_popup('popup_form')">
        <?php
            adminNavigation();
            popUp('pmfki.php');
        ?>

        <div id="popup_form" class="popup-form">
            <div class="popup-content">
                <p>Are you sure you want to delete this PMFKI account?</p>
                <form action="pmfki_delete.php?id=<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" method="POST"> 
                    <input type="text" id="pmfki_id" name="pmfki_id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" hidden>
                    <button class="normal-btn" type="button" action="" onclick="location.href='pmfki.php'">Cancel</button>
                    <button class="decline-btn" type="submit" name="confirm">Confirm</button>
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
                <tbody>
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
            $pmfki_id = $_POST['pmfki_id'];
            
            if(isset($_POST["confirm"])){
                $sql = "DELETE FROM pmfki WHERE pmfki_id = '$pmfki_id'";
                $delsql = "DELETE FROM event WHERE pmfki_id = '$pmfki_id'";
                $result = mysqli_query($conn, $delsql);
                if (mysqli_query($conn, $sql) && $result) {
                    echo '<script>auto_popup_message("PMFKI account with matrics number ' . $pmfki_id . ' has been deleted");</script>';
                }
                else{
                    echo '<script>auto_popup_message("There was an error deleting ' . $pmfki_id . ' account");</script>';
                }
                
            }
        }
    ?>
</html>
