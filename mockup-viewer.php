<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Mockup Viewer - GlitchWizard Solutions</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .header h1 {
            color: #4a2c6b;
            margin-bottom: 15px;
            font-size: 2.5em;
        }
        
        .header p {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 10px;
        }
        
        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            color: #856404;
            font-size: 0.95em;
        }
        
        .mockup-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .mockup-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .mockup-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }
        
        .mockup-header {
            background: linear-gradient(135deg, #4a2c6b 0%, #673AB7 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .mockup-header h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        
        .mockup-header p {
            opacity: 0.9;
            font-size: 0.95em;
        }
        
        .mockup-status {
            background: #e8f4f8;
            border-bottom: 2px solid #4a2c6b;
            padding: 15px 30px;
            font-size: 0.9em;
            color: #333;
        }
        
        .mockup-status strong {
            color: #4a2c6b;
        }
        
        .mockup-content {
            padding: 30px;
        }
        
        .mockup-section {
            margin-bottom: 25px;
        }
        
        .mockup-section h3 {
            color: #4a2c6b;
            font-size: 1.2em;
            margin-bottom: 12px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 8px;
        }
        
        .change-highlight {
            background: #fff3cd;
            padding: 12px 15px;
            border-left: 4px solid #ffc107;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .change-highlight strong {
            color: #856404;
            display: block;
            font-size: 0.85em;
            margin-bottom: 5px;
        }
        
        .before-after {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }
        
        .before, .after {
            padding: 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        
        .before {
            background: #ffe5e5;
            border: 1px solid #ff9999;
        }
        
        .before::before {
            content: "CURRENT: ";
            font-weight: bold;
            color: #c33;
            display: block;
            margin-bottom: 5px;
            font-size: 0.85em;
        }
        
        .after {
            background: #e5f5e5;
            border: 1px solid #99ff99;
        }
        
        .after::before {
            content: "IMPROVED: ";
            font-weight: bold;
            color: #3a3;
            display: block;
            margin-bottom: 5px;
            font-size: 0.85em;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .view-btn {
            flex: 1;
            min-width: 150px;
            padding: 15px 25px;
            background: linear-gradient(135deg, #4a2c6b 0%, #673AB7 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .view-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(103, 58, 183, 0.4);
        }
        
        .view-btn.secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .view-btn.secondary:hover {
            background: #d0d0d0;
        }
        
        .comparison {
            margin-top: 40px;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .comparison h2 {
            color: #4a2c6b;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .key-improvements {
            list-style: none;
        }
        
        .key-improvements li {
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-left: 4px solid #673AB7;
            border-radius: 4px;
        }
        
        .key-improvements strong {
            color: #4a2c6b;
            display: block;
            margin-bottom: 5px;
        }
        
        .key-improvements span {
            color: #666;
            font-size: 0.95em;
        }
        
        @media (max-width: 768px) {
            .mockup-grid {
                grid-template-columns: 1fr;
            }
            
            .before-after {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎨 Website Mockup Comparison</h1>
            <p>Improved Messaging & Conversion Optimization</p>
            <div class="note">
                <strong>Note:</strong> These are mockups showing potential improvements to messaging, structure, and conversion optimization. Click the buttons below to view full-page mockups.
            </div>
        </div>
        
        <div class="mockup-grid">
            <!-- HOME PAGE MOCKUP -->
            <div class="mockup-card">
                <div class="mockup-header">
                    <h2>Home Page</h2>
                    <p>index-mockup.php</p>
                </div>
                <div class="mockup-status">
                    <strong>Status:</strong> Unified services framework with pricing visibility
                </div>
                <div class="mockup-content">
                    <div class="mockup-section">
                        <h3>Key Changes</h3>
                        
                        <div class="change-highlight">
                            <strong>1. Services Now Match Pricing Page</strong>
                            <div class="before-after">
                                <div class="before">MVP Website → Pay-as-You-Grow → Add-Ons</div>
                                <div class="after">Brochure Sites | Small Business | Custom Solutions</div>
                            </div>
                        </div>
                        
                        <div class="change-highlight">
                            <strong>2. Pricing Shown on Home</strong>
                            <div class="before-after">
                                <div class="before">No pricing mentioned</div>
                                <div class="after">$1,500+ | $3,500-$5,500 | Custom Quote</div>
                            </div>
                        </div>
                        
                        <div class="change-highlight">
                            <strong>3. Real Service Examples</strong>
                            <div class="before-after">
                                <div class="before">Vague descriptions</div>
                                <div class="after">Portfolio sites, brochure sites, service businesses, personal websites, e-commerce, ADHD dashboards</div>
                            </div>
                        </div>
                        
                        <div class="change-highlight">
                            <strong>4. Accessibility as Differentiator</strong>
                            <div class="before-after">
                                <div class="before">Buried in "Our Mission"</div>
                                <div class="after">Featured as a core value in each section</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <a href="index-mockup.php" class="view-btn" target="_blank">View Full Mockup →</a>
                </div>
            </div>
            
            <!-- CONTACT PAGE MOCKUP -->
            <div class="mockup-card">
                <div class="mockup-header">
                    <h2>Contact Page</h2>
                    <p>message-me-mockup.php</p>
                </div>
                <div class="mockup-status">
                    <strong>Status:</strong> Enhanced form with complete service options
                </div>
                <div class="mockup-content">
                    <div class="mockup-section">
                        <h3>Key Changes</h3>
                        
                        <div class="change-highlight">
                            <strong>1. Form Dropdown Expanded</strong>
                            <div class="before-after">
                                <div class="before">6 generic options</div>
                                <div class="after">Specific service types including ADHD dashboards</div>
                            </div>
                        </div>
                        
                        <div class="change-highlight">
                            <strong>2. Pricing Tier Structure</strong>
                            <div class="before-after">
                                <div class="before">3 tiers (MVP, Foundational, Expanded)</div>
                                <div class="after">Organized by type + examples of what fits each price point</div>
                            </div>
                        </div>
                        
                        <div class="change-highlight">
                            <strong>3. Webmaster Retainer Highlighted</strong>
                            <div class="before-after">
                                <div class="before">Just pricing list</div>
                                <div class="after">Shows value story: "Site cost + yearly support = growth"</div>
                            </div>
                        </div>
                        
                        <div class="change-highlight">
                            <strong>4. "Other" Option Better Explained</strong>
                            <div class="before-after">
                                <div class="before">Generic "Other"</div>
                                <div class="after">"Something else? We do custom work." with clear CTA</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <a href="message-me-mockup.php" class="view-btn" target="_blank">View Full Mockup →</a>
                </div>
            </div>
        </div>
        
        <!-- COMPARISON SUMMARY -->
        <div class="comparison">
            <h2>Conversion Optimization Summary</h2>
            <ul class="key-improvements">
                <li>
                    <strong>✓ Clarity Over Vagueness</strong>
                    <span>Visitors immediately understand what you offer and at what price point</span>
                </li>
                <li>
                    <strong>✓ Consistent Messaging</strong>
                    <span>Home page and contact page use same framework—reduces confusion and builds trust</span>
                </li>
                <li>
                    <strong>✓ Niche Specificity</strong>
                    <span>Mentions ADHD dashboards, personal websites, portfolios—signals you understand their needs</span>
                </li>
                <li>
                    <strong>✓ Accessibility as Competitive Edge</strong>
                    <span>Featured prominently to differentiate you from competitors</span>
                </li>
                <li>
                    <strong>✓ Value Story Clarity</strong>
                    <span>Webmaster retainer is positioned as ongoing growth, not just maintenance</span>
                </li>
                <li>
                    <strong>✓ Better Lead Qualification</strong>
                    <span>Enhanced form dropdown helps visitors self-select, reducing mismatched inquiries</span>
                </li>
                <li>
                    <strong>✓ Trust Signals</strong>
                    <span>Clear pricing builds confidence—visitors don't feel hidden quotes are coming</span>
                </li>
                <li>
                    <strong>✓ Mobile-Friendly Structure</strong>
                    <span>Information hierarchy works on all devices</span>
                </li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 40px; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <h3 style="color: #4a2c6b; margin-bottom: 20px;">View Side-by-Side</h3>
            <div class="button-group">
                <a href="index.php" class="view-btn secondary" target="_blank">Current Home Page</a>
                <a href="message-me.php" class="view-btn secondary" target="_blank">Current Contact Page</a>
            </div>
        </div>
    </div>
</body>
</html>
