<?php
function OpenCon()
{
    $conn = mysqli_connect('localhost', 'ITC', 'password') or die('Could not connect: ' . mysqli_connect_error());
    mysqli_select_db($conn, 'ITC') or die('Could not select database');
    return $conn;
}
function CloseCon($conn)
{
    $conn->close();
}
