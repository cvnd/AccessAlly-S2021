<?php
    include("FavouriteTimes.class.php");
    include("BloodDistribution.class.php");

    $times;
    $blood;
    if(isset($_POST["favourite_times_submit"])) {
        $input = $_POST["input"];
        $times = new FavouriteTimes;
        $times->endTimes($input);    
    }
    
    if(isset($_POST["blood_distribution_submit"])) {
        $input = $_POST["input"];
        $blood = new BloodDistribution;
        $blood->parse($input);
    }


?>

<!DOCTYPE>
<html lang=en>
    <head>
        <title>AccessAlly Assessment | Claudia Dioneda</title>

        <style>
            html {
                font-family: sans-serif;
            }
            form {
                width: 100%;
            }

            body {
                width: 20%;
                margin: auto;
                padding-top: 5%;
            }

            textarea {
                width: 100%;
                height: 100px;
            }

            input {
                width: 100%;
            }

            input[type="submit"] {
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <form action="" method="post" name="favourite_times">
            <label>Favourite Time:</label>
            <input type="text" name="input"></input>
            <input type="submit" value="Submit" name="favourite_times_submit">
        </form>
        <form action="" method="post" name="blood_distribution">
            <label>Blood Distribution:</label>
            <textarea type="text" name="input"></textarea>
            <input type="submit" value="Submit" name="blood_distribution_submit">
        </form>
        <div style="text-align: center">
            <?php 
            if(isset($times)) {
                echo $times->countTimes();
            } 

            if(isset($blood)) {
                echo $blood->getMaxPatients();
            }
            ?>
        </div>
    </body>
</html>
