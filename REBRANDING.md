# Theme Rebranding - Vicksburg Daily News

This document outlines the rebranding changes made to rename the theme from "Zox News" to "Vicksburg Daily News".

## Changes Made

### 1. Theme Header (style.css)
- **Theme Name**: Changed from "Zox News" to "Vicksburg Daily News"
- **Author**: Changed from "MVP Themes" to "Randy Breland, Land Tech Web Designs, Corp"
- **Author URI**: Changed to "https://landtechwebdesigns.com"
- **Theme URI**: Changed to "https://landtechwebdesigns.com"
- **Description**: Updated to reference "Vicksburg Daily News" instead of "Zox News"
- **Text Domain**: Kept as "zox-news" for backward compatibility with existing translations

### 2. Admin Options Page (functions.php)
- **Page Title**: Changed from "Zox News Options" to "Vicksburg Daily News Options"

### 3. Widget Names
All widget names updated from "Zox News: [Widget Name]" to "Vicksburg Daily News: [Widget Name]":
- Side Tabber Widget
- Home Dark Widget
- Ad Widget
- Flexible Posts Widget
- Home Featured 1 Widget
- Facebook Widget
- Home Featured 2 Widget

### 4. Copyright Text (admin/options.php)
- **Default Copyright**: Changed from "Copyright © 2017 Zox News Theme. Theme by MVP Themes, powered by WordPress." to "Copyright © [Current Year] Vicksburg Daily News. Theme by Randy Breland, Land Tech Web Designs, Corp, powered by WordPress."
- Now uses dynamic year with `date('Y')`

## Files Modified

1. `style.css` - Theme header information
2. `functions.php` - Admin options page title
3. `widgets/widget-tabber.php` - Widget name
4. `widgets/widget-home-dark.php` - Widget name
5. `widgets/widget-ad.php` - Widget name
6. `widgets/widget-flex.php` - Widget name
7. `widgets/widget-home-feat1.php` - Widget name
8. `widgets/widget-facebook.php` - Widget name
9. `widgets/widget-home-feat2.php` - Widget name
10. `admin/options.php` - Default copyright text

## Notes

- The text domain remains "zox-news" to maintain compatibility with existing translations
- Language files (.pot and .po) will need to be regenerated if translations are updated
- The theme slug and internal identifiers remain unchanged for database compatibility
- All functionality remains the same; only branding has been updated

## Author Information

**Theme Name**: Vicksburg Daily News  
**Author**: Randy Breland, Land Tech Web Designs, Corp  
**Website**: https://landtechwebdesigns.com  
**Version**: 3.1.1

