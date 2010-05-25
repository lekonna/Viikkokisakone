
<html xmlns="http://www.w3.org/1999/xhtml">     <head>
        <title>Tasoitukset</title>
        <link rel=stylesheet href="viikkokisat/viikkis.css" type="text/css">
    </head>
    <body>
        <div id="container">
            <div id="tasoitus_taulu">
    <?
include("viikkokisat/config.php");    
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
        print "Kantaa ei lˆydy.";
        return false;
    }
    
    
    $query = sprintf("SELECT * FROM %sPelaaja
                            INNER JOIN %sTasoitus_taulu WHERE %sPelaaja.id = %sTasoitus_taulu.pelaaja ORDER BY tasoitus ASC",$settings['DB_PREFIX'],$settings['DB_PREFIX'],
                            $settings['DB_PREFIX'],$settings['DB_PREFIX'],$settings['DB_PREFIX'],$settings['DB_PREFIX'],$settings['DB_PREFIX']);

    $result = mysql_query( $query );
    if (!$result) {
        echo mysql_error();
        return false;
    }
    print "Tasoitukset:<table border=1><tr><td>Nimi</td><td>Tasoitus</td><td>rundit</td></tr>";
    while($row = mysql_fetch_array($result)) 
        {
        print '<tr><td>'.$row['nimi'].'</td>';
        print '<td>'.$row['tasoitus'].'</td>';
        $pelaaja_id = $row[0];
        
        $query = sprintf("SELECT * FROM %sKiessi WHERE %sKiessi.pelaaja = %d",$settings['DB_PREFIX'],$settings['DB_PREFIX'],$row['0'] );
         $result2 = mysql_query( $query );
         if (!$result2) {
             echo mysql_error();
             return false;
         }
        while($row2 = mysql_fetch_assoc($result2)) {
                print "<td>".$row2['tulos']."</td>";
        }
       print "</tr>";

        
//        print "<tr><td>".$row[1]."</td><td>".$row[2]."</td></tr>\n";
    }
    print "</table>";
      
    ?>
            </div>
            <div id="infotaulu">
                <h4>Miten tasoitukset lasketaan</h4>
            <p>
                Tasoituksien laskemiseen k‰ytet‰‰n golffista tuttua tasoituslaskutapaa jossa radalle on m‰‰ritelty rating ja slope.
                Periaatteessa n‰m‰ arvot vastaavat tulosta jonka huippuheitt‰j‰ (rating) ja huono heitt‰j‰ (slope) heitt‰v‰t tyypillisesti radalla.
                </p>
                <p>
                    Pelaajan rating siis m‰‰rittyy heitettyjen kierrosten perusteella. Jos kieroksia on alle 6 on rating parhaasta kierroksesta laskettu
                    tasoitus radan vaativuuta vasten kerrottuna 0.96:lla. Tasoitukseen otetaan huomioon parhaat kierokset seuraavalla tavalla
                    </p>
                    <p>
                    <table id="kiessitaulu">
                        <tr><th>Kierrokset</th><th>Parasta kierrosta</th></tr>
                        <tr><td>1-6</td><td>1</td></tr>
                        <tr><td>7-8</td><td>2</td></tr>
                        <tr><td>9-10</td><td>3</td></tr>
                        <tr><td>11-12</td><td>4</td></tr>
                        <tr><td>13-14</td><td>5</td></tr>
                        <tr><td>15+</td><td>6</td></tr>
                    </table>
                 </p>
                 <p>
                    Kisan voittajaa laskettaessa kilpailian heiteetyjen heittojen m‰‰r‰st‰ v‰hennet‰‰n h‰nen tasoituksensa. Esimerkiksi: jos
                    Pertti peruspukkaaja on heitt‰nyt kierostuloksen 54 ja h‰nen tasoituksensa on 10, on h‰nen tasoitettu tuloksensa 44.
                    Tasoitus kisan voittaa pelaaja jolla on pienin tasoitettu tulos.
                    </p>
            </div>    
        </div>      
    