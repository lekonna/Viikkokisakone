
<html xmlns="http://www.w3.org/1999/xhtml">     <head>
        <title>Pelaajien Lisäys</title>
        <link rel=stylesheet href="viikkis.css" type="text/css">
    </head>
    <body>
            <? include ("menu.php"); ?>
    <form method="POST">
        <h1>Yllätpitohommelit</h1>
        <table>
       </table>
    <input type="submit" value="laske tasoitukset">
    </form>
    <?
   include("config.php");    
    global $settings;
    $db = $settings['DB_ADDRESS'];
    $user = $settings['DB_USERNAME'];
    $pass = $settings['DB_PASSWORD'];
    
    $conn = mysql_connect($db,$user,$pass);
    if (!$conn) {
        print "Tietokantayhteys on munillaan.";
        return false;        
    }
    if (!mysql_select_db($settings['DB_DB'])){
        print "Kantaa ei löydy.";
        return false;
    }
    
    $query = sprintf("SELECT * from %sPelaaja",$settings['DB_PREFIX']);
    $result = mysql_query( $query );
    if (!$result) {
        echo mysql_error();
        return false;
    }
    print "Kannassa olevat Pelaajat:<table width=400 border=1><tr><td>Nimi</td></tr>";
    while($row = mysql_fetch_row($result)) {
        print "<tr><td>".$row[1]."</td></tr>\n";
	laske_tasoitus( $row[0] );
    }
    print "</table>";

    if (count($_POST)) {
	
    }
 function laske_tasoitus( $pelaaja ) {

        global $settings;
        /* katotaan monta kiessiä pelaaja on pelannut ja sen perusteella otetaan x parasta */
        $query = sprintf("SELECT count(*) FROM %sKiessi WHERE pelaaja=%d",$settings['DB_PREFIX'],$pelaaja);

        $result = mysql_query($query);
        if (!$result) {
            echo mysql_error();
            return false;
        }
        $row = mysql_fetch_row($result);

        switch ($row[0]) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $rounds_to_use = 1;
                break;
            case 7:
            case 8:
                $rounds_to_use = 2;
                break;
            case 9:
            case 10:
                $rounds_to_use = 3;
                break;
            case 11:
            case 12:
                $rounds_to_use = 4;
                break;
            case 13:
            case 14:
                $rounds_to_use = 5;
                break;
           default:
                $rounds_to_use = 6;
    }


        $query = sprintf("SELECT tulos,rata from %sKiessi where pelaaja=%d ORDER BY tulos ASC LIMIT %d",$settings['DB_PREFIX'],$pelaaja,$rounds_to_use);
        $result2 = mysql_query($query);
        if(!$result2){
            echo mysql_error();
            return false;
        }
        /* sitte lasketaa diffikset */
        $diffit = array();
        while ($row = mysql_fetch_assoc($result2))
        {

            /* tarvitaan radan slope ja rating */
            $query2 = sprintf("SELECT course_rating,slope_rating from %sRata where id=%d",$settings['DB_PREFIX'],$row['rata']);
            $result3 = mysql_query($query2);
            if(!$result3){
                echo mysql_error();
            return false;
            }
            $rata = mysql_fetch_assoc($result3);

            $diff = ($row['tulos']-$rata['course_rating'])*64/$rata['slope_rating'];

            array_push($diffit,$diff);
        }


        $tasoitus = (array_sum($diffit)/count($diffit))*0.96;


        $query = sprintf("UPDATE %sTasoitus_taulu set tasoitus=%2f where pelaaja=%d",$settings['DB_PREFIX'],$tasoitus,$pelaaja);


        $result3 = mysql_query($query);
          if(!$result3){
                echo mysql_error();
            return false;
            }
    }    

    ?>
