<?php
error_reporting( E_ALL & ~E_DEPRECATED & ~E_NOTICE );
if(!mysql_connect("localhost","kealcom","h5+u@om@3.oU"))
{
   die('oops connection problem ! --> '.mysql_error());
}
if(!mysql_select_db("kealcom_recruitment"))
{
   die('oops database selection problem ! --> '.mysql_error());
}

?>