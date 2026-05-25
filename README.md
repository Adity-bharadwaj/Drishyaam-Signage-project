# DRISHYAAM SIGNAGE PVT. LTD. Website

A modern, premium, fully responsive business website for DRISHYAAM SIGNAGE PVT. LTD., a signage and advertising company serving Pune, Nagpur, and across Vidarbha, Maharashtra.

## Features

- **Modern Dark Theme**: Premium dark design with orange/red gradient highlights
- **Fully Responsive**: Mobile-first design that works on all devices
- **Smooth Animations**: AOS (Animate On Scroll) library for beautiful animations
- **Interactive Elements**: Hover effects, smooth scrolling, and interactive counters
- **WhatsApp Integration**: Floating WhatsApp button with pre-filled message
- **Portfolio Gallery**: Masonry-style gallery with category filtering and lightbox
- **Contact Form**: Professional inquiry form with Google Maps integration
- **SEO Optimized**: Complete meta tags for search engine optimization
- **Loading Animation**: Professional splash screen on page load
- **Scroll-to-Top Button**: Easy navigation back to top

## Sections

1. **Hero Section**: Full-width banner with CTAs and animated counters
2. **About Us**: Company introduction, mission, vision, and why choose us
3. **Services**: Service cards with icons and hover animations
   - LED Boards
   - ACP Signage
   - Acrylic Boards
   - Digital Displays
   - Solar Signage
   - Flex Printing
   - Glow Signs
   - Branding
   - Outdoor Advertising
   - Printing Services
4. **Portfolio**: Filterable gallery with lightbox preview
5. **Testimonials**: Client review cards with star ratings
6. **Contact**: Inquiry form, Google Maps, and contact information
7. **Footer**: Quick links, services, social media icons, and contact details

## Technologies Used

- **HTML5**: Semantic markup
- **CSS3**: Custom styles with CSS variables
- **AOS**: Animation library for scroll animations
- **Font Awesome**: Icon library
- **Google Fonts**: Poppins and Playfair Display fonts
- **JavaScript**: Vanilla JS for interactivity

## File Structure

```
Drishyaam Signage project/
├── index.html           # Main HTML file (homepage)
├── products.html        # Products/services detailed page
├── styles.css           # Custom CSS styles
├── script.js            # JavaScript functionality
├── logo.png             # Main logo
├── DRISHYAMHEADER.png   # Header banner image
├── fivlogo.png          # Favicon logo
├── image/               # Image assets directory
├── .opencode/           # Project configuration
└── README.md            # This file
```

## How to Use

### Local Development

1. Simply open `index.html` in any modern web browser
2. No build process or dependencies required
3. All libraries are loaded via CDN

### Deployment

#### Option 1: Static Hosting (Recommended)

1. **Netlify** (Free):
   - Sign up at [netlify.com](https://www.netlify.com)
   - Drag and drop the project folder to deploy
   - Your site will be live instantly

2. **Vercel** (Free):
   - Sign up at [vercel.com](https://vercel.com)
   - Install Vercel CLI: `npm i -g vercel`
   - Run `vercel` in the project directory
   - Follow the prompts to deploy

3. **GitHub Pages** (Free):
   - Create a GitHub repository
   - Upload the files
   - Go to Settings > Pages
   - Select the main branch and save
   - Your site will be available at `https://yourusername.github.io/repository-name`

#### Option 2: Traditional Web Hosting

1. Purchase a domain and hosting (e.g., GoDaddy, HostGator, Bluehost)
2. Upload the files to the public_html or www directory
3. Access your website via your domain

## Customization

### Company Information

Update the following in `index.html`:

- **Phone Number**: Search for `+917972231388` and replace with your number
- **Address**: Update in the Contact section and Footer
- **Email**: Replace `info@drishyaamsignage.com` with your email
- **Google Maps**: Update the iframe src with your actual location coordinates

### Colors

Modify the CSS variables in `styles.css`:

```css
:root {
    --primary: #FF4500;       /* Main orange color */
    --secondary: #FF6B35;     /* Secondary orange-red */
    --dark: #0D0D0D;          /* Main dark background */
    --darker: #080808;        /* Darker background */
    --card: #1A1A1A;          /* Card background */
    --light: #F5F5F5;         /* Light text color */
}
```

### Images

Replace placeholder images with your actual project images:
- Hero banner
- About section image
- Portfolio gallery images
- Testimonial avatars

### Services

Add, remove, or modify service cards in the Services section. Each card follows this structure:

```html
<div class="service-card">
    <div class="service-icon">
        <i class="fas fa-icon-name"></i>
    </div>
    <h3>Service Name</h3>
    <p>Service description</p>
</div>
```

## Contact Form Integration

The contact form currently shows an alert on submission. To integrate with a backend:

1. **Formspree** (Easiest):
   - Sign up at [formspree.io](https://formspree.io)
   - Create a new form
   - Update the form tag: `<form action="https://formspree.io/f/your-form-id" method="POST">`

2. **EmailJS**:
   - Sign up at [emailjs.com](https://www.emailjs.com)
   - Follow their documentation to integrate

3. **Custom Backend**:
   - Create a backend endpoint (Node.js, PHP, etc.)
   - Update the form submission JavaScript to send data to your endpoint

## Performance Optimization

- Images should be optimized for production (compress PNGs/JPEGs)
- Lazy loading is implemented for better performance
- CSS and JS can be minified for production
- CDN usage ensures fast loading globally

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## SEO Features

- Meta description and keywords
- Open Graph tags for social sharing
- Twitter Card tags
- Semantic HTML structure
- Alt tags for images
- Mobile-friendly design

## Contact Information

**DRISHYAAM SIGNAGE PVT. LTD.**
- Address: Pune, Maharashtra, India
- Phone: +91 7972231388
- Email: info@drishyaamsignage.com

## License

This project is created for DRISHYAAM SIGNAGE PVT. LTD. All rights reserved.

## Support

For any questions or support, please contact:
- Phone: +91 7972231388
- Email: info@drishyaamsignage.com

---

**Note**: This is a complete, production-ready website. Simply deploy it as-is or customize it to match your specific requirements.
