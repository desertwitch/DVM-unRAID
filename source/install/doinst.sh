#!/bin/bash
#
# Copyright Derek Macias (parts of code from NUT package)
# Copyright macester (parts of code from NUT package)
# Copyright gfjardim (parts of code from NUT package)
# Copyright SimonF (parts of code from NUT package)
# Copyright Mohamed Emad (icon from vnstat-client package)
# Copyright desertwitch
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License 2
# as published by the Free Software Foundation.
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
BOOT="/boot/config/plugins/dwdvm"
DOCROOT="/usr/local/emhttp/plugins/dwdvm"

# Update file permissions of scripts
chmod 755 $DOCROOT/scripts/*
chmod 755 /etc/rc.d/rc.vnstat
chmod 755 /usr/sbin/dvm-notify

# copy the default GUI configuration
cp -n $DOCROOT/default.cfg $BOOT/dwdvm.cfg >/dev/null 2>&1

# create DVM configuration directory
if [ ! -d /etc/vnstat ]; then
    mkdir /etc/vnstat
fi

# prepare conf backup directory on flash drive, if it does not already exist
if [ ! -d $BOOT/vnstat ]; then
    mkdir $BOOT/vnstat
fi

# copy default conf files to flash drive, if no backups exist there
cp -nr $DOCROOT/vnstat/* $BOOT/vnstat/ >/dev/null 2>&1

# copy conf files from flash drive to local system, for our services to use
cp -rf $BOOT/vnstat/* /etc/vnstat/ >/dev/null 2>&1

# link default configuration location to actual configuration location for binaries to use
if [ ! -L /etc/vnstat.conf ]; then
    ln -sf /etc/vnstat/vnstat.conf /etc/vnstat.conf
fi

# set up plugin-specific polling tasks
rm -f /etc/cron.daily/dvm-poller >/dev/null 2>&1
ln -sf /usr/local/emhttp/plugins/dwdvm/scripts/poller /etc/cron.daily/dvm-poller >/dev/null 2>&1
chmod +x /etc/cron.daily/dvm-poller >/dev/null 2>&1

# set up permissions
if [ -d /etc/vnstat ]; then
    echo "Updating permissions for DVM..."
    chown root:root /etc/vnstat
    chmod 755 /etc/vnstat
    chown root:root /etc/vnstat/*
    chmod 644 /etc/vnstat/*
    chmod 755 /etc/vnstat/dvm-custom-alarms.sh
    chmod 755 /etc/vnstat/dvm-user-actions.sh
fi
