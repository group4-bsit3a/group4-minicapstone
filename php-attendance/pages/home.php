<?php
//session_start();
//require_once('send.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
   // $timein = $_POST['timein'];
   // $timeout = $_POST['timeout'];
   // $logdate= $_POST['logdate'];
   // $status= $_POST['status'];

    $db = new DBconnect();

    // Check if the user is entering or leaving
    $sql_check = "SELECT id, timeout FROM students_qrcode WHERE name = '$name' AND DATE(timeout) IS NULL";
    $result_check = $db->conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // User is leaving, update timeout, logdate, and set status to 1
        $sql_update_timeout = "UPDATE students_qrcode SET timeout = NOW(), logdate = NOW(), status = 1 WHERE name = '$name' AND DATE(timeout) IS NULL";
        $db->conn->query($sql_update_timeout);
        $_SESSION['success'] = 'Leaving recorded successfully';
    } else {
        // User is entering, insert timein, logdate, and set status to 0
        $sql_insert_timein = "INSERT INTO students_qrcode (name, timein, logdate, status) VALUES ('$name', NOW(), NOW(), 0)";
        $db->conn->query($sql_insert_timein);
        $_SESSION['success'] = 'Attendance added successfully';
    }

    $db->conn->close();
}

?>
<?php
class DBconnect
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "simple_attendance_db";
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<style>
    .picture2 {
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-color: white;
        opacity: 5.5;
        font-weight: bold;
        background-size: 1000%;
    }

    .bodybackground.picture2 {
        opacity: 0.6;
    }
</style>
  

<body background ="https://i.ibb.co/TBG5CDf/370225840-255844097485264-239052655727897735-n.jpg" alt="ISCC" class="picture2">

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <video id="preview" width="100%"></video>
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>
                       <h4>Error!</h4>
                       " . $_SESSION['error'] . "
                       </div>";
                }
                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-primary'>
                           <h4>Success!</h4>
                           " . $_SESSION['success'] . "
                           </div>";
                }
                ?>
            </div>

            <div class="col-md-6">
                <form action=" " method="post" class="form-horizontal">
                    <label>SCAN QR CODE</label>
                    <input type="text" name="name" id="text" readonly placeholder="Scan QR code" class="form-control">
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>id</td>
                            <td>name</td>
                            <td>time In</td>
                            <td>timeout</td>
                            <td>logdate</td>
                            <td>status</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "simple_attendance_db";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed" . $conn->connect_error);
                        }

                        $sql = "SELECT id, name, TIME_FORMAT(timein, '%H:%i:%s') AS timein, timeout, logdate, status FROM students_qrcode WHERE DATE(timein) = CURDATE()";
                        $query = $conn->query($sql);
                        while ($row = $query->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['timein']; ?></td>
                                <td><?php echo $row['timeout']; ?></td>
                                <td><?php echo $row['logdate']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview')
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found');
                }
            }).catch(function (e) {
                console.error(e);
            });

            scanner.addListener('scan', function (content) {
                document.getElementById('text').value = content;
                document.forms[0].submit();
            });
        });
    </script>

</body>

</html>