=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9.8
Tested up to: 4.9
Stable tag: 4.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A new editing experience for WordPress is in the works, with the goal of making it easier than ever to make your words, pictures, and layout look just right. This is the beta plugin for the project.

== Description ==

Gutenberg is more than an editor. While the editor is the focus right now, the project will ultimately impact the entire publishing experience including customization (the next focus area).

<a href="https://wordpress.org/gutenberg">Discover more about the project</a>.

= Editing focus =

> The editor will create a new page- and post-building experience that makes writing rich posts effortless, and has “blocks” to make it easy what today might take shortcodes, custom HTML, or “mystery meat” embed discovery. — Matt Mullenweg

One thing that sets WordPress apart from other systems is that it allows you to create as rich a post layout as you can imagine -- but only if you know HTML and CSS and build your own custom theme. By thinking of the editor as a tool to let you write rich posts and create beautiful layouts, we can transform WordPress into something users _love_ WordPress, as opposed something they pick it because it's what everyone else uses.

Gutenberg looks at the editor as more than a content field, revisiting a layout that has been largely unchanged for almost a decade.This allows us to holistically design a modern editing experience and build a foundation for things to come.

Here's why we're looking at the whole editing screen, as opposed to just the content field:

1. The block unifies multiple interfaces. If we add that on top of the existing interface, it would _add_ complexity, as opposed to remove it.
2. By revisiting the interface, we can modernize the writing, editing, and publishing experience, with usability and simplicity in mind, benefitting both new and casual users.
3. When singular block interface takes center stage, it demonstrates a clear path forward for developers to create premium blocks, superior to both shortcodes and widgets.
4. Considering the whole interface lays a solid foundation for the next focus, full site customization.
5. Looking at the full editor screen also gives us the opportunity to drastically modernize the foundation, and take steps towards a more fluid and JavaScript powered future that fully leverages the WordPress REST API.

= Blocks =

Blocks are the unifying evolution of what is now covered, in different ways, by shortcodes, embeds, widgets, post formats, custom post types, theme options, meta-boxes, and other formatting elements. They embrace the breadth of functionality WordPress is capable of, with the clarity of a consistent user experience.

Imagine a custom “employee” block that a client can drag to an About page to automatically display a picture, name, and bio. A whole universe of plugins that all extend WordPress in the same way. Simplified menus and widgets. Users who can instantly understand and use WordPress  -- and 90% of plugins. This will allow you to easily compose beautiful posts like <a href="http://moc.co/sandbox/example-post/">this example</a>.

Check out the <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">FAQ</a> for answers to the most common questions about the project.

= Compatibility =

Posts are backwards compatible, and shortcodes will still work. We are continuously exploring how highly-tailored metaboxes can be accommodated, and are looking at solutions ranging from a plugin to disable Gutenberg to automatically detecting whether to load Gutenberg or not. While we want to make sure the new editing experience from writing to publishing is user-friendly, we’re committed to finding  a good solution for highly-tailored existing sites.

= The stages of Gutenberg =

Gutenberg has three planned stages. The first, aimed for inclusion in WordPress 5.0, focuses on the post editing experience and the implementation of blocks. This initial phase focuses on a content-first approach. The use of blocks, as detailed above, allows you to focus on how your content will look without the distraction of other configuration options. This ultimately will help all users present their content in a way that is engaging, direct, and visual.

These foundational elements will pave the way for stages two and three, planned for the next year, to go beyond the post into page templates and ultimately, full site customization.

Gutenberg is a big change, and there will be ways to ensure that existing functionality (like shortcodes and meta-boxes) continue to work while allowing developers the time and paths to transition effectively. Ultimately, it will open new opportunities for plugin and theme developers to better serve users through a more engaging and visual experience that takes advantage of a toolset supported by core.

= Contributors =

Gutenberg is built by many contributors and volunteers. Please see the full list in <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTORS.md">CONTRIBUTORS.md</a>.

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

We'd love to hear your bug reports, feature suggestions and any other feedback! Please head over to <a href="https://github.com/WordPress/gutenberg/issues">the GitHub issues page</a> to search for existing issues or open a new one. While we'll try to triage issues reported here on the plugin forum, you'll get a faster response (and reduce duplication of effort) by keeping everything centralized in the GitHub repository.

= How can I contribute? =

We’re calling this editor project "Gutenberg" because it's a big undertaking. We are working on it every day in GitHub, and we'd love your help building it.You’re also welcome to give feedback, the easiest is to join us in <a href="https://make.wordpress.org/chat/">our Slack channel</a>, `#core-editor`.

See also <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTING.md">CONTRIBUTING.md</a>.

= Where can I read more about Gutenberg? =

- <a href="http://matiasventura.com/post/gutenberg-or-the-ship-of-theseus/">Gutenberg, or the Ship of Theseus</a>, with examples of what Gutenberg might do in the future
- <a href="https://make.wordpress.org/core/2017/01/17/editor-technical-overview/">Editor Technical Overview</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/design-principles/">Design Principles and block design best practices</a>
- <a href="https://github.com/Automattic/wp-post-grammar">WP Post Grammar Parser</a>
- <a href="https://make.wordpress.org/core/tag/gutenberg/">Development updates on make.wordpress.org</a>
- <a href="https://wordpress.org/gutenberg/handbook/">Documentation: Creating Blocks, Reference, and Guidelines</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">Additional frequently asked questions</a>


== Changelog ==

= Latest =

## Changelog

* Implement a block navigation system that allows selecting child or parent blocks within nested blocks (like folder path traversal) as well as functioning as a general fast navigation system when a root block is selected.
* Add a Media & Text block that can facilitate the creation of split column content and allows the split to be resizable.
* Show block style selector in the block inspector.
* Rename Cover Image to just Cover and add support for video backgrounds.
* Add a new accessible Date Picker. This was months in the works.
* Reimplement the Color Picker component to greatly improve keyboard navigation and screenreader operations.
* Add style variation for Table block with stripe design.
* Add “Options” modal to toggle on/off the different document panels.
* Allow toggling visibility of registered meta-boxes from the “Options” modal.
* Handle cases where a block is on the page but the block is not registered by showing a dialog with the available options.
* Ensure compatibility with WordPress 5.0.
* When pasting single lines of text, treat them as inline text.
* Add ability to insert images from URL directly in the Image block.
* Make Columns block responsive.
* Make responsive embeds a theme option.
* Add direction attribute / LTR button to the Paragraph block.
* Display accurate updated and publish notices based on post type.
* Update buttons in the editor header area to improve consistency (save, revert to draft, etc).
* Avoid horizontal writing flow navigation in native inputs.
* Move toggle buttons to the left of their control handle.
* Add explicit bottom margin to figure elements (like image and embed).
* Allow transforming a Pullquote to a Quote and viceversa.
* Allow block inserter to search for blocks by typing their category.
* Add a label to the URL field in the Publishing Flow panel.
* Use the stored date format in settings for the LatestPosts block.
* Remove the placeholder text and use visible label instead in TokenField.
* Add translator comment for “View” menu label.
* Make YouTube embed classes consistent between front-end and back-end.
* Take into account citation when transforming a Quote to a Paragraph.
* Restore ⌘A’s “select all blocks” behaviour.
* Allow themes to disable custom font size functionality.
* Make missing custom font sizes labels translatable.
* Ensure cite is string when merging quote.
* Defer fetching non-hierarchical terms in FlatTermSelector.
* Move the theme support data previously exposed at the REST API index into a read-only theme controller for the active theme.
* Detect oEmbed responses where the oEmbed provider is missing.
* Use “Save as Pending” when the Pending checkbox is active.
* Use the post type’s REST controller class as autosave parent controller.
* Use post type labels in PostFeaturedImage component.
* Enforce text color within inline boundaries to ensure contrast and legibility.
* Add self-closing tag support (like path element) when comparing HTML.
* Make sure autocomplete triggers are regex safe.
* Silence PHP errors on REST API responses.
* Show permalink label as bold text.
* Change the block renderer controller endpoint and namespace from /gutenberg/v1/block-renderer/ to /wp/v2/block-renderer/.
* Hide “edit image” toolbar buttons when no image is selected.
* Hide “Add to Reusable Blocks” action when ‘core/block’ is disabled.
* Handle blocks passing null as RichText value.
* Improve validation for attribute names in rich-text toHTMLString.
* Allow to globally overwrite defined colors in PanelColorSettings.
* Fix regressions with Button block preview display.
* Fix issue with color picker not appearing on mobile.
* Fix publish buttons with long text.
* Fix link to manifest file in contributing file.
* Fix demo content crash on malformed URL.
* Fix issue in docs manifest.
* Fix media caption processing with the new RichText structure.
* Fix problem with Gallery losing assigned columns when alignments are applied.
* Fix an issue where the Categories block would always use the center class alignment regardless of what was set.
* Fix scroll issue on small viewports.
* Fix formatting in getEditorSettings docs and update getTokenSettings docs.
* Fix padding in block validation modal.
* Fix extra instances of old rich text value source.
* Fix issue with adding links from the auto-completer.
* Fix outdated docs for RichText.
* Fix pre-publish panel overflow issue.
* Fix missing styles for medium and huge font size classes.
* Fix autocomplete keyboard navigation in link popover.
* Fix a text selection exception in Safari.
* Fix WordPress embed URL resolution and embeds as reusable blocks.
* Avoid triggering a redirect when creating a new Table block.
* Only use rich text value internally to the RichText component.
* Ensure multiline prop is either “p” or “li” in RichText.
* Do not use dangerouslySetInnerHTML with i18n string.
* Account for null value in redux-routine createRuntime.
* Extract link container from RichText.
* Allow default_title, default_content, and default_excerpt filters to function as expected.
* Replace gutenberg in classNames with block-editor.
* Restore the order of actions usually fired in edit-form-advanced.php.
* Update REST Search controller to use ‘wp/v2’ namespace.
* Improve keyboard shortcuts section in FAQ.
* Change all occurrences of ‘new window’ to ‘new tab’.
* Conditionally load PHP classes in preparation for inclusion in core.
* Update rich-text source mentions in docs.
* Deprecate PanelColor components.
* Use mock response for demo test if Vimeo is down.
* Adding a bit more verbosity to the install script instructions.
* Document isDefault option for block styles.
* Update docs for new REST API namespace.
* Update shortcut docs with new block navigation menu shortcut.
* Further improve Release docs.
* Updated custom icon documentation links.
* Add all available script handles to documentation.
* Add wp-polyfill to scripts.md.
* Add e2e tests for List and Quote transformations.
* Fail CI build if local changes exist.
* Attempt to always use the latest version of nvm.
* Add bare handling for lint-js script.
* Add support for Webpack bundle analyzer.
* Improve setup of Lerna.
* Update clipboard dependency to at least 2.0.1.
* Recreate the demo content post as an e2e test using keyboard commands.
* Add mobile SVG compatibility for SVG block icons.
* Fix className style in SVG primitive.
* Split Paragraph and Heading blocks on enter.KEY. Refactor block splitting code on paragraph and heading blocks.
* Add caption support for image block.
* Rename PHP functions to prevent conflict with core
* Fix some typos
* Improve the Toggle Control elements DOM order for better accessibility
* Set correct media type for video poster image and manage focus properly.
* Implement fetchAllMiddleware to handle per_page=-1 through pagination in wp.apiFetch
* Fix Slash autocomplete: Support non-Latin inputs
* Add WordPress logo animation for preview
