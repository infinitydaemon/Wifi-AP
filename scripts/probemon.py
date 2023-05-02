from scapy.all import *
import time

def arp_display(pkt):
    if pkt[ARP].op == 1: #who-has (request)
        if pkt[ARP].psrc == '0.0.0.0': # ARP Probe
            print("ARP Probe: " + pkt[ARP].hwsrc)


def sniff_network():
    sniff(prn=arp_display, filter="arp", store=0)

while True:
    sniff_network()
    time.sleep(10) # wait 10 seconds before capturing again
