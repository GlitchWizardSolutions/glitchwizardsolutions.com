<?php
/*
This is for the skip link, for accessibility purposes.
Include this file in place of the end header tag and the begin body tag.
It also requires an include 'https://glitchwizardsolutions.com/universal/primary-content.php'; on every page

*/

echo '<!-- GlitchWizard Solutions Accessibility CSS -->
 	 <link href="https://glitchwizardsolutions.com/universal/accessibility.css" rel="stylesheet">
</head>
<body>
<a id="skip-nav" class="screenreader-text" href="#primary-content">Skip to Content</a>';