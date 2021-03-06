<?php
    session_start();
    require "database.php";

	// Pulling data from "students" -> the amount of students there are ordered by position, lastname, and firstname

		$sql = "SELECT * FROM students WHERE Position='Student' ORDER BY LastName, FirstName";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$studentCount = $stmt->rowCount();
		$studentIDs = array();
		array_push($studentIDs, $stmt->fetchAll(PDO::FETCH_COLUMN, 0));

	//Check if current user is logged in

		if(isset($_SESSION["StudentID"])){

			// Pulling data from the current user

				$sql = "SELECT * FROM students WHERE StudentID=:studentID";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(["studentID" => $_SESSION["StudentID"]]);
				$data = $stmt->fetch(PDO::FETCH_OBJ);

			//Kicking out non-leadership if they want to "manage"
				if(isset($_GET["manage"]) && $_GET['manage']==="true" && $data->Position!=="Vice  President" && $data->Position!=="President" && $data->Position!=="Advisor" && $data->Position!=="Admin"){
					header('location: members.php');
				}
			
			//Resetting "students" $data if a VP is tryring to manage...

			if((isset($_GET["manage"]) && htmlspecialchars($_GET["manage"])==="true" && $data->Position==="Vice President")){
				//If a VP is trying to manage members, only load their specific students
				$sql = "SELECT * FROM students WHERE VicePresident=:vpID AND Position='Student' ORDER BY LastName, FirstName";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['vpID' => $_SESSION['StudentID']]);
				
				$studentCount = $stmt->rowCount();
				$studentIDs = array();
				array_push($studentIDs, $stmt->fetchAll(PDO::FETCH_COLUMN, 0));
			}

			// Checking users permissions based on "Position"
	
				if(isset($_GET["manage"]) && htmlspecialchars($_GET["manage"])==="true" && ($data->Position==="Vice President")):

					// User "Position" : admin view

						echo '<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Position</th>
							<th>VP</th>
							<th>Hours Completed</th>
							<th>Submit Changes</th>
							<th>Remove</th>
							</tr>';

						
						// Looping data for each student

							for($i = 0; $i<$studentCount; $i++){
								
								// Pulling data from "students"

									$sql = "SELECT * FROM students WHERE StudentID=:studentID";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(["studentID" => $studentIDs[0][$i]]);
									$data = array();
									$data = $stmt->fetchAll();
								
								// Displaying the student data into HTML elements

									echo '<tr>';
									echo '<td>', $data[0][1],' ',$data[0][2] ,'</td>';
									echo '<td>', $data[0][3], '</td>';
								
								// Display list of positions
									$positions = array("President", "Vice President", "Student");
									echo '<td><select name="position[', $i,']">';
									foreach($positions as $p){
										echo '<option ';
										//set default value
										if($data[0][7] === $p){
											echo 'selected = "selected" ';
										}
										echo 'value = "', $p, '">', $p, '</option>';
									}
									unset($p);
									echo '</select></td>';
								
								// Getting a list of all Vice Presidents

									$sql = "SELECT * FROM students WHERE Position=:vp";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['vp'=>"Vice President"]);
									$vpData = array();
									$vpData = $stmt->fetchAll();

								// Displaying Vice President data

									echo '<td><select name = "vicePresident[', $i, ']" form = "manageMembersForm">';
									for($vp = 0; $vp<count($vpData); $vp++){
										echo '<option ';
										//set default value
										if($vpData[$vp][0] === $data[0][6]){
											echo 'selected = "selected" ';
										}
										echo 'value = "', $vpData[$vp][0], '">', $vpData[$vp][1], ' ', $vpData[$vp][2], '</option>';
									}
									echo '</select></td>';
									echo '<td><input name = "hoursCompleted[', $i,']" type = "number" style = "max-width: 40px;" value=', $data[0][5], '></td>';
									echo '<td><input name = "submit[', $i,']" value = "Submit" class = "classicColor" type = "submit"></td>';
									echo '<td><input name = "remove[', $i,']" value = "Remove" class = "classicColor" type = "submit" onclick="return confirm(\'Are you sure?\')" style = "margin-right: 0px; background-color:red"></td>';
									echo '</tr>';
							}
					elseif(isset($_GET["manage"]) && htmlspecialchars($_GET["manage"])==="true" && ($data->Position==="President" || $data->Position==="Advisor" || $data->Position==="Admin")):

						// User "Position" : admin view
	
							echo '<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Position</th>
								<th>VP</th>
								<th>Hours Completed</th>
								<th>Submit Changes</th>
								<th>Remove</th>
								</tr>';
	
							
							// Looping data for each student
	
								for($i = 0; $i<$studentCount; $i++){
									
									// Pulling data from "students"
	
										$sql = "SELECT * FROM students WHERE StudentID=:studentID";
										$stmt = $pdo->prepare($sql);
										$stmt->execute(["studentID" => $studentIDs[0][$i]]);
										$data = array();
										$data = $stmt->fetchAll();
									
									// Displaying the student data into HTML elements
	
										echo '<tr>';
										echo '<td><input name = "studFirstName[', $i,']" value="', $data[0][1],'">
										<input name = "studLastName[', $i,']" value="',$data[0][2] ,'"></td>';
										echo '<td><input name="studEmail[', $i,']" value="', $data[0][3], '"></td>';
									
									// Display list of positions
										$positions = array("Admin", "Advisor", "President", "Vice President", "Student");
										echo '<td><select name="position[', $i,']">';
										foreach($positions as $p){
											echo '<option ';
											//set default value
											if($data[0][7] === $p){
												echo 'selected = "selected" ';
											}
											echo 'value = "', $p, '">', $p, '</option>';
										}
										unset($p);
										echo '</select></td>';
									
									// Getting a list of all Vice Presidents
	
										$sql = "SELECT * FROM students WHERE Position=:vp";
										$stmt = $pdo->prepare($sql);
										$stmt->execute(['vp'=>"Vice President"]);
										$vpData = array();
										$vpData = $stmt->fetchAll();
	
									// Displaying Vice President data
	
										echo '<td><select name = "vicePresident[', $i, ']" form = "manageMembersForm">';
										for($vp = 0; $vp<count($vpData); $vp++){
											echo '<option ';
											//set default value
											if($vpData[$vp][0] === $data[0][6]){
												echo 'selected = "selected" ';
											}
											echo 'value = "', $vpData[$vp][0], '">', $vpData[$vp][1], ' ', $vpData[$vp][2], '</option>';
										}
										echo '</select></td>';
										echo '<td><input name = "hoursCompleted[', $i,']" type = "number" style = "max-width: 40px;" value=', $data[0][5], '></td>';
										echo '<td><input name = "submit[', $i,']" value = "Submit" class = "classicColor" type = "submit"></td>';
										echo '<td><input name = "remove[', $i,']" value = "Remove" class = "classicColor" type = "submit" onclick="return confirm(\'Are you sure?\')" style = "margin-right: 0px; background-color:red"></td>';
										echo '</tr>';
								}
					else:

						// User "Position" : student view

							echo '<tr>
							<th>Name</th>
							<th>Email</th>
							</tr>';
							for($i = 0; $i<$studentCount; $i++){
								
								// Pulling data from "students"

									$sql = "SELECT * FROM students WHERE StudentID=:studentID";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(["studentID" => $studentIDs[0][$i]]);
									$studentData = array();
									$studentData = $stmt->fetchAll();

								// Displaying data from "students" into HTML elements

									if($studentData[0][7]==='Student'){
										echo '<tr>';
										//Link to Event History if user is leadership
										if($data->Position==="Vice President" && $studentData[0][6]===$data->StudentID
										|| $data->Position==="President"
										|| $data->Position==="Advisor"
										|| $data->Position==="Admin"){
											echo '<td><a href = "eventHistory.php?StudentID=', $studentData[0][0], '" title = "Click to view event history">', $studentData[0][1],' ',$studentData[0][2] ,'</a></td>';
										}
										else{
											echo '<td>', $studentData[0][1],' ',$studentData[0][2] ,'</td>';
										}
										echo '<td>', $studentData[0][3], '</td>';
										echo '</tr>';
									}
							} 
					endif;
				}

	// If the user is not logged in

		else{
			echo '<tr>
			<th>Name</th>
			<th>Email</th>
			</tr>';
			for($i = 0; $i<$studentCount; $i++){
				
				// Pulling data from "students"

					$sql = "SELECT * FROM students WHERE StudentID=:studentID";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(["studentID" => $studentIDs[0][$i]]);
					$data = array();
					$data = $stmt->fetchAll();

				// Displaying data from "students" into HTML elements

					if($data[0][7]==='Student'){
						echo '<tr>';
						echo '<td>', $data[0][1],' ',$data[0][2] ,'</td>';
						echo '<td>', $data[0][3], '</td>';
						echo '</tr>';
					}
			} 
		}
?>