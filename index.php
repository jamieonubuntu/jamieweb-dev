<!DOCTYPE html>
<html lang="en">

<!--Copyright Jamie Scaife-->
<!--Legal Information at https://www.jamieweb.net/contact-->

<head>
    <title>JamieWeb</title>
    <meta name="description" content="Website of Jamie Scaife - United Kingdom">
    <meta name="keywords" content="Jamie, Scaife, jamie scaife, jamiescaife, jamieonubuntu, jamie90437, jamie90437x, jamieweb, jamieweb.net">
    <meta name="author" content="Jamie Scaife">
    <link href="/jamie.css" rel="stylesheet">
    <link href="https://www.jamieweb.net/" rel="canonical">
</head>

<body>

<?php include "navbar.php"; ?>

<div class="body">
    <div class="content redlink">
        <h1>Jamie Scaife - United Kingdom</h1>
        <hr>

        <h1 class="no-mar-bottom">About</h1>
        <h3 class="no-mar-bottom intro-mar-top">No Ads, No Tracking, No JavaScript</h3>
        <p class="two-mar-top">This website does not have any adverts, tracking or other internet annoyances.<br/>It's also 100% JavaScript free.</p>
        <h3 class="no-mar-bottom">Tor Hidden Services</h3>
        <p class="two-mar-top">This site is available through Tor at:</p>
        <ul>
            <li><p class="onionlink">Onion v2: <a class="two-mar-left" href="http://jamiewebgbelqfno.onion" target="_blank" rel="noopener">jamiewebgbelqfno.onion</a></p></li>
            <li><p class="onionlink">Onion v3: <a class="two-mar-left" href="http://jamie3vkiwibfiwucd6vxijskbhpjdyajmzeor4mc4i7yopvpo4p7cyd.onion" target="_blank" rel="noopener">jamie3vkiwibfiwucd6vxijskbhpjdyajmzeor4mc4i7yopvpo4p7cyd.onion</a></p></li>
        </ul>
        <hr>

        <h1 class="no-mar-bottom">Featured Content</h1>
        <div class="featured">
            <a href="/projects/computing-stats/">
                <h3 class="no-mar-bottom">Raspberry Pi + BOINC Stats</h3>
                <p class="two-no-mar"><b>Stats from my RPi cluster + BOINC.</b></p>
                <p class="two-mar-top">Updated Every 10 Minutes</p>
            </a>
        </div>
        <div class="featured">
            <a href="/projects/block-exploitable-content/">
                <h3 class="no-mar-bottom">Exploitable Web Content</h3>
                <p class="two-no-mar"><b>Test whether exploitable web content is blocked in your web browser.</b></p>
            </a>
        </div>
        <div class="featured">
            <a href="/blog/namecoin-bit-domain/">
                <h3 class="no-mar-bottom">Namecoin .bit Domain</h3>
                <p class="two-no-mar"><b>Guide to registering a Namecoin .bit domain.</b></p>
                <p class="two-mar-top">Tuesday 16th January 2017</p>
            </a>
        </div>
        <div class="featured">
            <a href="/blog/onionv3-hidden-service/">
                <h3 class="no-mar-bottom">Tor Onion v3 Hidden Service</h3>
                <p class="two-no-mar"><b>Testing the new Onion v3 Hidden Services.</b></p>
                <p class="two-mar-top">Saturday 21st October 2017</p>
            </a>
        </div>
        <hr>
        <div class="recent-posts">
<?php bloglist("home"); ?>
        </div>
    </div>

    <div class="sidebar">
        <input type="radio" class="gravityradio" id="identicon">
        <label class="gravitylabel" for="identicon"></label>
        <!--Thanks to jdenticon.com for the identicon image generation!-->
        <!--My identicon seed is the sha256 hash of the plain text word "JamieOnUbuntu".-->
        <hr>
        <div class="centertext sideitems">
            <div class="sideitem">
                <a href="https://github.com/jamieweb" target="_blank" rel="noopener"><img src="/images/fontawesome/github.svg"></a>
                <h4><a href="https://github.com/jamieweb" target="_blank" rel="noopener">GitHub</a></h4>
            </div>
            <div class="sideitem">
                <a href="https://twitter.com/jamieweb" target="_blank" rel="noopener"><img src="/images/fontawesome/tw.svg"></a>
                <h4><a href="https://twitter.com/jamieweb" target="_blank" rel="noopener">Twitter</a></h4>
            </div>
            <div class="sideitem">
                <a href="https://www.youtube.com/jamie90437x" target="_blank" rel="noopener"><img src="/images/fontawesome/yt.svg"></a>
                <h4><a href="https://www.youtube.com/jamie90437x" target="_blank" rel="noopener">YouTube</a></h4>
            </div>
            <div class="sideitem">
                <a href="https://keybase.io/jamieweb" target="_blank" rel="noopener"><img src="/images/fontawesome/id-card.svg"></a>
                <h4><a href="https://keybase.io/jamieweb" target="_blank" rel="noopener">Keybase</a></h4>
            </div>
            <div class="sideitem">
                <a href="https://hackerone.com/jamieweb" target="_blank" rel="noopener"><img class="h1" src="/images/hackerone.png"></a>
                <h4><a href="https://hackerone.com/jamieweb" target="_blank" rel="noopener">HackerOne</a></h4>
            </div>
            <div class="sideitem">
                <a href="/rss.xml" target="_blank"><img src="/images/fontawesome/rss.svg"></a>
                <h4><a href="/rss.xml" target="_blank">RSS</a></h4>
            </div>
        </div>
        <hr>
        <h2 class="centertext">Recent Posts</h2>
        <div class="redlink tops">
            <a href="/blog/launching-a-public-hackerone-program/">
                <h4 class="no-mar-bottom">Launching a Public HackerOne Security Vulnerability Disclosure Program</h4>
                <h5 class="two-no-mar">A write-up of launching my HackerOne program.</h5>
                <h5 class="two-mar-top">Friday 11th May 2018</h5>
            </a>
            <a href="/blog/secure-public-wifi-access/">
                <h4 class="no-mar-bottom">Using a Public Wi-Fi Hotspot Securely</h4>
                <h5 class="two-no-mar">Connecting to hotel Wi-Fi through a Raspberry Pi and VPN.</h5>
                <h5 class="two-mar-top">Tuesday 8th May 2018</h5>
            </a>
            <a href="/blog/letsencrypt-scts-in-certificates/">
                <h4 class="no-mar-bottom">Let's Encrypt SCTs in Certificates</h4>
                <h5 class="two-no-mar">LE certificates now have embedded SCTs by default.</h5>
                <h5 class="two-mar-top">Wednesday 4th April 2018</h5>
            </a>
            <a href="/blog/disabling-tls1.0-tls1.1/">
                <h4 class="no-mar-bottom">Disabling TLS 1.0 and TLS 1.1</h4>
                <h5 class="two-no-mar">Assessing browser compatibility and disabling older TLS protocol versions.</h5>
                <h5 class="two-mar-top">Tuesday 13th March 2018</h5>
            </a>
        </div>
    </div>
</div>

<?php include "footer.php" ?>

</body>

</html>
