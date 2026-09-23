# WordPress Developer Assignment

A builder-free WordPress landing page implemented as a custom plugin.

## Requirements covered
- Custom PHP, HTML, CSS and JavaScript
- No Elementor or page builder
- Plugin-based implementation
- Responsive desktop/tablet/mobile layouts
- Dynamic CMS-managed homepage content
- Clean separation of plugin logic, admin settings and assets

## Installation
1. Zip the `wordpress-developer-assignment` folder.
2. In WordPress admin go to **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP and activate it.
4. Activation creates a **Developer Assignment** page at `/developer-assignment/`.
5. Edit content from **Settings → Assignment Website**.

## Shortcode
`[wda_assignment]`

You can place this shortcode on any normal WordPress page.

## Dynamic content
Hero copy, buttons, stats, service cards and footer text are stored in a WordPress option and edited from the WordPress admin screen. This demonstrates CMS usability without a page builder.

 
