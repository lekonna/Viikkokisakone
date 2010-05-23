
<html xmlns="http://www.w3.org/1999/xhtml">     <head>
        <title>Pelaajien Lisäys</title>
        <link rel=stylesheet href="viikkis.css" type="text/css">
    </head>
    <body>
            <? include ("menu.php"); ?>
    <form method="POST">
        <h1>Pelaajien Lisäys</h1>
        <table>
        <tr><td>Nimi</td><td><input type="text" name="nimi"></td></tr>
       </table>
    <input type="submit" value="Save">
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
    }
    print "</table>";

    if (count($_POST)) {
        if (LisaaPelaaja()){
            print "Pelaaja Lisätty";
        }
        else print "Ei onnistu";
    }

    function LisaaPelaaja() {
    global $settings;
    echo '<table>';
    $query = sprintf("INSERT INTO %sPelaaja (id, nimi)
    VALUES (NULL, '%s')",
    $settings['DB_PREFIX'],
    mysql_real_escape_string($_POST['nimi']));
    $result =  mysql_query($query);
    if (!$result) {
        echo mysql_error();
        return false;
    }
    $query = sprintf("INSERT INTO %sTasoitus_taulu (id,pelaaja,tasoitus)
    VALUES (NULL, %d,%f)",    $settings['DB_PREFIX'],mysql_insert_id(),0);
    
    if (mysql_query($query)) {
        return true;
    } else {
        echo mysql_error();
        return false;
    }    

    }
    
    ?>