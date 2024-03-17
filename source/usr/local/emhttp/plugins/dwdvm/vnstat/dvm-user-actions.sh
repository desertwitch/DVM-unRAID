#!/bin/bash
#
# DO NOT TOUCH THIS FILE IF YOU DO NOT KNOW WHAT YOU ARE DOING
# ESPECIALLY DO NOT TOUCH THIS FILE IF YOU HAVE NEVER USED A LINUX TERMINAL
#
# This bash script defines functions which are executed when set DVM limits are triggered
# It allows to easily execute custom commands whenever DVM exceeds or resets such a data limit
#
# The actions ONLY EXECUTE when the RESPECTIVE volume-based USER ACTIONS are also ENABLED
#
# REACHED actions only execute when EXCEEDED USER ACTIONS are ENABLED in the GUI
# NORMAL AGAIN actions only execute when RESET USER ACTIONS are ENABLED in the GUI
#
# These actions follow the same behavior as the notifications - execute ONCE when limit changes
# Advanced scripting can be done using DVM-CUSTOM-ALARMS.SH (independent from DVM user actions)
#
# For any filtering needs, the following parameters are passed to this script by the service:
#
# $1: Alarm ID (0 = Primary Threshold Alarm / 1-6 Secondary Threshold Alarms)
# $2: Alarm Interface (Monitored Network Interface)
#
# DO NOT PUT ANY COMMANDS OR STATEMENTS OUTSIDE OF THE ALREADY DEFINED FUNCTIONS
# BEWARE THAT FUNCTIONS CANNOT BE EMPTY SO KEEP THE ECHO STATEMENT IN EACH FUNCTION
#
# ACTIONS FOR HOURLY VOLUME TRIGGERS
#
hour_rx_reached () {
    echo "[$1] DVM: hour_rx_reached user actions are now executing..."
    # Put commands to run when the PER HOUR threshold for INBOUND has been EXCEEDED inside this function


}
hour_rx_normal_again () {
    echo "[$1] DVM: hour_rx_normal_again user actions are now executing..."
    # Put commands to run when the PER HOUR threshold for INBOUND is back to NORMAL inside this function


}
hour_tx_reached () {
    echo "[$1] DVM: hour_tx_reached user actions are now executing..."
    # Put commands to run when the PER HOUR threshold for OUTBOUND has been EXCEEDED inside this function


}
hour_tx_normal_again () {
    echo "[$1] DVM: hour_tx_normal_again user actions are now executing..."
    # Put commands to run when the PER HOUR threshold for OUTBOUND is back to NORMAL inside this function


}
#
# ACTIONS FOR DAILY VOLUME TRIGGERS
#
day_rx_reached () {
    echo "[$1] DVM: day_rx_reached user actions are now executing..."
    # Put commands to run when the PER DAY threshold for INBOUND has been EXCEEDED inside this function


}
day_rx_normal_again () {
    echo "[$1] DVM: day_rx_normal_again user actions are now executing..."
    # Put commands to run when the PER DAY threshold for INBOUND is back to NORMAL inside this function


}
day_tx_reached () {
    echo "[$1] DVM: day_tx_reached user actions are now executing..."
    # Put commands to run when the PER DAY threshold for OUTBOUND has been EXCEEDED inside this function


}
day_tx_normal_again () {
    echo "[$1] DVM: day_tx_normal_again user actions are now executing..."
    # Put commands to run when the PER DAY threshold for OUTBOUND is back to NORMAL inside this function


}
#
# ACTIONS FOR MONTHLY VOLUME TRIGGERS
#
month_rx_reached () {
    echo "[$1] DVM: month_rx_reached user actions are now executing..."
    # Put commands to run when the PER MONTH threshold for INBOUND has been EXCEEDED inside this function


}
month_rx_normal_again () {
    echo "[$1] DVM: month_rx_normal_again user actions are now executing..."
    # Put commands to run when the PER MONTH threshold for INBOUND is back to NORMAL inside this function


}
month_tx_reached () {
    echo "[$1] DVM: month_tx_reached user actions are now executing..."
    # Put commands to run when the PER MONTH threshold for OUTBOUND has been EXCEEDED inside this function


}
month_tx_normal_again () {
    echo "[$1] DVM: month_tx_normal_again user actions are now executing..."
    # Put commands to run when the PER MONTH threshold for OUTBOUND is back to NORMAL inside this function


}
#
# ACTIONS FOR YEARLY VOLUME TRIGGERS
#
year_rx_reached () {
    echo "[$1] DVM: year_rx_reached user actions are now executing..."
    # Put commands to run when the PER YEAR threshold for INBOUND has been EXCEEDED inside this function


}
year_rx_normal_again () {
    echo "[$1] DVM: year_rx_normal_again user actions are now executing..."
    # Put commands to run when the PER YEAR threshold for INBOUND is back to NORMAL inside this function


}
year_tx_reached () {
    echo "[$1] DVM: year_tx_reached user actions are now executing..."
    # Put commands to run when the PER YEAR threshold for OUTBOUND has been EXCEEDED inside this function


}
year_tx_normal_again () {
    echo "[$1] DVM: year_tx_normal_again user actions are now executing..."
    # Put commands to run when the PER YEAR threshold for OUTBOUND is back to NORMAL inside this function


}
