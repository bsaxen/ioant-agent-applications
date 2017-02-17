<?php
//=========================================
function lib_showFileContent($filename)
//=========================================
{
    //echo("$filename");
    $handle = fopen($filename, "r");
    if ($handle)
    {
        $ix = 0;
        while (($line = fgets($handle)) !== false)
        {
          $ix++;
          sscanf($line,"%s %s",$tag,$value);
          $res[$ix][0] = $tag;
          $res[$ix][1] = $value;
          //$res[0] = 'TEST';
          //$res[1] = '25.5';
          //echo "$line $tag $value<br>";
        }
    }
    return $res;
}
//=========================================
function lib_listTopicName($filename)
//=========================================
{
    $handle = fopen($filename, "r");
    if ($handle)
    {
        $ix = 0;
        while (($line = fgets($handle)) !== false)
        {
          $ix++;
          $line = trim($line);
          $data = explode("/",$line);
          $dir = $data[0];
          $topic = $data[1];
          //echo "<h2>$topic</h2>";
          //$tfile = "time/".$topic;
          //lib_showFileContent($tfile);
          $tfile = "data/".$topic;
          $res = lib_showFileContent($tfile);
          $mres[$ix] = $res;
        }
    }
    $mres[0] = $ix;
    return $mres;
}
//=========================================
function lib_listTopics()
//=========================================
{
    system("ls sub/* > sub_list.pscp");
    $mres = lib_listTopicName("sub_list.pscp");
    return $mres;
}

$mres = lib_listTopics();
echo json_encode($mres);
?>
