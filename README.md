# 📦 Resources Plugin for WordPress

A lightweight custom WordPress plugin that registers a **Resources** Custom Post Type, provides a responsive shortcode to display resources in a grid/list format, and supports fallback images, clean templating, and shortcode filtering options.

## 🚀 Features

### ✅ Custom Post Type: **Resources**
Includes:
- Title  
- Featured Image  
- Short Description (Excerpt)

### ✅ Shortcode: `[latest_resources]`

Displays the latest resources in a responsive grid.

### Shortcode Attributes:
| Attribute | Example | Description |
|----------|---------|-------------|
| `limit`  | `[latest_resources limit="10"]` | Number of items to show (default: 5) |
| `item`   | `[latest_resources item="87"]` | Show only a specific Resource post by ID |

### Behavior:
- If `item` is provided → shows **only that one post**
- If `item` is empty → shows latest posts using `limit`
- If featured image missing → a **default placeholder image** is used from `assets/images/placeholder.png`

### ✅ Single Resource Template
Displays:
- Title  
- Excerpt  
- Featured Image  
- Full Content  

### ✅ Clean Folder Structure
```
my-resources-plugin/
│
├── assets/
│   ├── css/
│   │   └── resources.css
│   └── images/
│       └── placeholder.png
│
├── includes/
│   └── shortcode-resources.php
│
├── my-resources-plugin.php
└── README.md
```

## 📥 Installation

1. Download or clone this repository:
```bash
git clone https://github.com/yourusername/my-resources-plugin.git
```

2. Place the folder in:
```
/wp-content/plugins/my-resources-plugin/
```

3. Activate it in **WordPress Admin → Plugins**

4. Add the shortcode to any page:
```
[latest_resources]
```

## 🧩 Usage Examples

### Show 5 latest resources (default)
```
[latest_resources]
```

### Show 12 latest resources
```
[latest_resources limit="12"]
```

### Show only resource with post ID = 42
```
[latest_resources item="42"]
```

### Show specific item (item takes priority over limit)
```
[latest_resources limit="20" item="42"]
```

## 🎨 Styling

All CSS is stored in:
```
assets/css/resources.css
```

You can freely modify this file to match your theme.

## 📁 Placeholder Image

If a resource doesn’t have a featured image, the plugin automatically loads:
```
assets/images/placeholder.png
```

## 🧹 Security & Coding Standards

- Follows **WordPress Coding Standards**
- Uses `sanitize_*()` and `esc_*()` functions
- Enqueues styles with `wp_enqueue_style()`

## 🔧 Requirements

- PHP 7.4+  
- WordPress 5.0+


