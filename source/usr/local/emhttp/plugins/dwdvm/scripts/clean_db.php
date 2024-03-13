<?
require_once '/usr/local/emhttp/plugins/dwdvm/include/dwdvm_report.php';
$ifaces = getInterfaces(true);

if($ifaces) {
	foreach($ifaces as $iface) {
	echo("Checking... $iface" . "<br>");
	echo("Result: " . file_exists("/sys/class/net/$iface") . "<br>");
	}
}
else {
	echo "FAILED TO GET INTERFACES!";
}
?>
