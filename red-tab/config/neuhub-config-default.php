<?php

/*
 * Neuhub Red Dash Theme - Configuration File
 * Author: Scott M. Stolz
 * License: MIT (Expat version) - https://license.neuhub.org
 * Copyright (c) 2022 WisTex TechSero Ltd. Co.
 * Source: https://neuhub.org/
 */

// ! Important Instructions:
// ! Rename this file neuhub-config.php (or make a copy called neuhub-config.php) so it does not get overwritten by changes.

// * Left Sidebar Icon Class and Title
// Icon can be any ForkAwesome icon or Hubzilla icon. 
// $SiteIcon = "icon-hz-64"; // Hubzilla Logo
$SiteIcon = "icon-hz-64";
$SidebarTitle = "Hubzilla";

// * Navigation Links
// Override the destination of the Home link. Affects breadcrumbs, footer, and sidebar links to Home.
$HomeURL = "/";
// URL to your Terms of Service
$TermsURL = "/help/TermsOfService";
// URL to your Privacy Policy
$PrivacyURL = "/help/TermsOfService";

// * Social Media Links
// Website's Main Channel (usually the channel where you give announcements)
// Example: $WebsiteChannel = "/channel/blog";
$WebsiteChannel = "/";
// Other social media channels for the website.
$SocialMastodon = "";
$SocialFacebook = "";
$SocialLinkedIn = "";
$SocialTwitter = "";
$SocialYouTube = "";
$SocialDigitalAuthorship = "";
$SocialInstagram = "";
$SocialGithub = "";
$SocialGitlab = "";
$SocialGit = "";
$SocialPatreon = "";
// TODO: Create a way for people to add their own.

// * Copyright Notice (for content on the website)
// Note: It should be the name of the person or company that owns the copyright, not the name of your website.
// $Copyright = "Copyright &copy; 2023 WisTex TechSero Ltd. Co. All rights reserved."; // example
// $Copyright = "Copyright &copy; 2023 <a href='https://wistex.com'>WisTex TechSero Ltd. Co.</a> All rights reserved."; // example
$Copyright = "";
// Instead of a copyright notice, you can put any text you want here. 
// It will appear in the footer where the copyright notice normally would be.

// ? Site Name in Breadcrumbs?
// If true, the Site Name defined in the Site Admin section is used in the breadcrumbs.
// If false, it is replaced with: $SiteNameinBreadcrumbsText (HTML not permitted.)
$SiteNameinBreadcrumbs = true;
$SiteNameinBreadcrumbsText = "Home";
// Note: the default is to use the site name instead of "home" in the breadcrumbs to make it obvious to visitors which website they are on.
// This is useful for visitors since Hubzilla websites may look similar. It is also useful if you manage more than one hub.

// ? Site Name in Footer?
// If true, the Site Name defined in the Site Admin section is used in the footer.
// If false, it is replaced with: $SiteNameinFooterText (HTML permitted.)
$SiteNameinFooter = true;
$SiteNameinFooterText = "Neuhub";

// Show powered by Hubzilla and Neuhub in footer.
$PoweredBy = true;
// Show that this is an independent federated hub in footer.
$FederatedHub = true;

?>