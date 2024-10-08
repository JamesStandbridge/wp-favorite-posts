# WP Favorite Posts

WP Favorite Posts is a WordPress plugin that allows users to mark custom post types (CPT) as favorites and view a paginated list of their favorites.

## Tested up to

Tested up to: 6.6.1

## Stable Tag

Stable Tag: 1.8

## License

This plugin is licensed under the MIT License.

However, to ensure compatibility with the WordPress Plugin Repository guidelines, this plugin is also GPLv2 (or later) compatible.

**MIT License**: See the LICENSE file for the full text.

**GPLv2 or later**:
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/).

## Features

- Add any custom post type to favorites.
- AJAX-based toggle for adding/removing posts from favorites.
- Display a paginated list of favorite posts using a shortcode.
- User-friendly and lightweight.
- Customizable favorite button with text, icons (image or SVG), or both.
- Button state updates dynamically without page reload.
- Disabled button with a loading spinner during AJAX requests.
- Flexible display options for favorite lists (list, grid, etc.).

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
- `container_tag`: The HTML tag to use for the container of the favorite posts list (default: "ul").
- `container_class`: CSS class to apply to the container (optional).
- `item_class`: CSS class to apply to each item in the list (optional).
- `next_text`: Text to display on the "Next" pagination button (default: "Next").
- `prev_text`: Text to display on the "Previous" pagination button (default: "Previous").

**Example usage:**

Display a list of favorite posts for a custom post type "recettes" with 10 posts per page, using a `<div>` container styled as a grid:

```php
[favorite_posts
    post_type="recettes"
    posts_per_page="10"
    container_tag="div"
    container_class="favorite-recipes-grid"
    item_class="favorite-recipe-grid-item"
    next_text="Suivant"
    prev_text="Précédent"]
```

### Customization

#### Styling the Favorite Button

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

### Customizing the Favorites List

You can create custom templates for how each post in the favorites list is displayed by creating a template file in your theme with the name `favorite-{post_type}-item.php`. This allows you to fully customize the display of each favorite post for different custom post types.

For example, to customize the display for the `recettes` post type, create a file named `favorite-recettes-item.php` in your theme, and use it to control the HTML and PHP output for each favorite item.

This approach provides flexibility in designing the favorite lists, allowing you to adapt the presentation to your site's specific needs.
