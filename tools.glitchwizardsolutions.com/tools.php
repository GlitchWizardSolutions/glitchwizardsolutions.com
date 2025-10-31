<?php 
/* 
LOCATION: index.html
USE CASE: template for accessibility
ACCESS:   public facing
ORIGIN:   glitchwizardsolutions 2024
STACKED:  html, bootstrap v5.3, css3, php 8.1
UPDATED:  2024-01-16 Accessibility Audits PASS
TO NOTE:
REQUIRED: included accessibility files
*/

/*update*/
$copyright = "GWS";
$copyYear = 2024;

echo '<!DOCTYPE html>
<html lang="en">
<head>';

/* Add Google Analytics/GBP Tags BEFORE the <meta charset="utf-8"  */

echo '<meta charset="utf-8">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta name="author" content="Barbara Moore" >';
      
/* Upload an image for Social Media Share for the page: 297 X 170 named social-media.png Change the social media meta links & connical page below. */
echo '<meta property="og:title" content="Websites that Grow with Your Business Goals" />
      <meta property="og:type" content="website" />
      <meta property="og.image" content="https://glitchwizardsolutions.com/assets/img/glitchwizardsolutions-social-share-image.png" />
      <meta property="og:url" content="https://glitchwizardsolutions.com/index.html">
      <meta property="og:description" content="From your initial footprint through your digital marketing!" />
      <meta property="og:site_name" content="GlitchWizard Solutions, Inc." />
      <!--Facebook--><!--<meta property="fb:app_id" content="" />-->
      <meta name="twitter:card" content="summary_large_image">
      <meta property="twitter:domain" content="glitchwizardsolutions.com">
      <meta property="twitter:url" content="https://glitchwizardsolutions.com/index.html">
      <meta name="twitter:title" content="Websites that Grow with Your Business Goals">
      <meta name="twitter:description" content="From your initial footprint through your digital marketing!">
      <meta name="twitter:image" content="https://glitchwizardsolutions.com/assets/img/glitchwizardsolutions-social-share-image.png">
      <meta name="twitter:image:alt" content="Web Presence Growth Plans written on a binary code background">
      <!-- <meta name="twitter:site" content="@" /> <meta name="twitter:creator" content="@" />-->
      <link href="https://www.glitchwizardsolutions.com/index.html" rel="canonical">';
/* Change description, keywords & title: Research keywords for this industry, use key phrase in the description */
echo '<title>Tools - GlitchWizardSolutions.com</title>
      <meta name="description"  content="Best Tools I Use and Recommended for Affiliate Marketing" >
      <meta name="keywords"     content="Best Affiliate Marketing Tools, Easiest Affiliate Marketing" name="keywords">';
/* Create, Upload, & Link favicons/icons: Change the links below. */ 
echo '<link href=" https://glitchwizardsolutions.com/universal/construction/favicon.png" rel="icon">
      <link href=" https://glitchwizardsolutions.com/universal/construction/apple-touch-icon.png" rel="apple-touch-icon">';
/* Add Fonts, Vendor CSS Files */ 
/* Link the custom .css for this website */ 
echo '<link href="" rel="stylesheet">';
/* Do not Delete accessibility.css */ 
echo '<!-- GlitchWizard Solutions Accessibility CSS -->
      <link href="https://glitchwizardsolutions.com/universal/accessibility.css" rel="stylesheet">';
/* The following include statement contains the </HEAD> and <BODY> tags, as well as a skip link for accessibility. */ ?>
<?php
require_once('https://glitchwizardsolutions.com/universal/head-off-body.php');
/* MAKE SURE THE FOLLOWING LINE IS AFTER THE NAVIGATION */
require_once('https://glitchwizardsolutions.com/universal/primary-content.php'); 
echo '</body>';
/* The following statement contains a function for the copyright date to increment. */
require_once('https://glitchwizardsolutions.com/universal/copyright.php'); 
echo '</html>'; ?>