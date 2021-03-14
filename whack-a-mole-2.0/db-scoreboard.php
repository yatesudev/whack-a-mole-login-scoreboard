<!DOCTYPE html> 
<html> 
	<head> 
		<title>Whack a Mole Bestenliste</title> 
        <style>
            body
            {
                margin: 0;
                padding: 0;
                background-color: rgb(58, 58, 58);
                background-size: unset;
                background-position: unset;
            }
            #success 
            {                
                font-size:30px;
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                color: #fff;
            }
            .whiteFont
            {
                color: #4ad1caee;
            }
            
            #back
            {
                font-size: 26px;
                outline: none;
                border: none;                      
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                height: 100px;
                width: 350px;
                cursor: pointer;
                border-radius: 20px;
                background-color: #4ad1caee;
                color: white;
            }
            h2
            {
                background-color: #4ad1caee;
            }
            form
            {
                text-align: center;
                margin-top: 50px;
            }
            td
            {
                margin-left: 20px;
            }
            table
            {
                margin: 0 auto;
                border-spacing: 15px;
            }
            </style>
    </head> 
	<body> 
        <form>           
            <div id="success">            
                <table>        
                    <tr> 
                        <h2>BESTENLISTE</h2>
                        <td class="whiteFont">Ranking</td> 
                        <td class="whiteFont">Username</td> 
                        <td class="whiteFont">Points</td> 
                    </tr>            
<?php
	session_start();
    
    $db = mysqli_connect("localhost","root","", "login_goralewski");
    
    if (mysqli_connect_errno()) 
    {
        echo("Connect error");
        exit();
    } 

    //error_reporting(0);

if(isset($_POST['score']))
{
    $score = mysqli_real_escape_string($db, $_POST['score']);

    $username = mysqli_real_escape_string($db, $_SESSION["Username"]); 

    $checkScore = "SELECT points FROM scoreboard WHERE username = '$username'";

    if (!$res = mysqli_query($db, $checkScore))
    {
        echo("Fehler res ist null");
        mysqli_close($db);
        exit();
    }

    $row = mysqli_fetch_assoc($res);

    if(isset($row["points"]))
    {
        $row = intval($row["points"]);
        $score = intval($score);
    }    

    if (!$row) 
    {
        echo("Insert");
        $query = "INSERT INTO scoreboard (Username, Points) VALUES ('$username', '$score')";
        mysqli_query($db, $query);
    }
    else if($row < $score)
    {
        echo("Update");
        $query = "UPDATE scoreboard SET Points = '$score' where username = '$username'";
        mysqli_query($db, $query);
    } 
    else 
    {
        echo("Score  kleiner als Highscore");
    }
}

    $result = mysqli_query($db, "SELECT username, points FROM scoreboard ORDER BY points DESC"); 

    $ranking = 1;

    if(mysqli_num_rows($result)) 
    { 
        while($row = mysqli_fetch_array($result)) 
        { 
            echo "<tr><td>{$ranking}</td> 
            <td>{$row['username']}</td> 
            <td>{$row['points']}</td></tr>"; 
            $ranking++;
        } 
    } 
?>
</table>
</div>
    <p>
        <input id="back" type="button" value="Zurück zum Spiel" onclick="window.location.href = 'whack-a-mole.htm';">
    </p> 
</form>
</body>
</html>