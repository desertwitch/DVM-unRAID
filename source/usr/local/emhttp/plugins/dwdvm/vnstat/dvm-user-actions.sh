#!/bin/bash
#
# DO NOT TOUCH THIS FILE IF YOU DO NOT KNOW WHAT YOU ARE DOING
# ESPECIALLY DO NOT TOUCH THIS FILE IF YOU HAVE NEVER USED A LINUX TERMINAL
#
# This bash script defines functions which are executed together with DVM notifications
# It allows to easily execute custom commands whenever DVM sends a data limit notification
#
# These actions ONLY EXECUTE when AT LEAST ONE volume-based NOTIFICATION is ENABLED in the GUI
# These actions follow the same behavior as the notifications - execute once when limit changes
# Advanced scripting can be done using DVM-CUSTOM-ALARMS.SH (independent from DVM notifications)
#
# DO NOT PUT ANY COMMANDS OUTSIDE OF THE ALREADY DEFINED FUNCTIONS
#
# ACTIONS FOR HOURLY VOLUME TRIGGERS
#
hour_rx_reached () {
    echo "DVM user actions for hour_rx_reached are now executing..."
    # Put commands to run when the PER HOUR threshold for INBOUND has been EXCEEDED inside this function


}
hour_rx_normal_again () {
    echo "DVM user actions for hour_rx_normal_again are now executing..."
    # Put commands to run when the PER HOUR threshold for INBOUND is back to NORMAL inside this function


}
hour_tx_reached () {
    echo "DVM user actions for hour_tx_reached are now executing..."
    # Put commands to run when the PER HOUR threshold for OUTBOUND has been EXCEEDED inside this function


}
hour_tx_normal_again () {
    echo "DVM user actions for hour_tx_normal_again are now executing..."
    # Put commands to run when the PER HOUR threshold for OUTBOUND is back to NORMAL inside this function


}
#
# ACTIONS FOR DAILY VOLUME TRIGGERS
#
day_rx_reached () {
    echo "DVM user actions for day_rx_reached are now executing..."
    # Put commands to run when the PER DAY threshold for INBOUND has been EXCEEDED inside this function


}
day_rx_normal_again () {
    echo "DVM user actions for day_rx_normal_again are now executing..."
    # Put commands to run when the PER DAY threshold for INBOUND is back to NORMAL inside this function


}
day_tx_reached () {
    echo "DVM user actions for day_tx_reached are now executing..."
    # Put commands to run when the PER DAY threshold for OUTBOUND has been EXCEEDED inside this function


}
day_tx_normal_again () {
    echo "DVM user actions for day_tx_normal_again are now executing..."
    # Put commands to run when the PER DAY threshold for OUTBOUND is back to NORMAL inside this function


}
#
# ACTIONS FOR MONTHLY VOLUME TRIGGERS
#
month_rx_reached () {
    echo "DVM user actions for month_rx_reached are now executing..."
    # Put commands to run when the PER MONTH threshold for INBOUND has been EXCEEDED inside this function


}
month_rx_normal_again () {
    echo "DVM user actions for month_rx_normal_again are now executing..."
    # Put commands to run when the PER MONTH threshold for INBOUND is back to NORMAL inside this function


}
month_tx_reached () {
    echo "DVM user actions for month_tx_reached are now executing..."
    # Put commands to run when the PER MONTH threshold for OUTBOUND has been EXCEEDED inside this function


}
month_tx_normal_again () {
    echo "DVM user actions for month_tx_normal_again are now executing..."
    # Put commands to run when the PER MONTH threshold for OUTBOUND is back to NORMAL inside this function


}
#
# ACTIONS FOR YEARLY VOLUME TRIGGERS
#
year_rx_reached () {
    echo "DVM user actions for year_rx_reached are now executing..."
    # Put commands to run when the PER YEAR threshold for INBOUND has been EXCEEDED inside this function


}
year_rx_normal_again () {
    echo "DVM user actions for year_rx_normal_again are now executing..."
    # Put commands to run when the PER YEAR threshold for INBOUND is back to NORMAL inside this function


}
year_tx_reached () {
    echo "DVM user actions for year_tx_reached are now executing..."
    # Put commands to run when the PER YEAR threshold for OUTBOUND has been EXCEEDED inside this function


}
year_tx_normal_again () {
    echo "DVM user actions for year_tx_normal_again are now executing..."
    # Put commands to run when the PER YEAR threshold for OUTBOUND is back to NORMAL inside this function


}
