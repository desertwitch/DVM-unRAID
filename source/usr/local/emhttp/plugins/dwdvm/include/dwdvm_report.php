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
require_once '/usr/local/emhttp/plugins/dwdvm/include/dwdvm_config.php';

function humanFileSize($sizeObj,$unit="") {
    try {
        $size = intval($sizeObj);
        if($size) {
            if( (!$unit && $size >= 1<<30) || $unit == "GB")
                return number_format($size/(1<<30),2)." GB";
            if( (!$unit && $size >= 1<<20) || $unit == "MB")
                return number_format($size/(1<<20),2)." MB";
            if( (!$unit && $size >= 1<<10) || $unit == "KB")
                return number_format($size/(1<<10),2)." KB";
            return number_format($size)." B";
        } else {
            return "-";
        }
    } catch (Throwable $e) { // For PHP 7
        return "-";
    } catch (Exception $e) { // For PHP 5
        return "-";
    }
}

function filterVirts($string) {
    $virt_ifaces = shell_exec("find /sys/class/net/ -type l -lname '*/devices/virtual/net/*' 2>/dev/null");
    if(strpos($string, "veth") !== false) { return false; }
    return strpos($virt_ifaces, $string) === false;
}

function filterNonExisting($string) {
    return file_exists("/sys/class/net/$string") === true;
}

function getInterfaces()
{
    global $dwdvm_vifaces;
    
    $db_ifaces_array = "";
    try {
        $db_ifaces_temp = shell_exec("vnstat --config /etc/vnstat/vnstat.conf --dbiflist 2>/dev/null");
        $db_ifaces_regex = '/Interfaces in database\: (.*)/';
        preg_match_all($db_ifaces_regex, $db_ifaces_temp, $db_ifaces_matches);
        $db_ifaces = $db_ifaces_matches[1][0];
        $db_ifaces_array = explode(" ", trim($db_ifaces));  
        if ($dwdvm_vifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'filterVirts'); }
        if ($dwdvm_oifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'filterNonExisting'); }
    } catch (Throwable $e) { // For PHP 7
        return false;
    } catch (Exception $e) { // For PHP 5
        return false;
    }
    return $db_ifaces_array; 
}

function getXMLforInterface($iface)
{
    $xml = "";
    try {
        $xml_raw = shell_exec("vnstat --config /etc/vnstat/vnstat.conf -i ". trim($iface) ." --limit 1 --xml 2>/dev/null");
        if(strpos($xml_raw, "xmlversion")) {
            $xml = new SimpleXMLElement($xml_raw);
        } else {
            return false;
        }
    } catch (Throwable $e) { // For PHP 7
        return false;
    } catch (Exception $e) { // For PHP 5
        return false;
    }
    return $xml;
}

function checkAgainstLimits($iface, $time, $limit, $unit, $mode, $str)
{
    $returnStr = "";
    try {
        if(intval($limit) < 0) {
            return $str;
        } else {
            $returnStr = shell_exec("if ! vnstat --config /etc/vnstat/vnstat.conf --alert 0 3 {$time} {$mode} {$limit} {$unit} {$iface} >/dev/null 2>&1; then echo 1; else echo 0; fi");
            if(intval($returnStr) > 0) {
                return "<span class='red-text'>$str</span>";
            } else if (intval($returnStr) == 0) {
                return "<span class='green-text'>$str</span>";
            } else {
                return $str;
            }
        }
    } catch (Throwable $e) { // For PHP 7
        return $str;
    } catch (Exception $e) { // For PHP 5
        return $str;
    }
}

function build_report_light()
{
    global $dwdvm_hlimit_rx;
    global $dwdvm_hunit_rx;
    global $dwdvm_dlimit_rx;
    global $dwdvm_dunit_rx;
    global $dwdvm_mlimit_rx;
    global $dwdvm_munit_rx;
    global $dwdvm_ylimit_rx;
    global $dwdvm_yunit_rx;
    global $dwdvm_hlimit_tx;
    global $dwdvm_hunit_tx;
    global $dwdvm_dlimit_tx;
    global $dwdvm_dunit_tx;
    global $dwdvm_mlimit_tx;
    global $dwdvm_munit_tx;
    global $dwdvm_ylimit_tx;
    global $dwdvm_yunit_tx;
    global $dwdvm_primary;

    $returnStr = "";
    $db_ifaces_array = getInterfaces();
    
    if($db_ifaces_array) {
        foreach($db_ifaces_array as $db_iface) {
            $xml = getXMLforInterface($db_iface);

            if($xml) {
                if($db_iface == $dwdvm_primary) {
                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }
                    $returnStr .= "<td>". $db_iface . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "h", $dwdvm_hlimit_rx, $dwdvm_hunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "h", $dwdvm_hlimit_tx, $dwdvm_hunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "d", $dwdvm_dlimit_rx, $dwdvm_dunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "d", $dwdvm_dlimit_tx, $dwdvm_dunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "m", $dwdvm_mlimit_rx, $dwdvm_munit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "m", $dwdvm_mlimit_tx, $dwdvm_munit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "y", $dwdvm_ylimit_rx, $dwdvm_yunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx)) . "</td>";
                    $returnStr .= "<td>". checkAgainstLimits($db_iface, "y", $dwdvm_ylimit_tx, $dwdvm_yunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx)) . "</td>";
                } else {
                    $returnStr .= "<tr>";
                    $returnStr .= "<td>". $db_iface . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx) . "</td>";
                    $returnStr .= "<td>". humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx) . "</td>";
                }
            } else {
                $returnStr .= "<tr>";
                $returnStr .= "<td>". $db_iface . "</td>";
                $returnStr .= "<td colspan='11'><em>Error Occured While Querying Network Interface</em></td>";
                $returnStr .= "</tr>";
            }
        }
    } else {
        $returnStr .= "<tr>";
        $returnStr .= "<td colspan='11'><em>Error Occured While Querying Network Interfaces</em></td>";
        $returnStr .= "</tr>";
    }
    return $returnStr;
}

function build_footer() 
{
    global $dwdvm_hlimit_rx;
    global $dwdvm_hunit_rx;
    global $dwdvm_dlimit_rx;
    global $dwdvm_dunit_rx;
    global $dwdvm_mlimit_rx;
    global $dwdvm_munit_rx;
    global $dwdvm_ylimit_rx;
    global $dwdvm_yunit_rx;
    global $dwdvm_hlimit_tx;
    global $dwdvm_hunit_tx;
    global $dwdvm_dlimit_tx;
    global $dwdvm_dunit_tx;
    global $dwdvm_mlimit_tx;
    global $dwdvm_munit_tx;
    global $dwdvm_ylimit_tx;
    global $dwdvm_yunit_tx;
    global $dwdvm_primary;
    global $dwdvm_footerformat;

    $xml = getXMLforInterface($dwdvm_primary);

    if($xml) {
        switch ($dwdvm_footerformat) {
            case '5':
                $trafficRx = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx);
                $trafficTx = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx);
                break;
            case 'h':
                $trafficRx = checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_rx, $dwdvm_hunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx));
                $trafficTx = checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_tx, $dwdvm_hunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx));
                break;
            case 'd':
                $trafficRx = checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_rx, $dwdvm_dunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx));
                $trafficTx = checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_tx, $dwdvm_dunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx));
                break;
            case 'm':
                $trafficRx = checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_rx, $dwdvm_munit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx));
                $trafficTx = checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_tx, $dwdvm_munit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx));
                break;
            case 'y':
                $trafficRx = checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_rx, $dwdvm_yunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx));
                $trafficTx = checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_tx, $dwdvm_yunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx));
                break;
        }
    } else {
        $trafficRx = "<i class='fa fa-times red-text' title='Error Querying Network Interface - Misspelled Primary Interface?'></i>";
        $trafficTx = "<i class='fa fa-times red-text' title='Error Querying Network Interface - Misspelled Primary Interface?'></i>";
    }
    return("<i class='fa fa-arrow-down'></i> " . $trafficRx . " <i class='fa fa-arrow-up'></i> " . $trafficTx);
}

function getMetricsForPrimary() 
{
    global $dwdvm_primary;
    global $dwdvm_hlimit_rx;
    global $dwdvm_hunit_rx;
    global $dwdvm_dlimit_rx;
    global $dwdvm_dunit_rx;
    global $dwdvm_mlimit_rx;
    global $dwdvm_munit_rx;
    global $dwdvm_ylimit_rx;
    global $dwdvm_yunit_rx;
    global $dwdvm_hlimit_tx;
    global $dwdvm_hunit_tx;
    global $dwdvm_dlimit_tx;
    global $dwdvm_dunit_tx;
    global $dwdvm_mlimit_tx;
    global $dwdvm_munit_tx;
    global $dwdvm_ylimit_tx;
    global $dwdvm_yunit_tx;
    
    $trafficTotal = [];
    $xml = getXMLforInterface($dwdvm_primary);

    if($xml) {
        $trafficTotal[0][0] = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx);
        $trafficTotal[0][1] = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx);

        $trafficTotal[1][0] = checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_rx, $dwdvm_hunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx));
        $trafficTotal[1][1] = checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_tx, $dwdvm_hunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx));

        $trafficTotal[2][0] = checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_rx, $dwdvm_dunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx));
        $trafficTotal[2][1] = checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_tx, $dwdvm_dunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx));

        $trafficTotal[3][0] = checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_rx, $dwdvm_munit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx));
        $trafficTotal[3][1] = checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_tx, $dwdvm_munit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx));

        $trafficTotal[4][0] = checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_rx, $dwdvm_yunit_rx, "rx", humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx));
        $trafficTotal[4][1] = checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_tx, $dwdvm_yunit_tx, "tx", humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx));
    } else {
        return false;
    }
    return $trafficTotal;
}


function build_report() 
{
    global $dwdvm_report;
    global $dwdvm_vifaces;
    global $dwdvm_primary;

    $returnStr = "";
    $db_ifaces_array = getInterfaces();

    if($db_ifaces_array) {
        foreach($db_ifaces_array as $db_iface) {
            if ($dwdvm_report == "both") {
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

                if($db_iface == $dwdvm_primary) {
                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }
                } else {
                    $returnStr .= "<tr>";
                }
                $returnStr .= "<td>". $db_iface ."</td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -s -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -5 -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -h -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -d -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -m -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -y -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "</tr>";
            } else if ($dwdvm_report == "images") {
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

                if($db_iface == $dwdvm_primary) {
                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }
                } else {
                    $returnStr .= "<tr>";
                }
                $returnStr .= "<td>". $db_iface ."</td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'></details></td>";
                $returnStr .= "</tr>";
            } else {
                if($db_iface == $dwdvm_primary) {
                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }
                } else {
                    $returnStr .= "<tr>";
                }
                $returnStr .= "<td>". $db_iface ."</td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -s -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -5 -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -h -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -d -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -m -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat --config /etc/vnstat/vnstat.conf -y -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "</tr>";
            }
        }
    } else {
        $returnStr .= "<tr>";
        $returnStr .= "<td colspan='11'><em>Error Occured While Querying Network Interfaces</em></td>";
        $returnStr .= "</tr>";
    }
    return $returnStr;
}
?>
