#!/bin/bash
#
# DO NOT TOUCH THIS FILE IF YOU HAVE NEVER USED A LINUX TERMINAL
# ESPECIALLY DO NOT TOUCH THIS FILE IF YOU DO NOT KNOW WHAT YOU ARE DOING
#
# THIS BASH SCRIPT IS FOR RUNNING BASH COMMANDS TOGETHER WITH THE VOLUME-BASED NOTIFICATIONS.
#
# These actions ONLY EXECUTE when AT LEAST ONE volume-based NOTIFICATION is ENABLED in GUI.
# These actions follow the same behavior as the notifications - execute once when limit changes.
# More advanced scripting can be done in the DVM-CUSTOM-ALARMS.SH which executes once per minute.
#
# DO NOT PUT ANY COMMANDS OUTSIDE OF THE ALREADY DEFINED FUNCTIONS, ONLY INSIDE OF THE FUNCTIONS.
#

#
# ACTIONS FOR HOURLY VOLUME TRIGGERS
#

hour_rx_reached () {
    echo "DVM user actions for hour_rx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER HOUR threshold for INBOUND has been EXCEEDED below this line

}

hour_rx_normal_again () {
    echo "DVM user actions for hour_rx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER HOUR threshold for INBOUND is back to NORMAL below this line

}

hour_tx_reached () {
    echo "DVM user actions for hour_tx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER HOUR threshold for OUTBOUND has been EXCEEDED below this line

}

hour_tx_normal_again () {
    echo "DVM user actions for hour_tx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER HOUR threshold for OUTBOUND is back to NORMAL below this line

}

#
# ACTIONS FOR DAILY VOLUME TRIGGERS
#

day_rx_reached () {
    echo "DVM user actions for day_rx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER DAY threshold for INBOUND has been EXCEEDED below this line

}

day_rx_normal_again () {
    echo "DVM user actions for day_rx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER DAY threshold for INBOUND is back to NORMAL below this line

}

day_tx_reached () {
    echo "DVM user actions for day_tx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER DAY threshold for OUTBOUND has been EXCEEDED below this line

}

day_tx_normal_again () {
    echo "DVM user actions for day_tx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER DAY threshold for OUTBOUND is back to NORMAL below this line

}

#
# ACTIONS FOR MONTHLY VOLUME TRIGGERS
#

month_rx_reached () {
    echo "DVM user actions for month_rx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER MONTH threshold for INBOUND has been EXCEEDED below this line

}

month_rx_normal_again () {
    echo "DVM user actions for month_rx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER MONTH threshold for INBOUND is back to NORMAL below this line

}

month_tx_reached () {
    echo "DVM user actions for month_tx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER MONTH threshold for OUTBOUND has been EXCEEDED below this line

}

month_tx_normal_again () {
    echo "DVM user actions for month_tx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER MONTH threshold for OUTBOUND is back to NORMAL below this line

}

#
# ACTIONS FOR YEARLY VOLUME TRIGGERS
#

year_rx_reached () {
    echo "DVM user actions for year_rx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER YEAR threshold for INBOUND has been EXCEEDED below this line

}

year_rx_normal_again () {
    echo "DVM user actions for year_rx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER YEAR threshold for INBOUND is back to NORMAL below this line

}

year_tx_reached () {
    echo "DVM user actions for year_tx_reached are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER YEAR threshold for OUTBOUND has been EXCEEDED below this line

}

year_tx_normal_again () {
    echo "DVM user actions for year_tx_normal_again are now executing..." | logger -t "dvm-action"
    # Put commands to run when the PER YEAR threshold for OUTBOUND is back to NORMAL below this line

}
