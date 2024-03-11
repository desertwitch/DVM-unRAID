<?PHP
require_once '/usr/local/emhttp/plugins/dwdvm/include/dwdvm_config.php';

function filterVirts($string) {
    $virt_ifaces = shell_exec("find /sys/class/net/ -type l -lname '*/devices/virtual/net/*' 2>/dev/null");
    return strpos($virt_ifaces, $string) === false;
}

function build_report() {
    global $dwdvm_report;
    global $dwdvm_vifaces;
    $db_ifaces = shell_exec("vnstat --dbiflist | sed 's/Interfaces in database: //g' 2>/dev/null");
    
    $db_ifaces_array = explode(" ", trim($db_ifaces));

    if ($dwdvm_vifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'filterVirts'); }

    foreach($db_ifaces_array as $db_iface) {
        if ($dwdvm_report == "both") {
            shell_exec("vnstati --small -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

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
            shell_exec("vnstati --small -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_s.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_5.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_h.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_d.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_m.png -i ". $db_iface ." 2>/dev/null");
            shell_exec("vnstati --small -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". $db_iface ."_y.png -i ". $db_iface ." 2>/dev/null");

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
