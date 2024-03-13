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

$dwdvm_good_notify = isset($dwdvm_cfg['GOODNOTIFY'])  ? htmlspecialchars($dwdvm_cfg['GOODNOTIFY'])  : 'disable';
$dwdvm_bad_notify  = isset($dwdvm_cfg['BADNOTIFY'])   ? htmlspecialchars($dwdvm_cfg['BADNOTIFY']) : 'disable';

$dwdvm_hlimit_rx  = trim(isset($dwdvm_cfg['HlimitRX'])  ? htmlspecialchars($dwdvm_cfg['HlimitRX'])  : '-1');
$dwdvm_hunit_rx   = trim(isset($dwdvm_cfg['HunitRX'])   ? htmlspecialchars($dwdvm_cfg['HunitRX'])   : 'GB');
$dwdvm_dlimit_rx  = trim(isset($dwdvm_cfg['DlimitRX'])  ? htmlspecialchars($dwdvm_cfg['DlimitRX'])  : '-1');
$dwdvm_dunit_rx   = trim(isset($dwdvm_cfg['DunitRX'])   ? htmlspecialchars($dwdvm_cfg['DunitRX'])   : 'GB');
$dwdvm_mlimit_rx  = trim(isset($dwdvm_cfg['MlimitRX'])  ? htmlspecialchars($dwdvm_cfg['MlimitRX'])  : '-1');
$dwdvm_munit_rx   = trim(isset($dwdvm_cfg['MunitRX'])   ? htmlspecialchars($dwdvm_cfg['MunitRX'])   : 'GB');
$dwdvm_ylimit_rx  = trim(isset($dwdvm_cfg['YlimitRX'])  ? htmlspecialchars($dwdvm_cfg['YlimitRX'])  : '-1');
$dwdvm_yunit_rx   = trim(isset($dwdvm_cfg['YunitRX'])   ? htmlspecialchars($dwdvm_cfg['YunitRX'])   : 'GB');

$dwdvm_hlimit_tx  = trim(isset($dwdvm_cfg['HlimitTX'])  ? htmlspecialchars($dwdvm_cfg['HlimitTX'])  : '-1');
$dwdvm_hunit_tx   = trim(isset($dwdvm_cfg['HunitTX'])   ? htmlspecialchars($dwdvm_cfg['HunitTX'])   : 'GB');
$dwdvm_dlimit_tx  = trim(isset($dwdvm_cfg['DlimitTX'])  ? htmlspecialchars($dwdvm_cfg['DlimitTX'])  : '-1');
$dwdvm_dunit_tx   = trim(isset($dwdvm_cfg['DunitTX'])   ? htmlspecialchars($dwdvm_cfg['DunitTX'])   : 'GB');
$dwdvm_mlimit_tx  = trim(isset($dwdvm_cfg['MlimitTX'])  ? htmlspecialchars($dwdvm_cfg['MlimitTX'])  : '-1');
$dwdvm_munit_tx   = trim(isset($dwdvm_cfg['MunitTX'])   ? htmlspecialchars($dwdvm_cfg['MunitTX'])   : 'GB');
$dwdvm_ylimit_tx  = trim(isset($dwdvm_cfg['YlimitTX'])  ? htmlspecialchars($dwdvm_cfg['YlimitTX'])  : '-1');
$dwdvm_yunit_tx   = trim(isset($dwdvm_cfg['YunitTX'])   ? htmlspecialchars($dwdvm_cfg['YunitTX'])   : 'GB');

$dwdvm_running    = (intval(trim(shell_exec( "[ -f /proc/`cat /var/run/vnstat/vnstat.pid 2> /dev/null`/exe ] && echo 1 || echo 0 2> /dev/null" ))) === 1 );
$dwdvm_installed_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'vnstat*' -printf '%f\n' 2> /dev/null"));

?>
