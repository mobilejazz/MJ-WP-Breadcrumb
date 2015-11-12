# MJ-WP-Breadcrumb

A lightweight, customisable function to generate and display a breadcrumb for WordPress.

The MJ WordPress Breadcrumb is a quick and easy to use function for creating a WordPress trail. It features support for:

* Static and blog home pages
* Blog and archives (categories, tags, dates and authors)
* Pages and parent pages
* Attachments
* 404 pages
* Search result pages

This function simply generates a list of links and returns or echos it. There is no styling, however this can be used out-of-the-box with Bootstrap and Foundation.

## Usage

Simply drop this function into your theme's `functions.php` file and call `mj_wp_breadcrumb()` where you wish to display the breadcrumb in your theme's template files.

There are a few parameters you can pass to alter the breadcrumb:

```
mj_wp_breadcrumb( $list_style, $list_id, $list_class, $active_class, $echo )
```

The default values are:

```
$list_style = 'ol'           // ol or ul
$list_id = 'breadcrumb'      // An id applied to the list
$list_class = 'breadcrumb'   // A class applied to the list
$active_class = 'active'     // A class applied to the list-item of the current page
$echo = false                // false to return, true to echo
```

### For Use with Bootstrap

To use this function with Bootstrap, call the function with these parameters:

```
mj_wp_breadcrumb( 'ol', 'breadcrumb', 'breadcrumb, 'active', true )
```

### For Use with Foundation

To use this function with Foundation, call the function with these parameters:

```
mj_wp_breadcrumb( 'ul', 'breadcrumb', 'breadcrumbs, 'current', true )
```

## Support

If your having trouble using MJ-WP-Breadcrumb, please raise an issue and we'll be happy to help!

## How to Contribute Using GitHub

### Raising Issues

If you've found an issue that's great, please let us know as we're always looking to improve. Please provide as much information about the bug as you can and where possible, include a url which demonstrates the issue.

### Pull Requests

Pull request are welcome, please make sure your modifications are well tested.

### Feature Requests

If you have an idea or a request, you may raise an issue. Please provide as much detail as possible, demos and examples would be fantastic if appropriate.

## Todo & Possible Future Features

* Custom Post Type and Custom Taxonomy Support

## Version History

### 1.0
*November 12, 2015*

Initial release.
