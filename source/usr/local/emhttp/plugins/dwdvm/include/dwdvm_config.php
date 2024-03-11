<?PHP
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
$dwdvm_cfg          = parse_ini_file("/boot/config/plugins/dwdvm/dwdvm.cfg");
$dwdvm_service      = isset($dwdvm_cfg['SERVICE'])      ? htmlspecialchars($dwdvm_cfg['SERVICE'])       : 'disable';
$dwdvm_backupdb     = isset($dwdvm_cfg['BACKUPDB'])     ? htmlspecialchars($dwdvm_cfg['BACKUPDB'])      : 'disable';
$dwdvm_report       = isset($dwdvm_cfg['REPORT'])       ? htmlspecialchars($dwdvm_cfg['REPORT'])        : 'text';
$dwdvm_vifaces      = isset($dwdvm_cfg['VIFACES'])      ? htmlspecialchars($dwdvm_cfg['VIFACES'])       : 'disable';
$dwdvm_cronint      = isset($dwdvm_cfg['CRONINT'])      ? htmlspecialchars($dwdvm_cfg['CRONINT'])       : 'disable';
$dwdvm_running      = (intval(trim(shell_exec( "[ -f /proc/`cat /var/run/vnstat/vnstat.pid 2> /dev/null`/exe ] && echo 1 || echo 0 2> /dev/null" ))) === 1 );
$dwdvm_installed_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'vnstat*' -printf '%f\n' 2> /dev/null"));

?>
