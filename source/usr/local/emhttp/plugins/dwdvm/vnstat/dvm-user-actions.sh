#!/bin/bash
#
# DO NOT TOUCH THIS FILE IF YOU DO NOT KNOW WHAT YOU ARE DOING
# ESPECIALLY DO NOT TOUCH THIS FILE IF YOU HAVE NEVER USED A LINUX TERMINAL
#
# This bash script defines functions which are executed together with DVM notifications
# It allows to easily execute custom commands whenever DVM sends a data limit notification
#
# The actions ONLY EXECUTE when the RESPECTIVE volume-based NOTIFICATION is also ENABLED
#
# REACHED actions only execute when EXCEEDED NOTIFICATIONS are ENABLED in the GUI
# NORMAL AGAIN actions only execute when RESET NOTIFICATIONS are ENABLED in the GUI
#
# These actions follow the same behavior as the notifications - execute ONCE when limit changes
# Advanced scripting can be done using DVM-CUSTOM-ALARMS.SH (independent from DVM notifications)
#
# DO NOT PUT ANY COMMANDS OR STATEMENTS OUTSIDE OF THE ALREADY DEFINED FUNCTIONS
# BEWARE THAT FUNCTIONS CANNOT BE EMPTY SO KEEP THE ECHO STATEMENT IN EACH FUNCTION
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
