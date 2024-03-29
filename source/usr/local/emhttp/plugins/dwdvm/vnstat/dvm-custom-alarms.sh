#!/bin/bash
#
# DO NOT TOUCH THIS FILE IF YOU DO NOT KNOW WHAT YOU ARE DOING
# ESPECIALLY DO NOT TOUCH THIS FILE IF YOU HAVE NEVER USED A LINUX TERMINAL
#
# This bash script is for scripting custom data volume related alarm functions
# If not needed DVM-USER-ACTIONS.SH provides easier and more ready to use functions
#
# This bash script is run periodically as a cronjob when enabled in the GUI settings
# It operates independently from the user action settings and also DVM-USER-ACTIONS.SH
#
# Please keep the below line to ensure that the script is only executed when DVM is running:
if ! pgrep -x vnstatd >/dev/null 2>&1; then exit 0; fi
#
# This is a usage summary for the command through which the data volume limits could be queried:
#
# vnstat --config /etc/vnstat/vnstat.conf --alert <output> <exit> <type> <condition> <limit> <unit> <interface>
#
#  <output>
#     0 - no output
#     1 - always show output
#     2 - show output only if usage estimate exceeds limit
#     3 - show output only if limit is exceeded
#
#  <exit>
#     0 - always use exit status 0
#     1 - always use exit status 1
#     2 - use exit status 1 if usage estimate exceeds limit
#     3 - use exit status 1 if limit is exceeded
#
#  <type>
#     h, hour, hourly        d, day, daily        p, 95, 95%
#     m, month, monthly      y, year, yearly
#
#  <condition>
#     rx, tx, total, rx_estimate, tx_estimate, total_estimate
#
#  <limit>
#     greater than zero integer, no decimals
#
#  <unit> for <limit>
#     B, KiB, MiB, GiB, TiB, PiB, EiB
#     B, KB, MB, GB, TB, PB, EB
#
#  <unit> for <limit> when 95th percentile <type> is used
#     B/s, KiB/s, MiB/s, GiB/s, TiB/s, PiB/s, EiB/s                (IEC 1024^n)
#     B/s, kB/s, MB/s, GB/s, TB/s, PB/s, EB/s                      (SI  1000^n)
#     bit/s, Kibit/s, Mibit/s, Gibit/s, Tibit/s, Pibit/s, Eibit/s  (IEC 1024^n)
#     bit/s, kbit/s, Mbit/s, Gbit/s, Tbit/s, Pbit/s, Ebit/s        (SI  1000^n)
#
# EXAMPLE PERIODIC CHECKING USAGE IN THIS SCRIPT
#
# if ! vnstat --config /etc/vnstat/vnstat.conf --alert 0 3 d total 100 GB eth0 >/dev/null 2>&1; then
#   echo "Stopping Syncthing... (data limit has been exceeded)"
#   docker stop syncthing
# else
#   echo "Starting Syncthing... (data limit has not been exceeded)"
#   docker start syncthing
# fi
#
