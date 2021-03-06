<?php
	session_start();
    require "database.php";

	include 'loading.html'; // Display loading screen

	// Pulling data from "eventshift" 

		$sql = "SELECT * FROM eventshift WHERE EventID=:eventID";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(["eventID" => $_POST['eventID']]);
		$shiftsList = array();
		$shiftsList = $stmt->fetchAll();

	// Looping for every shift
		for($l = 0; $l<count($shiftsList); $l++){

			// Pulling data from shifts to see if it's full or not

			$sql = "SELECT * FROM shifts WHERE ShiftID=:shiftID";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['shiftID' => $_POST['shiftID'][$l]]);
			$sc = $stmt->fetch(PDO::FETCH_OBJ);
			$PA = $sc->PositionsAvailable;

			// Checks if that shift is picked -> $l -> shift number
				if(isset($_POST['submit'][$l])){
					if($PA!=0){
						// Pulling data from "studentshiftrequest" to check for a repeat

							$sql = "SELECT * FROM studentshiftrequests WHERE EventID = :eventID AND StudentID = :studentID AND ShiftID = :shiftID";
							$stmt = $pdo->prepare($sql);
							$stmt->execute(['eventID' => $_POST['eventID'], 'studentID' => $_SESSION['StudentID'], 'shiftID' => $_POST['shiftID'][$l]]);

						// Check for repeat

							if($stmt->rowCount()===0){
								// Inserting data into "studentshiftrequests"

									$sql = "INSERT INTO studentshiftrequests (EventID, StudentID, ShiftID) VALUES ( :eventID, :studentID, :shiftID)";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['eventID' => $_POST['eventID'], 'studentID' => $_SESSION['StudentID'], 'shiftID' => $_POST['shiftID'][$l]]);

									header('location: events.php?formSubmitConfirm=true');
							}
					}
					else{header('location: events.php?Volunteer=false');}
				}
		}

	// Rerouting user to "events.php"

	header('location: events.php');
?>