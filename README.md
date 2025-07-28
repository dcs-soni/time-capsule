# ⏰ Time Capsule

A POC, modern time capsule application built with [Statamic](https://statamic.com) that allows you to create digital time capsules with scheduled unlock dates. Perfect for preserving memories, messages, and moments for future discovery.

![Time Capsule Preview](https://img.shields.io/badge/Status-Live-brightgreen)
![Statamic Version](https://img.shields.io/badge/Statamic-5.0-blue)
![PHP Version](https://img.shields.io/badge/PHP-8.2+-purple)

## 🌟 Features

- **📅 Scheduled Unlock Dates** - Set specific dates when your capsules become accessible
- **🔐 Early Unlock Option** - Password-protected early access for special occasions
- **📝 Rich Content Support** - Markdown formatting for beautiful message formatting
- **🖼️ Media Attachments** - Add images and audio to your capsules
- **🌐 Public/Private Visibility** - Choose who can see your capsules
- **📱 Responsive Design** - Beautiful, mobile-friendly interface
- **🚀 Static Site Export** - Generate static HTML for easy deployment

## 🚀 Live Demo

Visit the live site: [Time Capsule Demo](https://dcs-soni.github.io/time-capsule/)

## 🛠️ Tech Stack

- **Backend**: [Statamic 5.0](https://statamic.com) (Laravel-based CMS)
- **Frontend**: [Tailwind CSS](https://tailwindcss.com) + [Vite](https://vitejs.dev)
- **Static Export**: [Spatie Laravel Export](https://github.com/spatie/laravel-export)
- **Deployment**: GitHub Pages with automated static site generation

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 16+ and npm
- Git


## 🎯 Usage

### Creating a Time Capsule

1. **Access the Control Panel**
   - Navigate to `/cp` in your browser
   - Login with your admin credentials

2. **Create New Capsule [Only Admin for now]**
   - Go to Collections → Capsules
   - Click "Create Entry"
   - Fill in the capsule details:
     - **Title**: A memorable name for your capsule
     - **Unlock Date**: When the capsule becomes accessible
     - **Message**: Your main content (supports Markdown)
     - **Visibility**: Public or Private
     - **Media**: Optional images or audio files
     - **Early Unlock**: Enable password protection for early access

3. **Publish Your Capsule**
   - Save and publish your entry
   - Your capsule is now live and will unlock on the specified date

### Managing Capsules

- **View All Capsules**: Browse all capsules in the control panel
- **Edit Capsules**: Modify content, dates, or settings anytime
- **Delete Capsules**: Remove capsules that are no longer needed
- **Bulk Operations**: Manage multiple capsules at once

## 🚀 Deployment

### Static Site Export

This project includes automated static site generation for easy deployment:

```bash
# Generate static site
php artisan site:export

# Or use the composer script
composer run static
```

The static files will be generated in the `/dist` directory, ready for deployment to any static hosting service.

### GitHub Pages Deployment

The project is configured for automatic deployment to GitHub Pages:


## 🎨 Customization

### Styling

The application uses Tailwind CSS for styling. Customize the design by modifying:

- `resources/css/app.css` - Main stylesheet
- `resources/views/templates/capsule.antlers.html` - Capsule template
- `tailwind.config.js` - Tailwind configuration

### Content Structure

The capsule content structure is defined in:
- `resources/blueprints/collections/capsules/capsule.yaml` - Content blueprint
- `content/collections/capsules/` - Content directory

### Templates

Customize the capsule display template:
- `resources/views/templates/capsule.antlers.html` - Main capsule template
- `resources/views/layout.antlers.html` - Base layout


### Project Structure

```
timecapsule/
├── app/                    # Laravel application code
├── content/               # Statamic content
│   └── collections/
│       └── capsules/      # Time capsule entries
├── resources/
│   ├── blueprints/        # Content structure definitions
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Templates
├── dist/                 # Static export output
└── public/               # Public assets
```


## 🙏 Acknowledgments

- Built with [Statamic](https://statamic.com) - The flat-first, Laravel-powered CMS
- Styled with [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- Static export powered by [Spatie Laravel Export](https://github.com/spatie/laravel-export)

## 📞 Support

If you have any questions or need help, please:

- Open an issue on GitHub
- Check the [Statamic documentation](https://statamic.dev/)
- Join the [Statamic community](https://statamic.com/discord)

---

**Made with ❤️ for preserving memories across time**
