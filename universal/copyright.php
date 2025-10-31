<?php 
/* 
LOCATION:  universal/copyright.php
USE CASE: in template for accessibility 
ACCESS:   public facing
ORIGIN:   glitchwizardsolutions 2024
STACKED:  html, bootstrap v5.3, css3, php 8.1
UPDATED:  2024-01-16 Accessibility Audits PASS
TO NOTE:
REQUIRED:  
*/ 
echo "<!--copyright-->";
$curYear=date('Y');
$copyrightgws='Website by GlitchWizard Solutions, LLC';

echo "&copy; Copyright <?=$copyYear?> - <?=$curYear?> <span><?=$copyright?>.  All Rights Reserved   |  <?=$copyrightgws?></span>."; 
?>