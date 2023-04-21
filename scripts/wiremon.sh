#!/bin/bash

# Check stat if the WireGuard service is up and running

# Set the path to the wg command
WG_CMD=/usr/bin/wg

# Set the name of the WireGuard configuration file
WG_CONFIG=/etc/wireguard/wg0.conf

# Check if the wg command exists
if ! command -v $WG_CMD &> /dev/null
then
    echo "WireGuard command not found. Please install WireGuard."
    exit 1
fi

# Check if the WireGuard configuration file exists
if [ ! -f "$WG_CONFIG" ]; then
    echo "WireGuard configuration file not found."
    exit 1
fi

# Check the status of the VPN
VPN_STATUS=$($WG_CMD show wg0 | grep -q 'interface: wg0' && echo "UP" || echo "DOWN")

# Print the VPN status
echo "WireGuard VPN is $VPN_STATUS."
