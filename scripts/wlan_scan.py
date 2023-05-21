# pip install pywifi matplotlib
import time
import matplotlib.pyplot as plt
import numpy as np
import pywifi

def scan_wireless_channels(interface):
    wifi = pywifi.PyWiFi()
    iface = wifi.interfaces()[interface]
    iface.scan()
    time.sleep(5)  # Wait for scan completion.

    networks = iface.scan_results()
    channels = set(network.channel for network in networks)

    return channels

def generate_graph(channels):
    channel_counts = [0] * 14  # Use 2.4Ghz bands
    for channel in channels:
        channel_counts[channel] += 1

    plt.bar(range(1, 15), channel_counts)
    plt.xlabel('Channel')
    plt.ylabel('Count')
    plt.title('Wireless Channel Distribution')
    plt.xticks(np.arange(1, 15))
    plt.show()

if __name__ == '__main__':
    wlan_interface = 0  # Default interface on the board

    channels = scan_wireless_channels(wlan_interface)
    generate_graph(channels)
