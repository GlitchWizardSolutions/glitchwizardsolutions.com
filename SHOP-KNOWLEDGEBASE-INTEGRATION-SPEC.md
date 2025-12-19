# GlitchWizard Solutions - Shop & Knowledgebase Integration Spec

## Overview
Integration of a product/service shop with a knowledgebase (blog-like educational content) connected to the main website, similar to Web-Jive's structure.

## Current Architecture Reference
**Web-Jive Model:**
- **Primary Domain** (www.web-jive.com): Marketing site, case studies, blog content, service descriptions
- **Subdomain** (members.web-jive.com): Billing/client portal, knowledgebase, product store, support ticketing

---

## Proposed Architecture for GlitchWizard Solutions

### 1. **Main Website Structure**
   - **Primary Domain**: www.glitchwizardsolutions.com
   - **Content**: Marketing pages, service overviews, testimonials, project portfolios
   - **Blog/Knowledgebase Integration**: Educational articles about services (can appear on main site or redirect to subdomain)
   - **Navigation**: Links to shop and knowledgebase

### 2. **Subdomain Structure**
   - **Subdomain**: members.glitchwizardsolutions.com or shop.glitchwizardsolutions.com
   - **Components**:
     - Knowledgebase (searchable, categorized articles)
     - Product/Service Shop
     - Client Support Area (existing/future)
     - Client Dashboard (manage services)

---

## Component Specifications

### A. KNOWLEDGEBASE (Blog-Like Educational Content)
**Location**: `/members/knowledgebase/` or `/shop/knowledge-base/`

**Features:**
- Categorized articles (Service Types, Troubleshooting, Guides, FAQ)
- Search functionality
- Related articles linking
- Breadcrumb navigation
- Article metadata (author, date, views, difficulty level)
- Comments/support ticket integration
- Table of contents for longer articles
- SEO optimization (meta tags, structured data)

**Database Structure:**
```
kb_articles
  - id
  - title
  - slug
  - category_id
  - content
  - excerpt
  - author_id
  - created_date
  - updated_date
  - views_count
  - status (published/draft)

kb_categories
  - id
  - name
  - slug
  - description
  - display_order

kb_comments
  - id
  - article_id
  - user_id
  - comment_text
  - created_date
  - status (approved/pending)
```

**Integration Points:**
- Link from product pages → Related KB articles
- Suggest KB articles when support tickets created
- Embed in main website as "Learning Center" or "Resources"

---

### B. SHOP/PRODUCTS SYSTEM
**Location**: `/members/shop/` or `/shop/`

**Features:**
- Product/Service Catalog
  - Service name, description, pricing
  - Product images/icons
  - Service tiers (Basic, Pro, Enterprise)
  - Feature lists
  - Comparison tables
- Shopping Cart
- Checkout (using existing custom PHP or integration)
- Product Categories
- Product Search & Filtering
- Customer Reviews/Ratings
- Product Bundles/Packages

**Database Structure:**
```
products
  - id
  - name
  - slug
  - category_id
  - description
  - long_description
  - price
  - image_url
  - status (active/inactive)
  - created_date
  - updated_date
  - kb_article_id (optional - link to KB)

product_categories
  - id
  - name
  - slug
  - description
  - display_order

product_images
  - id
  - product_id
  - image_url
  - alt_text
  - display_order

product_reviews
  - id
  - product_id
  - customer_id
  - rating
  - review_text
  - created_date
  - status (approved/pending)

cart_items
  - id
  - session_id / user_id
  - product_id
  - quantity
  - price_at_time

orders
  - (Use existing custom order system)
```

**Integration Points:**
- Include KB articles in product sidebar ("Learn More")
- Display related products on KB articles
- Customer reviews on product pages
- Link checkout pages to relevant KB (setup guides, FAQs)

---

### C. NAVIGATION & CROSS-LINKING STRATEGY

**Main Website (www.glitchwizardsolutions.com)**
- Header/Footer links to:
  - Shop: "/shop/" or direct to members subdomain
  - Knowledgebase: "/learn/" or direct to members subdomain
  - Support: Direct to support contact form
  
**Members/Shop Subdomain (members.glitchwizardsolutions.com)**
- Header Navigation:
  - Home (back to www.glitchwizardsolutions.com)
  - Shop (products/services)
  - Knowledgebase/Learning Center
  - Account/Support (if applicable)
  - Announcements
  - Downloads (if applicable)

**Cross-Linking Features:**
- Product pages include "Learn More" → KB articles
- KB articles include "Explore Products" → Related services
- Sidebar widgets showing related items
- "You may also like" sections

---

### D. URL STRUCTURE

**Main Site:**
```
www.glitchwizardsolutions.com/
www.glitchwizardsolutions.com/services/
www.glitchwizardsolutions.com/about/
www.glitchwizardsolutions.com/contact/
```

**Members/Shop Subdomain:**
```
members.glitchwizardsolutions.com/
members.glitchwizardsolutions.com/shop/
members.glitchwizardsolutions.com/shop/[product-slug]
members.glitchwizardsolutions.com/shop/category/[category-slug]

members.glitchwizardsolutions.com/knowledgebase/
members.glitchwizardsolutions.com/knowledgebase/[article-slug]
members.glitchwizardsolutions.com/knowledgebase/category/[category-slug]

members.glitchwizardsolutions.com/cart/
members.glitchwizardsolutions.com/checkout/
members.glitchwizardsolutions.com/account/
members.glitchwizardsolutions.com/support/
```

---

### E. AUTHENTICATION & USER MANAGEMENT

**Scope:**
- Guest users can browse knowledgebase and shop
- User login for:
  - Saving cart items
  - Order history (if applying to digital services)
  - Support ticket access
  - Potential future membership features

**Integration:**
- Single login system for subdomain
- Optional: SSO with main website (consider security)
- User roles: Guest, Customer, Support Staff, Admin

---

## Implementation Phases

### Phase 1: Foundation
1. Setup subdomain (members.glitchwizardsolutions.com or shop.glitchwizardsolutions.com)
2. Basic knowledgebase structure
   - Articles table & admin interface
   - Category management
   - Search functionality
3. Basic shop structure
   - Products table
   - Product display pages
   - Add to cart functionality

### Phase 2: Integration & Enhancement
1. Cross-linking between KB and shop
2. Product categorization
3. Related content suggestions
4. Customer reviews/ratings
5. Navigation refinement

### Phase 3: Advanced Features
1. Advanced search (filters, full-text search)
2. Product comparison tools
3. Analytics/tracking
4. Email notifications (new articles, order confirmations)
5. Product bundles/packages

---

## Technical Considerations

**Database**
- Use existing MySQL/MariaDB instance
- Separate tables for knowledgebase and shop (clean architecture)
- Ensure proper indexing on frequently searched fields
- Regular backups

**Performance**
- Caching strategy for articles & products
- Image optimization
- Pagination for article/product lists
- Lazy loading for images

**Security**
- Input validation on all forms
- SQL injection prevention
- XSS protection on comments/reviews
- HTTPS for checkout/sensitive data
- Rate limiting on search/API endpoints

**SEO**
- Clean URLs with descriptive slugs
- Sitemap generation (articles + products)
- Meta tags on all pages
- Structured data (Schema.org) for products
- Breadcrumb navigation
- Internal linking strategy

**Scalability**
- Design for future growth in article/product count
- Consider caching layer if traffic grows
- CDN for images (optional future enhancement)

---

## Deliverables Checklist

- [ ] Subdomain configured & SSL certificate
- [ ] Database schema created
- [ ] Admin interface for article management
- [ ] Admin interface for product management
- [ ] Public knowledgebase interface
- [ ] Public shop interface
- [ ] Search functionality
- [ ] Navigation/header components
- [ ] Cross-linking system
- [ ] User authentication
- [ ] Analytics tracking
- [ ] Documentation
- [ ] Testing & QA
- [ ] Deployment to production
- [ ] User training/documentation

---

## Notes

- This spec is intentionally agnostic about billing/payment processing (using your existing custom PHP code)
- The focus is on content management (KB + shop) and their integration
- Web-Jive uses WHMCS for their billing system; you may have different requirements
- Consider the user experience journey: Browse KB → Interest in service → Shop → Learn more about service
