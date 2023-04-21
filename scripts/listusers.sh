#!/bin/bash

# Set the SSH server credentials
server_address="cwd-gate-ip"
server_username="username"
server_password="password"

# Connect to the GATE and get the wireguard user stats
sshpass -p "$server_password" ssh -o StrictHostKeyChecking=no $server_username@$server_address pivpn -c
