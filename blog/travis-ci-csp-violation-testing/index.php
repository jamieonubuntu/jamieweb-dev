<!DOCTYPE html>
<html lang="en">

<!--Copyright Jamie Scaife-->
<!--Legal Information at https://www.jamieweb.net/contact-->

<head>
    <title>Automatically Testing Your Content Security Policy Using Travis-Ci and Headless Chrome Crawler</title>
    <meta name="description" content="desc">
    <meta name="keywords" content="Jamie, Scaife, jamie scaife, jamiescaife, jamieonubuntu, jamie90437, jamie90437x, jamieweb, jamieweb.net">
    <meta name="author" content="Jamie Scaife">
    <link href="/jamie.css" rel="stylesheet">
    <link href="https://www.jamieweb.net/blog/url/" rel="canonical">
</head>

<body>

<?php include "navbar.php" ?>

<div class="body">
    <h1>Automatically Testing Your Content Security Policy Using Travis-CI and Headless Chrome Crawler</h1>
    <hr>
    <p><b>Wednesday 25th July 2018</b></p>
    <p>I have recently put together a Travis-CI build environment that automatically tests your website for Content Security Policy violations. The configuration sets up a local copy of your site in the Travis-CI virtual machine with a CSP header set to send reports to a local endpoint. The site is then crawled using Headless Chrome Crawler, which causes CSP violation reports to be generated where required. These are then displayed at the end of the build log.</p>
    <p>If you want to give this a go, all of the required files and instructions are available in the GitHub repository: <a href="https://github.com/jamieweb/travis-ci_csp-tester" target="_blank" rel="noopener">https://github.com/jamieweb/travis-ci_csp-tester</a>
    <p><b>Skip to Section:</b></p>
    <pre><b>Automatically Testing Your CSP Using Travis-CI and Headless Chrome Crawler</b>
&#x2523&#x2501&#x2501 <a href="#travis-ci">What is Travis-CI?</a>
&#x2523&#x2501&#x2501 <a href="#headless-chrome-crawler">Headless Chrome Crawler</a>
&#x2523&#x2501&#x2501 <a href="#report-handler">CSP Violation Report Handler</a>
&#x2523&#x2501&#x2501 <a href="#apache-reverse-proxy">Apache Reverse Proxy</a>
&#x2523&#x2501&#x2501 <a href="#tunnels">Report-URI Integration</a>
&#x2517&#x2501&#x2501 <a href="#conclusion">Conclusion</a></pre>

    <h2 id="travis-ci">What is Travis-CI?</h2>
    <div class="display-flex">
        <div class="width-450">
            <p class="no-mar-top"><a href="https://travis-ci.org/" target="_blank" rel="noopener">Travis-CI</a> is an online continuous integration service for projects hosted on GitHub.</p>
            <p>It allows you to automatically test your code after every commit. These tests, known as 'builds', could be checking that your code compiles successfully, checking dependencies, checking compatibility, or in this case - checking a website for CSP violation reports.</p>
            <p>Each build uses its own virtual machine on Google Compute Engine, or a container on Amazon EC2. The GCE VMs provide you will full root access to a complete system, while the EC2 containers are faster to boot.</p>
            <p class="no-mar-bottom">Travis-CI is free for open-source projects.</p>
        </div>
        <div class="width-545 display-flex flex-align-center flex-justify-center">
            <img src="/travisci-full-colour.png" width="450px">
        </div>
    </div>
    <h2 id="headless-chrome-crawler">Headless Chrome Crawler</h2>
    <p><a href="https://github.com/yujiosaka/headless-chrome-crawler" target="_blank" rel="noopener">Headless Chrome Crawler</a> by <a href="https://github.com/yujiosaka" target="_blank" rel="noopener">yujiosaka</a> is a web crawler powered by Headless Chrome.</p>
    <img class="radius-8" src="/blog/testing-your-csp-using-travis-ci-and-headless-chrome-crawler/headless-chrome-crawler-github.png" width="1000px">
    <p>Headless Chrome is a feature of the Google Chrome/Chromium browsers that allow you to run them in a headless environment. This essentially means that you can utilise the full functionality of a desktop web browser, but in a command-line environment. It's essentially a 'no GUI' mode, and comes in extremely useful for automated testing, page rendering, etc.</p>
    <p>The reason that this comes in so useful for a crawler is that it allows the crawler to see the website as though it is an actual user using a proper desktop web browser. Many websites these days use frameworks such as AngularJS or React, which means that much of the content on the screen is actually written to the DOM using JavaScript, rather than simply being raw HTML. This means that using tools such as <code>curl</code> or <code>wget</code> to crawl websites will often result in an incomplete output, as the JavaScript has not run to populate the page with its content.</p>
</div>

<?php include "footer.php" ?>

</body>

</html>
