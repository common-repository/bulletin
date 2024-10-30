<?php

/**
 * The widget provided by this plugin.
 *
 * @link       https://eresults.nl
 * @since      1.0.0
 *
 * @package    Bulletin
 * @subpackage Bulletin/public
 */

/**
 * The widget provided by this plugin.
 *
 * Defines the Widget.
 *
 * @package    Bulletin
 * @subpackage Bulletin/public
 * @author     Martijn Gastkemper <martijngastkemper@eresults.nl>
 */
class Bulletin_Widget extends WP_Widget
{

    /**
     * @since      1.0.0
     */
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'widget_bulletin',
            'description' => __('The Bulletin widget.', 'bulletin'),
            'customize_selective_refresh' => true
        );

        parent::__construct('widget_bulletin', 'Bulletin', $widget_details);

    }

    /**
     * @since      1.0.0
     * @param array $instance
     */
    public function form($instance)
    {
        $title = '';
        if( !empty( $instance['title'] ) ) {
            $title = $instance['title'];
        }

        $selectedList = '';
        if( !empty( $instance['list'] ) ) {
            $selectedList = $instance['list'];
        }

        $sourceLabel = '';
        if( !empty( $instance['source_label'] ) ) {
            $sourceLabel = $instance['source_label'];
        }

        $api = new \Bulletin\Api\Client(get_option('bulletin_api_token'));

        try {
            $lists = $api->getLists();
        } catch(\Bulletin\Api\Exception $e) { ?>
            <p><?php printf(__('Please set an API token on <a href="%s">the Bulletin settings page</a>.', 'bulletin'), admin_url('options-general.php?page=bulletin')) ?></p>
            <?php
            return;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bulletin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name('list') ?>"><?php _e('List:', 'bulletin') ?></label>
            <select name="<?php echo $this->get_field_name('list'); ?>" class="widefat" id="<?php echo $this->get_field_id('list'); ?>">
                <?php foreach($lists as $list) :
                    ?>
                    <option value="<?php echo $list['id'] ?>" <?php selected($selectedList, $list['id']); ?>><?php echo $list['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('source_label'); ?>"><?php _e('Source label:', 'bulletin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('source_label'); ?>" name="<?php echo $this->get_field_name('source_label'); ?>" type="text" value="<?php echo esc_attr($sourceLabel); ?>" />
        </p>

        <?php
    }

    /**
     * @since      1.0.0
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    /**
     * @since      1.0.0
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if(isset($instance['title'])) {
            $title = apply_filters( 'widget_title', $instance['title'] );
            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
        }

        $error = false;
        $success = false;
        $email = '';

        if(isset($_POST['bulletin-email']) && isset($_POST['widget_id']) && $_POST['widget_id'] === $args['widget_id']) {
            $email = sanitize_text_field($_POST['bulletin-email']);

            if(is_email($email) === false) {
                $error = __('Invalid email address', 'bulletin');
            }

            $data = [
                'email' =>  $email,
                'source_label' => isset($instance['source_label']) ? $instance['source_label'] : ''
            ];

            $api = new \Bulletin\Api\Client(get_option('bulletin_api_token'));
            try {
                if(isset($instance['list']) === false) {
                    foreach($api->getLists() as $l) {
                        if($l['name'] === '__default__') {
                            $list = $l['id'];
                        }
                    }
                } else {
                    $list = $instance['list'];
                }

                $api->subscribe($list, $data);
                $success = true;
            } catch(\Bulletin\Api\Exception $e) {
                $error = $e->getMessage();

                if(strstr($error, 'Already subscribed') !== false) {
                    $error = __('You are already subscribed', 'bulletin');
                }
            }
        }
        ?>
        <form method="post" class="bulletin-form" id="<?= $args['widget_id'] ?>" action="#<?= $args['widget_id'] ?>">
            <label for="bulletin-email" class="bulletin-email"><?php _e('Email', 'bulletin') ?></label>
            <input type="email" name="bulletin-email" id="bulletin-email" value="<?php echo $success ? '' : $email ?>" class="bulletin-email" placeholder="<?php _e('Your email address...', 'bulletin') ?>"/>

            <button type="submit" value="<?php _e('Subscribe', 'bulletin') ?>"  class="bulletin-submit"></button>

            <input type="hidden" value="<?= $args['widget_id'] ?>" name="widget_id" />

            <?php if($error): ?>
                <span class="bulletin-error"><?php echo $error ?></span>
            <?php elseif ($success) : ?>
                <span class="bulletin-success"><?php _e('You are subscribed', 'bulletin') ?></span>
            <?php endif; ?>
        </form>
<?php
        echo $args['after_widget'];
    }

}