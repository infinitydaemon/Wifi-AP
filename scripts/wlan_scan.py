import subprocess

def scan_wireless_channels(interface):
    try:
        output = subprocess.check_output(["iwlist", interface, "scan"], stderr=subprocess.STDOUT)
        output = output.decode("utf-8")
        return output
    except subprocess.CalledProcessError as e:
        print(f"Error scanning wireless channels: {e.output.decode('utf-8')}")
        return None

interface = "wlan0"
scan_results = scan_wireless_channels(interface)

if scan_results:
    print(scan_results)
