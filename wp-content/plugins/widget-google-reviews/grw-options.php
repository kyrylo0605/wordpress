<?php global $wp_version; if (version_compare($wp_version, '3.5', '>=')) { wp_enqueue_media(); ?>
<div class="form-group">
    <div class="col-sm-12">
        <img id="<?php echo $this->get_field_id('place_photo_img'); ?>" src="<?php echo $place_photo; ?>" alt="<?php echo $place_name; ?>" class="grw-place-photo-img" style="display:<?php if ($place_photo) { ?>inline-block<?php } else { ?>none<?php } ?>;width:32px;height:32px;border-radius:50%;">
        <a id="<?php echo $this->get_field_id('place_photo_btn'); ?>" href="#" class="grw-place-photo-btn"><?php echo grw_i('Change Place photo'); ?></a>
        <input type="hidden" id="<?php echo $this->get_field_id('place_photo'); ?>" name="<?php echo $this->get_field_name('place_photo'); ?>" value="<?php echo $place_photo; ?>" class="form-control grw-place-photo" tabindex="2"/>
    </div>
</div>
<?php } ?>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('place_name'); ?>" name="<?php echo $this->get_field_name('place_name'); ?>" value="<?php echo $place_name; ?>" class="form-control grw-google-place-name" placeholder="<?php echo grw_i('Google Place Name'); ?>" readonly />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('place_id'); ?>" name="<?php echo $this->get_field_name('place_id'); ?>" value="<?php echo $place_id; ?>" class="form-control grw-google-place-id" placeholder="<?php echo grw_i('Google Place ID'); ?>" readonly />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('reviews_lang'); ?>" name="<?php echo $this->get_field_name('reviews_lang'); ?>" value="<?php echo $reviews_lang; ?>" class="form-control grw-place-lang" placeholder="<?php echo grw_i('Language'); ?>" readonly />
    </div>
</div>

<?php if (isset($title)) { ?>
<div class="form-group">
    <div class="col-sm-12">
        <label><?php echo grw_i('Title'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="form-control" />
    </div>
</div>
<?php } ?>

<div class="form-group">
    <div class="col-sm-12">
        <label><?php echo grw_i('Pagination'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('pagination'); ?>" name="<?php echo $this->get_field_name('pagination'); ?>" value="<?php echo $pagination; ?>" class="form-control"/>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label><?php echo grw_i('Characters before \'read more\' link'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('text_size'); ?>" name="<?php echo $this->get_field_name('text_size'); ?>" value="<?php echo $text_size; ?>" class="form-control"/>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label for="<?php echo $this->get_field_id('max_width'); ?>"><?php echo grw_i('Widget width'); ?></label>
        <input id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" value="<?php echo $max_width; ?>" class="form-control" type="text" />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label for="<?php echo $this->get_field_id('max_height'); ?>"><?php echo grw_i('Widget height'); ?></label>
        <input id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" value="<?php echo $max_height; ?>" class="form-control" type="text" />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label>
            <input id="<?php echo $this->get_field_id('centered'); ?>" name="<?php echo $this->get_field_name('centered'); ?>" type="checkbox" value="1" <?php checked('1', $centered); ?> class="form-control" />
            <?php echo grw_i('Place by center (only if Width is set)'); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label>
            <input id="<?php echo $this->get_field_id('dark_theme'); ?>" name="<?php echo $this->get_field_name('dark_theme'); ?>" type="checkbox" value="1" <?php checked('1', $dark_theme); ?> class="form-control" />
            <?php echo grw_i('Dark background'); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label>
            <input id="<?php echo $this->get_field_id('open_link'); ?>" name="<?php echo $this->get_field_name('open_link'); ?>" type="checkbox" value="1" <?php checked('1', $open_link); ?> class="form-control" />
            <?php echo grw_i('Open links in new Window'); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <label>
            <input id="<?php echo $this->get_field_id('nofollow_link'); ?>" name="<?php echo $this->get_field_name('nofollow_link'); ?>" type="checkbox" value="1" <?php checked('1', $nofollow_link); ?> class="form-control" />
            <?php echo grw_i('Use no follow links'); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="rplg-pro">
        <?php echo grw_i('Try more features in the Business version: '); ?>
        <a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank">
            <?php echo grw_i('Upgrade to Business'); ?>
        </a>
    </div>
</div>

<input id="<?php echo $this->get_field_id('view_mode'); ?>" name="<?php echo $this->get_field_name('view_mode'); ?>" type="hidden" value="list" />
