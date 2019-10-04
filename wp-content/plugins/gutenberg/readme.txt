=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.1.0
Tested up to: 5.2
Stable tag: 6.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The block editor was introduced in core WordPress with version 5.0. This beta plugin allows you to test bleeding-edge features around editing and customization projects before they land in future WordPress releases.

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

### Enhancements

-   Turn [Stack on mobile toggle on by default](https://github.com/WordPress/gutenberg/pull/14364) in the Media & Text block.
-   Only show the Inserter [help panel in the topbar inserter](https://github.com/WordPress/gutenberg/pull/17545).
-   Use minimum height instead of height for [Cover height control label](https://github.com/WordPress/gutenberg/pull/17634).
-   Update the [buttons](https://github.com/WordPress/gutenberg/pull/17645)  [styling](https://github.com/WordPress/gutenberg/pull/17651) to match core.
-   Add [preview examples](https://github.com/WordPress/gutenberg/pull/17493) for multiple core blocks.

### New APIs

-   Implement [EntityProvider](https://github.com/WordPress/gutenberg/pull/17153) and use it to refactor the meta block attributes.

### Experimental

-   Introduce the [wp_template custom post type](https://github.com/WordPress/gutenberg/pull/17513) to preempt the block content areas work.
-   Use the [entities store for the widgets](https://github.com/WordPress/gutenberg/pull/17319) screen.

### Bugs

-   Fix javascript error potentially triggered when using [saveEntityRecord action](https://github.com/WordPress/gutenberg/pull/17492).
-   Avoid marking the [post as dirty when forcing an undo level](https://github.com/WordPress/gutenberg/pull/17487) (RichText).
-   Fix [Post Publish Panel overlapping the user profile](https://github.com/WordPress/gutenberg/pull/17075) dropdown menu.
-   Fix and align [collapsing logic for Save Draft and Saved](https://github.com/WordPress/gutenberg/pull/17506) button states.
-   [Remove Reusable block name and description](https://github.com/WordPress/gutenberg/pull/17530) from the inserter help panel.
-   Fix spacing issues in the [inserter panel previews](https://github.com/WordPress/gutenberg/pull/17531).
-   Gallery block: [Don't show the caption gradient overlay](https://github.com/WordPress/gutenberg/pull/17561) unless image is selected or a caption is set.
-   Gallery block: Fix [custom alignment layouts](https://github.com/WordPress/gutenberg/pull/17586)
-   Fix [dirtiness detection when server-side saving filters](https://github.com/WordPress/gutenberg/pull/17532) are used.
-   Remove [wrong i18n](https://github.com/WordPress/gutenberg/pull/17546)  [domain](https://github.com/WordPress/gutenberg/pull/17591).
-   Fix [invalid block warning](https://github.com/WordPress/gutenberg/pull/17572) panel.
-   Fix various issues in related to the [BlockDirectory inserter](https://github.com/WordPress/gutenberg/pull/17517).
-   Cover block: [Show Height control](https://github.com/WordPress/gutenberg/pull/17371) only if an image background is selected.
-   Fix [RichText composition input](https://github.com/WordPress/gutenberg/pull/17610) issues.
-   Fix [block placeholders spacing](https://github.com/WordPress/gutenberg/pull/17616) after Core inputs updates.
-   Fix [checkbox design](https://github.com/WordPress/gutenberg/pull/17571) (color and background) after Core updates.
-   Fix [radio buttons design](https://github.com/WordPress/gutenberg/pull/17613) after Core updates.
-   Remove any existing subscriptions before adding a new save metaboxes sub to [prevent multiple saves](https://github.com/WordPress/gutenberg/pull/17522).
-   [Clear auto-draft titles](https://github.com/WordPress/gutenberg/pull/17633) on save if not changed explicitly.
-   Fix [block error boundary](https://github.com/WordPress/gutenberg/pull/17602).
-   Fix [select elements](https://github.com/WordPress/gutenberg/pull/17646) design in the sidebar after Core updates.
-   Allow using [space with modifier keys](https://github.com/WordPress/gutenberg/pull/17611) at the beginning of list items.
-   Fix the [inputs height](https://github.com/WordPress/gutenberg/pull/17659) after Core updates.
-   fix conflict between [remote and local autosaves](https://github.com/WordPress/gutenberg/pull/17501).

### Performance

-   Request the [Image block’s metadata](https://github.com/WordPress/gutenberg/pull/17504) only if the block is selected.
-   Improve the performance of the [block reordering animation in Safari](https://github.com/WordPress/gutenberg/pull/17573).
-   Remove [Autocomplete component wrappers](https://github.com/WordPress/gutenberg/pull/17580).

### Various

-   [Replace registered social links blocks](https://github.com/WordPress/gutenberg/pull/17494) if already registered in Core.
-   More stable [List block e2e](https://github.com/WordPress/gutenberg/pull/17482)  [tests](https://github.com/WordPress/gutenberg/pull/17599).
-   Add e2e tests to validate the [date picker UI](https://github.com/WordPress/gutenberg/pull/17490) behavior.
-   Add e2e tests to validate the [local auto-save](https://github.com/WordPress/gutenberg/pull/17503) behavior.
-   Mark the [social links block as experimental](https://github.com/WordPress/gutenberg/pull/17526).
-   [Update the e2e tests](https://github.com/WordPress/gutenberg/pull/17566) to accommodate the new theme.
-   Align the [version of lodash](https://github.com/WordPress/gutenberg/pull/17528) with WordPress core.
-   Add phpcs rule to [detect unused variables](https://github.com/WordPress/gutenberg/pull/17300).
-   Simplify [Block Selection Reducer](https://github.com/WordPress/gutenberg/pull/17467).
-   Add [has-background classes](https://github.com/WordPress/gutenberg/pull/17529) to pullquote and Media & Text blocks for consistency.
-   Tidy up [button vertical align styles](https://github.com/WordPress/gutenberg/pull/17601).
-   Update [browserslist](https://github.com/WordPress/gutenberg/pull/17643) dependency.

### Documentation

-   Add [scripts/styles dependency management](https://github.com/WordPress/gutenberg/pull/17489) documentation.
-   Update [docs with the example property](https://github.com/WordPress/gutenberg/pull/17507) used for Inserter previews.
-   Typos and tweaks: [1](https://github.com/WordPress/gutenberg/pull/17449), [2](https://github.com/WordPress/gutenberg/pull/17499), [3](https://github.com/WordPress/gutenberg/pull/17514), [4](https://github.com/WordPress/gutenberg/pull/17502), [5](https://github.com/WordPress/gutenberg/pull/17595).

### Mobile

-   Add [rounded corners on media placeholder](https://github.com/WordPress/gutenberg/pull/16729) and unsupported blocks.
-   Fix link editing when the [cursor is at the beginning of a link](https://github.com/WordPress/gutenberg/pull/17631).

