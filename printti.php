
<html xmlns="http://www.w3.org/1999/xhtml">     <head>
        <title>Tasoitukset</title>
        <link rel=stylesheet href="viikkis.css" type="text/css">
    </head>
    <body>
            <? include ("menu.php"); ?>
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
        print "Kantaa ei l�ydy.";
        return false;
    }
    
    
    $query = sprintf("SELECT * FROM %sPelaaja
                            INNER JOIN %sTasoitus_taulu WHERE %sPelaaja.id = %sTasoitus_taulu.pelaaja ORDER BY nimi ASC",$settings['DB_PREFIX'],$settings['DB_PREFIX'],
                            $settings['DB_PREFIX'],$settings['DB_PREFIX'],$settings['DB_PREFIX'],$settings['DB_PREFIX'],$settings['DB_PREFIX']);

    $result = mysql_query( $query );
    if (!$result) {
        echo mysql_error();
        return false;
    }
    print "Tasoitukset:<table cellspacing=0><tr><td>Nimi</td><td>Tasoitus</td><td>+/-</td><td>rundi</td><td>Tasoitettu</td></tr>";
    while($row = mysql_fetch_array($result)) 
        {
        print '<tr><td>'.$row['nimi'].'</td>';
        print '<td>'.$row['tasoitus'].'</td>';
	print '<td>'.(54-$row['tasoitus']).'</td>';
	print '<td>&nbsp;</td>';
	print '<td>&nbsp;</td>';
        $pelaaja_id = $row[0];
        
       print "</tr>";

        
//        print "<tr><td>".$row[1]."</td><td>".$row[2]."</td></tr>\n";
    }
    print "</table>";
      

    ?>
