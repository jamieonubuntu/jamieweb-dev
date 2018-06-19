<!DOCTYPE html>
<html lang="en">

<!--Copyright Jamie Scaife-->
<!--Legal Information at https://www.jamieweb.net/contact-->

<head>
    <title>Dual-Hosting Git Repositories on GitLab and GitHub</title>
    <meta name="description" content="Hosting Git repositories on both GitLab and GitHub at the same time.">
    <meta name="keywords" content="Jamie, Scaife, jamie scaife, jamiescaife, jamieonubuntu, jamie90437, jamie90437x, jamieweb, jamieweb.net">
    <meta name="author" content="Jamie Scaife">
    <link href="../jamie.css" rel="stylesheet">
    <link href="https://www.jamieweb.net/blog/dual-hosting-git-repositories-on-gitlab-and-github/" rel="canonical">
</head>

<body>

<?php include "navbar.php" ?>

<div class="body">
    <h1>Dual-Hosting Git Repositories on GitLab and GitHub</h1>
    <hr>
    <p><b>Saturday 9th June 2018</b></p>
    <p>In response to the recent news that Microsoft is to be acquiring GitHub, I have decided to begin hosting my main JamieWeb Git repository on GitLab in addition to the original on GitHub. This means that when I issue a <code>git push</code>, it automatically pushes to both remotes, ensuring that they are always in-sync. My new GitLab repository is accessible at: <b><a href="https://gitlab.com/jamieweb/jamieweb" target="_blank" rel="noopener">https://gitlab.com/jamieweb/jamieweb</a></b></p>
    <div class="slider-right slider-1000-right slider-gl radius-8">
        <div class="slider-left slider-1000-left slider-gh">
            <textarea readonly cols="0" rows="0" class="slider slider-1000"></textarea>
        </div>
    </div>
    <p class="centertext two-mar-top"><i>GitHub / GitLab Profile Screen Comparison Slider</i></p>

    <h2>Setting up GitLab</h2>
    <p>Setting up GitLab is extremely easy, however there are a couple of quick things to note:</p>
    <ul>
        <li>Make sure that the email address used for your commits is registered with your GitLab account, otherwise your commits may not be correctly associated with your user account.</li>
        <li>Don't forget to add your GPG key to your account, otherwise your signed commits will not be properly verified.</li>
        <li>Verify the SSH server fingerprints before connecting to the GitLab SSH server. Unfortunately these were very difficult to find, which was a bit disappointing. You can find them on the GitLab website <a href="https://docs.gitlab.com/ee/user/gitlab_com/#ssh-host-keys-fingerprints" target="_blank" rel="noopener">here</a>, or a copy of them below:</li>
    </ul>
    <table class="gl-fp">
        <tr class="gl-fp-header">
            <th class="gl-fp-algo">Algorithm</th>
            <th class="gl-fp-md5">MD5</th>
            <th class="gl-fp-sha256">SHA256</th>
        </tr>
        <tr>
            <td>DSA</td>
            <td>7a:47:81:3a:ee:89:89:64:33:ca:44:52:3d:30:d4:87</td>
            <td>p8vZBUOR0XQz6sYiaWSMLmh0t9i8srqYKool/Xfdfqw</td>
        </tr>
        <tr>
            <td>ECDSA</td>
            <td>f1:d0:fb:46:73:7a:70:92:5a:ab:5d:ef:43:e2:1c:35</td>
            <td>HbW3g8zUjNSksFbqTiUWPWg2Bq1x8xdGUrliXFzSnUw</td>
        </tr>
        <tr>
            <td>ED25519</td>
            <td>2e:65:6a:c8:cf:bf:b2:8b:9a:bd:6d:9f:11:5c:12:16</td>
            <td>eUXGGm1YGsMAS7vkcx6JOJdOGHPem5gQp4taiCfCLB8</td>
        </tr>
        <tr>
            <td>RSA</td>
            <td>b6:03:0e:39:97:9e:d0:e7:24:ce:a3:77:3e:01:42:09</td>
            <td>ROQFvPThGrW4RuWLoL9tq9I9zJ42fK4XywyRtbOz/EQ</td>
        </tr>
    </table>

    <h2>Configuring Git to Dual-Host</h2>
    <p>The configuration that I am using involves setting two different push URLs. This means that a single <code>git push</code> will push to both remotes at the same time. When pulling or fetching, the primary repository will be used.</p>
    <p>In order to set this up, you can either use the useful guide <a href="https://stackoverflow.com/questions/14290113" target="_blank" rel="noopener">here</a> or edit your Git configuration file directly. <b>Editing your Git configuration file directly has the potential to corrupt your repository if done incorrectly. You should also be aware of any automated processes such as CI/CD that could be performing Git operations in the background.</b></p>
    <p>If you view your Git repository configuration file directly, you may see a remote defined like the example below:</p>
    <pre>[remote "web"]
	url = git@github.com:jamieweb/jamieweb.git
	fetch = +refs/heads/*:refs/remotes/gh/*</pre>
    <p>You can manually add the push URLs to your remote using the <code>pushurl</code> directive in the configuration file. <b>Ensure that you add the original remote URL as a push URL too</b>, as the new push URLs will override your the default push destination.</p>
    <pre>[remote "web"]
	url = git@github.com:jamieweb/jamieweb.git
	fetch = +refs/heads/*:refs/remotes/gh/*
	pushurl = git@github.com:jamieweb/jamieweb.git
	pushurl = git@gitlab.com:jamieweb/jamieweb.git</pre>
    <p>Ensure that you very carefully check the configuration file syntax and URLs, as getting any of this wrong could either corrupt your Git repository or potentially cause a security breach (for example if you misspell/typo the URL and don't properly verify the SSH server fingerprints).</p>
    <p>Once you have verified your configuration, you can issue a <code>git push</code> to check that it's working and push your entire repository (or just a particular branch) to the new push destination. In my case I pushed the entire master branch:</p>
    <pre>$ git push -u web master
Branch 'master' set up to track remote branch 'master' from 'web'.
Everything up-to-date
Counting objects: 3975, done.
Compressing objects: 100% (1656/1656), done.
Writing objects: 100% (3975/3975), 11.90 MiB | 12.06 MiB/s, done.
Total 3975 (delta 2314), reused 3420 (delta 2000)
remote: Resolving deltas: 100% (2314/2314), done.
To gitlab.com:jamieweb/jamieweb.git
 * [new branch]      master -> master
Branch 'master' set up to track remote branch 'master' from 'web'.</pre>
</div>

<?php include "footer.php" ?>

</body>

</html>
