<?PHP
require_once '/usr/local/emhttp/plugins/dwdvm/include/dwdvm_config.php';

function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}

function humanFileSize($size,$unit="") {
    if(intval($size)) {
        if( (!$unit && $size >= 1<<30) || $unit == "GB")
            return number_format($size/(1<<30),2)."GB";
        if( (!$unit && $size >= 1<<20) || $unit == "MB")
            return number_format($size/(1<<20),2)."MB";
        if( (!$unit && $size >= 1<<10) || $unit == "KB")
            return number_format($size/(1<<10),2)."KB";
        return number_format($size)." bytes";
    } else {
        return "-";
    }
}

function filterVirts($string) {
    $virt_ifaces = shell_exec("find /sys/class/net/ -type l -lname '*/devices/virtual/net/*' 2>/dev/null");
    return strpos($virt_ifaces, $string) === false;
}

function build_report_light() {
    global $dwdvm_report;
    global $dwdvm_vifaces;
    $db_ifaces = shell_exec("vnstat --dbiflist | sed 's/Interfaces in database: //g' 2>/dev/null");
    
    $db_ifaces_array = explode(" ", trim($db_ifaces));

    if ($dwdvm_vifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'filterVirts'); }

    foreach($db_ifaces_array as $db_iface) {
      
    $xml = new SimpleXMLElement(shell_exec("vnstat -i ". trim($db_iface) ." --limit 1 --xml 2>/dev/null"));
    echo("<tr>");
    echo("<td>". $db_iface . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx) . "</td>");
    echo("<td>". humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx) . "</td>");

    }
}

function build_footer() {
    global $dwdvm_primary;
    global $dwdvm_footerformat;

    $db_iface = $dwdvm_primary;
    if(!empty($db_iface)) {
        $xml = new SimpleXMLElement(shell_exec("vnstat -i ". trim($db_iface) ." --limit 1 --xml 2>/dev/null"));

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
        
        return("<i class='fa fa-arrow-down'></i>&thinsp;" . $trafficRx . " <i class='fa fa-arrow-up'></i>&thinsp;" . $trafficTx);
    }
    else {
        return "";
    }
}

function build_report() {
    global $dwdvm_report;
    global $dwdvm_vifaces;
    $db_ifaces = shell_exec("vnstat --dbiflist | sed 's/Interfaces in database: //g' 2>/dev/null");
    
    $db_ifaces_array = explode(" ", trim($db_ifaces));

    if ($dwdvm_vifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'filterVirts'); }

    foreach($db_ifaces_array as $db_iface) {
        if ($dwdvm_report == "both") {
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

            echo("<tr>");
            echo("<td>". $db_iface ."</td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -s -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -5 -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -h -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -d -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -m -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -y -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("</tr>");
        } else if ($dwdvm_report == "images") {
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

            echo("<tr>");
            echo("<td>". $db_iface ."</td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'></details></td>");
            echo("</tr>");
        } else {
            echo("<tr>");
            echo("<td>". $db_iface ."</td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -s -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -5 -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -h -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -d -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -m -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -y -i ". $db_iface ." 2>/dev/null")."</pre></details></td>");
            echo("</tr>");
        }
    }
}
?>
