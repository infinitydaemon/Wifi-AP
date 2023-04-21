// Copyright (C) 2023, CWD Systems <cwdsystems-at-protonmail.ch>
// Monitor , block and re-monitor intrusion attemps in NodeJS

const { exec } = require("child_process");

const startPort = 1;
const endPort = 65535;
const threshold = 50;
const monitorDuration = 30000; // in milliseconds
const blockCommand = (ip) => `iptables -A INPUT -s ${ip} -j DROP`;
const unblockCommand = (ip) => `iptables -D INPUT -s ${ip} -j DROP`;
const connections = {};

const monitor = () => {
  console.log("Monitoring ports...");
  exec(`netstat -an | grep -E '^(tcp|udp).*:${startPort}-${endPort}'`, (err, stdout) => {
    if (err) {
      console.error(err);
      return;
    }

    const lines = stdout.split("\n");
    lines.forEach((line) => {
      const parts = line.split(/\s+/);
      if (parts.length < 5) {
        return;
      }

      const localAddress = parts[3];
      const foreignAddress = parts[4];

      if (!foreignAddress) {
        return;
      }

      const [ip, port] = foreignAddress.split(":");
      if (!ip || !port) {
        return;
      }

      if (connections[ip]) {
        connections[ip] += 1;
      } else {
        connections[ip] = 1;
      }

      if (connections[ip] >= threshold) {
        console.log(`Blocking ${ip}...`);
        exec(blockCommand(ip), (err) => {
          if (err) {
            console.error(err);
          }
        });
      }
    });
  });
};

const unblockAll = () => {
  console.log("Unblocking all IPs...");
  exec("iptables -F", (err) => {
    if (err) {
      console.error(err);
    }
  });
};

setTimeout(() => {
  clearInterval(monitorInterval);
  unblockAll();
}, monitorDuration);

monitor();
const monitorInterval = setInterval(monitor, 1000);
