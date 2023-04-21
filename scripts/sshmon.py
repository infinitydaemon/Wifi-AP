# Copyright (C) 2023, CWD Systems <cwdsystems-at-pprotonmail.ch>

#!/usr/bin/env python3

import re
import subprocess

BAN_TIME = 600  
MAX_ATTEMPTS = 3 

def get_ssh_logs():
    """
    Gets the SSH logs and returns the lines containing authentication failures
    """
    cmd = "journalctl -u sshd -o cat | grep 'Failed password'"
    output = subprocess.check_output(cmd, shell=True, text=True)
    return output.strip().split("\n")

def get_failed_attempts(log_lines):
    """
    Parses the SSH log lines and returns the number of failed attempts per IP address
    """
    attempts = {}
    for line in log_lines:
        ip_match = re.search(r"from (\d+\.\d+\.\d+\.\d+)", line)
        if ip_match:
            ip = ip_match.group(1)
            attempts[ip] = attempts.get(ip, 0) + 1
    return attempts

def block_ip(ip):
    """
    Blocks the given IP address using iptables
    """
    subprocess.run(f"iptables -I INPUT -s {ip} -j DROP", shell=True)

def unblock_ip(ip):
    """
    Removes the block for the given IP address using iptables
    """
    subprocess.run(f"iptables -D INPUT -s {ip} -j DROP", shell=True)

def main():
    while True:
        log_lines = get_ssh_logs()
        attempts = get_failed_attempts(log_lines)
        for ip, count in attempts.items():
            if count >= MAX_ATTEMPTS:
                print(f"Blocking {ip} for {BAN_TIME} seconds")
                block_ip(ip)

        cmd = "iptables -L INPUT --line-numbers | grep DROP | grep ssh | awk '{print $1}'"
        output = subprocess.check_output(cmd, shell=True, text=True)
        for line_number in output.strip().split("\n"):
            rule = f"IPTABLES -D INPUT {line_number}"
            ip = subprocess.check_output(rule, shell=True, text=True).strip().split(" ")[3]
            cmd = f"iptables -L INPUT -n | grep {ip} | grep -q 'state NEW tcp dpt:ssh'"
            try:
                subprocess.check_output(cmd, shell=True, text=True)
            except subprocess.CalledProcessError:
                print(f"Unblocking {ip}")
                unblock_ip(ip)

        time.sleep(60)

if __name__ == "__main__":
    main()
