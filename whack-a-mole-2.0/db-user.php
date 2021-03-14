<?php
    //Die folgenden beiden Funktionen gibt es eigentlich immer
    function error($str)
    {
        return "<!DOCTYPE html>
        <html lang=\"de\">
        <title>Datenbank</title>
				<head>
					<meta charset=\"utf-8\">
                    <style>
                    body{
                        margin: 0;
                        padding: 0;
                        background-color: rgb(58, 58, 58);
                        background-size: unset;
                        background-position: unset;
                    }
                    #success {                
                        font-size:30px;
                        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                        color: #4ad1caee;
                    }
                    #back{
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
                    form{
                        text-align: center;
                        margin-top: 400px;
                    }
                    </style>
                </head>     
                <body> 
                    <form>           
                        <div id=\"success\">$str.</div>
                        <p>
                            <input id=\"back\" type=\"button\" value=\"Zurück zu Login Seite\" onclick=\"window.location.href = 'loginPage.htm';\">
                        </p>
                    </form>
                </body>
        </html>";
    }
    
    function success($str)
    {
        return "<!DOCTYPE html>
        <html lang=\"de\">
        <title>Datenbank</title>
				<head>
					<meta charset=\"utf-8\">
                    <style>
                    body{
                        margin: 0;
                        padding: 0;
                        background-color: rgb(58, 58, 58);
                        background-size: unset;
                        background-position: unset;
                    }
                    #success {              
                        font-size:30px;
                        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                        color: #4ad1caee;
                    }
                    #back{
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
                    form{
                        text-align: center;
                        margin-top: 400px;
                    }
                    </style>
                </head>     
                <body> 
                    <form>           
                        <div id=\"success\">$str.</div>
                        <p>
                            <input id=\"back\" type=\"button\" value=\"Zurück zu Login Seite\" onclick=\"window.location.href = 'loginPage.htm';\">
                        </p>
                    </form>
                </body>
        </html>";
    }
    
    session_start();

    $db = mysqli_connect("localhost", "root", "", "login_goralewski");

    if (mysqli_connect_errno())
    {
        printf("Verbindung fehlgeschlagen: " . mysqli_connect_error());
        exit();
    }

    if ($_POST['usecase'] == "log")
    {
        $usr = mysqli_real_escape_string($db, $_POST['usr']);
        $query = "SELECT Passwort FROM user WHERE Username = '$usr'";

        if(!$res = mysqli_query($db, $query))
        {
            printf(error("Fehler: ".mysqli_error($db)));
            mysqli_close($db);
            exit();
        }

        if(!$row = mysqli_fetch_assoc($res)) 
        {
            printf(error("Der Benutzer ".$usr." ist nicht in der Datenbank vorhanden"));
            mysqli_close($db);
            exit();
        }
        else
        {
            if(password_verify($_POST['psw1'], $row['Passwort']))
            {     
                $_SESSION['loginSuccess'] = true;

                $_SESSION['Username'] = $usr;

                //header('Location: http://localhost/home/mywebapp.php');

                //echo(success("Willkommen " .$usr. ""));
                header("Location: http://localhost/whack-a-mole-2.0/whack-a-mole.htm");
            }
            else
            {
                echo(error("Das Passwort ist falsch"));
                mysqli_close($db);
                exit();
            }
        }               
        mysqli_free_result($res);
    }
    else if($_POST['usecase'] == "reg")
    {
        $usr = mysqli_real_escape_string($db, $_POST['usr']);
        
        $pswhash = password_hash($_POST['psw1'], PASSWORD_DEFAULT);
        
        $query = "SELECT Username FROM user WHERE Username = '$usr'";

        $res = mysqli_query($db, $query);

        if(!$row = mysqli_fetch_assoc($res))
        {
            $query = "INSERT INTO user (Username, Passwort) VALUES ('$usr', '$pswhash')";
            mysqli_query($db, $query);
            echo(success("Sie haben den Benutzer ".$usr. " erfolgreich angelegt"));
        }
        else
        {
            echo(error("Der Benutzer ".$usr. " ist schon in der Datenbank vorhanden"));
        }
        mysqli_close($db);
    }   
    if($_POST['usecase'] == "checkName")
    {
        $usr = mysqli_real_escape_string($db, $_POST['name']); 
          
        $query = "SELECT count(*) as countTest FROM user WHERE Username = '$usr'";

        $res = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($res);

        if($row['countTest'] >= 1)
        {
            echo("1");
        }
        else
        {
            echo("0");
        }       
    }
?>