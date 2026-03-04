# Sustainability Work - Flattened WordPress Site Cleanup

This repository contains course websites originally built in WordPress and then flattened to static HTML using `wget`. The `.zip` file should be ignored; only the site folders require processing.

## Cleanup Process for wget-Flattened WordPress Sites

When asked to "clean up this wget flattened WordPress site", follow this process:

### 1. Identify Duplicate Pages

wget captures both the WordPress `?p=ID` (query-parameter) URLs and the human-readable permalink URLs, creating duplicate HTML files for the same page. For example:
- `index.html?p=12.html` (WordPress post ID URL)
- `about/index.html` or `about.html` (human-readable URL)

**How to find duplicates:** Named pages contain a `<link rel="canonical" href="...">` tag pointing to their `?p=` equivalent. Extract this mapping to identify which `?p=` file corresponds to which named page.

Some sites may not have the `?p=` files on disk (wget didn't save them), but their canonical URLs still incorrectly reference the `?p=` versions. These canonicals still need fixing.

### 2. Remove Duplicates and Update Links

- **Keep** the human-readable URL (e.g., `about/index.html`, `about.html`)
- **Remove** the `?p=` version (e.g., `index.html?p=12.html`)
- **Update all internal links** across all HTML files to point to the kept version
- **Fix canonical URLs** so they self-reference the named page instead of the `?p=` version

**Critical bug to avoid:** When replacing links with regex, if there's an optional `#fragment` capture group, always check if it's `None` before concatenating. Use `fragment = '' if fragment is None else fragment` to prevent URLs like `policies/index.htmlNone`.

### 3. Remove Legacy WordPress Files

These files are artifacts of the WordPress CMS and serve no purpose in the static site:

| Category | Examples | Notes |
|----------|----------|-------|
| **wp-json/** | REST API endpoint files, oembed files | Entire directory tree |
| **feed/** | RSS feed XML/HTML files | Including `comments/feed/` |
| **author/** | Author archive pages | WordPress author listing pages |
| **comments/** | Comment-related pages | Comment feeds |
| **.htaccess** | Apache server config | Not needed for static hosting |
| **.DS_Store** | macOS filesystem metadata | |
| **wlwmanifest.xml** | Windows Live Writer manifest | In `wp-includes/` |
| **cookies-for-comments** | Plugin cache/tracking files | `css.php?k=...` files |

### 4. Clean Up HTML References to Removed Files

After deleting legacy files, remove the corresponding HTML tags that reference them:

- `<link rel="alternate" type="application/rss+xml" ...>` (RSS feed links)
- `<link rel="https://api.w.org/" ...>` (REST API discovery)
- `<link rel="alternate" type="application/json+oembed" ...>` and `text/xml+oembed` (oEmbed links)
- `<link rel="wlwmanifest" ...>` (Windows Live Writer)
- `<link rel="EditURI" ...>` (RSD/XML-RPC)
- `<link rel="shortlink" ...>` (WordPress shortlinks)
- `<link rel="pingback" ...>` (Pingback endpoint)
- `<img ... src="...cookies-for-comments/css.php..." ...>` (Tracking pixels)
- Author archive `<a>` tags → convert to plain text, keeping the author name
- `file:///` local path `<a>` tags → convert to plain text

### 5. Verify Links

After all changes, scan every HTML file and verify all `href` and `src` attributes point to existing files. Watch specifically for:
- `None` concatenation in URLs (the regex bug mentioned above)
- References to deleted files
- Broken relative paths

### Script

The `cleanup_wget_sites.py` script in this repo automates the full process. Run it from the repo root:

```bash
python3 cleanup_wget_sites.py
```

It processes all site directories, performs deduplication, cleans up legacy files, and verifies all links.

### Site Structure Patterns

Sites may use two different URL structures:
- **Flat:** Pages at root level as `about.html`, `policies.html`, etc.
- **Directory-based:** Pages in subdirectories as `about/index.html`, `policies/index.html`, etc.
- **Blog posts:** In date-based directories like `2023/01/26/post-title/index.html`

The cleanup script handles all three patterns.
