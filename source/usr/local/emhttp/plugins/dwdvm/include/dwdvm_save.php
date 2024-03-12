<?
/* Copyright Derek Macias (parts of code from NUT package)
 * Copyright macester (parts of code from NUT package)
 * Copyright gfjardim (parts of code from NUT package)
 * Copyright SimonF (parts of code from NUT package)
 * Copyright Mohamed Emad (icon from vnstat-client package)
 * Copyright desertwitch
 *
 * Copyright Dan Landon
 * Copyright Bergware International
 * Copyright Lime Technology
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 */
$base     = '/etc/vnstat/';
$plgpath  = '/boot/config/plugins/dwdvm/vnstat/';
$editfile = realpath($_POST['editfile']);
$plgfile  = $plgpath.basename($editfile);

if(!strpos($editfile, $base) && file_exists($editfile) && array_key_exists('editdata', $_POST)){
    // remove carriage returns
    $editdata = str_replace("\r", '', $_POST['editdata']);

    // create directory on flash drive if missing (shouldn't happen)
    if(! is_dir($plgpath)){
        mkdir($plgpath);
    }

    // save conf file to flash drive
    file_put_contents($plgfile, $editdata);

    // save conf file to local system
    $return_var = file_put_contents($editfile, $editdata);
}else{
    $return_var = false;
}

if($return_var)
    $return = ['success' => true, 'saved' => $editfile];
else
    $return = ['error' => $editfile];

echo json_encode($return);
?>
