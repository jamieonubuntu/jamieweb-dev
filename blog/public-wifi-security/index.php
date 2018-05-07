<!DOCTYPE html>
<html lang="en">

<!--Copyright Jamie Scaife-->
<!--Legal Information at https://www.jamieweb.net/contact-->

<head>
    <title>Using a Public Wi-Fi Hotspot Securely</title>
    <meta name="description" content="Connecting to hotel Wi-Fi using a Raspberry Pi and forwarding a VPN connection.">
    <meta name="keywords" content="Jamie, Scaife, jamie scaife, jamiescaife, jamieonubuntu, jamie90437, jamie90437x, jamieweb, jamieweb.net">
    <meta name="author" content="Jamie Scaife">
    <link href="jamie.css" rel="stylesheet">
    <link href="https://www.jamieweb.net/blog/public-wifi-security/" rel="canonical">
</head>

<body>

<?php include "navbar.php" ?>

<div class="body">
    <h1>Using a Public Wi-Fi Hotspot Securely</h1>
    <hr>
    <p><b>Monday 7th May 2018</b></p>
    <p><u>The problem:</u> You want to connect to the internet in a hotel or coffee shop, but don't want to expose your laptop to the insecure, unencrypted Wi-Fi network.</p>
    <p><u>A solution:</u> Connect to the internet through a Raspberry Pi, and have it forward a secure VPN connection through to your laptop.</p>
    <pre><b>Using a Public Wi-Fi Hotspot Securely</b>
&#x2523&#x2501&#x2501 <a href="#what-is-wrong-with-using-a-vpn">What is wrong with just using a VPN?</a>
&#x2523&#x2501&#x2501 <a href="#raspberry-pi-secure-connection">Using a Raspberry Pi to Connect Securely</a>
&#x2523&#x2501&#x2501 <a href="#raspberry-pi-setup">Initial Raspberry Pi Setup</a>
&#x2523&#x2501&#x2501 <a href="#vnc-ssh-tunnel">VNC SSH Tunnel</a>
&#x2523&#x2501&#x2501 <a href="#firewall-rules">Network Forwarding and Blocking</a>
&#x2523&#x2501&#x2501 <a href="#connecting-to-the-internet">Connecting to the Internet</a>
&#x2523&#x2501&#x2501 <a href="#potential-problems">Potential Problems With This Design</a>
&#x2517&#x2501&#x2501 <a href="#conclusion">Conclusion</a></pre>
    <h2 id="what-is-wrong-with-using-a-vpn">What is wrong with just using a VPN?</h2>
    <p>It is common advice to use a VPN when browsing the internet from a public Wi-Fi hotspot, however this is only effective to a certain extent:</p>
    <ul class="spaced-list">
        <li>Your device is still connected to the network, so everybody else can see it.</li>
        <li>You have to deal with the captive portal (login page), which is essentially a man-in-the-middle attack on your device. It can be challenging to prevent these from auto-opening too, meaning that you are essentially surrendering your device into opening an arbitrary (and potentially malicious) web page.</li>
        <li>VPN leaks can be difficult to avoid and often catch you out:</li>
            <ul>
                <li>DNS</li>
                <li>IPv6</li>
                <li>Unexpected Disconnect</li>
                <li>Initial Connection</li>
                <li>Background Services on some OS's that seem to refuse to use the VPN</li>
                <li>Routes for private IP ranges</li>
            </ul>
        </li>
    </ul>
    <h2 id="raspberry-pi-secure-connection">Using a Raspberry Pi to Connect Securely</h2>
    <p>A solution to this problem is to use a Raspberry Pi as a router, and have it forward through a connection to your external VPN.</p>
    <p>The Raspberry Pi will connect to the public Wi-Fi hotspot and deal with the captive portal. The Pi will then forward an internet connection through the ethernet interface, which has all traffic blocked except for the IP address of your external VPN.</p>
    <p>This means that your secure laptop never has to touch the insecure network, and it is not possible for VPN leaks to occur since all other traffic is blocked.</p>
    <p>Requirements:</p>
    <ul class="spaced-list">
        <li>Secure VPN accessible over the internet with a static IP (eg: OpenVPN on rented server)</li>
        <li>Raspberry Pi (any model)</li>
        <li>Wi-Fi adapter (if required)</li>
        <li>Ethernet cable</li>
        <li>SD card for Raspberry Pi</li>
    </ul>
    <p>You don't <i>have</i> to use a Raspberry Pi - any device can function in the same way. A Raspberry Pi is just convenient, cost effective and easily accessible.</p>
    <h2 id="raspberry-pi-setup">Initial Raspberry Pi Setup</h2>
    <p>This guide assumes that you already have basic Linux knowledge as well as a Raspberry Pi set up and working, and that you are able to connect to it over SSH or directly with a mouse and keyboard.</p>
    <h3>a. Ensure That SSH is Enabled at Boot</h3>
    <p>In order to ensure that the SSH server is set to start at boot, you can either place an empty file named <code>ssh</code> in the /boot directory:</p>
    <pre>$ sudo touch /boot/ssh</pre>
    <p>...followed by rebooting.</p>
    <p>Or use <code>raspi-config</code> to enable it:</p>
    <pre>$ sudo raspi-config</pre>
    <p>Navigate to <code>Interfacing Options</code> -> <code>SSH</code> and ensure that SSH is enabled:</p>
    <img class="radius-8" width="700px" src="rpi-images/raspi-config-interfaces.png">
    <h3>b. Configure UFW (Uncomplicated Firewall) and Fail2ban</h3>
    <p>Install <code>ufw</code> and <code>fail2ban</code> if they aren't already installed:</p>
    <pre>$ sudo apt-get install ufw fail2ban</pre>
    <p>Enable rate-limiting for SSH and enable the firewall:</p>
    <pre>$ sudo ufw limit 22/tcp
$ sudo ufw enable</pre>
    <p>Set a basic fail2ban config in order to block repeated failed SSH authentication attempts. Create the file <code>/etc/ssh/jail.local</code> and add the following content:</p>
    <pre>[DEFAULT]
bantime = 3600
findtime = 3600
maxretry = 3</pre>
    <h3>c. SSH Server Hardening</h3>
    <p>It is recommended to use <a href="https://help.ubuntu.com/community/SSH/OpenSSH/Keys" target="_blank" rel="noopener">SSH key authentication</a>, however if you wish to use password authentication, ensure that the password is strong.</p>
    <p>Use your favourite text editor (eg: <code>nano</code>) to edit the file <code>/etc/fail2ban/sshd_config</code>, and ensure that the following values are set:</p>
    <pre>PermitRootLogin no
X11Forwarding no
PermitTunnel yes</pre>
    <p>Also ensure that the <code>AcceptEnv LANG_LC*</code> value is commented out (put a # at the start of the line).</p>
    <p>If you are using SSH key authentcation, you should also set <code>PasswordAuthentication</code> to <code>no</code>.</p>
    <p>Additionally, you must set access rules in order to ensure that logins are only permitted from certain locations:</p>
    <pre>AllowUsers pi@&lt;your-local-ip-address&gt; pi@192.168.2.2</pre>
    <p>Substitute "&lt;your-local-ip-address&gt;" for the private IP address of your client device. This is the one that you can find from <code>ifconfig</code> (*nix) or <code>ipconfig</code> (Windows) - it most likely begins with "192.168.". Make sure that you use your private IPv4 address, as IPv6 will be disabled in order to help prevent VPN leaks.</p>
    <p>The 192.168.2.2 address is part of the subnet that will be created later in this guide. If this subnet is in use on your network, you may select another one and adjust your configuration according throughout the rest of the guide.</p>
    <h3>d. Disable IPv6</h3>
    <p>As much as <a href="/blog/ipv6-site-upgrade" target="_blank">I like IPv6</a>, in this particular use case, it provides unecessary complications and security risks. Public Wi-Fi hotspots rarely support IPv6 anyway, so it's not like you're missing out.</p>
    <p>Edit the file <code>/etc/sysctl.conf</code> and set the following values:</p>
    <pre>net.ipv6.conf.all.disable_ipv6 = 1
net.ipv6.conf.default.disable_ipv6 = 1
net.ipv6.conf.lo.disable_ipv6 = 1
net.ipv6.conf.wlan0.disable_ipv6 = 1
net.ipv6.conf.eth0.disable_ipv6 = 1</pre>

    <h2 id="vnc-ssh-tunnel">VNC SSH Tunnel</h2>
    <p>For this setup, VNC will be used in order to provide remote desktop functionality. Remote desktop is required so that you can view the captive portal and authenticate in order to access the public Wi-Fi hotspot.</p>
    <p>While it could be possible to handle the captive portal using a command-line browser such as <code>elinks</code>, many captive portals nowadays are unfortunately very JavaScript heavy and involve filling out forms, which elinks sometimes doesn't handle well.</p>
    <p>It is not safe to run a VNC server that is exposed to an untrusted network. In order to lock it down, SSH tunneling can be used. This will tunnel the insecure VNC connection through the secure SSH tunnel, meaning that the traffic will be encrypted and integrity checked.</p>
    <h3>a. Install TightVNC Server</h3>
    <p>Install TightVNC server on your Pi:</p>
    <pre>$ sudo apt-get instal tightvncserver</pre>
    <p>You can then start a VNC desktop bound only to localhost using the following command (adjust screen resolution as required):</p>
    <pre>$ vncserver :3 -geometry 1920x1080 -localhost</pre>
    <p>The <code>:3</code> refers to the ID of the virtual screen. If you use another number here, you'll have to adjust the port number for connections later on.</p>
    <p>The first time you start a VNC desktop, it will ask you to set a password. This password really does not matter, as it will not be used for authentication in this setup - the SSH tunnel handles this instead.</p>
    <h3>b. Configure the SSH Tunnel</h3>
    <p>On your client device, you can start an SSH tunnel connection to your Pi with the following command:</p>
    <pre>$ ssh -e none -x -L 5902:127.0.0.1:2903 pi@&lt;your-pi-ip-address&gt;</pre>
    <p>Syntax explanation:</p>
    <ul class="spaced-list">
        <li><b>-e none</b>: Disable the escape character, which prevents binary data (in this case, VNC) from accidentally closing the connection.</li>
        <li><b>-x</b>: Disable X11 forwarding - meaning that you can't view graphical applications through the connection. In this case, X11 forwarding is not required so it is disabled for security.</li>
        <li><b>-L 5902:127.0.0.1:5903</b>: This creates the tunnel. Connections to 127.0.0.1 (localhost) on port 5902 on the client will be forwarded through the tunnel to 127.0.0.1 port 5903 on the remote host. This means that connecting from your client to <code>localhost:5902</code> will connect you to the remote desktop running on port 5903. Port 5900+N is the default port for VNC, where N is the display number. For example, if you want to connect to display 4, then port 5904 is what you should use.</p>
    </ul>
    <p>For further details, please see the <a href="https://linux.die.net/man/1/ssh" target="_blank" rel="noopener">SSH manual page</a>.</p>
    <p>If you are using SSH key authentication, you can manually specify the location of the key using <code>-i</code> for example: <code>ssh -i ~/.ssh/pi</code>
    <h3>c. Connect to VNC</h3>
    <p>Now that the SSH tunnel is established, you can connect to the remote VNC desktop through it.</p>
    <p>Using your favourite VNC-compatible remote desktop client (eg: Remmina), simply connect to <code>localhost:5902</code>. You should be prompted for the VNC password and the remote desktop session will start.</p>
    <p><i>To clarify, connect from your client device to <code>localhost:5902</code>. The SSH tunnel that is running is listening for connections on this address, and it will forward them through the tunnel to the remote host (the Pi).</i></p>
    <img class="radius-8" width="700px" src="rpi-images/rpi-remote-desktop-vnc.png">

    <h2 id="firewall-rules">Network Forwarding and Blocking</h2>
    <p>Next, you must configure your Raspberry Pi to act as a router, and then to block all connections except for those out to your VPN.</p>
    <h3>a. Enable Native IPv4 Packet Forwarding</h3>
    <p>Edit the file <code>/etc/sysctl.conf</code> and ensure that the option <code>net.ipv4.ip_forward=1</code> is set. It is probably commented out by default - just remove the hash.</p>
    <p>This configuration will be applied at the next reboot, although you can also reload the configuration now using <code>sudo sysctl -p /etc/sysctl.conf</code>.</p>
    <p>If you forget to do this, your forwarded network connection will be extremely slow.</p>
    <h3>b. Configure Firewall Rules</h3>
    <p>Create a file named <code>forward.sh</code> (any name is fine), and insert the script shown below. This script will configure your Raspberry Pi to act as a router. All traffic between the Raspberry Pi and your laptop will also be blocked except for connections to itself and your external VPN.</p>
    <p>In this example, I have used the IPv4 address of this web server (139.162.222.67) as the external VPN address - you must substitute this with the IP of yours. You may also need to use different network interface names (<code>wlan0</code> and <code>eth0</code>). Check your interfaces using <code>ifconfig</code>.</p>
    <pre>#!/bin/bash
#Allow NAT
iptables -t nat -A POSTROUTING -o wlan0 -j MASQUERADE

#Block everything between Wi-Fi and Ethernet
iptables -I FORWARD -i wlan0 -o eth0 -j DROP
iptables -I FORWARD -i eth0 -o wlan0 -j DROP

#Allow VPN out
iptables -I FORWARD -o wlan0 -i eth0 -d 139.162.222.67 -j ACCEPT

#Allow VPN in
iptables -I FORWARD -i wlan0 -o eth0 -s 139.162.222.67 -m state --state RELATED,ESTABLISHED -j ACCEPT

#Allow client to Pi communication for SSH, etc
iptables -I INPUT -i eth0 -s 192.168.2.2 -d 192.168.2.1 -j ACCEPT

#Create 192.168.2.0/24 subnet
ifconfig eth0 192.168.2.1 netmask 255.255.255.0

#Delete default route for eth0
ip route del 0/0 dev eth0</pre>
    <p>Syntax explanation:</p>
    <ul class="spaced-list">
        <li><b>iptables</b>: A tool to modify the Linux kernel firewall.</li>
        <li><b>-t</b>: The table to modify, for example: <code>-t nat</code>. When no table is specified, the <code>filter</code> table is used, which contains the standard <code>INPUT</code>, <code>OUTPUT</code>, and <code>FORWARD</code> chains.</li>
        <li><b>-A</b>: Append the rule to the specified chain, for example <code>-A POSTROUTING</code>. The <code>POSTROUTING</code> chain is part of the <code>nat</code> table, and is used to alter NATted packets as they are about to go out (<i>post</i>-routing).</li>
        <li><b>-I</b>: Insert a rule at the top of the specified chain (meaning it will be applied first), for example <code>-A FORWARD</code>. The <code>FORWARD</code> chain is used to alter packets that are being forwarded, the <code>INPUT</code> chain is used for incoming packets, and the <code>OUTPUT</code> chain is used for outgoing packets.</li>
        <li><b>-o</b>: Match the name of the outgoing network interface (where the packet is going). <code>wlan0</code> is Wi-Fi, and <code>eth0</code> is ethernet, however you may have different interface names. You can see your interfaces using the <code>ifconfig</code> command.</li>
        <li><b>-i</b>: Match the name of the incoming network interface (where the packet arrived (where the packet arrived from).</li>
        <li><b>-s</b>: Match the source address of the packets.</li>
        <li><b>-d</b>: Match the destination address of the packets.</li>
        <li><b>-m</b>: Match using an extension module. For example <code>-m state --state RELATED,ESTABLISHED</code>, which will not match unsolicited connections.</li>
        <li><b>-j</b>: The action to take, for example: <code>ACCEPT</code>, <code>DROP</code>, <code>REJECT</code> and <code>MASQUERADE</code>. Packet masquerading is another term for many-to-one NAT, which is what most IPv4 home/office networks use.</li>
    </ul>
    <p>For further details, please see the <a href="https://linux.die.net/man/8/iptables" target="_blank" rel="noopener">iptables manual page</a>.</p>
    <p>Mark the script as executable:</p>
    <pre>$ chmod +x forward.sh</pre>
    <p>...and run the script once to apply it (or you can reboot):</p>
    <pre>$ sudo ./forward.sh</pre>
    <h3>c. Configure the Script to Run at Boot</h3>
    <p>Add this file to root's crontab (task scheduler config):</p>
    <pre>$ sudo crontab -e</pre>
    <p>Add the following line, setting the correct path and name for your file:</p>
    <pre>@reboot sleep 3 && /path/to/forward.sh</pre>
    <p>This will run the script at boot, ensuring that your rules and configurations are always applied.</p>

    <h2 id="connecting-to-the-internet">Connecting to the Internet</h2>
    <p>
</div>

<?php include "footer.php" ?>

</body>

</html>
