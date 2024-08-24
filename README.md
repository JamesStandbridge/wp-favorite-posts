# WP Favorite Posts

WP Favorite Posts is a WordPress plugin that allows users to mark custom post types (CPT) as favorites and view a paginated list of their favorites.

## Features

- Add any custom post type to favorites.
- AJAX-based toggle for adding/removing posts from favorites.
- Display a paginated list of favorite posts using a shortcode.
- User-friendly and lightweight.
- Customizable favorite button with text, icons (image or SVG), or both.
- Button state updates dynamically without page reload.
- Disabled button with a loading spinner during AJAX requests.

## Installation

1. Download or clone the repository into your `wp-content/plugins/` directory:

```bash
git clone https://github.com/JamesStandbridge/wp-favorite-posts
```

2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Add the `[favorite_posts]` shortcode to any page or post where you want to display favorite posts.

## Usage

### Favorite Button Shortcode

Use the `[favorite_button]` shortcode to display a "Favorite" button anywhere on your site.

**Attributes:**

- `post_id`: The ID of the post you want to favorite (optional, defaults to current post ID).
- `class`: Additional CSS class for styling (optional).
- `add_text`: Text to display when the post is not in the user's favorites (default: "Ajouter aux favoris").
- `remove_text`: Text to display when the post is in the user's favorites (default: "Retirer des favoris").
- `add_icon`: URL of the icon (image or SVG) to display when the post is not in the user's favorites (optional).
- `remove_icon`: URL of the icon (image or SVG) to display when the post is in the user's favorites (optional).
- `display`: Specifies what to display in the button. Options:
  - `both`: Display both text and icon.
  - `text`: Display only the text.
  - `icon`: Display only the icon.

**Example usage:**

Display a favorite button with custom text and icons:

```php
[favorite_button
    post_id="123"
    class="my-custom-class"
    add_text="Add to Favorites"
    remove_text="Remove from Favorites"
    add_icon="https://example.com/path/to/add-icon.svg"
    remove_icon="https://example.com/path/to/remove-icon.svg"
    display="both"]
```

or

```php
<?php echo do_shortcode('[favorite_button
    post_id="123"
    class="my-custom-class"
    add_text="Add to Favorites"
    remove_text="Remove from Favorites"
    add_icon="https://example.com/path/to/add-icon.svg"
    remove_icon="https://example.com/path/to/remove-icon.svg"
    display="both"]'); ?>
```

### Query Favorites Shortcode

Use the `[favorite_posts]` shortcode to display a paginated list of the user's favorite posts.

**Attributes:**

- `post_type`: The custom post type you want to display favorites for (default: "post").
- `posts_per_page`: Number of posts to display per page (default: 10).

**Example usage:**

Display a list of favorite posts for a custom post type "recettes" with 10 posts per page:

```php
[favorite_posts post_type="recettes" posts_per_page="10"]
```

## Customization

### Styling the Favorite Button

You can add custom styles to the favorite button using the `class` attribute or by modifying the styles in your theme's `style.css`. The button includes different states for when it is active or inactive, as well as a loading spinner during AJAX requests.

**Example CSS:**

```css
.favorite-button {
  background-color: #0073aa;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  position: relative;
}

.favorite-button:disabled {
  background-color: #c0c0c0;
  cursor: not-allowed;
}

.spinner-border {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  border: 0.2em solid currentColor;
  border-right-color: transparent;
  border-radius: 50%;
  animation: spinner-border 0.75s linear infinite;
  margin-left: 10px;
}

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}
```

### JavaScript Interaction

The favorite button automatically disables itself and displays a loading spinner during the AJAX request to prevent multiple submissions. The button's text and icon will update dynamically based on the current favorite status of the post.
