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

function dvm_humanFileSize($sizeObj,$unit="") {
    try {
        $size = intval($sizeObj);
        if($size) {
            if( (!$unit && $size >= 1000000000000) || $unit == "TB")
                return number_format(($size/1000000000000),2) . " TB";
            if( (!$unit && $size >= 1000000000) || $unit == "GB")
                return number_format(($size/1000000000),2) . " GB";
            if( (!$unit && $size >= 1000000) || $unit == "MB")
                return number_format(($size/1000000),2) . " MB";
            if( (!$unit && $size >= 1000) || $unit == "KB")
                return number_format(($size/1000),2) . " KB";
            return number_format($size) . " B";
        } else {
            return "-";
        }
    } catch (\Throwable $t) {
        return "-";
    } catch (\Exception $e) {
        return "-";
    }
}

function dvm_getVirtualInterfaceServiceMatches() {
    try {
        $matches_raw = shell_exec("/usr/local/emhttp/plugins/dwdvm/scripts/match_virts 2>/dev/null");
        if($matches_raw) {
            $returnArr = [];
            foreach(explode(PHP_EOL, trim($matches_raw)) as $match) {
                $submatch = explode(":", $match);
                if(!empty($submatch[0]) && !empty($submatch[1])) {
                    if(!empty($returnArr[$submatch[0]])) {
                        $returnArr[$submatch[0]] = $returnArr[$submatch[0]] . " " . $submatch[1];
                    } else {
                        $returnArr[$submatch[0]] = $submatch[1];
                    }
                } else {
                    continue;
                }
            }
            return $returnArr;
        } else {
            return false;
        }
    } catch (\Throwable $t) {
        error_log($t);
        return false;
    } catch (\Exception $e) {
        error_log($e);
        return false;
    }
}

function dvm_isPhysicalInterface($string) {
    try {
        $phys_ifaces = shell_exec("find /sys/class/net/ -type l ! -lname '*/devices/virtual/net/*' 2>/dev/null");
        $existing_ifaces = shell_exec("find /sys/class/net/ -type l 2>/dev/null");

        if($phys_ifaces && $existing_ifaces) {

            $phys_ifaces_array = explode(PHP_EOL, trim($phys_ifaces));
            $existing_ifaces_array = explode(PHP_EOL, trim($existing_ifaces));

            if(substr($string, 0, 4) == "veth") { return false; }
            if(!in_array("/sys/class/net/" . $string, $existing_ifaces_array)) {
                return true; // keep removed interfaces for later removal
            }

            return in_array("/sys/class/net/" . $string, $phys_ifaces_array);
        } else {
            return true;
        }
    } catch (\Throwable $t) {
        error_log($t);
        return true;
    } catch (\Exception $e) {
        error_log($e);
        return true;
    }
}

function dvm_isExistingInterface($string) {
    try {
        $existing_ifaces = shell_exec("find /sys/class/net/ -type l 2>/dev/null");
        if($existing_ifaces) {
            $existing_ifaces_array = explode(PHP_EOL, trim($existing_ifaces));
            return in_array("/sys/class/net/" . $string, $existing_ifaces_array);
        } else {
            return true;
        }
    } catch (\Throwable $t) {
        error_log($t);
        return true;
    } catch (\Exception $e) {
        error_log($e);
        return true;
    }
}

function dvm_getInterfaces()
{
    global $dwdvm_vifaces;
    global $dwdvm_oifaces;

    $db_ifaces_array = "";
    try {
        $db_ifaces_raw = shell_exec("vnstat --config /etc/vnstat/vnstat.conf --dbiflist 2>/dev/null");
        if($db_ifaces_raw) {
            $db_ifaces_regex = '/Interfaces in database\: (.*)/';
            preg_match_all($db_ifaces_regex, $db_ifaces_raw, $db_ifaces_matches);
            $db_ifaces = $db_ifaces_matches[1][0];
            $db_ifaces_array = explode(" ", trim($db_ifaces));
            if ($dwdvm_vifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'dvm_isPhysicalInterface'); }
            if ($dwdvm_oifaces !== "enable") { $db_ifaces_array = array_filter($db_ifaces_array, 'dvm_isExistingInterface'); }
        } else {
            return false;
        }
    } catch (\Throwable $t) {
        error_log($t);
        return false;
    } catch (\Exception $e) {
        error_log($e);
        return false;
    }
    return $db_ifaces_array;
}

function dvm_getXMLforInterface($iface)
{
    $xml = "";
    $iface = trim($iface);

    try {
        if(!empty($iface) && $iface !== "noiface") {
            $xml_raw = shell_exec("vnstat --config /etc/vnstat/vnstat.conf -i ". escapeshellarg($iface) ." --limit 1 --xml 2>/dev/null");
            if($xml_raw) {
                if(strpos($xml_raw, "xmlversion") !== false) {
                    $xml = new SimpleXMLElement($xml_raw);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (\Throwable $t) {
        error_log($t);
        return false;
    } catch (\Exception $e) {
        error_log($e);
        return false;
    }
    return $xml;
}

function dvm_checkAgainstLimits($iface, $time, $limit, $unit, $mode, $str)
{
    try {
        if(intval($limit) < 0) {
            return $str;
        } else {
            $iface = escapeshellarg($iface);
            $time = escapeshellarg($time);
            $limit = escapeshellarg($limit);
            $unit = escapeshellarg($unit);
            $mode = escapeshellarg($mode);
            $returnStr = shell_exec("if ! vnstat --config /etc/vnstat/vnstat.conf --alert 0 3 {$time} {$mode} {$limit} {$unit} {$iface} >/dev/null 2>&1; then echo 1; else echo 0; fi");
            if(intval($returnStr) > 0) {
                return "<span class='red-text'>$str</span>";
            } else if (intval($returnStr) == 0) {
                return "<span class='green-text'>$str</span>";
            } else {
                return $str;
            }
        }
    } catch (\Throwable $t) {
        error_log($t);
        return $str;
    } catch (\Exception $e) {
        error_log($e);
        return $str;
    }
}

function dvm_get_primary_metrics()
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
    $xml = dvm_getXMLforInterface($dwdvm_primary);

    if($xml) {
        $trafficTotal[0][0] = dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx ?? 0);
        $trafficTotal[0][1] = dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx ?? 0);

        $trafficTotal[1][0] = dvm_checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_rx, $dwdvm_hunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx ?? 0));
        $trafficTotal[1][1] = dvm_checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_tx, $dwdvm_hunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx ?? 0));

        $trafficTotal[2][0] = dvm_checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_rx, $dwdvm_dunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx ?? 0));
        $trafficTotal[2][1] = dvm_checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_tx, $dwdvm_dunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx ?? 0));

        $trafficTotal[3][0] = dvm_checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_rx, $dwdvm_munit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx ?? 0));
        $trafficTotal[3][1] = dvm_checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_tx, $dwdvm_munit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx ?? 0));

        $trafficTotal[4][0] = dvm_checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_rx, $dwdvm_yunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx ?? 0));
        $trafficTotal[4][1] = dvm_checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_tx, $dwdvm_yunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx ?? 0));
    } else {
        return false;
    }
    return $trafficTotal;
}

function dvm_build_report()
{
    global $dwdvm_report;
    global $dwdvm_vifaces;
    global $dwdvm_primary;
    global $dwdvm_match_virts;

    $returnStr = "";
    $db_ifaces_array = dvm_getInterfaces();

    $virt_iface_matches = false;
    if($dwdvm_match_virts == "enable") {
        $virt_iface_matches = dvm_getVirtualInterfaceServiceMatches();
    }

    if($db_ifaces_array) {
        foreach($db_ifaces_array as $db_iface) {
            $matchedService = "";
            if($virt_iface_matches) {
                $matchedService = $virt_iface_matches[$db_iface] ?? false;
                if($matchedService) {
                    $matchedService = "<span style='font-size:x-small;color:gray;'> {$matchedService}</span>";
                }
            }
            if ($dwdvm_report == "both") {
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_s.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_5.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_h.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_d.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_m.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_y.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");

                if($db_iface == $dwdvm_primary) {
                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }
                } else {
                    $returnStr .= "<tr>";
                }
                $returnStr .= "<td>". $db_iface . $matchedService . "</td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_s.png'><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -s -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_5.png'><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -5 -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_h.png'><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -h -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_d.png'><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -d -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_m.png'><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -m -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><img src='/plugins/dwdvm/images/". $db_iface ."_y.png'><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -y -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "</tr>";
            } else if ($dwdvm_report == "images") {
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -s -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_s.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -5 -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_5.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -h -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_h.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -d -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_d.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -m -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_m.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");
                shell_exec("vnstati --config /etc/vnstat/vnstat.conf -y -c 1 -o /usr/local/emhttp/plugins/dwdvm/images/". escapeshellarg($db_iface) ."_y.png -i ". escapeshellarg($db_iface) ." 2>/dev/null");

                if($db_iface == $dwdvm_primary) {
                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }
                } else {
                    $returnStr .= "<tr>";
                }
                $returnStr .= "<td>". $db_iface . $matchedService . "</td>";
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
                $returnStr .= "<td>". $db_iface . $matchedService . "</td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -s -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -5 -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -h -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -d -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -m -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "<td><details><summary>...</summary><pre style='font-size:x-small;'>".htmlspecialchars(shell_exec("vnstat --config /etc/vnstat/vnstat.conf -y -i ". escapeshellarg($db_iface) ." 2>/dev/null") ?? "")."</pre></details></td>";
                $returnStr .= "</tr>";
            }
        }
    } else {
        $returnStr .= "<tr>";
        $returnStr .= "<td colspan='7'><em>Error Occured While Querying Network Interfaces</em></td>";
        $returnStr .= "</tr>";
    }
    return $returnStr;
}

function dvm_build_report_light()
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

    global $dwdvm_custom1_interface;
    global $dwdvm_custom1_mode;
    global $dwdvm_custom1_time;
    global $dwdvm_custom1_limit;
    global $dwdvm_custom1_unit;
    global $dwdvm_custom2_interface;
    global $dwdvm_custom2_mode;
    global $dwdvm_custom2_time;
    global $dwdvm_custom2_limit;
    global $dwdvm_custom2_unit;
    global $dwdvm_custom3_interface;
    global $dwdvm_custom3_mode;
    global $dwdvm_custom3_time;
    global $dwdvm_custom3_limit;
    global $dwdvm_custom3_unit;
    global $dwdvm_custom4_interface;
    global $dwdvm_custom4_mode;
    global $dwdvm_custom4_time;
    global $dwdvm_custom4_limit;
    global $dwdvm_custom4_unit;
    global $dwdvm_custom5_interface;
    global $dwdvm_custom5_mode;
    global $dwdvm_custom5_time;
    global $dwdvm_custom5_limit;
    global $dwdvm_custom5_unit;
    global $dwdvm_custom6_interface;
    global $dwdvm_custom6_mode;
    global $dwdvm_custom6_time;
    global $dwdvm_custom6_limit;
    global $dwdvm_custom6_unit;

    global $dwdvm_vifaces;
    global $dwdvm_match_virts;

    $custom_iface_string = "{$dwdvm_custom1_interface};{$dwdvm_custom2_interface};{$dwdvm_custom3_interface};{$dwdvm_custom4_interface};{$dwdvm_custom5_interface};{$dwdvm_custom6_interface}";
    $custom_iface_array = explode(";", trim($custom_iface_string)) ?? [];

    $virt_iface_matches = false;
    if($dwdvm_vifaces == "enable" && $dwdvm_match_virts == "enable") {
        $virt_iface_matches = dvm_getVirtualInterfaceServiceMatches();
    }

    $returnStr = "";
    $db_ifaces_array = dvm_getInterfaces();

    if($db_ifaces_array) {
        foreach($db_ifaces_array as $db_iface) {
            $xml = dvm_getXMLforInterface($db_iface);

            if($xml) {
                if($db_iface == $dwdvm_primary) {
                    $tmpStr = "";

                    $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx ?? 0) . "</td>";
                    $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx ?? 0) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "h", $dwdvm_hlimit_rx, $dwdvm_hunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "h", $dwdvm_hlimit_tx, $dwdvm_hunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "d", $dwdvm_dlimit_rx, $dwdvm_dunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "d", $dwdvm_dlimit_tx, $dwdvm_dunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "m", $dwdvm_mlimit_rx, $dwdvm_munit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "m", $dwdvm_mlimit_tx, $dwdvm_munit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "y", $dwdvm_ylimit_rx, $dwdvm_yunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx ?? 0)) . "</td>";
                    $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "y", $dwdvm_ylimit_tx, $dwdvm_yunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx ?? 0)) . "</td>";

                    if(count($db_ifaces_array) > 1) {
                        $returnStr .= "<tr style='border:2px solid;'>";
                    } else {
                        $returnStr .= "<tr>";
                    }

                    if(dvm_isExistingInterface($db_iface)) {
                        $matchedService = "";
                        if($virt_iface_matches) {
                            $matchedService = $virt_iface_matches[$db_iface] ?? false;
                            if($matchedService) {
                                $matchedService = "<span style='font-size:x-small;color:gray;'> {$matchedService}</span>";
                            }
                        }
                        if ( strpos ( $tmpStr , "red-text" ) !== false ) {
                            $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle red-orb' title='Monitored - Limits Exceeded'></i>" . $db_iface . $matchedService . "</td>";
                        } else if ( strpos ( $tmpStr , "green-text" ) !== false ) {
                            $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle green-orb' title='Monitored - Limits Not Exceeded'></i>" . $db_iface .  $matchedService . "</td>";
                        } else {
                            $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle dvm-gray-orb' title='Monitored - No Limits'></i>" . $db_iface . $matchedService . "</td>";
                        }
                    } else {
                        $returnStr .= "<td><i class='dvmorbiconactive fa fa-low-vision' title='Removed or Inactive Interface'></i>" . $db_iface . "</td>";
                    }
                    $returnStr .= $tmpStr;
                    $returnStr .= "</tr>";
                } else if (in_array($db_iface,$custom_iface_array)) {
                    $iface_key = array_search($db_iface, $custom_iface_array);
                    $iface_key = strval($iface_key + 1);
                    $tmpStr = "";

                    $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx ?? 0) . "</td>";
                    $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx ?? 0) . "</td>";

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "h" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "rx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "h", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "h" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "tx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "h", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "d" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "rx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "d", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "d" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "tx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "d", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "m" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "rx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "m", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "m" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "tx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "m", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "y" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "rx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "y", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx ?? 0) . "</td>";
                    }

                    if ("{${'dwdvm_custom'.$iface_key.'_time'}}" == "y" && "{${'dwdvm_custom'.$iface_key.'_mode'}}" == "tx") {
                        $tmpStr .= "<td>". dvm_checkAgainstLimits($db_iface, "y", "{${'dwdvm_custom'.$iface_key.'_limit'}}", "{${'dwdvm_custom'.$iface_key.'_unit'}}", "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx ?? 0)) . "</td>";
                    } else {
                        $tmpStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx ?? 0) . "</td>";
                    }

                    $returnStr .= "<tr>";

                    if(dvm_isExistingInterface($db_iface)) {
                        $matchedService = "";
                        if($virt_iface_matches) {
                            $matchedService = $virt_iface_matches[$db_iface] ?? false;
                            if($matchedService) {
                                $matchedService = "<span style='font-size:x-small;color:gray;'> {$matchedService}</span>";
                            }
                        }
                        if ( strpos ( $tmpStr , "red-text" ) !== false ) {
                            $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle red-orb' title='Monitored - Limits Exceeded'></i>" . $db_iface . $matchedService . "</td>";
                        } else if ( strpos ( $tmpStr , "green-text" ) !== false ) {
                            $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle green-orb' title='Monitored - Limits Not Exceeded'></i>" . $db_iface . $matchedService . "</td>";
                        } else {
                            $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle dvm-gray-orb' title='Monitored - No Limits'></i>" . $db_iface . $matchedService . "</td>";
                        }
                    } else {
                        $returnStr .= "<td><i class='dvmorbiconactive fa fa-low-vision' title='Removed or Inactive Interface'></i>" . $db_iface . "</td>";
                    }
                    $returnStr .= $tmpStr;
                    $returnStr .= "</tr>";
                } else {
                    $returnStr .= "<tr>";

                    if(dvm_isExistingInterface($db_iface)) {
                        $matchedService = "";
                        if($virt_iface_matches) {
                            $matchedService = $virt_iface_matches[$db_iface] ?? false;
                            if($matchedService) {
                                $matchedService = "<span style='font-size:x-small;color:gray;'> {$matchedService}</span>";
                            }
                        }
                        $returnStr .= "<td><i class='dvmorbiconactive fa fa-circle dvm-gray-orb' title='Monitored - No Limits'></i>". $db_iface . $matchedService . "</td>";
                    } else {
                        $returnStr .= "<td><i class='dvmorbiconactive fa fa-low-vision' title='Removed or Inactive Interface'></i>" . $db_iface . "</td>";
                    }
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx ?? 0) . "</td>";
                    $returnStr .= "<td>". dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx ?? 0) . "</td>";
                    $returnStr .= "</tr>";
                }
            } else {
                $returnStr .= "<tr>";
                $returnStr .= "<td>". $db_iface . "</td>";
                $returnStr .= "<td colspan='10'><i class='dvmorbiconactive fa fa-exclamation-circle' title='Removed or Inactive Interface'></i><em>Error Occured While Querying Network Interface</em></td>";
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

function dvm_build_dashboard() {
    $returnStr = "";
    $dvm_metrics = [];

    $dvm_5 = [];
    $dvm_h = [];
    $dvm_d = [];
    $dvm_m = [];
    $dvm_y = [];

    $dvm_metrics = dvm_get_primary_metrics();

    if($dvm_metrics) {
        $dvm_5 = $dvm_metrics[0];
        $dvm_h = $dvm_metrics[1];
        $dvm_d = $dvm_metrics[2];
        $dvm_m = $dvm_metrics[3];
        $dvm_y = $dvm_metrics[4];

        $returnStr .= "{$dvm_5[0]};{$dvm_5[1]};{$dvm_h[0]};{$dvm_h[1]};{$dvm_d[0]};{$dvm_d[1]};{$dvm_m[0]};{$dvm_m[1]};{$dvm_y[0]};{$dvm_y[1]}";
    } else {
        $returnStr .= "<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>;<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>";
    }
    return $returnStr;
}

function dvm_build_footer()
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

    $xml = dvm_getXMLforInterface($dwdvm_primary);

    if($xml) {
        switch ($dwdvm_footerformat) {
            case '5':
                $trafficRx = dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->rx ?? 0);
                $trafficTx = dvm_humanFileSize($xml->interface[0]->traffic[0]->fiveminutes[0]->fiveminute[0]->tx ?? 0);
                break;
            case 'h':
                $trafficRx = dvm_checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_rx, $dwdvm_hunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->rx ?? 0));
                $trafficTx = dvm_checkAgainstLimits($dwdvm_primary, "h", $dwdvm_hlimit_tx, $dwdvm_hunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->hours[0]->hour[0]->tx ?? 0));
                break;
            case 'd':
                $trafficRx = dvm_checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_rx, $dwdvm_dunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->rx ?? 0));
                $trafficTx = dvm_checkAgainstLimits($dwdvm_primary, "d", $dwdvm_dlimit_tx, $dwdvm_dunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->days[0]->day[0]->tx ?? 0));
                break;
            case 'm':
                $trafficRx = dvm_checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_rx, $dwdvm_munit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->rx ?? 0));
                $trafficTx = dvm_checkAgainstLimits($dwdvm_primary, "m", $dwdvm_mlimit_tx, $dwdvm_munit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->months[0]->month[0]->tx ?? 0));
                break;
            case 'y':
                $trafficRx = dvm_checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_rx, $dwdvm_yunit_rx, "rx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->rx ?? 0));
                $trafficTx = dvm_checkAgainstLimits($dwdvm_primary, "y", $dwdvm_ylimit_tx, $dwdvm_yunit_tx, "tx", dvm_humanFileSize($xml->interface[0]->traffic[0]->years[0]->year[0]->tx ?? 0));
                break;
        }
    } else {
        $trafficRx = "<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>";
        $trafficTx = "<i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>";
    }
    return("<i class='fa fa-arrow-down'></i> " . $trafficRx . " <i class='fa fa-arrow-up'></i> " . $trafficTx);
}

function dvm_build_dashboard_mini() {
    global $dwdvm_primary;
    global $dwdvm_footerformat;

    $returnStr = "";
    $dvm_mini_descr = "";

    $dvm_mini = dvm_build_footer();

    if($dvm_mini) {
        switch ($dwdvm_footerformat) {
            case '5':
                $dvm_mini_descr = "Last 5 Minutes:";
                break;
            case 'h':
                $dvm_mini_descr = "Last Hour:";
                break;
            case 'd':
                $dvm_mini_descr = "Last Day:";
                break;
            case 'm':
                $dvm_mini_descr = "Last Month:";
                break;
            case 'y':
                $dvm_mini_descr = "Last Year:";
                break;
        }
        $returnStr .= "$dwdvm_primary / $dvm_mini_descr $dvm_mini";
    } else {
        $returnStr = "$dwdvm_primary / $dvm_mini_descr / <i class='fa fa-times red-text' title='Error Querying Network Interface - Missing or Misspelled Primary Interface?'></i>";
    }
    return $returnStr;
}

if(!empty($_GET["mode"])) {
    try {
        $dvm_retarr = [];
        switch($_GET["mode"]) {
            case "report":
                $dvm_funcout = dvm_build_report();
                if($dvm_funcout) {
                    $dvm_retarr["success"]["response"] = $dvm_funcout;
                } else {
                    $dvm_retarr["error"]["response"] = "Falsy dvm_build_report() function response!";
                }
                break;
            case "lightreport":
                $dvm_funcout = dvm_build_report_light();
                if($dvm_funcout) {
                    $dvm_retarr["success"]["response"] = $dvm_funcout;
                } else {
                    $dvm_retarr["error"]["response"] = "Falsy dvm_build_report_light() function response!";
                }
                break;
            case "footer":
                $dvm_funcout = dvm_build_footer();
                if($dvm_funcout) {
                    $dvm_retarr["success"]["response"] = $dvm_funcout;
                } else {
                    $dvm_retarr["error"]["response"] = "Falsy dvm_build_footer() function response!";
                }
                break;
            case "dashboard":
                $dvm_funcout = dvm_build_dashboard();
                if($dvm_funcout) {
                    $dvm_retarr["success"]["response"] = $dvm_funcout;
                } else {
                    $dvm_retarr["error"]["response"] = "Falsy dvm_build_dashboard() function response!";
                }
                break;
            case "dashboardmini":
                $dvm_funcout = dvm_build_dashboard_mini();
                if($dvm_funcout) {
                    $dvm_retarr["success"]["response"] = $dvm_funcout;
                } else {
                    $dvm_retarr["error"]["response"] = "Falsy dvm_build_dashboard_mini() function response!";
                }
                break;
            default:
                $dvm_retarr["error"]["response"] = "Invalid GET parameters";
                break;
        }
    }
    catch (\Throwable $t) {
        error_log($t);
        $dvm_retarr = [];
        $dvm_retarr["error"]["response"] = $t->getMessage();
    }
    catch (\Exception $e) {
        error_log($e);
        $dvm_retarr = [];
        $dvm_retarr["error"]["response"] = $e->getMessage();
    }
    echo(json_encode($dvm_retarr));
}
?>
