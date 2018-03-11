<!DOCTYPE html>
<html lang="en">

<!--Copyright Jamie Scaife-->
<!--Legal Information at https://www.jamieweb.net/contact-->

<head>
    <title>Disabling TLSv1 and TLSv1.1</title>
    <meta name="description" content="Disabling older TLS protocol versions.">
    <meta name="keywords" content="Jamie, Scaife, jamie scaife, jamiescaife, jamieonubuntu, jamie90437, jamie90437x, jamieweb, jamieweb.net">
    <meta name="author" content="Jamie Scaife">
    <link href="jamie.css" rel="stylesheet">
    <link href="https://www.jamieweb.net/blog/disabling-tlsv1-tlsv1.1/" rel="canonical">
</head>

<body>

<?php include "navbar.php" ?>

<div class="body">
    <h1>Disabling TLSv1 and TLSv1.1</h1>
    <hr>
    <p><b>Sunday 11th March 2018</b></p>
    <p>I recently received a security report to my <a href="/contact" target="_blank">HackerOne program</a> by <a href="https://hackerone.com/retr0" target="_blank" rel="noopener">retr0</a>, who suggested that I disable TLSv1 on my web server.</p>
    <p>At first I was reluctant as this breaks compatibility with many older browsers, however after monitoring the TLS protocol versions in use by users, I've now disabled both TLSv1 and TLSv1.1, meaning that only TLSv1.2 can be used.</p>
    <p><b>Skip to Section:</b></p>
    <pre><b>Disabling TLSv1 and TLSv1.1</b>
&#x2523&#x2501&#x2501 <a href="#why">What is wrong with TLSv1 and TLSv1.1?</a>
&#x2523&#x2501&#x2501 <a href="#checking-support">Checking for TLSv1 and TLSv1.1 Support</a>
&#x2523&#x2501&#x2501 <a href="#browser-compatibility">Browser Compatibility</a>
&#x2523&#x2501&#x2501 <a href="#logs">Logging TLS Protocol Versions In Use</a>
&#x2523&#x2501&#x2501 <a href="#buying-namecoin">Disabling TLSv1 and TLSv1.1 in Apache</a>
&#x2517&#x2501&#x2501 <a href="#registering-domain">Conclusion</a></pre>

    <h2 id="why">What is wrong with TLSv1 and TLSv1.1?</h2>
    <p>These versions of the Transport Layer Security (TLS) protocol were first defined in January 1999 (<a href="https://www.ietf.org/rfc/rfc2246.txt" target="_blank">RFC2246</a>) and April 2006 (<a href="https://www.ietf.org/rfc/rfc4346.txt" target="_blank">RFC4346</a>) respectively, and since then various issues and vulnerabilities have been discovered.</p>
    <p>However, there are currently no major vulnerabilities affecting TLSv1 and TLSv1.1 when using the latest browsers and server-side implementations. The notable issues with these protocols are found in certain TLS implementations, rather than being fundamental protocol flaws. This means that simply updating your TLS implementation (eg: OpenSSL, GnuTLS) as well as your browser will fix the issues.</p>
    <p><a href="https://tools.ietf.org/html/rfc7457" target="_blank">RFC7457</a> provides summaries on some of these attacks, and there is an <a href="https://www.acunetix.com/blog/articles/tls-vulnerabilities-attacks-final-part/" target="_blank">article by Acunetix</a> that also provides some useful details.</p>

    <h2 id="checking-support">Checking for TLSv1 and TLSv1.1 Support</h2>
    <p>You can easily check whether your website supports TLSv1 and TLSv1.1 using <a href="https://nmap.org/" target="_blank">Nmap</a>. This is available in the defalt repositories on most Linux distributions, and is also available on BSD, macOS and Windows.</p>
    <p>Nmap has a built-in script to enumerate the available ciphers and protocols:</p>
    <pre>$ nmap -p 443 --script ssl-enum-ciphers jamieweb.net</pre>
    <p>This wil output all of the supported SSL/TLS protocol versions and ciphers.</p>
    <p>Alternatively, you can use the <a href="https://www.ssllabs.com/ssltest/index.html" target="_blank">Qualys SSLLabs Scanner</a>.</p>
</div>

<?php include "footer.php" ?>

</body>

</html>
