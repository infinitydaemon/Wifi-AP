 <p align="center">
 <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://cwd.systems/img/cwd-ap.png">
    <img src="https://cwd.systems/img/cwd-ap.png"  alt="CWD AP">
  </picture>
  </p>
  <br>
  
  ```python
class CWD_AP():
    
  def __init__(self):
    self.name = "cwd";
    self.username = "cwdsystems";
    self.location = "KyrgzRepublic";
    self.protonmail = "@cwdsystems";
    self.web = "https://cwd.systems";
    self.languages ="JS,PHP,HTML,C";
  
  def __str__(self):
    return self.name

if __name__ == '__main__':
    me = CWD_AP()
```
  
Telco grade secured Wi-Fi router with 1000 + wireless user handling on a single node. Its able to act as a full blown wireless router, client, forwarder, VPN client or wireless relay. Best suited for large enterprises, universities, exhibitions, media companies or organizations that require fool proof anti man in the middle attack operation and 99.99999% appliance uptime.. The kernel + OS is tailored for end-user security with a minimal usable RASPAP web-ui. Mainline OpSec Kernel (https://github.com/infinitydaemon/OpSec-Kernel) is used as base. Out of the box setup ready to connect with CWD GATE for Wireguard VPN access.

To provide wireless access for 1022 wireless users for Class A, you need to use a CIDR Notation of /22 i.e 10.3.1.1/22. 

```
IP Address:	10.3.1.1
Network Address:	10.3.0.0
Usable Host IP Range:	10.3.0.1 - 10.3.3.254
Broadcast Address:	10.3.3.255
Total Number of Hosts:	1,024
Number of Usable Hosts:	1,022
Subnet Mask:	255.255.252.0
Wildcard Mask:	0.0.3.255
Binary Subnet Mask:	11111111.11111111.11111100.00000000
IP Class:	B
CIDR Notation:	/22
IP Type:	Private
```

Similarly, for 2046 wireless users on Class A network, you have to use a CIDR Notation of /21 i.e 10.3.1.1/21.

```
P Address:	10.3.1.1
Network Address:	10.3.0.0
Usable Host IP Range:	10.3.0.1 - 10.3.7.254
Broadcast Address:	10.3.7.255
Total Number of Hosts:	2,048
Number of Usable Hosts:	2,046
Subnet Mask:	255.255.248.0
Wildcard Mask:	0.0.7.255
Binary Subnet Mask:	11111111.11111111.11111000.00000000
IP Class:	B
CIDR Notation:	/21
IP Type:	Private
```

In order to keep buffer bloat balanced, it is advised to keep number of wireless users under 250 per access point. 
