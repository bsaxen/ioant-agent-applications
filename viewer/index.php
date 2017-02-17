<?php
//=========================================
//
// 2017-02-12
//=========================================
//=========================================
function lib_showFileContent($filename)
//=========================================
{
    //echo("$filename");
    $handle = fopen($filename, "r");
    if ($handle)
    {
        while (($line = fgets($handle)) !== false)
        {
          //sscanf($line,"%d %s %s %s %s",$sid,$appid,$ip,$yymmdd,$hhmmss);
          echo "$line<br>";
        }
    }
}
//=========================================
function lib_listTopicName($filename)
//=========================================
{
    $handle = fopen($filename, "r");
    if ($handle)
    {
        while (($line = fgets($handle)) !== false)
        {
          $line = trim($line);
          $data = explode("/",$line);
          $dir = $data[0];
          $topic = $data[1];
          echo "<h2>$topic</h2>";
          $tfile = "time/".$topic;
          lib_showFileContent($tfile);
          $tfile = "data/".$topic;
          lib_showFileContent($tfile);
        }
    }
}
//=========================================
function lib_listTopics()
//=========================================
{
    system("ls sub/* > sub_list.pscp");
    lib_listTopicName("sub_list.pscp");
}
//=========================================
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $formid = $_POST['formid'];
    if($formid == 'addsub')
    {
        $global        = $_POST['global'];
        $local         = $_POST['local'];
        $clientid      = $_POST['clientid'];
        $messagetype   = $_POST['messagetype'];
        $streamindex   = $_POST['streamindex'];
    }

}

$action = $_GET['action'];

//=========================================
echo("<!doctype html>");
echo("<html>");
echo("<head>");
    echo("<title>IOAnt Agent</title>");
    echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />");
    echo("<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js\"></script>");
    echo("<style>");
    echo("
    body {
        text-align: center;
        margin-bottom: 100px;
        background: black;
        font-family: monospace;
    }
    #d_container{
        position: static;
        background: black;
        border: 0px solid #AAAAAA;
        padding: 0px;
        color: white;
        width: 100%;
        height: 100%;
    }

    #d_left{
        display: static;
        float: left;
        background: black;
        border: 0px solid #AAAAAA;
        padding: 0px;
        color: red;
        width: 50%;
        text-align: left;
    }

    #d_right{
        display: static;
        float: right;
        background: black;
        border: 0px solid #AAAAAA;
        padding: 0px;
        color: green;
        width: 50%;
        text-align: left;
    }

    ");
echo("</style>");

?>

<script type="text/javascript">

window.onload = function(){

    $.ajax({
        url:		'ajax.php',
        dataType:	'json',
        success:	initAgent,
        type:		'GET',
        data:		{
        place: 'jupiter'
        }
    });


function initAgent(result) {
        var rr = result[0];
        for(i=1; i <= rr; i++)
        {
            var div = document.createElement('div');
            document.body.appendChild(div);
            div.id = "benny".concat(i);;
            div.style.float = 'left';
            div.style.backgroundColor = 'red';
            div.style.width  = '100px';
            div.style.height = '20px';
        }
    }

    var tid = setInterval(getData, 3000);
    function getData() {
        console.log("Getting  data");
        $.ajax({
            url:		'ajax.php',
            dataType:	'json',
            success:	readSubTopics,
            type:		'GET',
            data:		{
                place: 'venus'
            }
        });
    }

    function readSubTopics(result) {

        console.log(result);
        var temp1 = result[0];
        var total_number = temp1;
        var divtag;
        console.log(total_number);
        for (i=1; i<= total_number; i++)
        {
            temp1 = result[i];
            console.log(temp1[1]);

            var temp2 = temp1[1];
            console.log(temp2[0]);
            console.log(temp2[1]);
            divtag = "benny".concat(i);
            document.getElementById(divtag).innerHTML = temp2[1];

            console.log(temp1[2]);
            temp2 = temp1[2];
            console.log(temp2[0]);
            console.log(temp2[1]);




        }
        temp = result[1];
        //var elpow = 'contact-number';
        //document.getElementById(elpow).innerHTML = temp[1];
        //console.log(result['1']);
        //console.log(result['2']);
        //console.log(result['0']['2']);
    }

}
</script>

<?php
echo("</head>");
echo("<body>");
echo("<div id=\"d_container\">");

if($action)
{
    echo("action= $action<br>");
}
if($formid)
{
    echo("formid = $formid<br>");
    if($formid == 'addsub')
    {
        $ffile = "live-$global-$local-$clientid-$messagetype-$streamindex";
        echo("$ffile<br>");
        system("pwd");
        system("touch newsub/$ffile");
    }

}

echo("<div id=\"d_left\">");
echo "Subscriptions<br>";
lib_listTopics();

echo("</div>");
echo("<div id=\"d_right\">");
//echo("<span id=\"temperature\">test</span><br>");
//echo("<span id=\"elpow\">test</span>");
echo("<table>
      <form action=\"index.php\" method=\"post\">
            <input type=\"hidden\" name=\"formid\" value=\"addsub\" />
            <tr><td>Global</td><td><input type=\"text\" name=\"global\" value=\"$global\" size=\"20\" /></td></tr>
            <tr><td>Local</td><td><input type=\"text\" name=\"local\" value=\"$local\" size=\"20\" /></td></tr>
            <tr><td>Client Id</td><td><input type=\"text\" name=\"clientid\" value=\"$clientid\" size=\"20\" /></td></tr>
            <tr><td>Message type</td><td><select name=\"messagetype\">
                <option value=\"4\">Temperature</option>
                <option value=\"5\">Humidity</option>
                <option value=\"6\">Mass</option>
                <option value=\"8\">ElectricPower</option>
            </select></td></tr>
            <tr><td>stream Index</td><td><select name=\"streamindex\">
                <option value=\"0\">0</option>
                <option value=\"1\">1</option>
                <option value=\"2\">2</option>
                <option value=\"3\">3</option>
                <option value=\"4\">4</option>
                <option value=\"5\">5</option>
                <option value=\"6\">6</option>
                <option value=\"7\">7</option>
            </select></td></tr>
            <tr><td>-</td><td><button type=\"submit\">Add Subscription</button></td><td></tr>
      </form>
      </table>
");

echo("</div>");

echo("</div>");


echo("</body>");
echo("</html>");
?>
