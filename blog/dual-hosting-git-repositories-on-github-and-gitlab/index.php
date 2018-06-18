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
    <h2>Configuring Git to Dual-Host</h2>
    <p>The configuration that I am using involves setting two different push URLs. This means that a single <code>git push</code> will push to both remotes at the same time. When pulling or fetching, the primary repository will be used.</p>
    <p>In order to set this up, you can either use the useful guide <a href="https://stackoverflow.com/questions/14290113" target="_blank" rel="noopener">here</a> or edit your Git configuration file directly. <b>Editing your Git configuration file directly has the potential to corrupt your repository if done incorrectly. You should also be aware of any automated processes such as CI/CD that could be performing Git operations in the background.</b></p>
    <pre>$ </pre>
</div>

<?php include "footer.php" ?>

</body>

</html>
