import subprocess

def connect_to_internet(dial_number, apn, username, password):
    try:
        subprocess.run(['sudo', 'apt-get', 'install', '-y', 'ppp'], check=True)
        subprocess.run(['sudo', 'apt-get', 'install', '-y', 'usb-modeswitch'], check=True)

        config = f'''/etc/chatscripts/gprs
        ABORT BUSY
        ABORT "NO CARRIER"
        ABORT ERROR
        TIMEOUT 10
        "" "ATZ"
        OK "AT+CGDCONT=1,\\\"IP\\\",\\\"{apn}\\\""
        OK "ATD{dial_number}"
        CONNECT'''

        ppp_options = f'''/etc/ppp/peers/gprs
        noauth
        defaultroute
        persist
        usepeerdns
        /dev/ttyUSB0
        115200
        connect 'chat -s -v -f /etc/chatscripts/gprs' 
        user "{username}"
        password "{password}"'''

        with open('/etc/chatscripts/gprs', 'w') as f:
            f.write(config)

        with open('/etc/ppp/peers/gprs', 'w') as f:
            f.write(ppp_options)

        subprocess.run(['sudo', 'chmod', '600', '/etc/ppp/peers/gprs'], check=True)
        subprocess.run(['sudo', 'pon', 'gprs'], check=True)
        print("Connected to the internet using the 4G dongle.")
    except subprocess.CalledProcessError as e:
        print(f"An error occurred while connecting to the internet: {str(e)}")

if __name__ == '__main__':
    dial_number = '*99#'
    apn = 'your_apn'
    username = 'your_username'
    password = 'your_password'

    connect_to_internet(dial_number, apn, username, password)
