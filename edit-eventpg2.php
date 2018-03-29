<!DOCTYPE HTML>
<?php 
    session_start();
    include "database.php";
    include "adminCheck.php";

    // Checking all previous entries for content and then updating the event

		if(!empty($_POST['name']))
        {
            $sql = "UPDATE events SET Name=:name WHERE EventID=:eventID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["name" => $_POST['name'], "eventID" => $_POST['eventID']]); 
        }
		if(!empty($_POST['releasedate']))
        {
            $sql = "UPDATE events SET ReleaseDate=:releasedate WHERE EventID=:eventID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["releasedate" => $_POST['releasedate'], "eventID" => $_POST['eventID']]);
        }
        if(!empty($_POST['startdate']))
        {
            $sql = "UPDATE events SET StartDate=:startdate WHERE EventID=:eventID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["startdate" => $_POST['startdate'], "eventID" => $_POST['eventID']]);
        }
        if(!empty($_POST['location']))
        {
            $sql = "UPDATE events SET Location=:location WHERE EventID=:eventID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["location" => $_POST['location'], "eventID" => $_POST['eventID']]); 
        }
        if(!empty($_POST['enddate']))
        {
            $sql = "UPDATE events SET EndDate=:enddate WHERE EventID=:eventID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["enddate" => $_POST['enddate'], "eventID" => $_POST['eventID']]);
        }
        if(!empty($_POST['description']))
        {
            $sql = "UPDATE events SET Description=:description WHERE EventID=:eventID";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["description" => $_POST['description'], "eventID" => $_POST['eventID']]);
        }
        for($i = 0;$i<(int)$_GET["shifts"];$i++){

            // Putting in inputed data for the shift based on what has been changed

                if(!empty($_POST['date'][$i])){
                    $sql = "UPDATE `shifts` SET Date=:date WHERE ShiftID=:ShiftID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(["date" => $_POST['date'][$i], "ShiftID" => $_POST['shiftID'][$i]]); //order of arrays corresponds order of ?
                }
                if(!empty($_POST['starttime'][$i])){
                    $sql = "UPDATE `shifts` SET StartTime=:starttime WHERE ShiftID=:ShiftID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(["starttime" => $_POST['starttime'][$i], "ShiftID" => $_POST['shiftID'][$i]]); //order of arrays corresponds order of ?
                }
                if(!empty($_POST['endtime'][$i])){
                    $sql = "UPDATE `shifts` SET EndTime=:endtime WHERE ShiftID=:ShiftID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(["endtime" => $_POST['endtime'][$i], "ShiftID" => $_POST['shiftID'][$i]]); //order of arrays corresponds order of ?    
                }

                $sql = "SELECT * FROM positions WHERE ShiftID=:shiftID";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['shiftID' => $_POST['shiftID'][$i]]);
                $positionData = $stmt->rowcount();


                for($f = 0 ; $f < $positionData;$f++){

                    if(isset($_POST["remove"][$i][$f])){

                        $sql = "DELETE FROM positions WHERE PositionID=:PID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['PID' => $_POST['positionID'][$i][$f]]);

                        $sql = "UPDATE `shifts` SET PositionsAvailable=:PA WHERE ShiftID=:ShiftID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(["PA" => ((int)$_POST['PA']-1), "ShiftID" => $_POST['shiftID'][$i]]); //order of arrays corresponds order of ?    
                    
                    }

                    if(isset($_POST["empty"][$i][$f])){

                        $sql = "SELECT StudentID FROM positions WHERE PositionID=:PID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['PID' => $_POST['positionID'][$i][$f]]);
                        $data = $stmt->fetch(PDO::FETCH_OBJ);
                        $sid = $data->StudentID;
                        echo $sid;
                        if(!empty($sid)){

                            $sql = "UPDATE positions SET StudentID=:SID WHERE PositionID=:PID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['PID' => $_POST['positionID'][$i][$f],'SID' => NULL]);

                            $sql = "UPDATE `shifts` SET PositionsAvailable=:PA WHERE ShiftID=:ShiftID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(["PA" => ((int)$_POST['PA']-1), "ShiftID" => $_POST['shiftID'][$i]]); //order of arrays corresponds order of ?    
                        }

                    }

                }
        }
        
    // Setting cookie for Submit confirmation and rerouting user plus resetting session variables
            
        $temp = $_SESSION['StudentID'];
        session_unset();
        $_SESSION['StudentID'] = $temp;

        header("Location: edit-eventpg1.php?formSubmitConfirm=true&eventID=".$_POST['eventID']);
    
?>

