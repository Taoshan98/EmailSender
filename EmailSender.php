<?php
/**
 * Plugin Name:       Email Sender
 * Description:       Wordpress Plugin for sending mail
 * Version:           1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Nunzio Marfè
 * Text Domain:       Email Sender
 */

/*register_activation_hook(__FILE__, array('test-plugin', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('test-plugin', 'plugin_deactivation'));
register_uninstall_hook(__FILE__, array('test-plugin', 'plugin_uninstallation'));*/

function html_form()
{
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">
            <label for="name">Your Name*</label>
            <input type="text" name="name" value="' . (isset($_POST["name"]) ? esc_attr($_POST["name"]) : '') . '" size="40" />
            
            <label for="email">Your Email*</label>
            <input type="email" name="email" value="' . (isset($_POST["email"]) ? esc_attr($_POST["email"]) : '') . '" size="40" />
            
            <label for="subject">Subject*</label>
            <input type="text" name="subject" pattern="[a-zA-Z ]+" value="' . (isset($_POST["subject"]) ? esc_attr($_POST["subject"]) : '') . '" size="40" />
             
            <label for="message">Message*</label>
            <textarea rows="10" cols="35" name="message">' . (isset($_POST["message"]) ? esc_attr($_POST["message"]) : '') . '</textarea>
           <br>
            <input type="submit" name="cf-submitted" value="Send">
        </form>';
}

function send_mail()
{

    // if the submit button is clicked, send the email
    if (isset($_POST['cf-submitted'])) {

        // sanitize form values
        $name = sanitize_text_field($_POST["name"]);
        $email = sanitize_email($_POST["email"]);
        $subject = sanitize_text_field($_POST["subject"]);
        $message = esc_textarea($_POST["message"]);

        // get the blog administrator's email address
        $to = get_option('admin_email');

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if (wp_mail($to, $subject, $message, $headers)) {

            echo '<div>
                    <p>Thanks for contacting me, expect a response soon.</p>
                  </div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}

function cf_shortcode()
{
    ob_start();
    send_mail();
    html_form();

    return ob_get_clean();
}

add_shortcode('send_mail_short', 'cf_shortcode');