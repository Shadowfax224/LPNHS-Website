<?php

// This document registers students for postions in events

	include "database.php";

	// Pulling data from "events"

		$sql = "SELECT * FROM events";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$eventData = array();
		$eventData = $stmt->fetchAll();
		$eventCount = $stmt->rowCount();

	// Looping for every event

	for($i = 0; $i<$eventCount; $i++){

		// Checking if that event was picked -> $i -> event number

			if(isset($_POST["submit"][$i])){

				// Deletes data from "requests" table

					$sql = "DELETE FROM studentshiftrequests WHERE StudentID=:studentID AND EventID=:eventID";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['studentID' => $_POST['studentID'][$i], 'eventID' => $_POST['eventID'][$i]]);

				// Adds data to studentevent/shifts/positions tables

					$sql = "INSERT INTO studentevent (StudentID, EventID) VALUES (:studentID, :eventID)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['studentID' => $_POST['studentID'][$i], 'eventID' => $_POST['eventID'][$i]]);

				// Updates "shifts" and "positions" data tables with new data

					$sql = "UPDATE shifts SET PositionsAvailable = PositionsAvailable - 1 WHERE ShiftID =:shiftID AND PositionsAvailable > 0";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['shiftID' => $_POST['shiftID'][$i]]);

					$sql = "UPDATE positions SET StudentID =:studentID WHERE ShiftID =:shiftID AND StudentID IS NULL LIMIT 1";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(['shiftID' => $_POST['shiftID'][$i], 'studentID' => $_POST['studentID'][$i]]);

				setcookie("formSubmitConfirm", "Student registered", time()+3600);
			}
	}

	header('Location: roster-requests.php');
?>