# WP Favorite Posts

WP Favorite Posts is a WordPress plugin that allows users to mark custom post types (CPT) as favorites and view a paginated list of their favorites.

## Features

- Add any custom post type to favorites.
- AJAX-based toggle for adding/removing posts from favorites.
- Display a paginated list of favorite posts using a shortcode.
- User-friendly and lightweight.

## Installation

1. Download or clone the repository into your `wp-content/plugins/` directory:

   ```bash
   git clone https://github.com/yourusername/wp-favorite-posts.git``

   ```

2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the `[favorite_posts]` shortcode to any page or post where you want to display favorite posts.

## Usage

### Shortcode

### Favorite Button Shortcode

Use the `[favorite_button]` shortcode to display a "Favorite" button anywhere on your site.

**Attributes:**

- `post_id`: The ID of the post you want to favorite (optional, defaults to current post ID).
- `class`: Additional CSS class for styling (optional).

Example usage:

```php
[favorite_button post_id="123" class="my-custom-class"]
```

### Query favorites

- `[favorite_posts post_type="your_cpt" posts_per_page="10"]`:
  - `post_type`: The custom post type you want to display favorites for.
  - `posts_per_page`: Number of posts to display per page.
