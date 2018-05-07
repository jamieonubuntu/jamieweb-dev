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
&#x2523&#x2501&#x2501 <a href="#">VNC SSH Tunnel</a>
&#x2523&#x2501&#x2501 <a href="#">Network Forwarding and Blocking</a>
&#x2523&#x2501&#x2501 <a href="#">Connecting to the Internet</a>
&#x2523&#x2501&#x2501 <a href="#">Potential Problems With This Design</a>
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
    <pre>$ vncserver :8 -geometry 1920x1080 -localhost</pre>
    <p>The first time you start a VNC desktop, it will ask you to set a password. This password really does not matter, as it will not be used for authentication in this setup - the SSH tunnel handles this instead.</p>
    <h3>b. Configure the SSH Tunnel</h3>
    <p>On your client device, you can start an SSH tunnel connection to your Pi with the following command:</p>
    <pre>$ ssh -e none -x -L 5902:127.0.0.1:2908 pi@&lt;your-pi-ip-address&gt;</pre>
    <p>Syntax explanation:</p>
    <ul class="spaced-list">
        <li><b>-e none</b>: Disable the escape character, which prevents binary data (in this case, VNC) from accidentally closing the connection.</li>
        <li><b>-x</b>: Disable X11 forwarding - meaning that you can't view graphical applications through the connection. In this case, X11 forward is not required so it is disabled for security.</li>
        <li><b>-L 5902:127.0.0.1:2803</b>: This creates the tunnel. Connections to 127.0.0.1 (localhost) on port 5902 on the client will be forwarded through the tunnel to 127.0.0.1 port 2803 on the remote host. This means that connecting on your client to <code>vnc://localhost:5902</code> will connect you to the remote desktop running on port 5903. Port 5900+N is the default port for VNC, where N is the display number.</p>

</div>

<?php include "footer.php" ?>

</body>

</html>
