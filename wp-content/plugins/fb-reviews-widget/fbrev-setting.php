<?php

if (!current_user_can('manage_options')) {
    die('The account you\'re logged in to doesn\'t have permission to access this page.');
}

function fbrev_has_valid_nonce() {
    $nonce_actions = array('fbrev_settings', 'fbrev_active');
    $nonce_form_prefix = 'fbrev-form_nonce_';
    $nonce_action_prefix = 'fbrev-wpnonce_';
    foreach ($nonce_actions as $key => $value) {
        if (isset($_POST[$nonce_form_prefix.$value])) {
            check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
            return true;
        }
    }
    return false;
}

if (!empty($_POST)) {
    $nonce_result_check = fbrev_has_valid_nonce();
    if ($nonce_result_check === false) {
        die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
    }
}

// Post fields that require verification.
$valid_fields = array(
    'fbrev_active' => array(
        'key_name' => 'fbrev_active',
        'values' => array('Disable', 'Enable')
    ));

// Check POST fields and remove bad input.
foreach ($valid_fields as $key) {

    if (isset($_POST[$key['key_name']]) ) {

        // SANITIZE first
        $_POST[$key['key_name']] = trim(sanitize_text_field($_POST[$key['key_name']]));

        // Validate
        if (isset($key['regexp']) && $key['regexp']) {
            if (!preg_match($key['regexp'], $_POST[$key['key_name']])) {
                unset($_POST[$key['key_name']]);
            }

        } else if (isset($key['type']) && $key['type'] == 'int') {
            if (!intval($_POST[$key['key_name']])) {
                unset($_POST[$key['key_name']]);
            }

        } else {
            $valid = false;
            $vals = $key['values'];
            foreach ($vals as $val) {
                if ($_POST[$key['key_name']] == $val) {
                    $valid = true;
                }
            }
            if (!$valid) {
                unset($_POST[$key['key_name']]);
            }
        }
    }
}

if (isset($_POST['fbrev_active']) && isset($_GET['fbrev_active'])) {
    update_option('fbrev_active', ($_GET['fbrev_active'] == '1' ? '1' : '0'));
}

if (isset($_POST['fbrev_setting'])) {
    //TODO
}

wp_register_style('twitter_bootstrap3_css', plugins_url('/static/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('twitter_bootstrap3_css', plugins_url('/static/css/bootstrap.min.css', __FILE__));

wp_register_style('rplg_setting_css', plugins_url('/static/css/rplg-setting.css', __FILE__));
wp_enqueue_style('rplg_setting_css', plugins_url('/static/css/rplg-setting.css', __FILE__));

wp_enqueue_script('jquery');

$fbrev_enabled = get_option('fbrev_active') == '1';
?>

<span class="rplg-version"><?php echo fbrev_i('Free Version: %s', esc_html(FBREV_VERSION)); ?></span>
<div class="rplg-setting container-fluid">
    <div class="rplg-setting-facebook">Facebook Reviews</div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#about" aria-controls="about" role="tab" data-toggle="tab"><?php echo fbrev_i('About'); ?></a>
        </li>
        <li role="presentation">
            <a href="#setting" aria-controls="setting" role="tab" data-toggle="tab"><?php echo fbrev_i('Setting'); ?></a>
        </li>
        <li role="presentation">
            <a href="#shortcode" aria-controls="shortcode" role="tab" data-toggle="tab"><?php echo fbrev_i('Shortcode Builder'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="about">
            <div class="row">
                <div class="col-sm-6">
                    <h4><?php echo fbrev_i('Facebook Reviews Widget for WordPress'); ?></h4>
                    <p><?php echo fbrev_i('Facebook Reviews plugin is an easy and fast way to integrate Facebook business reviews right into your WordPress website. This plugin works instantly and show Facebook reviews in sidebar widget.'); ?></p>
                    <ol>
                        <li>Go to menu <b>"Appearance"</b> -> <b>"Widgets"</b></li>
                        <li>Move "Facebook Reviews" widget to sidebar</li>
                        <li>Click by 'Connect to Facebook' button</li>
                        <li>Log in via Facebook and agree manage pages permission</li>
                        <li>After log in under the button you will see a list of your Facebook pages</li>
                        <li>Click on the needed page and <b>Save</b> the widget</li>
                    </ol>
                    <p><?php echo fbrev_i('Feel free to contact us by email <a href="mailto:support@richplugins.com">support@richplugins.com</a>.'); ?></p>
                    <div class="row">
                        <div class="col-sm-4">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.6&appId=1501100486852897";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-like" data-href="https://richplugins.com/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                        </div>
                        <div class="col-sm-4 twitter">
                            <a href="https://twitter.com/richplugins" class="twitter-follow-button" data-show-count="false">Follow @RichPlugins</a>
                            <script>!function (d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = p + '://platform.twitter.com/widgets.js';
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, 'script', 'twitter-wjs');</script>
                        </div>
                        <div class="col-sm-4 googleplus">
                            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/101080686931597182099"></div>
                            <script type="text/javascript">
                                window.___gcfg = { lang: 'en-US' };
                                (function () {
                                    var po = document.createElement('script');
                                    po.type = 'text/javascript';
                                    po.async = true;
                                    po.src = 'https://apis.google.com/js/plusone.js';
                                    var s = document.getElementsByTagName('script')[0];
                                    s.parentNode.insertBefore(po, s);
                                })();
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <br>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/o0HV-bJ6_qE?rel=0" allowfullscreen=""></iframe>
                    </div>
                </div>
            </div>
            <hr>
            <h4>Get More Features with Facebook Reviews Pro!</h4>
            <p><a href="https://richplugins.com/facebook-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;">Upgrade to Facebook Reviews Pro</a></p>
            <p>* Supports Google Rich Snippets (schema.org)</p>
            <p>* Support shortcode</p>
            <p>* Powerful <b>Shortcode Builder</b></p>
            <p>* Grid theme to show reviews like testimonials</p>
            <p>* Trust Facebook badge (fixed or inner)</p>
            <p>* Trim long reviews with "read more" link</p>
            <p>* Show/hide business photo and avatars</p>
            <p>* Minimum review rating filter</p>
            <p>* Custom business photo</p>
            <p>* Pagination</p>
            <p>* Priority support</p>
        </div>
        <div role="tabpanel" class="tab-pane" id="setting">
            <h4><?php echo fbrev_i('Facebook Reviews Widget Setting'); ?></h4>
            <!-- Enable/disable Facebook Reviews Widget toggle -->
            <form method="POST" action="?page=fbrev&amp;fbrev_active=<?php echo (string)((int)($fbrev_enabled != true)); ?>">
                <?php wp_nonce_field('fbrev-wpnonce_fbrev_active', 'fbrev-form_nonce_fbrev_active'); ?>
                <span class="status">
                    <?php echo fbrev_i('Facebook Reviews Widget are currently '). ($fbrev_enabled ? '<b>' . fbrev_i('enable') . '</b>' : '<b>' . fbrev_i('disable') . '</b>'); ?>
                </span>
                <input type="submit" name="fbrev_active" class="button" value="<?php echo $fbrev_enabled ? fbrev_i('Disable') : fbrev_i('Enable'); ?>" />
            </form>
            <hr>
            <button class="btn btn-primary btn-small" type="button" data-toggle="collapse" data-target="#debug" aria-expanded="false" aria-controls="debug">
                <?php echo fbrev_i('Debug Information'); ?>
            </button>
            <div id="debug" class="collapse">
                <textarea style="width:90%; height:200px;" onclick="this.select();return false;" readonly><?php
                    rplg_debug(FBREV_VERSION, fbrev_options(), 'widget_fbrev_widget');
                ?></textarea>
            </div>
            <div style="max-width:700px">Feel free to contact support team by support@richplugins.com for any issues but please don't forget to provide debug information that you can get by click on 'Debug Information' button.</div>
        </div>
        <div role="tabpanel" class="tab-pane" id="shortcode">
            <h4><?php echo fbrev_i('Shortcode Builder available in Facebook Reviews Pro plugin:'); ?></h4>
            <a href="https://richplugins.com/facebook-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;"><?php echo fbrev_i('Upgrade to Pro'); ?></a>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('a[data-toggle="tab"]').on('click', function(e)  {
        var active = $(this).attr('href');
        $('.tab-content ' + active).show().siblings().hide();
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });
    $('button[data-toggle="collapse"]').click(function () {
        $target = $(this);
        $collapse = $target.next();
        $collapse.slideToggle(500);
    });
});
</script>