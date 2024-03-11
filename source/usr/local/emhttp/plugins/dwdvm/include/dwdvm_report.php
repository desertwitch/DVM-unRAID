<?PHP
function build_report() {
    $dbifaces = shell_exec("vnstat --dbiflist | sed 's/Interfaces in database: //g' 2>/dev/null");
    $dbifacesarray = explode(" ", trim($dbifaces));
    foreach($dbifacesarray as $dbiface) {
        if ($dwdvm_report == "both") {
            shell_exec("vnstati --small --noheader --nolegend -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_s.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_5.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_h.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_d.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_m.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_y.png -i ".$dbiface);
            echo("<tr>");
            echo("<td>".$dbiface."</td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_s.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -s -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_5.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -5 -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_h.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -h -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_d.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -d -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_m.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -m -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_y.png'><pre style='font-size:x-small;'>".shell_exec("vnstat -y -i ".$dbiface)."</pre></details></td>");
            echo("</tr>");
        } else if ($dwdvm_report == "images") {
            shell_exec("vnstati --small --noheader --nolegend -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_s.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_5.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_h.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_d.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_m.png -i ".$dbiface);
            shell_exec("vnstati --small --noheader --nolegend -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/".$dbiface."_y.png -i ".$dbiface);
            echo("<tr>");
            echo("<td>".$dbiface."</td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_s.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_5.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_h.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_d.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_m.png'></details></td>");
            echo("<td><details><summary>...</summary><img src='/plugins/dwdvm/images/".$dbiface."_y.png'></details></td>");
            echo("</tr>");
        } else {
            echo("<tr>");
            echo("<td>".$dbiface."</td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -s -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -5 -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -h -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -d -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -m -i ".$dbiface)."</pre></details></td>");
            echo("<td><details><summary>...</summary><pre style='font-size:x-small;'>".shell_exec("vnstat -y -i ".$dbiface)."</pre></details></td>");
            echo("</tr>");
        }
    }
}
?>
