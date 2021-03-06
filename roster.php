<?php
    session_start();
    require "database.php";
    //redirect if no shift is specified
    if(!isset($_GET['eventID']) || !isset($_GET['shiftID'])){
        header("location: events.php");
    }

    $sql = "SELECT * FROM students WHERE StudentID=:studentID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["studentID" => $_SESSION["StudentID"]]);
    $Sdata = $stmt->fetch(PDO::FETCH_OBJ);
    $level = $Sdata->Position;
?>
<!DOCTYPE HTML>
<html>
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="320" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, width=device-width, user-scalable=no" />
    <head>

        <title>LPNHS - Rosters</title>
        
        <link rel="stylesheet" href="baseCSS.css">
        <link rel="icon" type="image/png" href="img/nhs_logo.png">
        <style>
            th, td{
                font-family: Bookman, sans-serif;
                text-align: center;
            }
            table, table tr{
                width: 100%;
            }
            #shiftDataTable tr th, td{
                width: 33.33%;
            }
            #rosterTable tr th. td{
                width: 100%;
            }
            table tr:nth-child(even){
                background-color: #e8cfa4;
            }
            
        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="headerJQuery.js"></script>
    </head>

    <header id = "header"><?php include "header.php"; ?></header>

    <body>
        
        <img id = "fixedBGImg" src = "img/NHS_logo.png"><!--Fixed Image in Background-->
        
        <div id = "footerPusher">
            <div class = "classic panel">
                <!--Event and Shift Data-->
                <form method = "post" action = "requestShift.php">
                    <table id = "shiftDataTable">
                        <?php
                            $sql = "SELECT * FROM events WHERE EventID= :eventID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['eventID'=>$_GET['eventID']]);
                            $eventData = array();
                            $eventData = $stmt->fetchAll();

                            echo '<p>', $eventData[0][1], '</p>'; //event name
                            
                            //shift data
                            $sql = "SELECT * FROM shifts WHERE ShiftID=:shiftID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['shiftID' => $_GET['shiftID']]);
                            $shiftData = array();
                            $shiftData = $stmt->fetchAll();
                            $formatted_startTime = date('g:i A', strtotime($shiftData[0][2]));
                            $formatted_endTime = date('g:i A', strtotime($shiftData[0][3]));
                            $formatted_date = date('m/d/Y', strtotime($shiftData[0][1]));

                            // Displaying the data for each shift
                            echo '<tr><th colspan = "3">Shift Information</th></tr>
                                <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Request Shift</th>
                                </tr>';
                            
                            if(count($shiftData)>0){
                                echo '<tr>';

                                // Hidden form info to be passed
                                    echo '<input type = "hidden" name = "eventID" value = "', $_GET['eventID'], '">';
                                    echo '<input type = "hidden" name = "shiftID" value = "', $_GET['shiftID'], '">';

                                echo '<td>', $formatted_date, '</td>';
                                echo '<td>', $formatted_startTime, ' to ', $formatted_endTime, '</td>';
                                
                                // Check if the volunteer button should appear
                                    $otherEntry = "none";
                                    
                                    if($shiftData[0][4]==0){
                                        $otherEntry = "Full";
                                    }

                                    $sql = "SELECT * FROM positions WHERE ShiftID=:shiftID AND StudentID=:studentID";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['shiftID'=>$shiftData[0][0], 'studentID'=>$_SESSION['StudentID']]);
                                    $shiftRepetitions = $stmt->rowCount();
                                    if($level==="Advisor"){$otherEntry="N/A for Advisors";}
                                    if($shiftRepetitions>0){
                                        $otherEntry = "Already Registered";
                                    }

                                    if($otherEntry==="none"){
                                        echo '<td><input type = "submit" name = "submit" value = "Volunteer!" class = "classicColor"></td>';
                                    }
                                    else{
                                        echo '<td>', $otherEntry, '</td>';
                                    }
                                echo '</tr>';
                            }
                        ?>
                    </table>
                </form>

                <hr>

                    <table id = "rosterTable">
                        <!--Fill in table with roster data-->
                        <?php
                            $sql = "SELECT * FROM positions WHERE ShiftID = :shiftID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['shiftID'=>$_GET['shiftID']]);

                            $positionData = array();
                            $positionData = $stmt->fetchAll();

                            echo '<tr><th>Current Roster</th></tr>';
                            for($i = 0; $i<count($positionData); $i++){
                                echo '<tr>';                                
                                if($positionData[$i][2]!==null){
                                    $sql = "SELECT * FROM students WHERE StudentID= :studentID";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['studentID'=>$positionData[$i][2]]);
                                    $studentData = array();
                                    $studentData = $stmt->fetchAll();
                                    echo '<td>', $studentData[0][1], ' ', $studentData[0][2],'</td>';
                                }
                                else{
                                    echo '<td style = "font-style: italic;">Position Available</td>';
                                }
                                echo '</tr>';
                            }
                        ?>
                    </table>
            </div>
        </div>
    </body>

    <footer id = "footer"><?php include "footer.php"; ?></footer>

</html>