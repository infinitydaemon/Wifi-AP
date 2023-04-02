#!/bin/bash

# Set the SSH server credentials
server_address="your_server_address"
server_username="your_username"
server_password="your_password"

# Set the reverse SSH tunnel configuration
remote_port="remote_port_number"
local_port="local_port_number"

# Create the reverse SSH tunnel
sshpass -p "$server_password" ssh -o StrictHostKeyChecking=no -N -R $remote_port:localhost:$local_port $server_username@$server_address
