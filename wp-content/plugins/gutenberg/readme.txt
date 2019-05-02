=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.0.0
Tested up to: 5.1
Stable tag: 5.6.0
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

For 5.6.1.

= Miscellaneous =

- Republish Gutenberg 5.6.0.

For 5.6.0.

= Enhancements =

- Improve [focus state for button block](https://github.com/WordPress/gutenberg/pull/15058).
- [Reduce specificity of block styles](https://github.com/WordPress/gutenberg/pull/14407) to make it easier for themes to style the editor.
- Optimize data subscribers to [avoid unnecessary work](https://github.com/WordPress/gutenberg/pull/15041) on each editor change.
- Avoid [overlapping block breadcrumb](https://github.com/WordPress/gutenberg/pull/15112) when block movers are visible for full- and wide-aligned blocks.
- Preload [user permissions for reusable blocks](https://github.com/WordPress/gutenberg/pull/15061) to avoid UI flickering for block settings menu options.
- Remove [unnecessary bottom padding](https://github.com/WordPress/gutenberg/pull/15158) for nested lists.
- Restore [block movers to focus mode](https://github.com/WordPress/gutenberg/pull/15109).
- Improve display of [categories list panel](https://github.com/WordPress/gutenberg/pull/15075).

= Bug Fixes =

- [Restore block movers](https://github.com/WordPress/gutenberg/pull/15022) to full- and wide-aligned blocks.
- Always show [drag handles](https://github.com/WordPress/gutenberg/pull/15025) for nested blocks, even when only a single block exists.
- Improve [HTML output for formatted text](https://github.com/WordPress/gutenberg/pull/14555).
- Fix an [error preventing registerFormatType to be called](https://github.com/WordPress/gutenberg/pull/15072) wrongly indicated as duplicate.
- Resolve problematic [post lock release behavior](https://github.com/WordPress/gutenberg/pull/14994) when leaving the editor when using newer versions of Chrome.
- Resolve an issue to [detect autosave presence at editor load](https://github.com/WordPress/gutenberg/pull/7945) in considering saveability.
- Resolve a typo which could interfere with [audio shortcode transforms](https://github.com/WordPress/gutenberg/pull/15118).
- [Apply RichText attributes correctly](https://github.com/WordPress/gutenberg/pull/15070) to resolve an issue with registerFormatType.
- [Preserve attributes](https://github.com/WordPress/gutenberg/pull/15128) of a multi-line classic block paragraph.
- Resolve an issue for Windows and Linux development mode due to a [access key safeguard](https://github.com/WordPress/gutenberg/pull/15044).

= Various =

- Refactor a number of core blocks toward better interoperability with the Blocks RFC: [[1]](https://github.com/WordPress/gutenberg/pull/14979), [[2]](https://github.com/WordPress/gutenberg/pull/14902), [[3]](https://github.com/WordPress/gutenberg/pull/14903), [[4]](https://github.com/WordPress/gutenberg/pull/14899).
- [Move selection state](https://github.com/WordPress/gutenberg/pull/14640) for RichText components to the block editor store, to enable future work to resolve or improve selection behavior.
- Change the behavior of reusable blocks autocomplete to [fetch upon input](https://github.com/WordPress/gutenberg/pull/14915), improving reliability of tests and avoiding unnecessary network requests.
- Allow [development mode constant](https://github.com/WordPress/gutenberg/pull/14165) to be redefined by plugins.
- Improve reliability of e2e tests: [[1]](https://github.com/WordPress/gutenberg/pull/13161), [[2]](https://github.com/WordPress/gutenberg/pull/15046), [[3]](https://github.com/WordPress/gutenberg/pull/15063).
- Avoid running [files contained in the git subfolder](https://github.com/WordPress/gutenberg/pull/14997) as tests.
- Resolve an [issue with e2e errors](https://github.com/WordPress/gutenberg/pull/14998) related to dependencies updates.
- Add a new ESLint rule to [enforce accessible use of BaseControl](https://github.com/WordPress/gutenberg/pull/14151).
- Remove [redundant CSS styles](https://github.com/WordPress/gutenberg/pull/14520).
- Add e2e tests for [dynamic allowed blocks](https://github.com/WordPress/gutenberg/pull/14992), [transforms from media to embed block](https://github.com/WordPress/gutenberg/pull/13997), [explicit persistence undo regression](https://github.com/WordPress/gutenberg/pull/15049).
- Add a new [`wpDataSelect` e2e test utility](https://github.com/WordPress/gutenberg/pull/15052).
- Include a [React hooks ESLint configuration](https://github.com/WordPress/gutenberg/pull/14995).
- Add a [new Webpack plugin](https://github.com/WordPress/gutenberg/pull/14869) to help externalize and extract script dependencies (not yet published).

= Documentation =

- Include auto-generated documentation for [core data module actions and selectors](https://github.com/WordPress/gutenberg/pull/15200).
- Update [contributing documentation](https://github.com/WordPress/gutenberg/pull/15187) to extract detailed sections to their own documents.
- Document the [withGlobalEvents](https://github.com/WordPress/gutenberg/pull/15175) higher-order component creator.
- Add [related resources](https://github.com/WordPress/gutenberg/pull/15194) for BlockEditor components.
- Clarify [requirements for e2e-test-utils](https://github.com/WordPress/gutenberg/pull/15171) package.
- [Exclude private, experimental, and unstable APIs](https://github.com/WordPress/gutenberg/pull/15188) from auto-generated data documentation.
- Include [missing DropZone component props](https://github.com/WordPress/gutenberg/pull/15223) in documentation.
- Mention [component stylesheets](https://github.com/WordPress/gutenberg/pull/15241) in usage instructions.
- Update [copy/paste support list](https://github.com/WordPress/gutenberg/pull/15149) to reflect paste support of images from Microsoft Word and Libre/Open Office.
- Add [missing "Code is Poetry" footers](https://github.com/WordPress/gutenberg/pull/15140).
- Improve [wording of JavaScript Tutorial](https://github.com/WordPress/gutenberg/pull/14838) document.
- Improve [Travis build performance](https://github.com/WordPress/gutenberg/pull/15228) by expanding containers for e2e tests.

= Mobile =

- [Refine transitions](https://github.com/WordPress/gutenberg/pull/14831) for bottom sheets.
- Extract a [HorizontalRule component](https://github.com/WordPress/gutenberg/pull/14361) for use in a cross-platform separator block.
- Fix an [error with changing list types](https://github.com/WordPress/gutenberg/pull/15010).
- [Avoid setting caret](https://github.com/WordPress/gutenberg/pull/15021) when rich-text text will be trimmed.
- Fix [title not focusing](https://github.com/WordPress/gutenberg/pull/15069).
- Improve [post title accessibility](https://github.com/WordPress/gutenberg/pull/15106).
- Improve image block accessibility for [deselected](https://github.com/WordPress/gutenberg/pull/14713), and [selected](https://github.com/WordPress/gutenberg/pull/15122) states.
- Add [accessibility label for unselected paragraph](https://github.com/WordPress/gutenberg/pull/15126).
- Fix [history stack when not empty](https://github.com/WordPress/gutenberg/pull/15055) on a fresh start of the editor.
- Improve [Heading block accessibility](https://github.com/WordPress/gutenberg/pull/15144).
- Make [accessibility string properly localizable](https://github.com/WordPress/gutenberg/pull/15161).
- [Update string concatenation](https://github.com/WordPress/gutenberg/pull/15181) for accessibility labels.
