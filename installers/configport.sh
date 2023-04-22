#!/bin/bash

# Exit on error
set -o errexit
# Exit on error inside functions
set -o errtrace
# Turn on traces, disabled by default
#set -o xtrace

server_port=
server_bind=
lighttpd_conf=
host=
restart_service=0
conf_change=0

while [[ $# -gt 0 ]]; do
    case $1 in
        -p|--port)
        server_port=$2
        shift
        shift
        ;;
        -b|--bind)
        server_bind=$2
        shift
        shift
        ;;
        -c|--config)
        lighttpd_conf=$2
        shift
        shift
        ;;
        -h|--host)
        host=$2
        shift
        shift
        ;;
        -r|--restart)
        restart_service=1
        shift
        ;;
        *)
        break
        ;;
    esac
done

if [ "$restart_service" = 1 ]; then
    echo "Restarting lighttpd in 3 seconds..."
    sleep 3
    systemctl restart lighttpd.service
fi

if [ -n "$server_port" ]; then
    echo "Changing lighttpd server.port to $server_port ..."
    sed -i "s/^\(server\.port *= *\)[0-9]*/\1$server_port/g" "$lighttpd_conf"
    echo "AP will now be available at port $server_port"
    conf_change=1
fi

if [ -n "$server_bind" ]; then
    echo "Changing lighttpd server.bind to $server_bind ..."
    if grep -q 'server.bind' "$lighttpd_conf"; then
        sed -i "s/^\(server\.bind.*= \)\".*\"*/\1\"$server_bind\"/g" "$lighttpd_conf"
    else
        printf "server.bind \t\t\t\t = \"$server_bind\"\n" >> "$lighttpd_conf"
    fi
    echo "AP will now be available at address $server_bind"
    conf_change=1
fi

if [ "$conf_change" == 1 ]; then
    echo "Restart lighttpd for new settings to take effect"
fi
