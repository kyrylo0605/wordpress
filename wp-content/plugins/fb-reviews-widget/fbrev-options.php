<button class="fbrev-connect"><?php echo fbrev_i('Connect to Facebook'); ?></button>

<div class="fbrev-pages"></div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('page_name'); ?>" name="<?php echo $this->get_field_name('page_name'); ?>" value="<?php echo $page_name; ?>" class="form-control fbrev-page-name" placeholder="<?php echo fbrev_i('Page Name'); ?>" readonly />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('page_id'); ?>" name="<?php echo $this->get_field_name('page_id'); ?>" value="<?php echo $page_id; ?>" class="form-control fbrev-page-id" placeholder="<?php echo fbrev_i('Page ID'); ?>" readonly />
    </div>
</div>

<input type="hidden" id="<?php echo $this->get_field_id('page_access_token'); ?>" name="<?php echo $this->get_field_name('page_access_token'); ?>" value="<?php echo $page_access_token; ?>" class="form-control fbrev-page-token" placeholder="<?php echo fbrev_i('Access token'); ?>" readonly />

<?php if (isset($title)) { ?>
<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo fbrev_i('Widget title'); ?>" />
    </div>
</div>
<?php } ?>

<!-- Review Options -->
<h4 class="rplg-options-toggle"><?php echo fbrev_i('Review Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo fbrev_i('Enable Google Rich Snippets for rating (schema.org)'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo fbrev_i('Minimum Review Rating'); ?>
            <select class="form-control" disabled>
                <option value="" selected="selected"><?php echo fbrev_i('No filter'); ?></option>
                <option value="5"><?php echo fbrev_i('5 Stars'); ?></option>
                <option value="4"><?php echo fbrev_i('4 Stars'); ?></option>
                <option value="3"><?php echo fbrev_i('3 Stars'); ?></option>
                <option value="2"><?php echo fbrev_i('2 Stars'); ?></option>
                <option value="1"><?php echo fbrev_i('1 Star'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <?php echo fbrev_i('Minimum review letter count filter'); ?>
            <input class="form-control" type="text" placeholder="for instance: 150" disabled />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label><?php echo fbrev_i('Pagination'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('pagination'); ?>" name="<?php echo $this->get_field_name('pagination'); ?>" value="<?php echo $pagination; ?>" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <div class="rplg-pro">
            <?php echo fbrev_i('These features are available in Facebook Reviews Pro plugin: '); ?>
            <a href="https://richplugins.com/facebook-reviews-pro-wordpress-plugin" target="_blank">
                <?php echo fbrev_i('Upgrade to Pro'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Display Options -->
<h4 class="rplg-options-toggle"><?php echo fbrev_i('Display Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <?php echo fbrev_i('Custom business photo'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo fbrev_i('Hide business photo'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo fbrev_i('Hide user avatars'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('dark_theme'); ?>" name="<?php echo $this->get_field_name('dark_theme'); ?>" type="checkbox" value="1" <?php checked('1', $dark_theme); ?> class="form-control" />
                <?php echo fbrev_i('Dark background'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label><?php echo fbrev_i('Review limit before \'read more\' link'); ?></label>
            <input class="form-control" type="text" placeholder="for instance: 120" disabled />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo fbrev_i('Widget theme'); ?>
            <select id="<?php echo $this->get_field_id('view_mode'); ?>" name="<?php echo $this->get_field_name('view_mode'); ?>" class="form-control">
                <option value="list" <?php selected('list', $view_mode); ?>><?php echo fbrev_i('Review List'); ?></option>
                <option value="slider" <?php selected('slider', $view_mode); ?> disabled><?php echo fbrev_i('Reviews Slider'); ?></option>
                <option value="grid" <?php selected('grid', $view_mode); ?> disabled><?php echo fbrev_i('Reviews Grid'); ?></option>
                <option value="badge" <?php selected('badge', $view_mode); ?> disabled><?php echo fbrev_i('Facebook Badge: right'); ?></option>
                <option value="badge_left" <?php selected('badge_left', $view_mode); ?> disabled><?php echo fbrev_i('Facebook Badge: left'); ?></option>
                <option value="badge_inner" <?php selected('badge_inner', $view_mode); ?> disabled><?php echo fbrev_i('Facebook Badge: embed'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label for="<?php echo $this->get_field_id('max_width'); ?>"><?php echo fbrev_i('Maximum width'); ?></label>
            <input id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" value="<?php echo $max_width; ?>" class="form-control" type="text" placeholder="for instance: 300px" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label for="<?php echo $this->get_field_id('max_height'); ?>"><?php echo fbrev_i('Maximum height'); ?></label>
            <input id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" value="<?php echo $max_height; ?>" class="form-control" type="text" placeholder="for instance: 500px" />
        </div>
    </div>
    <div class="form-group">
        <div class="rplg-pro">
            <?php echo fbrev_i('<b>Slider</b>, <b>Grid</b>, <b>Badge</b> themes and other features are available in Facebook Reviews Pro plugin: '); ?>
            <a href="https://richplugins.com/facebook-reviews-pro-wordpress-plugin" target="_blank">
                <?php echo fbrev_i('Upgrade to Pro'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Slider Options -->
<h4 class="rplg-options-toggle"><?php echo fbrev_i('Slider Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group">
        <div class="rplg-pro">
            <?php echo fbrev_i('<b>Slider</b> theme is available in Facebook Reviews Pro plugin: '); ?>
            <a href="https://richplugins.com/facebook-reviews-pro-wordpress-plugin" target="_blank">
                <?php echo fbrev_i('Upgrade to Pro'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Advance Options -->
<h4 class="rplg-options-toggle"><?php echo fbrev_i('Advance Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('open_link'); ?>" name="<?php echo $this->get_field_name('open_link'); ?>" type="checkbox" value="1" <?php checked('1', $open_link); ?> class="form-control" />
                <?php echo fbrev_i('Open links in new Window'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('nofollow_link'); ?>" name="<?php echo $this->get_field_name('nofollow_link'); ?>" type="checkbox" value="1" <?php checked('1', $nofollow_link); ?> class="form-control" />
                <?php echo fbrev_i('User no follow links'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo fbrev_i('Cache data'); ?>
            <select id="<?php echo $this->get_field_id('cache'); ?>" name="<?php echo $this->get_field_name('cache'); ?>" class="form-control">
                <option value="1" <?php selected('1', $cache); ?>><?php echo fbrev_i('1 Hour'); ?></option>
                <option value="3" <?php selected('3', $cache); ?>><?php echo fbrev_i('3 Hours'); ?></option>
                <option value="6" <?php selected('6', $cache); ?>><?php echo fbrev_i('6 Hours'); ?></option>
                <option value="12" <?php selected('12', $cache); ?>><?php echo fbrev_i('12 Hours'); ?></option>
                <option value="24" <?php selected('24', $cache); ?>><?php echo fbrev_i('1 Day'); ?></option>
                <option value="48" <?php selected('48', $cache); ?>><?php echo fbrev_i('2 Days'); ?></option>
                <option value="168" <?php selected('168', $cache); ?>><?php echo fbrev_i('1 Week'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label><?php echo fbrev_i('Facebook Page Ratings API limit'); ?></label>
            <input id="<?php echo $this->get_field_id('api_ratings_limit'); ?>" name="<?php echo $this->get_field_name('api_ratings_limit'); ?>" value="<?php echo $api_ratings_limit; ?>" type="text" placeholder="By default: <?php echo FBREV_API_RATINGS_LIMIT; ?>" class="form-control"/>
        </div>
    </div>
</div>