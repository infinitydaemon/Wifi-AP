import time
import syslog

# Write some test messages to the kernel log
syslog.syslog("Testing kernel log - message 1")
syslog.syslog("Testing kernel log - message 2")
syslog.syslog("Testing kernel log - message 3")

# Wait for a few seconds to allow the messages to be written to the log
time.sleep(5)

# Read the kernel log and print the last 10 messages
with open('/var/log/kern.log') as f:
    lines = f.readlines()
    last_lines = lines[-10:]
    for line in last_lines:
        print(line.strip())
