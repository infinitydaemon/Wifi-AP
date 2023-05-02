import os
import time

def get_connected_clients():
    cmd = 'iw dev wlan0 station dump | grep "Station" | awk \'{print $2}\''
    output = os.popen(cmd).read()
    return output.strip().split('\n')

while True:
    clients = get_connected_clients()
    print(f'Connected clients: {clients}')
    time.sleep(10)
