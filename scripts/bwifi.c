// Copyright (C) 2023, CWD Systems <cwdsystems-at-protonmail.ch>
// Code is part of CWD AP and not to be used, distributed without permission

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <pcap.h>
#include <arpa/inet.h>

#define MAX_PACKET_SIZE 65535
#define MAC_ADDR_LEN 6

void packet_handler(u_char *args, const struct pcap_pkthdr *header, const u_char *packet);

int main(int argc, char **argv) {
    char *device;
    char errbuf[PCAP_ERRBUF_SIZE];
    pcap_t *handle;
    struct bpf_program filter;
    bpf_u_int32 mask;
    bpf_u_int32 net;
    char filter_exp[] = "not ether src xx:xx:xx:xx:xx:xx";

    device = pcap_lookupdev(errbuf);
    if (device == NULL) {
        fprintf(stderr, "Error finding wireless device: %s\n", errbuf);
        exit(1);
    }
  
    if (pcap_lookupnet(device, &net, &mask, errbuf) == -1) {
        fprintf(stderr, "Error getting network info: %s\n", errbuf);
        exit(1);
    }

    handle = pcap_open_live(device, MAX_PACKET_SIZE, 1, 1000, errbuf);
    if (handle == NULL) {
        fprintf(stderr, "Error opening wireless device: %s\n", errbuf);
        exit(1);
    }

    if (pcap_compile(handle, &filter, filter_exp, 0, net) == -1) {
        fprintf(stderr, "Error compiling filter: %s\n", pcap_geterr(handle));
        exit(1);
    }
    if (pcap_setfilter(handle, &filter) == -1) {
        fprintf(stderr, "Error setting filter: %s\n", pcap_geterr(handle));
        exit(1);
    }

    // start capturing packets
    pcap_loop(handle, -1, packet_handler, NULL);

    pcap_freecode(&filter);
    pcap_close(handle);

    return 0;
}

void packet_handler(u_char *args, const struct pcap_pkthdr *header, const u_char *packet) {
    struct ether_header *eth_hdr;
    u_char *mac_addr;
    int i;

    eth_hdr = (struct ether_header*)packet;
    mac_addr = eth_hdr->ether_shost;

    if (memcmp(mac_addr, "\xXX\xXX\xXX\xXX\xXX\xXX", MAC_ADDR_LEN) == 0) {
        printf("Blocked packet from MAC address: ");
        for (i = 0; i < MAC_ADDR_LEN; i++) {
            printf("%02x:", mac_addr[i]);
        }
        printf("\n");
    }
}
