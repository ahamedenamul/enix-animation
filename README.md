# Enix Animation - Advanced Elementor Animation Plugin

[![WordPress Version](https://img.shields.io/badge/WordPress-5.6+-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4+-green.svg)](https://php.net/)
[![Elementor Version](https://img.shields.io/badge/Elementor-3.0.0+-purple.svg)](https://elementor.com/)
[![License](https://img.shields.io/badge/License-GPL%20v2+-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A powerful WordPress plugin that adds advanced viewport scroll animations to any Elementor element. Features bidirectional animations with 20+ animation styles, fully customizable timing settings, and smooth 60fps performance.

## ✨ Features

- **20+ Animation Styles**: Fade, slide, zoom, rotate, flip, and more creative effects
- **Bidirectional Animations**: Different animations for scroll-in and scroll-out
- **Advanced Customization**: Control duration, delay, easing, and offset
- **Performance Optimized**: Pure CSS + Vanilla JS for 60fps smooth animations
- **Universal Support**: Works with sections, containers, columns, and all widgets
- **Intersection Observer**: Modern API for efficient viewport detection
- **Once or Repeat**: Choose between one-time or repeating animations

## 🚀 Installation

### From WordPress Repository (Recommended)

1. In your WordPress admin, go to **Plugins → Add New**
2. Search for "Enix Animation"
3. Click **Install Now** and then **Activate**

### Manual Installation

1. Download the plugin ZIP file
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**
3. Select the ZIP file and click **Install Now**
4. Activate the plugin

## 📋 Requirements

- WordPress 5.6 or higher
- PHP 7.4 or higher
- Elementor 3.0.0 or higher
- Elementor Pro (recommended for full functionality)

## 🎯 How to Use

1. **Edit any Elementor element** (section, container, column, or widget)
2. **Go to the Advanced tab** in the element settings
3. **Find "Enix Animation"** section
4. **Configure your animation**:
   - Choose animation type (fade, slide, zoom, etc.)
   - Set duration (fast: 300ms, normal: 600ms, slow: 1000ms)
   - Add delay (0-5000ms)
   - Select easing function
   - Set viewport offset (when to trigger)
   - Choose repeat behavior

## 🎨 Animation Types

### Fade Animations
- Fade In
- Fade In Up
- Fade In Down
- Fade In Left
- Fade In Right

### Slide Animations
- Slide In Up
- Slide In Down
- Slide In Left
- Slide In Right

### Zoom Animations
- Zoom In
- Zoom In Up
- Zoom In Down
- Zoom In Left
- Zoom In Right

### Special Effects
- Rotate In
- Flip In X
- Flip In Y
- Bounce In
- Elastic In

## ⚙️ Configuration Options

| Setting | Description | Options |
|---------|-------------|---------|
| **Animation Type** | Choose the animation style | 20+ options |
| **Duration** | Animation speed | Fast (300ms), Normal (600ms), Slow (1000ms) |
| **Delay** | Wait time before animation starts | 0-5000ms |
| **Easing** | Animation timing function | Ease, Ease-in, Ease-out, Ease-in-out, Linear |
| **Offset** | Viewport trigger point | 0-100% |
| **Once** | Animation repeat behavior | Yes/No |

## 🔧 Developer Options

### Filters and Hooks

```php
// Customize animation duration mapping
add_filter('enix_animation_duration_map', function($durations) {
    $durations['custom'] = 800;
    return $durations;
});

// Add custom animation classes
add_filter('enix_animation_custom_classes', function($classes) {
    $classes[] = 'my-custom-animation';
    return $classes;
});
```

### CSS Customization

```css
/* Override default animation timing */
.enix-anim-init {
    animation-duration: 0.8s !important;
}

/* Custom animation styles */
.custom-animation {
    animation: customKeyframe 1s ease-out;
}

@keyframes customKeyframe {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

## 🐛 Troubleshooting

### Common Issues

**Animations not working:**
- Ensure Elementor is activated and updated
- Check WordPress and PHP version requirements
- Clear browser and server cache
- Check for JavaScript conflicts in browser console

**Performance issues:**
- Limit the number of animated elements on a single page
- Use appropriate offset values to avoid early triggering
- Consider using "Once" option for better performance

**Mobile responsiveness:**
- Test animations on different screen sizes
- Consider reducing animation complexity on mobile devices
- Use Elementor's responsive settings for different breakpoints

## 🔄 Changelog

### Version 1.0.0
- Initial release
- 20+ animation styles
- Bidirectional animation support
- Advanced customization options
- Performance optimization
- WordPress 5.6+ compatibility
- Elementor 3.0+ integration

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 Development

### Local Development Setup

1. Clone the repository
2. Run `npm install` for development dependencies
3. Use `npm run build` to compile assets
4. Test with WordPress development environment

### File Structure

```
enix-animation/
├── enix-animation.php          # Main plugin file
├── includes/
│   └── class-enix-controls.php # Elementor controls
├── assets/
│   ├── css/
│   │   └── enix-frontend.css   # Frontend styles
│   └── js/
│       └── enix-frontend.js    # Frontend scripts
├── languages/                   # Translation files
└── README.md                   # This file
```

## 📄 License

This plugin is licensed under the GPL v2 or later License.

## 🙋‍♂️ Support

- **GitHub Issues**: [Report bugs and request features](https://github.com/ahamedenamul/enix-animation/issues)
- **WordPress.org**: [Plugin support forum](https://wordpress.org/support/plugin/enix-animation/)
- **Email**: support@enixsolutions.com

## 👥 Author

**Enix Solutions Ltd**
- Developed by: Enamul Islam
- Website: https://enixsolutions.com
- GitHub: [@ahamedenamul](https://github.com/ahamedenamul)

## 🙏 Credits

- Built with [Elementor](https://elementor.com/) API
- Uses [Intersection Observer API](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- Inspired by modern web animation best practices

---

**⭐ If you like this plugin, please consider leaving a review on WordPress.org!**
