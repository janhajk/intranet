<?php 
/* Original code by Erwin Poelman found on 
   http://www.weberdev.com/get_example-3096.html 

   This is a little more up to date and formatted for readability. 
   Some things in PHP have changed since Erwin Poelman first posted this 
   and a couple of things had to be added to resolve variables that are 
   no longer automagically (yes, I spelled it right!;) set.  Also added 
   a test for <?...?> enclosure.  Changed the hard coded file name (originally 
   'generate.php') in the src attributes to server resolution through the 
   'PHP_SELF' value. 
    
   Otherwise, its a pretty neat and tidy little browser PHP interpretter, just 
   as Erwin Poelman originally wrote it!;) 
*/ 
$theCode ='';
if(isset($_REQUEST['dispArea']))$dispArea=$_REQUEST['dispArea'];
if(isset($_REQUEST['theCode']))$theCode=$_REQUEST['theCode'];
if(!isset($dispArea)) 
{
    echo "<frameset cols='60%,40%'>\r\n"; 
    echo     "<frame name='theCode' src='".$_SERVER['PHP_SELF']."?dispArea=left'>\r\n";
    echo     "<frame name='theExec' src='".$_SERVER['PHP_SELF']."?dispArea=right'>\r\n";
    echo "</frameset>\r\n"; 
}
else
{
    if(!strcmp($dispArea,"left"))
    {
        echo "<body bgcolor='#D0DEED'>\r\n"; 
        echo "<font face='Arial,Verdana,Helvetica' color='FF0000' size='3'>PHP Tester</font>\r\n"; 
        echo     "<form method='post' action='".$_SERVER['PHP_SELF']."?dispArea=right' target='theExec'>\r\n"; 
        echo     "<table>\r\n"; 
        echo         "<tr><td align='center'>\r\n"; 
        echo             "<input type='submit' value='Execute'>\r\n"; 
        echo         "</td></tr>\r\n"; 
        echo         "<tr><td>\r\n"; 
        echo             "<textarea name='theCode' cols='65' rows='25' wrap='virtual'>\r\n"; 
        echo                 $theCode."\r\n"; 
        echo             "</textarea>\r\n"; 
        echo         "</td></tr>\r\n"; 
        echo     "</table>\r\n"; 
        echo     "</form>\r\n"; 
    } 
    else if(!strcmp($dispArea,"right")) 
    { 
        echo "<body bgcolor='#FFFFFF'>\r\n"; 
        if(empty($theCode)) 
        { 
            echo "Ready to parse..."; 
        } 
        else 
        { 
            $theCode=ltrim(rtrim(stripSlashes($theCode))); 
            if(!strncmp($theCode,"<?",2)) //if it's full php, remove the tags 
            { 
                if(!strncmp($theCode,"<?php",5)) 
                { 
                    $theCode=substr($theCode,5); 
                } 
                else 
                { 
                    $theCode=substr($theCode,2); 
                } 
                $theCode=substr($theCode,0,strlen($theCode)-2); 
                $theCode=ltrim(rtrim(stripSlashes($theCode))); 
            } 
            eval($theCode); 
        } 
    } 
    echo "</body>"; 
} 
?>