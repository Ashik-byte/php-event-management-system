<?php
debug_backtrace() || die (); 

require_once('config.php');


$sql = "SELECT * FROM events WHERE date_time > NOW()";

$stmt = $conn->query($sql);

if($stmt->rowCount() > 0){
    while($row = $stmt->fetch()){

        $isJoinDisabled = ($row["attendees_count"] >= $row["capacity"]) ? 'disabled' : '';

        echo '<div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="position-relative">
                        <img src="assets/img/events/' . $row["image"] . '" class="card-img-top" alt="' . $row["name"] . '">
                        <span class="badge bg-white text-dark position-absolute top-0 end-0 p-2">' . date("M d, Y h:i A", strtotime($row["date_time"])) . '</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">' . $row["name"] . '</h5>
                        <p class="card-text">' . substr($row["description"], 0, 80) . '...</p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-secondary details-btn " 
                                    data-id="' . $row["id"] . '">
                                Details
                            </button>
                            <button class="btn btn-success join-btn "'.$isJoinDisabled.' 
                                    data-id="' . $row["id"] . '" 
                                    data-name="' . htmlspecialchars($row["name"]) . '"
                                    data-address="' . htmlspecialchars($row["address"]) . '"
                                    data-time="' . htmlspecialchars($row["date_time"]) . '">
                                Join
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
    }
}else{
    echo "<p>No events found.</p>";
}
// Close connection
$conn = null;
?>