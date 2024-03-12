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
$dwdvm_dashb        = isset($dwdvm_cfg['DASHB'])        ? htmlspecialchars($dwdvm_cfg['DASHB'])         : 'disable';
$dwdvm_footer       = isset($dwdvm_cfg['FOOTER'])       ? htmlspecialchars($dwdvm_cfg['FOOTER'])        : 'disable';
$dwdvm_footerformat = isset($dwdvm_cfg['FOOTERFORMAT']) ? htmlspecialchars($dwdvm_cfg['FOOTERFORMAT'])  : 'd';
$dwdvm_primary      = trim(isset($dwdvm_cfg['PRIMARY']) ? htmlspecialchars($dwdvm_cfg['PRIMARY'])       : 'eth0');

$dwdvm_good_notify = isset($dwdvm_cfg['BADNOTIFY'])  ? htmlspecialchars($dwdvm_cfg['BADNOTIFY'])  : 'disable';
$dwdvm_bad_notify  = isset($dwdvm_cfg['GOODNOTIFY']) ? htmlspecialchars($dwdvm_cfg['GOODNOTIFY']) : 'disable';

$dwdvm_5limit  = isset($dwdvm_cfg['5LIMIT'])  ? htmlspecialchars($dwdvm_cfg['5LIMIT'])  : '-1';
$dwdvm_5unit   = isset($dwdvm_cfg['5UNIT'])   ? htmlspecialchars($dwdvm_cfg['5UNIT'])   : 'GB';
$dwdvm_hlimit  = isset($dwdvm_cfg['HLIMIT'])  ? htmlspecialchars($dwdvm_cfg['HLIMIT'])  : '-1';
$dwdvm_hunit   = isset($dwdvm_cfg['HUNIT'])   ? htmlspecialchars($dwdvm_cfg['HUNIT'])   : 'GB';
$dwdvm_dlimit  = isset($dwdvm_cfg['DLIMIT'])  ? htmlspecialchars($dwdvm_cfg['DLIMIT'])  : '-1';
$dwdvm_dunit   = isset($dwdvm_cfg['DUNIT'])   ? htmlspecialchars($dwdvm_cfg['DUNIT'])   : 'GB';
$dwdvm_mlimit  = isset($dwdvm_cfg['MLIMIT'])  ? htmlspecialchars($dwdvm_cfg['MLIMIT'])  : '-1';
$dwdvm_munit   = isset($dwdvm_cfg['MUNIT'])   ? htmlspecialchars($dwdvm_cfg['MUNIT'])   : 'GB';
$dwdvm_ylimit  = isset($dwdvm_cfg['YLIMIT'])  ? htmlspecialchars($dwdvm_cfg['YLIMIT'])  : '-1';
$dwdvm_yunit   = isset($dwdvm_cfg['YUNIT'])   ? htmlspecialchars($dwdvm_cfg['YDUNIT'])  : 'GB';

$dwdvm_running      = (intval(trim(shell_exec( "[ -f /proc/`cat /var/run/vnstat/vnstat.pid 2> /dev/null`/exe ] && echo 1 || echo 0 2> /dev/null" ))) === 1 );
$dwdvm_installed_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'vnstat*' -printf '%f\n' 2> /dev/null"));

?>
