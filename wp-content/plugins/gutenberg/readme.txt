=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9.8
Tested up to: 4.9
Stable tag: 4.4.0
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

* Add relevant attribute data from images to be used server side to handle things like theme specific responsive media.
* In order to be able to use srcset and sizes on the front end, wp-image-### CSS class has been added to the media and text block.
* Add minimal multi-selection block panel to replace “Coming Soon” message. It shows word and block count for the selection.
* Exclude reusable blocks from the global block count in Document Outline.
* Upgrade admin notices to use Notices module at runtime. It attempts to seamlessly upgrade notices output via an admin_notices or all_admin_notices action server-side.
* Adjust the prefix transforms so that they only execute when they match text right at the caret so that they are undoable. Also makes it faster by checking if the previous character is a space.
* Add ability to specify a different default editor font per locale.
* Add link rel and link class settings to Image block inspector.
* Transform an Image and Audio block to an Embed block if the URL matches an embed.
* Respect the “Disable Visual Editor” setting per user.
* Make it easy to access image IDs server-side on the Gallery block.
* Recursively step through edits to track individually changed post meta in Block API. This prevents saving the default value for each registered meta when only one of them is changed.
* Perform a complete draft save on preview.
* Save all meta-boxes when clicking the preview button. Set preview URL only after saving is complete.
* Disable hover interaction on mobile to improve scrolling.
* Update the displayed permalink when the slug is cleared.
* When converting to blocks, place unhandled HTML within an HTML block.
* Ensure content that cannot be handled in quotes is preserved within an HTML block.
* Localize the DateTimePicker Component.
* Fixes the behavior of link validation including properly handling URL fragments, validating forward slashes in HTTP URLs, more strictness to match getProtocol, addressing false positives in E2E tests.
* Fix issue where existing reusable blocks on a post would not render if the user was an author or a contributor. This happens because requests to fetch a single block or post are blocked when ?context=edit is passed and the current user is not an editor.
* Make sure the media library collection is refreshed when a user uploads media outside of the Media Library workflow (i.e. file drops, file uploads, etc).
* Update the editor reducer so that RESET_BLOCKS will only remove blocks that are actually in the post.
* It used to be possible to add a reusable block inside the same reusable block in the UI, e.g. someone could create a column block inside another column block. Now it is not.
* Deleting after certain types of selection was causing the caret to appear in the wrong place, now that it fixed, along with unexpected behavior of Ctrl+A after other kinds of selection, and the associated E2E tests updated.
* Remove permalink-based features from non-public CPTs.
* Address various issues with post locking modal.
* Fix issue with duplicating blocks and undo on Top Toolbar mode.
* Visual fix of margin on icons that are not dashicons in placeholders.
* Visual fix for centre-aligned text on coverblocks.
* Visual fix for embeds that are wider than the mobile breakpoint, cropping them to fit within the screen.
* Adds MediaUploadCheck before some MediaUpload components where it was not being checked in time, creating a confusing experience for users in the “contributor” role.
* Fix undefined variable warning in gutenberg.php.
* Add missing stringifier and iterator in TokenList component.
* Address i18n issue in MultiSelectionInspector.
* Fix small visual regression with button variation preview.
* Fix interaction regression with Sibling Inserter.
* Fix issue with the Privacy Policy help notice.
* Fix post visibility popover not appearing on mobile.
* Fix issue with toolbar in IE11.
* Fix small gap in style variation button.
* Fix popovers position in RTL languages.
* Fix double border issue with disabled toggle control.
* Fix the TinyMCE init array.
* RichText content that comes from headings content attribute should use the RichText.Content instead of rendering it directly.
* Makes the escape key consistently exit the inline link popover – previously this could behave unexpectedly depending on focus.
* Improve accessibility of permalink sidebar panel using external link component.
* Display selected block outlines on Top Toolbar mode.
* Avoid responding to componentDidUpdate in withDispatch.
* Allow previewing changes to the post featured image.
* Preserve unknown attributes and respect null in server attributes preparation
* Adds missing periods to notification that another user has control of the post.
* Restore the help modal in the Classic block.
* Reduce specificity in core button styles to reduce conflicts with theme styles.
* Update name of “Unified Toolbar” to “Top Toolbar” for extra clarity.
* Make it possible to have an editor-only annotation format that stays in
* position when typing inside RichText.
* Adds missing periods to notification that the user does not have permission to read the blocks of the post.
* Only add data-align for wide/full aligns if editor/theme supports them.
* Updates jest to latest version to address vulnerabilities.
* Removes redundant code now that TinyMCE is not being used to handle paste events.
* Remove the gutenberg text-domain from dynamic blocks.
* Remove redundant word from media and text block description.
* Makes the URL for the classic editor translatable, so that the appropriate translated version can be linked to.
* Update More block description.
* Avoid .default on browser global assignments.
* Mirror packages dependencies registration with core.
* Remove absolute positions in the link popover E2E test.
* Improve keyboard mappings in E2E tests, replacing custom utils with modifiers from the keycodes package.
* Add missing imports on some E2E test utilities.
* Update API Fetch documentation – removes unnecessary wp-json.
* Remove iOS scroll adjusting now that enter behavior is more smooth.
* Register the paragraph block as the default block.
* Handle isSelected in plain text blocks (currently Code and More blocks).
