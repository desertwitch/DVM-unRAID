<?PHP
require_once '/usr/local/emhttp/plugins/dwdvm/include/dwdvm_config.php';

function humanFileSize($size,$unit="") {
    if(intval($size)) {
        if( (!$unit && $size >= 1<<30) || $unit == " GB")
            return number_format($size/(1<<30),2)." GB";
        if( (!$unit && $size >= 1<<20) || $unit == " MB")
            return number_format($size/(1<<20),2)." MB";
        if( (!$unit && $size >= 1<<10) || $unit == " KB")
            return number_format($size/(1<<10),2)." KB";
        return number_format($size)." bytes";
    } else {
        return "-";
    }
}

function filterVirts($string) {
    $virt_ifaces = shell_exec("find /sys/class/net/ -type l -lname '*/devices/virtual/net/*' 2>/dev/null");
    return strpos($virt_ifaces, $string) === false;
}

function getInterfaces()
{
    global $dwdvm_vifaces;
    
    $db_ifaces_array = "";
    try {
        $db_ifaces_temp = shell_exec("vnstat --dbiflist 2>/dev/null");
        $db_ifaces_regex = '/Interfaces in database\: (.*)/';
        preg_match_all($db_ifaces_regex, $db_ifaces_temp, $db_ifaces_matches);
        $db_ifaces = $db_ifaces_matches[1][0];
        $db_ifaces_array = explode(" ", trim($db_ifaces));  
        if ($dwdvm_vifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'filterVirts'); }
    } catch (\Throwable $e) { // For PHP 7
            return false;
    } catch (\Exception $e) { // For PHP 5
            return false;
    }
    return $db_ifaces_array; 
}

function getXMLforInterface($iface)
{
    $xml = "";
    try {
            $xml = new SimpleXMLElement(shell_exec("vnstat -i ". trim($iface) ." --limit 1 --xml 2>/dev/null"));
    } catch (\Throwable $e) { // For PHP 7
            return false;
    } catch (\Exception $e) { // For PHP 5
            return false;
    }
    return $xml;
}


function build_report_light()
{
    $returnStr = "";
    $db_ifaces_array = getInterfaces();
    
    if($db_ifaces_array) {
        foreach($db_ifaces_array as $db_iface) {
            $xml = getXMLforInterface($db_iface);

            if($xml) {
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
    global $dwdvm_primary;
    global $dwdvm_footerformat;

    $db_iface = $dwdvm_primary;
    $xml = getXMLforInterface($db_iface);

    if($xml) {
        switch ($dwdvm_footerformat) {
            case '5':
                $trafficRx = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx);
                $trafficTx = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx);
                break;
            case 'h':
                $trafficRx = humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx);
                $trafficTx = humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx);
                break;
            case 'd':
                $trafficRx = humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx);
                $trafficTx = humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx);
                break;
            case 'm':
                $trafficRx = humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx);
                $trafficTx = humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx);
                break;
            case 'y':
                $trafficRx = humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx);
                $trafficTx = humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx);
                break;
        }
    } else {
        $trafficRx = "<i class='fa fa-times red-text' title='Interface not found - check configuration!'></i>";
        $trafficTx = "<i class='fa fa-times red-text' title='Interface not found - check configuration!'></i>";
    }
    return("<i class='fa fa-arrow-down'></i> " . $trafficRx . " <i class='fa fa-arrow-up'></i> " . $trafficTx);
}

function getMetricsForPrimary() 
{
    global $dwdvm_primary;
    
    $trafficTotal = [];
    $db_iface = $dwdvm_primary;
    $xml = getXMLforInterface($db_iface);

    if($xml) {

        $trafficTotal[0][0] = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx);
        $trafficTotal[0][1] = humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx);

        $trafficTotal[1][0] = humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx);
        $trafficTotal[1][1] = humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx);

        $trafficTotal[2][0] = humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx);
        $trafficTotal[2][1] = humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx);

        $trafficTotal[3][0] = humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx);
        $trafficTotal[3][1] = humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx);

        $trafficTotal[4][0] = humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx);
        $trafficTotal[4][1] = humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx);

    } else {
        return false;
    }
    return $trafficTotal;
}


function build_report() 
{
    global $dwdvm_report;
    global $dwdvm_vifaces;

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

                $returnStr .= "<tr>";
                $returnStr .= "<td>". $db_iface ."</td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -s -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -5 -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -h -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -d -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -m -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -y -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "</tr>";
            } else if ($dwdvm_report == "images") {
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

                $returnStr .= "<tr>";
                $returnStr .= "<td>". $db_iface ."</td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'></details></td>";
                $returnStr .= "</tr>";
            } else {
                $returnStr .= "<tr>";
                $returnStr .= "<td>". $db_iface ."</td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -s -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -5 -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -h -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -d -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -m -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -y -i ". $db_iface ." 2>/dev/null")."</pre></details></td>";
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
