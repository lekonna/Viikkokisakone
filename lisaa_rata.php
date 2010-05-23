
<html xmlns="http://www.w3.org/1999/xhtml">     <head>
        <title>Radan Lisäys</title>
        <link rel=stylesheet href="viikkis.css" type="text/css">
    </head>
    <body>
            <? include ("menu.php"); ?>
    <form method="POST">
        <h1>Radan Lisäys</h1>
        
        <table>
        <tr><td>Nimi</td><td><input type="text" name="nimi"></td></tr>
        <tr><td>Course Rating</td><td><input type="text" name="rating"></td></tr>
        <tr><td>Course Slope</td><td><input type="text" name="slope"></td></tr>
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
    
    $query = sprintf("SELECT * from %sRata",$settings['DB_PREFIX']);
    $result = mysql_query( $query );
    if (!$result) {
        echo mysql_error();
        return false;
    }
    print "Kannassa olevat Radat:<table width=600 border=1><tr><td>Nimi</td><td>Rating</td><td>Slope</td></tr>";
    while($row = mysql_fetch_row($result)) {
        print "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>\n";
    }
    print "</table>";

    if (count($_POST)) {
        if (LisaaRata()){
            print "Rata Lisätty";
        }
        else print "Ei onnistu";
    }

    function LisaaRata() {
    global $settings;
    echo '<table>';
    $query = sprintf("INSERT INTO %sRata (id, nimi, course_rating, slope_rating)
    VALUES (NULL, '%s', %s,%s)",
    $settings['DB_PREFIX'],
    mysql_real_escape_string($_POST['nimi']),
    mysql_real_escape_string($_POST['rating']),
    mysql_real_escape_string($_POST['slope']));
    
    if (mysql_query($query)) {
        return true;
    } else {
        echo mysql_error();
        return false;
    }    
    }
    ?>