#!/usr/bin/env python3
"""
Cleanup script for wget-flattened WordPress sites.

For each site directory:
1. Find duplicate pages (index.html?p=NNN.html files that duplicate named pages)
2. Remove the ?p= duplicates and update all internal links
3. Fix canonical URLs to point to the clean named pages
4. Remove legacy WordPress files (wp-json/, feed/, comments/feed/, etc.)
5. Clean up dangling references to removed files in HTML <head> sections
"""

import os
import re
import shutil
import sys
import glob
import urllib.parse


def find_site_dirs(base_dir):
    """Find all site directories (folders, not zip files)."""
    sites = []
    for entry in sorted(os.listdir(base_dir)):
        full_path = os.path.join(base_dir, entry)
        if os.path.isdir(full_path) and not entry.startswith('.'):
            sites.append(entry)
    return sites


def find_html_files(site_dir):
    """Find all HTML files in a site directory."""
    html_files = []
    for root, dirs, files in os.walk(site_dir):
        for f in files:
            if f.endswith('.html'):
                html_files.append(os.path.join(root, f))
    return html_files


def build_duplicate_map(site_dir):
    """
    Build a mapping from ?p=NNN files to their named-page equivalents.

    Strategy: Named pages (about/index.html, 2023/01/26/post-name/index.html, etc.)
    contain canonical URLs pointing to index.html?p=NNN.html. We use these canonical
    URLs to build the reverse mapping: ?p=NNN -> named page path.

    Returns dict: { "index.html?p=12.html": "about/index.html", ... }
    (paths relative to site_dir)
    """
    duplicate_map = {}

    for root, dirs, files in os.walk(site_dir):
        for f in files:
            if not f.endswith('.html'):
                continue
            if 'index.html?p=' in f:
                continue
            rel_root = os.path.relpath(root, site_dir)
            if 'wp-json' in rel_root:
                continue

            filepath = os.path.join(root, f)
            try:
                with open(filepath, 'r', encoding='utf-8', errors='replace') as fh:
                    content = fh.read()
            except Exception:
                continue

            canon_match = re.search(r'<link\s+rel="canonical"\s+href="([^"]+)"', content)
            if not canon_match:
                continue

            canon_url = urllib.parse.unquote(canon_match.group(1))
            if 'index.html?p=' not in canon_url:
                continue

            p_match = re.search(r'(index\.html\?p=\d+(?:\.html)?)', canon_url)
            if not p_match:
                continue

            p_filename = p_match.group(1)
            named_path = os.path.relpath(filepath, site_dir)

            p_filepath = os.path.join(site_dir, p_filename)
            if os.path.exists(p_filepath):
                duplicate_map[p_filename] = named_path

    return duplicate_map


def build_canonical_map(site_dir):
    """
    Build a mapping from ?p= canonical references to their named pages,
    even when the ?p= files don't exist on disk. This handles sites
    where wget didn't save the ?p= versions but the canonical URLs still
    reference them.

    Returns dict: { "index.html?p=12.html": "about.html", ... }
    """
    canon_map = {}

    for root, dirs, files in os.walk(site_dir):
        for f in files:
            if not f.endswith('.html'):
                continue
            if 'index.html?p=' in f:
                continue
            rel_root = os.path.relpath(root, site_dir)
            if 'wp-json' in rel_root:
                continue
            if '/feed' in os.path.relpath(os.path.join(root, f), site_dir):
                continue

            filepath = os.path.join(root, f)
            try:
                with open(filepath, 'r', encoding='utf-8', errors='replace') as fh:
                    content = fh.read()
            except Exception:
                continue

            canon_match = re.search(r'<link\s+rel="canonical"\s+href="([^"]+)"', content)
            if not canon_match:
                continue

            canon_url = urllib.parse.unquote(canon_match.group(1))
            if 'index.html?p=' not in canon_url:
                continue

            p_match = re.search(r'(index\.html\?p=\d+(?:\.html)?)', canon_url)
            if not p_match:
                continue

            p_filename = p_match.group(1)
            named_path = os.path.relpath(filepath, site_dir)
            canon_map[p_filename] = named_path

    return canon_map


def compute_relative_path(from_file, to_file):
    """Compute relative path from from_file to to_file (both relative to site root)."""
    from_dir = os.path.dirname(from_file)
    return os.path.relpath(to_file, from_dir)


def update_links_in_file(filepath, site_dir, link_replacements):
    """
    Update internal links in an HTML file.
    link_replacements: { "index.html?p=NNN.html": "named/path.html" }
    """
    try:
        with open(filepath, 'r', encoding='utf-8', errors='replace') as f:
            content = f.read()
    except Exception:
        return False

    original_content = content
    rel_file = os.path.relpath(filepath, site_dir)

    for p_file, named_file in link_replacements.items():
        rel_to_p = compute_relative_path(rel_file, p_file)
        rel_to_named = compute_relative_path(rel_file, named_file)

        rel_to_p_encoded = rel_to_p.replace('?', '%3F')

        def safe_replace(content, old, new):
            """Replace references, preserving any #fragment and avoiding None.

            The (?!\\d) lookahead prevents ?p=12 from partially matching ?p=121
            when the filename lacks a .html suffix terminator.
            """
            pattern = re.escape(old) + r'(?!\d)(#[^"\'>\s]*)?'

            def replacer(m):
                fragment = m.group(1)
                if fragment is None:
                    fragment = ''
                return new + fragment

            return re.sub(pattern, replacer, content)

        content = safe_replace(content, rel_to_p_encoded, rel_to_named)
        content = safe_replace(content, rel_to_p, rel_to_named)

    # Fix canonical URLs pointing to ?p= files
    for p_file, named_file in link_replacements.items():
        p_encoded = p_file.replace('?', '%3F')

        def fix_canonical(content, p_ref, named):
            pattern = r'(<link\s+rel="canonical"\s+href=")([^"]*?' + re.escape(p_ref) + r')(")'

            def replacer(m):
                if rel_file == named:
                    return m.group(1) + os.path.basename(named) + m.group(3)
                else:
                    return m.group(1) + compute_relative_path(rel_file, named) + m.group(3)

            return re.sub(pattern, replacer, content)

        content = fix_canonical(content, p_encoded, named_file)
        content = fix_canonical(content, p_file, named_file)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        return True
    return False


def clean_html_references(site_dir):
    """
    Remove/fix references in HTML files that point to removed legacy files.
    This cleans up <link> tags for feeds, wp-json, wlwmanifest, etc.,
    as well as cookies-for-comments references, author links, and file:// URLs.
    """
    html_files = find_html_files(site_dir)
    updated = 0

    for hf in html_files:
        try:
            with open(hf, 'r', encoding='utf-8', errors='replace') as f:
                content = f.read()
        except Exception:
            continue

        original = content

        # Remove <link> tags for RSS feeds (feed/index.html, comments/feed/index.html)
        content = re.sub(
            r'<link\s+rel="alternate"\s+type="application/rss\+xml"[^>]*href="[^"]*feed/index\.html"[^>]*/>\s*\n?',
            '', content
        )

        # Remove <link> tags for REST API (wp-json)
        content = re.sub(
            r'<link\s+rel=["\']https://api\.w\.org/["\'][^>]*/?>\s*\n?',
            '', content
        )

        # Remove <link> tags for oembed
        content = re.sub(
            r'<link\s+rel="alternate"\s+type="(?:application/json|text/xml)\+oembed"[^>]*/>\s*\n?',
            '', content
        )

        # Remove <link> tags for wlwmanifest
        content = re.sub(
            r'<link\s+rel="wlwmanifest"[^>]*/>\s*\n?',
            '', content
        )

        # Remove <link> tags for EditURI/RSD
        content = re.sub(
            r'<link\s+rel="EditURI"[^>]*/>\s*\n?',
            '', content
        )

        # Remove shortlink meta tags pointing to ?p= URLs
        content = re.sub(
            r'<link\s+rel=["\']shortlink["\'][^>]*href="[^"]*\?p=\d+"[^>]*/>\s*\n?',
            '', content
        )

        # Remove pingback link
        content = re.sub(
            r'<link\s+rel="pingback"[^>]*/>\s*\n?',
            '', content
        )

        # Remove cookies-for-comments <link> tags (stylesheet references to css.php)
        content = re.sub(
            r'<link\s+[^>]*href="[^"]*cookies-for-comments/css\.php[^"]*"[^>]*/>\s*\n?',
            '', content
        )

        # Remove cookies-for-comments tracking pixel <img> tags
        content = re.sub(
            r'<img\s+[^>]*src="[^"]*cookies-for-comments/css\.php[^"]*"[^>]*/?>',
            '', content
        )

        # Convert author archive links to plain text (keep the author name, remove the <a> wrapper)
        # Pattern: <a class="url fn n" href="...author/name/index.html">Author Name</a>
        content = re.sub(
            r'<a\s+[^>]*href="[^"]*author/[^"]*"[^>]*>(.*?)</a>',
            r'\1', content
        )

        # Remove file:// URL references (local filesystem references that shouldn't be in the site)
        content = re.sub(
            r'<a\s+href="file:///[^"]*"[^>]*>(.*?)</a>',
            r'\1', content
        )

        # Fix YouTube embeds: add referrerpolicy to prevent Error 153
        # YouTube requires a valid Referer header; without referrerpolicy,
        # static sites trigger "Error 153: Video player configuration error"
        content = re.sub(
            r'(<iframe\s[^>]*src="https?://(?:www\.)?youtube(?:-nocookie)?\.com/embed/[^"]*"[^>]*?)(/?>)',
            lambda m: m.group(1) + ' referrerpolicy="strict-origin-when-cross-origin"' + m.group(2)
                if 'referrerpolicy' not in m.group(1) else m.group(0),
            content
        )

        # Remove TinyMCE editor artifacts (bookmark spans left by WordPress WYSIWYG editor)
        content = re.sub(
            r'<span\s+data-mce-type="bookmark"[^>]*>\ufeff</span>',
            '', content
        )

        if content != original:
            with open(hf, 'w', encoding='utf-8') as f:
                f.write(content)
            updated += 1

    return updated


def remove_legacy_wordpress_files(site_dir):
    """
    Remove legacy WordPress files that aren't needed for static sites.
    """
    removed = []

    dirs_to_remove = []

    for root, dirs, files in os.walk(site_dir, topdown=False):
        rel_root = os.path.relpath(root, site_dir)

        # Remove wp-json directories
        if 'wp-json' in rel_root.split(os.sep):
            dirs_to_remove.append(root)
            continue

        # Remove feed directories (RSS feeds)
        if os.path.basename(root) == 'feed':
            dirs_to_remove.append(root)
            continue

        # Remove author directories
        if 'author' in rel_root.split(os.sep):
            dirs_to_remove.append(root)
            continue

        # Remove comments directories
        if 'comments' in rel_root.split(os.sep):
            dirs_to_remove.append(root)
            continue

    # Remove specific files
    for root, dirs, files in os.walk(site_dir):
        for f in files:
            filepath = os.path.join(root, f)
            rel_path = os.path.relpath(filepath, site_dir)
            should_remove = False

            if f == '.htaccess':
                should_remove = True
            elif f == '.DS_Store':
                should_remove = True
            elif f == 'wlwmanifest.xml':
                should_remove = True
            elif 'cookies-for-comments' in rel_path and f.startswith('css.php'):
                should_remove = True

            if should_remove:
                os.remove(filepath)
                removed.append(rel_path)

    # Remove directories
    for d in sorted(set(dirs_to_remove), key=len, reverse=True):
        if os.path.exists(d):
            rel_d = os.path.relpath(d, site_dir)
            file_count = sum(1 for _, _, files in os.walk(d) for _ in files)
            shutil.rmtree(d)
            removed.append(f"{rel_d}/ ({file_count} files)")

    # Clean up empty directories
    for root, dirs, files in os.walk(site_dir, topdown=False):
        if not os.listdir(root) and root != site_dir:
            os.rmdir(root)
            removed.append(os.path.relpath(root, site_dir) + "/ (empty)")

    return removed


def process_site(site_dir):
    """Process a single site: find duplicates, remove them, update links."""
    print(f"\n{'='*60}")
    print(f"Processing: {os.path.basename(site_dir)}")
    print(f"{'='*60}")

    # Step 1: Build duplicate map (for sites that have ?p= files)
    dup_map = build_duplicate_map(site_dir)

    if dup_map:
        print(f"\nFound {len(dup_map)} duplicate ?p= files:")
        for p_file, named_file in sorted(dup_map.items()):
            print(f"  {p_file} -> {named_file}")
    else:
        print("\nNo ?p= duplicate files found on disk.")

    # Step 2: Also build canonical map for sites where ?p= files don't exist
    # but canonical URLs still reference them
    canon_map = build_canonical_map(site_dir)
    # Merge: dup_map takes priority (files exist), but canon_map fills gaps
    full_map = {**canon_map, **dup_map}

    extra_canons = {k: v for k, v in canon_map.items() if k not in dup_map}
    if extra_canons:
        print(f"\nFound {len(extra_canons)} canonical URL references to fix (no ?p= files on disk):")
        for p_file, named_file in sorted(extra_canons.items()):
            print(f"  {p_file} -> {named_file}")

    # Step 3: Update links in ALL HTML files
    html_files = find_html_files(site_dir)
    updated_count = 0
    for hf in html_files:
        rel_hf = os.path.relpath(hf, site_dir)
        if rel_hf in dup_map:
            continue
        if update_links_in_file(hf, site_dir, full_map):
            updated_count += 1

    print(f"\nUpdated links/canonicals in {updated_count} files.")

    # Step 4: Remove duplicate ?p= files
    removed_dups = []
    for p_file in dup_map:
        p_filepath = os.path.join(site_dir, p_file)
        if os.path.exists(p_filepath):
            os.remove(p_filepath)
            removed_dups.append(p_file)

    if removed_dups:
        print(f"\nRemoved {len(removed_dups)} duplicate files.")

    # Step 5: Remove legacy WordPress files
    legacy_removed = remove_legacy_wordpress_files(site_dir)
    if legacy_removed:
        print(f"\nRemoved {len(legacy_removed)} legacy WordPress files/directories.")

    # Step 6: Clean up HTML references to removed files
    html_cleaned = clean_html_references(site_dir)
    print(f"Cleaned dangling references in {html_cleaned} HTML files.")

    # Step 7: Strip ?ver= query strings from asset filenames and HTML refs
    assets_renamed, refs_updated = strip_asset_query_strings(site_dir)
    if assets_renamed or refs_updated:
        print(f"Renamed {assets_renamed} asset files, updated refs in {refs_updated} HTML files.")

    return dup_map, updated_count, removed_dups, legacy_removed, html_cleaned


def strip_asset_query_strings(site_dir):
    """
    Strip ?<query> suffixes from asset filenames (CSS, JS, fonts, etc.) saved
    by wget with literal '?' in the filename. Over file://, browsers treat '?'
    as a query string separator, so 'style.css?ver=1.2' fails to load. Even when
    HTML references use %3F encoding, the mangled file extension (e.g. .js?ver=1.2
    ends in .2) can confuse MIME type detection.

    Renames files to strip everything from the first '?' onward, then updates
    every href/src in HTML files to use the clean path.
    """
    import filecmp

    renamed = 0
    for root, dirs, files in os.walk(site_dir):
        for f in list(files):
            if '?' not in f:
                continue
            old_path = os.path.join(root, f)
            new_name = f.split('?', 1)[0]
            if not new_name:
                continue
            new_path = os.path.join(root, new_name)
            if os.path.exists(new_path):
                # Conflict: only resolve if identical content
                if filecmp.cmp(old_path, new_path, shallow=False):
                    os.remove(old_path)
                    renamed += 1
                else:
                    print(f"  WARN: skipped rename (differs): {os.path.relpath(old_path, site_dir)}")
                continue
            os.rename(old_path, new_path)
            renamed += 1

    # Build set of existing files (relative to site_dir)
    existing = set()
    for root, dirs, files in os.walk(site_dir):
        for f in files:
            existing.add(os.path.relpath(os.path.join(root, f), site_dir))

    refs_updated = 0
    for hf in find_html_files(site_dir):
        rel_hf = os.path.relpath(hf, site_dir)
        hf_dir = os.path.dirname(rel_hf)
        try:
            with open(hf, 'r', encoding='utf-8', errors='replace') as fh:
                content = fh.read()
        except Exception:
            continue
        original = content

        def fix_ref(m):
            attr = m.group(1)
            quote = m.group(2)
            url = m.group(3)
            if url.startswith(('http://', 'https://', 'mailto:', 'javascript:',
                              'data:', '#', '//', 'file://')):
                return m.group(0)
            # Split off fragment
            frag = ''
            if '#' in url:
                url_path, frag_only = url.split('#', 1)
                frag = '#' + frag_only
            else:
                url_path = url
            # Find first '?' or '%3F' (case-insensitive) in the URL path
            qm = re.search(r'\?|%3[Ff]', url_path)
            if not qm:
                return m.group(0)
            # If the ?-versioned file still exists on disk (rename skipped due to
            # content conflict), keep the ref pointing to it - do not strip.
            full_decoded = urllib.parse.unquote(url_path)
            full_target = os.path.normpath(os.path.join(hf_dir, full_decoded))
            if full_target in existing:
                return m.group(0)
            prefix = url_path[:qm.start()]
            decoded_prefix = urllib.parse.unquote(prefix)
            target_rel = os.path.normpath(os.path.join(hf_dir, decoded_prefix))
            if target_rel in existing:
                return f'{attr}={quote}{prefix}{frag}{quote}'
            return m.group(0)

        content = re.sub(
            r'(href|src)=(["\'])([^"\']*)\2',
            fix_ref, content
        )

        if content != original:
            with open(hf, 'w', encoding='utf-8') as fh:
                fh.write(content)
            refs_updated += 1

    return renamed, refs_updated


def verify_links(site_dir):
    """
    Verify that all internal links in HTML files point to existing files.
    Returns list of broken links (ignoring expected missing references).
    """
    broken = []
    html_files = find_html_files(site_dir)

    for hf in html_files:
        try:
            with open(hf, 'r', encoding='utf-8', errors='replace') as f:
                content = f.read()
        except Exception:
            continue

        rel_file = os.path.relpath(hf, site_dir)
        file_dir = os.path.dirname(hf)

        for match in re.finditer(r'(?:href|src)="([^"]*)"', content):
            url = match.group(1)

            # Skip external URLs, mailto, javascript, data URIs, anchors-only, file://
            if url.startswith(('http://', 'https://', 'mailto:', 'javascript:',
                              'data:', '#', '//', 'file://')):
                continue
            if not url:
                continue

            # Strip fragment
            url_no_frag = url.split('#')[0]
            if not url_no_frag:
                continue

            # Check for None concatenation bug
            if 'None' in url:
                broken.append((rel_file, url, "Contains 'None' - likely regex bug"))
                continue

            # Resolve relative path - handle %3F in filenames
            # Files on disk may have literal ? in names, and HTML may reference
            # them with %3F encoding. Both are valid.
            target = os.path.normpath(os.path.join(file_dir, url_no_frag))
            target_decoded = os.path.normpath(
                os.path.join(file_dir, urllib.parse.unquote(url_no_frag))
            )

            if not os.path.exists(target) and not os.path.exists(target_decoded):
                # Classify the broken link
                if 'wp-json' in url or 'feed/' in url or 'wlwmanifest' in url:
                    # Expected - legacy WP references that may remain in some places
                    continue
                if 'wp-includes/js/jquery' in url:
                    # jQuery loaded from WordPress - was never saved by wget typically
                    continue
                broken.append((rel_file, url, "File not found"))

    return broken


def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))

    sites = find_site_dirs(base_dir)
    print(f"Found {len(sites)} site directories:")
    for s in sites:
        print(f"  {s}")

    all_results = {}
    for site in sites:
        site_path = os.path.join(base_dir, site)
        results = process_site(site_path)
        all_results[site] = results

    # Verification pass
    print(f"\n{'='*60}")
    print("VERIFICATION: Checking all internal links...")
    print(f"{'='*60}")

    total_broken = 0
    for site in sites:
        site_path = os.path.join(base_dir, site)
        broken = verify_links(site_path)
        if broken:
            print(f"\n{site}: {len(broken)} broken link(s):")
            for file_name, url, reason in broken[:30]:
                print(f"  {file_name}: {url} ({reason})")
            if len(broken) > 30:
                print(f"  ... and {len(broken) - 30} more")
            total_broken += len(broken)
        else:
            print(f"\n{site}: All links OK!")

    if total_broken > 0:
        print(f"\nWARNING: {total_broken} total broken links found!")
    else:
        print(f"\nAll links verified successfully!")

    # Summary
    print(f"\n{'='*60}")
    print("SUMMARY")
    print(f"{'='*60}")
    for site, (dup_map, updated, removed_dups, legacy, html_cleaned) in all_results.items():
        print(f"\n{site}:")
        print(f"  Duplicates found and removed: {len(removed_dups)}")
        print(f"  Files with updated links: {updated}")
        print(f"  Legacy WP files removed: {len(legacy)}")
        print(f"  HTML files with cleaned references: {html_cleaned}")


if __name__ == '__main__':
    main()
