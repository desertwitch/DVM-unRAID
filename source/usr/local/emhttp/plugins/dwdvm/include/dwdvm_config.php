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
$dwdvm_bad_notify  = isset($dwdvm_cfg['BADNOTIFY']) ? htmlspecialchars($dwdvm_cfg['BADNOTIFY']) : 'disable';

$dwdvm_hlimit_rx  = trim(isset($dwdvm_cfg['HLIMITRX'])  ? htmlspecialchars($dwdvm_cfg['HLIMITRX'])  : '-1');
$dwdvm_hunit_rx   = trim(isset($dwdvm_cfg['HUNITRX'])   ? htmlspecialchars($dwdvm_cfg['HUNITRX'])   : 'GB');
$dwdvm_dlimit_rx  = trim(isset($dwdvm_cfg['DLIMITRX'])  ? htmlspecialchars($dwdvm_cfg['DLIMITRX'])  : '-1');
$dwdvm_dunit_rx   = trim(isset($dwdvm_cfg['DUNITRX'])   ? htmlspecialchars($dwdvm_cfg['DUNITRX'])   : 'GB');
$dwdvm_mlimit_rx  = trim(isset($dwdvm_cfg['MLIMITRX'])  ? htmlspecialchars($dwdvm_cfg['MLIMITRX'])  : '-1');
$dwdvm_munit_rx   = trim(isset($dwdvm_cfg['MUNITRX'])   ? htmlspecialchars($dwdvm_cfg['MUNITRX'])   : 'GB');
$dwdvm_ylimit_rx  = trim(isset($dwdvm_cfg['YLIMITRX'])  ? htmlspecialchars($dwdvm_cfg['YLIMITRX'])  : '-1');
$dwdvm_yunit_rx   = trim(isset($dwdvm_cfg['YUNITRX'])   ? htmlspecialchars($dwdvm_cfg['YUNITRX'])   : 'GB');

$dwdvm_hlimit_tx  = trim(isset($dwdvm_cfg['HLIMITTX'])  ? htmlspecialchars($dwdvm_cfg['HLIMITTX'])  : '-1');
$dwdvm_hunit_tx   = trim(isset($dwdvm_cfg['HUNITTX'])   ? htmlspecialchars($dwdvm_cfg['HUNITTX'])   : 'GB');
$dwdvm_dlimit_tx  = trim(isset($dwdvm_cfg['DLIMITTX'])  ? htmlspecialchars($dwdvm_cfg['DLIMITTX'])  : '-1');
$dwdvm_dunit_tx   = trim(isset($dwdvm_cfg['DUNITTX'])   ? htmlspecialchars($dwdvm_cfg['DUNITTX'])   : 'GB');
$dwdvm_mlimit_tx  = trim(isset($dwdvm_cfg['MLIMITTX'])  ? htmlspecialchars($dwdvm_cfg['MLIMITTX'])  : '-1');
$dwdvm_munit_tx   = trim(isset($dwdvm_cfg['MUNITTX'])   ? htmlspecialchars($dwdvm_cfg['MUNITTX'])   : 'GB');
$dwdvm_ylimit_tx  = trim(isset($dwdvm_cfg['YLIMITTX'])  ? htmlspecialchars($dwdvm_cfg['YLIMITTX'])  : '-1');
$dwdvm_yunit_tx   = trim(isset($dwdvm_cfg['YUNITTX'])   ? htmlspecialchars($dwdvm_cfg['YUNITTX'])   : 'GB');

$dwdvm_running    = (intval(trim(shell_exec( "[ -f /proc/`cat /var/run/vnstat/vnstat.pid 2> /dev/null`/exe ] && echo 1 || echo 0 2> /dev/null" ))) === 1 );
$dwdvm_installed_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'vnstat*' -printf '%f\n' 2> /dev/null"));

?>
