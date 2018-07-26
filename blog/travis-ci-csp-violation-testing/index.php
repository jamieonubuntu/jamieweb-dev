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
    <img class="radius-8" src="/blog/testing-your-csp-using-travis-ci-and-headless-chrome-crawler/example-output.png" width="1000px">
    <p class="two-no-mar centertext"><i>An example output showing a report for an image that was blocked by the Content Security Policy.</i></p>
    <p>If you want to give this a go, all of the required files and instructions are available in the GitHub repository: <a href="https://github.com/jamieweb/travis-ci_csp-tester" target="_blank" rel="noopener">https://github.com/jamieweb/travis-ci_csp-tester</a>
    <p><b>Skip to Section:</b></p>
    <pre><b>Automatically Testing Your CSP Using Travis-CI and Headless Chrome Crawler</b>
&#x2523&#x2501&#x2501 <a href="#travis-ci">What is Travis-CI?</a>
&#x2523&#x2501&#x2501 <a href="#headless-chrome-crawler">Headless Chrome Crawler</a>
&#x2523&#x2501&#x2501 <a href="#apache-reverse-proxy">Apache Reverse Proxy</a>
&#x2523&#x2501&#x2501 <a href="#report-handler">CSP Violation Report Handler</a>
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
    <p>One of the other reasons for me using Headless Chrome Crawler for this project is that Headless Chrome will send Content Security Policy violation reports, which are exaclty what is needed to help check the compatibility of a CSP properly.</p>

    <h2 id="apache-reverse-proxy">Apache Reverse Proxy</h2>
    <p>In this build environment, the PHP built-in development server (<code>php -S</code>) is used to host the local website.</p>
    <p>The reason for using this and not just raw Apache is that it is not easy to get the latest version of mod_php set up and running in the Travis-CI environment. <a href="https://docs.travis-ci.com/user/languages/php/#apache--php" target="_blank" rel="noopener">According to the Travis-CI documentation</a>, mod_php is not officially supported and you should use php_fpm instead.</p>
    <p>In order to avoid these possible complications, the PHP built-in development server works fine. However, with this web server it is not possible to set headers on a global basis - it's only possible to set them in the code itself using the PHP <code>header()</code> function.</p>
    <p>In order to get around this, I am using Apache as a reverse proxy for the PHP built-in server. The Apache server can set the Content-Security-Policy header, as well as proxy all of the traffic relatively seamlessly. The configuration is pretty simple too:</p>
    <pre>ProxyPass "/" "http://localhost:8080/"
ProxyPassReverse "/" "http://localhost:8080/"</pre>
    <p>For a website using a content management system or framework, this solution probably wouldn't work, as the site depends on many web server configurations and other factors that the PHP built-in server cannot handle. I think that this Travis-CI build environment configuration could be easily modified in order to handle a website like this if required.</p>
    <p>Another alternative would be to not host a local copy of the website at all, and just scrape the real one or a development/staging version that is accessible over the internet.</p>

    <h2 id="report-handler">CSP Violation Report Handler</h2>
    <p>In order to receive the locally generated CSP violation reports, there needs to be a report handler somewhere on the server.</p>
    <p>During a build, the raw JSON reports are handled by report-uri.php. This file is not actually included in the repository by itself, instead it is created by a command in the <a href="https://github.com/jamieweb/travis-ci_csp-tester/blob/master/.travis.yml" target="_blank" rel="noopener">.travis.yml</a> file:</p>
    <pre class="pre-wrap-text">printf "&lt;?php \$report = json_decode(filter_var(file_get_contents('php://input'), FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)); if(json_last_error() !== JSON_ERROR_NONE) { exit(); } elseif((in_array(\$report-&gt;{'csp-report'}-&gt;{'blocked-uri'}, array_map('trim', file('blocked-uri-exclusions.txt')))) || (in_array(\$report-&gt;{'csp-report'}-&gt;{'document-uri'}, array_map('trim', file('document-uri-exclusions.txt'))))) { exit(); } else { file_put_contents('csp-reports.txt', json_encode(\$report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . \"\\\\n\\\\n\", FILE_APPEND); } ?&gt;" &gt; report-uri.php</pre>
    <p>The reason that this file is generated using <code>printf</code> and not just included in the repository is that the code is not suitable for internet-facing use. Because of this, I don't want it to be accidentally exposed to the internet on someone's web server.</p>
    <p>This is because the program is designed to output to a file that is then printed to the shell at the end of the build. Attacks such as cross-site scripting (XSS) are not possible here as the output is never actually parsed by a web browser. PHP functions such as <code>escapeshellcmd()</code> are also no use here as the output is not being used to construct a shell command.</p>
    <p>The only real risk is in actually displaying the untrusted data, as explained <a href="https://security.stackexchange.com/questions/56307/can-cat-ing-a-file-be-a-potential-security-risk" target="_blank" rel="noopener">here</a>. In order to help mitigate this risk, I have used <code>filter_var</code> with the flags <code>FILTER_FLAG_STRIP_LOW</code> and <code>FILTER_FLAG_STRIP_HIGH</code>, which will attempt to strip out characters with an ASCII value &lt;32 and &gt;127. Even if a successful attack were to take place, this code is running on a remote virtual machine/container that was specifcally created for your build - the VM/container will be destroyed as soon as the build is finished.</p>
    <p>If you pretty-print the code, it looks like the following:</p>
    <pre>&lt;?php $report = json_decode(filter_var(file_get_contents('php://input'), FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
if(json_last_error() !== JSON_ERROR_NONE) {
    exit();
} elseif((in_array($report-&gt;{'csp-report'}-&gt;{'blocked-uri'}, array_map('trim', file('blocked-uri-exclusions.txt')))) || (in_array($report-&gt;{'csp-report'}-&gt;{'document-uri'}, array_map('trim', file('document-uri-exclusions.txt'))))) {
    exit();
} else {
    file_put_contents('csp-reports.txt', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n", FILE_APPEND);
} ?&gt;</pre>
    <p>The code parses the raw JSON input. If it is valid JSON, the <code>blocked-uri</code> and <code>document-uri</code> values are checked against <code>blocked-uri-exclusions.txt</code> and <code>document-uri-exclusions.txt</code> respectively, which are optional configuration files that can be used to exclude certain reports, such as false positives.</p>
    <p>If the report is not excluded, it is appended to <code>csp-reports.txt</code>, ready to be printed out at the end of the build.</p>
</div>

<?php include "footer.php" ?>

</body>

</html>
