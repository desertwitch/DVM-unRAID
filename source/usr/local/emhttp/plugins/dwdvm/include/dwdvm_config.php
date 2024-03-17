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
$dwdvm_cfg                = parse_ini_file("/boot/config/plugins/dwdvm/dwdvm.cfg");

$dwdvm_service            = trim(isset($dwdvm_cfg['SERVICE'])           ? htmlspecialchars($dwdvm_cfg['SERVICE'])       : 'disable');
$dwdvm_backupdb           = trim(isset($dwdvm_cfg['BACKUPDB'])          ? htmlspecialchars($dwdvm_cfg['BACKUPDB'])      : 'enable');
$dwdvm_report             = trim(isset($dwdvm_cfg['REPORT'])            ? htmlspecialchars($dwdvm_cfg['REPORT'])        : 'text');
$dwdvm_vifaces            = trim(isset($dwdvm_cfg['VIFACES'])           ? htmlspecialchars($dwdvm_cfg['VIFACES'])       : 'enable');
$dwdvm_oifaces            = trim(isset($dwdvm_cfg['OIFACES'])           ? htmlspecialchars($dwdvm_cfg['OIFACES'])       : 'disable');
$dwdvm_autopurge          = trim(isset($dwdvm_cfg['AUTOPURGE'])         ? htmlspecialchars($dwdvm_cfg['AUTOPURGE'])     : 'disable');
$dwdvm_cronint            = trim(isset($dwdvm_cfg['CRONINT'])           ? htmlspecialchars($dwdvm_cfg['CRONINT'])       : 'disable');
$dwdvm_dashb              = trim(isset($dwdvm_cfg['DASHB'])             ? htmlspecialchars($dwdvm_cfg['DASHB'])         : 'enable');
$dwdvm_footer             = trim(isset($dwdvm_cfg['FOOTER'])            ? htmlspecialchars($dwdvm_cfg['FOOTER'])        : 'enable');
$dwdvm_footerformat       = trim(isset($dwdvm_cfg['FOOTERFORMAT'])      ? htmlspecialchars($dwdvm_cfg['FOOTERFORMAT'])  : 'd');
$dwdvm_primary            = trim(isset($dwdvm_cfg['PRIMARY'])           ? htmlspecialchars($dwdvm_cfg['PRIMARY'])       : 'eth0');

$dwdvm_good_notify        = trim(isset($dwdvm_cfg['GOODNOTIFY'])        ? htmlspecialchars($dwdvm_cfg['GOODNOTIFY'])  : 'disable');
$dwdvm_bad_notify         = trim(isset($dwdvm_cfg['BADNOTIFY'])         ? htmlspecialchars($dwdvm_cfg['BADNOTIFY']) : 'disable');

$dwdvm_good_actions       = trim(isset($dwdvm_cfg['GOODACTIONS'])       ? htmlspecialchars($dwdvm_cfg['GOODACTIONS'])  : 'disable');
$dwdvm_bad_actions        = trim(isset($dwdvm_cfg['BADACTIONS'])        ? htmlspecialchars($dwdvm_cfg['BADACTIONS']) : 'disable');

$dwdvm_hlimit_rx          = trim(isset($dwdvm_cfg['RXLIMITH'])          ? htmlspecialchars($dwdvm_cfg['RXLIMITH'])  : '-1');
$dwdvm_hlimit_tx          = trim(isset($dwdvm_cfg['TXLIMITH'])          ? htmlspecialchars($dwdvm_cfg['TXLIMITH'])  : '-1');
$dwdvm_hunit_rx           = trim(isset($dwdvm_cfg['RXUNITH'])           ? htmlspecialchars($dwdvm_cfg['RXUNITH'])   : 'GB');
$dwdvm_hunit_tx           = trim(isset($dwdvm_cfg['TXUNITH'])           ? htmlspecialchars($dwdvm_cfg['TXUNITH'])   : 'GB');
$dwdvm_hdocker_rx_start   = trim(isset($dwdvm_cfg['RXDOCKERHSTART'])    ? htmlspecialchars($dwdvm_cfg['RXDOCKERHSTART'])   : 'disable');
$dwdvm_hdocker_rx_stop    = trim(isset($dwdvm_cfg['RXDOCKERHSTOP'])     ? htmlspecialchars($dwdvm_cfg['RXDOCKERHSTOP'])   : 'disable');
$dwdvm_hdocker_tx_start   = trim(isset($dwdvm_cfg['TXDOCKERHSTART'])    ? htmlspecialchars($dwdvm_cfg['TXDOCKERHSTART'])   : 'disable');
$dwdvm_hdocker_tx_stop    = trim(isset($dwdvm_cfg['TXDOCKERHSTOP'])     ? htmlspecialchars($dwdvm_cfg['TXDOCKERHSTOP'])   : 'disable');
$dwdvm_hvm_rx_start       = trim(isset($dwdvm_cfg['RXVMHSTART'])        ? htmlspecialchars($dwdvm_cfg['RXVMHSTART'])   : 'disable');
$dwdvm_hvm_rx_stop        = trim(isset($dwdvm_cfg['RXVMHSTOP'])         ? htmlspecialchars($dwdvm_cfg['RXVMHSTOP'])   : 'disable');
$dwdvm_hvm_tx_start       = trim(isset($dwdvm_cfg['TXVMHSTART'])        ? htmlspecialchars($dwdvm_cfg['TXVMHSTART'])   : 'disable');
$dwdvm_hvm_tx_stop        = trim(isset($dwdvm_cfg['TXVMHSTOP'])         ? htmlspecialchars($dwdvm_cfg['TXVMHSTOP'])   : 'disable');

$dwdvm_dlimit_rx          = trim(isset($dwdvm_cfg['RXLIMITD'])          ? htmlspecialchars($dwdvm_cfg['RXLIMITD'])  : '-1');
$dwdvm_dlimit_tx          = trim(isset($dwdvm_cfg['TXLIMITD'])          ? htmlspecialchars($dwdvm_cfg['TXLIMITD'])  : '-1');
$dwdvm_dunit_rx           = trim(isset($dwdvm_cfg['RXUNITD'])           ? htmlspecialchars($dwdvm_cfg['RXUNITD'])   : 'GB');
$dwdvm_dunit_tx           = trim(isset($dwdvm_cfg['TXUNITD'])           ? htmlspecialchars($dwdvm_cfg['TXUNITD'])   : 'GB');
$dwdvm_ddocker_rx_start   = trim(isset($dwdvm_cfg['RXDOCKERDSTART'])    ? htmlspecialchars($dwdvm_cfg['RXDOCKERDSTART'])   : 'disable');
$dwdvm_ddocker_rx_stop    = trim(isset($dwdvm_cfg['RXDOCKERDSTOP'])     ? htmlspecialchars($dwdvm_cfg['RXDOCKERDSTOP'])   : 'disable');
$dwdvm_ddocker_tx_start   = trim(isset($dwdvm_cfg['TXDOCKERDSTART'])    ? htmlspecialchars($dwdvm_cfg['TXDOCKERDSTART'])   : 'disable');
$dwdvm_ddocker_tx_stop    = trim(isset($dwdvm_cfg['TXDOCKERDSTOP'])     ? htmlspecialchars($dwdvm_cfg['TXDOCKERDSTOP'])   : 'disable');
$dwdvm_dvm_rx_start       = trim(isset($dwdvm_cfg['RXVMDSTART'])        ? htmlspecialchars($dwdvm_cfg['RXVMDSTART'])   : 'disable');
$dwdvm_dvm_rx_stop        = trim(isset($dwdvm_cfg['RXVMDSTOP'])         ? htmlspecialchars($dwdvm_cfg['RXVMDSTOP'])   : 'disable');
$dwdvm_dvm_tx_start       = trim(isset($dwdvm_cfg['TXVMDSTART'])        ? htmlspecialchars($dwdvm_cfg['TXVMDSTART'])   : 'disable');
$dwdvm_dvm_tx_stop        = trim(isset($dwdvm_cfg['TXVMDSTOP'])         ? htmlspecialchars($dwdvm_cfg['TXVMDSTOP'])   : 'disable');

$dwdvm_mlimit_rx          = trim(isset($dwdvm_cfg['RXLIMITM'])          ? htmlspecialchars($dwdvm_cfg['RXLIMITM'])  : '-1');
$dwdvm_mlimit_tx          = trim(isset($dwdvm_cfg['TXLIMITM'])          ? htmlspecialchars($dwdvm_cfg['TXLIMITM'])  : '-1');
$dwdvm_munit_rx           = trim(isset($dwdvm_cfg['RXUNITM'])           ? htmlspecialchars($dwdvm_cfg['RXUNITM'])   : 'GB');
$dwdvm_munit_tx           = trim(isset($dwdvm_cfg['TXUNITM'])           ? htmlspecialchars($dwdvm_cfg['TXUNITM'])   : 'GB');
$dwdvm_mdocker_rx_start   = trim(isset($dwdvm_cfg['RXDOCKERMSTART'])    ? htmlspecialchars($dwdvm_cfg['RXDOCKERMSTART'])   : 'disable');
$dwdvm_mdocker_rx_stop    = trim(isset($dwdvm_cfg['RXDOCKERMSTOP'])     ? htmlspecialchars($dwdvm_cfg['RXDOCKERMSTOP'])   : 'disable');
$dwdvm_mdocker_tx_start   = trim(isset($dwdvm_cfg['TXDOCKERMSTART'])    ? htmlspecialchars($dwdvm_cfg['TXDOCKERMSTART'])   : 'disable');
$dwdvm_mdocker_tx_stop    = trim(isset($dwdvm_cfg['TXDOCKERMSTOP'])     ? htmlspecialchars($dwdvm_cfg['TXDOCKERMSTOP'])   : 'disable');
$dwdvm_mvm_rx_start       = trim(isset($dwdvm_cfg['RXVMMSTART'])        ? htmlspecialchars($dwdvm_cfg['RXVMMSTART'])   : 'disable');
$dwdvm_mvm_rx_stop        = trim(isset($dwdvm_cfg['RXVMMSTOP'])         ? htmlspecialchars($dwdvm_cfg['RXVMMSTOP'])   : 'disable');
$dwdvm_mvm_tx_start       = trim(isset($dwdvm_cfg['TXVMMSTART'])        ? htmlspecialchars($dwdvm_cfg['TXVMMSTART'])   : 'disable');
$dwdvm_mvm_tx_stop        = trim(isset($dwdvm_cfg['TXVMMSTOP'])         ? htmlspecialchars($dwdvm_cfg['TXVMMSTOP'])   : 'disable');

$dwdvm_ylimit_rx          = trim(isset($dwdvm_cfg['RXLIMITY'])          ? htmlspecialchars($dwdvm_cfg['RXLIMITY'])  : '-1');
$dwdvm_ylimit_tx          = trim(isset($dwdvm_cfg['TXLIMITY'])          ? htmlspecialchars($dwdvm_cfg['TXLIMITY'])  : '-1');
$dwdvm_yunit_rx           = trim(isset($dwdvm_cfg['RXUNITY'])           ? htmlspecialchars($dwdvm_cfg['RXUNITY'])   : 'GB');
$dwdvm_yunit_tx           = trim(isset($dwdvm_cfg['TXUNITY'])           ? htmlspecialchars($dwdvm_cfg['TXUNITY'])   : 'GB');
$dwdvm_ydocker_rx_start   = trim(isset($dwdvm_cfg['RXDOCKERYSTART'])    ? htmlspecialchars($dwdvm_cfg['RXDOCKERYSTART'])   : 'disable');
$dwdvm_ydocker_rx_stop    = trim(isset($dwdvm_cfg['RXDOCKERYSTOP'])     ? htmlspecialchars($dwdvm_cfg['RXDOCKERYSTOP'])   : 'disable');
$dwdvm_ydocker_tx_start   = trim(isset($dwdvm_cfg['TXDOCKERYSTART'])    ? htmlspecialchars($dwdvm_cfg['TXDOCKERYSTART'])   : 'disable');
$dwdvm_ydocker_tx_stop    = trim(isset($dwdvm_cfg['TXDOCKERYSTOP'])     ? htmlspecialchars($dwdvm_cfg['TXDOCKERYSTOP'])   : 'disable');
$dwdvm_yvm_rx_start       = trim(isset($dwdvm_cfg['RXVMYSTART'])        ? htmlspecialchars($dwdvm_cfg['RXVMYSTART'])   : 'disable');
$dwdvm_yvm_rx_stop        = trim(isset($dwdvm_cfg['RXVMYSTOP'])         ? htmlspecialchars($dwdvm_cfg['RXVMYSTOP'])   : 'disable');
$dwdvm_yvm_tx_start       = trim(isset($dwdvm_cfg['TXVMYSTART'])        ? htmlspecialchars($dwdvm_cfg['TXVMYSTART'])   : 'disable');
$dwdvm_yvm_tx_stop        = trim(isset($dwdvm_cfg['TXVMYSTOP'])         ? htmlspecialchars($dwdvm_cfg['TXVMYSTOP'])   : 'disable');


$dwdvm_custom1_interface  = trim(isset($dwdvm_cfg['CUSTOM1INTERFACE'])  ? htmlspecialchars($dwdvm_cfg['CUSTOM1INTERFACE'])  : 'noiface');
$dwdvm_custom1_mode       = trim(isset($dwdvm_cfg['CUSTOM1MODE'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM1MODE'])       : 'rx');
$dwdvm_custom1_time       = trim(isset($dwdvm_cfg['CUSTOM1TIME'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM1TIME'])       : 'h');
$dwdvm_custom1_limit      = trim(isset($dwdvm_cfg['CUSTOM1LIMIT'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM1LIMIT'])      : '-1');
$dwdvm_custom1_unit       = trim(isset($dwdvm_cfg['CUSTOM1UNIT'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM1UNIT'])       : 'GB');
$dwdvm_custom1_stop       = trim(isset($dwdvm_cfg['CUSTOM1STOP'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM1STOP'])       : 'disable');
$dwdvm_custom1_start      = trim(isset($dwdvm_cfg['CUSTOM1START'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM1START'])      : 'disable');
$dwdvm_custom1_vmstop     = trim(isset($dwdvm_cfg['CUSTOM1VMSTOP'])     ? htmlspecialchars($dwdvm_cfg['CUSTOM1VMSTOP'])     : 'disable');
$dwdvm_custom1_vmstart    = trim(isset($dwdvm_cfg['CUSTOM1VMSTART'])    ? htmlspecialchars($dwdvm_cfg['CUSTOM1VMSTART'])    : 'disable');

$dwdvm_custom2_interface  = trim(isset($dwdvm_cfg['CUSTOM2INTERFACE'])  ? htmlspecialchars($dwdvm_cfg['CUSTOM2INTERFACE'])  : 'noiface');
$dwdvm_custom2_mode       = trim(isset($dwdvm_cfg['CUSTOM2MODE'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM2MODE'])       : 'rx');
$dwdvm_custom2_time       = trim(isset($dwdvm_cfg['CUSTOM2TIME'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM2TIME'])       : 'h');
$dwdvm_custom2_limit      = trim(isset($dwdvm_cfg['CUSTOM2LIMIT'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM2LIMIT'])      : '-1');
$dwdvm_custom2_unit       = trim(isset($dwdvm_cfg['CUSTOM2UNIT'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM2UNIT'])       : 'GB');
$dwdvm_custom2_stop       = trim(isset($dwdvm_cfg['CUSTOM2STOP'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM2STOP'])       : 'disable');
$dwdvm_custom2_start      = trim(isset($dwdvm_cfg['CUSTOM2START'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM2START'])      : 'disable');
$dwdvm_custom2_vmstop     = trim(isset($dwdvm_cfg['CUSTOM2VMSTOP'])     ? htmlspecialchars($dwdvm_cfg['CUSTOM2VMSTOP'])     : 'disable');
$dwdvm_custom2_vmstart    = trim(isset($dwdvm_cfg['CUSTOM2VMSTART'])    ? htmlspecialchars($dwdvm_cfg['CUSTOM2VMSTART'])    : 'disable');

$dwdvm_custom3_interface  = trim(isset($dwdvm_cfg['CUSTOM3INTERFACE'])  ? htmlspecialchars($dwdvm_cfg['CUSTOM3INTERFACE'])  : 'noiface');
$dwdvm_custom3_mode       = trim(isset($dwdvm_cfg['CUSTOM3MODE'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM3MODE'])       : 'rx');
$dwdvm_custom3_time       = trim(isset($dwdvm_cfg['CUSTOM3TIME'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM3TIME'])       : 'h');
$dwdvm_custom3_limit      = trim(isset($dwdvm_cfg['CUSTOM3LIMIT'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM3LIMIT'])      : '-1');
$dwdvm_custom3_unit       = trim(isset($dwdvm_cfg['CUSTOM3UNIT'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM3UNIT'])       : 'GB');
$dwdvm_custom3_stop       = trim(isset($dwdvm_cfg['CUSTOM3STOP'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM3STOP'])       : 'disable');
$dwdvm_custom3_start      = trim(isset($dwdvm_cfg['CUSTOM3START'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM3START'])      : 'disable');
$dwdvm_custom3_vmstop     = trim(isset($dwdvm_cfg['CUSTOM3VMSTOP'])     ? htmlspecialchars($dwdvm_cfg['CUSTOM3VMSTOP'])     : 'disable');
$dwdvm_custom3_vmstart    = trim(isset($dwdvm_cfg['CUSTOM3VMSTART'])    ? htmlspecialchars($dwdvm_cfg['CUSTOM3VMSTART'])    : 'disable');

$dwdvm_custom4_interface  = trim(isset($dwdvm_cfg['CUSTOM4INTERFACE'])  ? htmlspecialchars($dwdvm_cfg['CUSTOM4INTERFACE'])  : 'noiface');
$dwdvm_custom4_mode       = trim(isset($dwdvm_cfg['CUSTOM4MODE'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM4MODE'])       : 'rx');
$dwdvm_custom4_time       = trim(isset($dwdvm_cfg['CUSTOM4TIME'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM4TIME'])       : 'h');
$dwdvm_custom4_limit      = trim(isset($dwdvm_cfg['CUSTOM4LIMIT'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM4LIMIT'])      : '-1');
$dwdvm_custom4_unit       = trim(isset($dwdvm_cfg['CUSTOM4UNIT'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM4UNIT'])       : 'GB');
$dwdvm_custom4_stop       = trim(isset($dwdvm_cfg['CUSTOM4STOP'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM4STOP'])       : 'disable');
$dwdvm_custom4_start      = trim(isset($dwdvm_cfg['CUSTOM4START'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM4START'])      : 'disable');
$dwdvm_custom4_vmstop     = trim(isset($dwdvm_cfg['CUSTOM4VMSTOP'])     ? htmlspecialchars($dwdvm_cfg['CUSTOM4VMSTOP'])     : 'disable');
$dwdvm_custom4_vmstart    = trim(isset($dwdvm_cfg['CUSTOM4VMSTART'])    ? htmlspecialchars($dwdvm_cfg['CUSTOM4VMSTART'])    : 'disable');

$dwdvm_custom5_interface  = trim(isset($dwdvm_cfg['CUSTOM5INTERFACE'])  ? htmlspecialchars($dwdvm_cfg['CUSTOM5INTERFACE'])  : 'noiface');
$dwdvm_custom5_mode       = trim(isset($dwdvm_cfg['CUSTOM5MODE'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM5MODE'])       : 'rx');
$dwdvm_custom5_time       = trim(isset($dwdvm_cfg['CUSTOM5TIME'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM5TIME'])       : 'h');
$dwdvm_custom5_limit      = trim(isset($dwdvm_cfg['CUSTOM5LIMIT'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM5LIMIT'])      : '-1');
$dwdvm_custom5_unit       = trim(isset($dwdvm_cfg['CUSTOM5UNIT'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM5UNIT'])       : 'GB');
$dwdvm_custom5_stop       = trim(isset($dwdvm_cfg['CUSTOM5STOP'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM5STOP'])       : 'disable');
$dwdvm_custom5_start      = trim(isset($dwdvm_cfg['CUSTOM5START'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM5START'])      : 'disable');
$dwdvm_custom5_vmstop     = trim(isset($dwdvm_cfg['CUSTOM5VMSTOP'])     ? htmlspecialchars($dwdvm_cfg['CUSTOM5VMSTOP'])     : 'disable');
$dwdvm_custom5_vmstart    = trim(isset($dwdvm_cfg['CUSTOM5VMSTART'])    ? htmlspecialchars($dwdvm_cfg['CUSTOM5VMSTART'])    : 'disable');

$dwdvm_custom6_interface  = trim(isset($dwdvm_cfg['CUSTOM6INTERFACE'])  ? htmlspecialchars($dwdvm_cfg['CUSTOM6INTERFACE'])  : 'noiface');
$dwdvm_custom6_mode       = trim(isset($dwdvm_cfg['CUSTOM6MODE'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM6MODE'])       : 'rx');
$dwdvm_custom6_time       = trim(isset($dwdvm_cfg['CUSTOM6TIME'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM6TIME'])       : 'h');
$dwdvm_custom6_limit      = trim(isset($dwdvm_cfg['CUSTOM6LIMIT'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM6LIMIT'])      : '-1');
$dwdvm_custom6_unit       = trim(isset($dwdvm_cfg['CUSTOM6UNIT'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM6UNIT'])       : 'GB');
$dwdvm_custom6_stop       = trim(isset($dwdvm_cfg['CUSTOM6STOP'])       ? htmlspecialchars($dwdvm_cfg['CUSTOM6STOP'])       : 'disable');
$dwdvm_custom6_start      = trim(isset($dwdvm_cfg['CUSTOM6START'])      ? htmlspecialchars($dwdvm_cfg['CUSTOM6START'])      : 'disable');
$dwdvm_custom6_vmstop     = trim(isset($dwdvm_cfg['CUSTOM6VMSTOP'])     ? htmlspecialchars($dwdvm_cfg['CUSTOM6VMSTOP'])     : 'disable');
$dwdvm_custom6_vmstart    = trim(isset($dwdvm_cfg['CUSTOM6VMSTART'])    ? htmlspecialchars($dwdvm_cfg['CUSTOM6VMSTART'])    : 'disable');


$dwdvm_running    = (intval(trim(shell_exec( "[ -f /proc/`cat /var/run/vnstat/vnstat.pid 2> /dev/null`/exe ] && echo 1 || echo 0 2> /dev/null" ))) === 1 );
$dwdvm_installed_backend = trim(htmlspecialchars(shell_exec("find /var/log/packages/ -type f -iname 'vnstat*' -printf '%f\n' 2> /dev/null")));
//$dwdvm_support_link = trim(shell_exec("plugin support /boot/config/plugins/dwdvm.plg"));

?>
