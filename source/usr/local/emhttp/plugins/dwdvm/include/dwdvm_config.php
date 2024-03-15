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
$dwdvm_backupdb     = isset($dwdvm_cfg['BACKUPDB'])     ? htmlspecialchars($dwdvm_cfg['BACKUPDB'])      : 'enable';
$dwdvm_report       = isset($dwdvm_cfg['REPORT'])       ? htmlspecialchars($dwdvm_cfg['REPORT'])        : 'text';
$dwdvm_vifaces      = isset($dwdvm_cfg['VIFACES'])      ? htmlspecialchars($dwdvm_cfg['VIFACES'])       : 'disable';
$dwdvm_oifaces      = isset($dwdvm_cfg['OIFACES'])      ? htmlspecialchars($dwdvm_cfg['OIFACES'])       : 'disable';
$dwdvm_cronint      = isset($dwdvm_cfg['CRONINT'])      ? htmlspecialchars($dwdvm_cfg['CRONINT'])       : 'disable';
$dwdvm_dashb        = isset($dwdvm_cfg['DASHB'])        ? htmlspecialchars($dwdvm_cfg['DASHB'])         : 'disable';
$dwdvm_footer       = isset($dwdvm_cfg['FOOTER'])       ? htmlspecialchars($dwdvm_cfg['FOOTER'])        : 'disable';
$dwdvm_footerformat = isset($dwdvm_cfg['FOOTERFORMAT']) ? htmlspecialchars($dwdvm_cfg['FOOTERFORMAT'])  : 'd';
$dwdvm_primary      = trim(isset($dwdvm_cfg['PRIMARY']) ? htmlspecialchars($dwdvm_cfg['PRIMARY'])       : 'eth0');

$dwdvm_good_notify = isset($dwdvm_cfg['GOODNOTIFY'])  ? htmlspecialchars($dwdvm_cfg['GOODNOTIFY'])  : 'disable';
$dwdvm_bad_notify  = isset($dwdvm_cfg['BADNOTIFY'])   ? htmlspecialchars($dwdvm_cfg['BADNOTIFY']) : 'disable';

$dwdvm_hlimit_rx  = trim(isset($dwdvm_cfg['RXLIMITH'])  ? htmlspecialchars($dwdvm_cfg['RXLIMITH'])  : '-1');
$dwdvm_hlimit_tx  = trim(isset($dwdvm_cfg['TXLIMITH'])  ? htmlspecialchars($dwdvm_cfg['TXLIMITH'])  : '-1');
$dwdvm_hunit_rx   = trim(isset($dwdvm_cfg['RXUNITH'])   ? htmlspecialchars($dwdvm_cfg['RXUNITH'])   : 'GB');
$dwdvm_hunit_tx   = trim(isset($dwdvm_cfg['TXUNITH'])   ? htmlspecialchars($dwdvm_cfg['TXUNITH'])   : 'GB');
$dwdvm_hunit_rx_start   = trim(isset($dwdvm_cfg['RXUNITHSTART'])   ? htmlspecialchars($dwdvm_cfg['RXUNITHSTART'])   : 'disable');
$dwdvm_hunit_rx_stop   = trim(isset($dwdvm_cfg['RXUNITHSTOP'])   ? htmlspecialchars($dwdvm_cfg['RXUNITHSTOP'])   : 'disable');
$dwdvm_hunit_tx_start   = trim(isset($dwdvm_cfg['TXUNITHSTART'])   ? htmlspecialchars($dwdvm_cfg['TXUNITHSTART'])   : 'disable');
$dwdvm_hunit_tx_stop   = trim(isset($dwdvm_cfg['TXUNITHSTOP'])   ? htmlspecialchars($dwdvm_cfg['TXUNITHSTOP'])   : 'disable');

$dwdvm_dlimit_rx  = trim(isset($dwdvm_cfg['RXLIMITD'])  ? htmlspecialchars($dwdvm_cfg['RXLIMITD'])  : '-1');
$dwdvm_dlimit_tx  = trim(isset($dwdvm_cfg['TXLIMITD'])  ? htmlspecialchars($dwdvm_cfg['TXLIMITD'])  : '-1');
$dwdvm_dunit_rx   = trim(isset($dwdvm_cfg['RXUNITD'])   ? htmlspecialchars($dwdvm_cfg['RXUNITD'])   : 'GB');
$dwdvm_dunit_tx   = trim(isset($dwdvm_cfg['TXUNITD'])   ? htmlspecialchars($dwdvm_cfg['TXUNITD'])   : 'GB');
$dwdvm_dunit_rx_start   = trim(isset($dwdvm_cfg['RXUNITDSTART'])   ? htmlspecialchars($dwdvm_cfg['RXUNITDSTART'])   : 'disable');
$dwdvm_dunit_rx_stop   = trim(isset($dwdvm_cfg['RXUNITDSTOP'])   ? htmlspecialchars($dwdvm_cfg['RXUNITDSTOP'])   : 'disable');
$dwdvm_dunit_tx_start   = trim(isset($dwdvm_cfg['TXUNITDSTART'])   ? htmlspecialchars($dwdvm_cfg['TXUNITDSTART'])   : 'disable');
$dwdvm_dunit_tx_stop   = trim(isset($dwdvm_cfg['TXUNITDSTOP'])   ? htmlspecialchars($dwdvm_cfg['TXUNITDSTOP'])   : 'disable');

$dwdvm_mlimit_rx  = trim(isset($dwdvm_cfg['RXLIMITM'])  ? htmlspecialchars($dwdvm_cfg['RXLIMITM'])  : '-1');
$dwdvm_mlimit_tx  = trim(isset($dwdvm_cfg['TXLIMITM'])  ? htmlspecialchars($dwdvm_cfg['TXLIMITM'])  : '-1');
$dwdvm_munit_rx   = trim(isset($dwdvm_cfg['RXUNITM'])   ? htmlspecialchars($dwdvm_cfg['RXUNITM'])   : 'GB');
$dwdvm_munit_tx   = trim(isset($dwdvm_cfg['TXUNITM'])   ? htmlspecialchars($dwdvm_cfg['TXUNITM'])   : 'GB');
$dwdvm_munit_rx_start   = trim(isset($dwdvm_cfg['RXUNITMSTART'])   ? htmlspecialchars($dwdvm_cfg['RXUNITMSTART'])   : 'disable');
$dwdvm_munit_rx_stop   = trim(isset($dwdvm_cfg['RXUNITMSTOP'])   ? htmlspecialchars($dwdvm_cfg['RXUNITMSTOP'])   : 'disable');
$dwdvm_munit_tx_start   = trim(isset($dwdvm_cfg['TXUNITMSTART'])   ? htmlspecialchars($dwdvm_cfg['TXUNITMSTART'])   : 'disable');
$dwdvm_munit_tx_stop   = trim(isset($dwdvm_cfg['TXUNITMSTOP'])   ? htmlspecialchars($dwdvm_cfg['TXUNITMSTOP'])   : 'disable');

$dwdvm_ylimit_rx  = trim(isset($dwdvm_cfg['RXLIMITY'])  ? htmlspecialchars($dwdvm_cfg['RXLIMITY'])  : '-1');
$dwdvm_ylimit_tx  = trim(isset($dwdvm_cfg['TXLIMITY'])  ? htmlspecialchars($dwdvm_cfg['TXLIMITY'])  : '-1');
$dwdvm_yunit_rx   = trim(isset($dwdvm_cfg['RXUNITY'])   ? htmlspecialchars($dwdvm_cfg['RXUNITY'])   : 'GB');
$dwdvm_yunit_tx   = trim(isset($dwdvm_cfg['TXUNITY'])   ? htmlspecialchars($dwdvm_cfg['TXUNITY'])   : 'GB');
$dwdvm_yunit_rx_start   = trim(isset($dwdvm_cfg['RXUNITYSTART'])   ? htmlspecialchars($dwdvm_cfg['RXUNITYSTART'])   : 'disable');
$dwdvm_yunit_rx_stop   = trim(isset($dwdvm_cfg['RXUNITYSTOP'])   ? htmlspecialchars($dwdvm_cfg['RXUNITYSTOP'])   : 'disable');
$dwdvm_yunit_tx_start   = trim(isset($dwdvm_cfg['TXUNITYSTART'])   ? htmlspecialchars($dwdvm_cfg['TXUNITYSTART'])   : 'disable');
$dwdvm_yunit_tx_stop   = trim(isset($dwdvm_cfg['TXUNITYSTOP'])   ? htmlspecialchars($dwdvm_cfg['TXUNITYSTOP'])   : 'disable');

$dwdvm_running    = (intval(trim(shell_exec( "[ -f /proc/`cat /var/run/vnstat/vnstat.pid 2> /dev/null`/exe ] && echo 1 || echo 0 2> /dev/null" ))) === 1 );
$dwdvm_installed_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'vnstat*' -printf '%f\n' 2> /dev/null"));

?>
