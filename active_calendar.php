<?php
/*
Plugin Name: Active Calendar
Plugin URI: http://example.com
Description: Display a calendar on your WordPress page featuring active dates starting from tomorrow and indicating inactive dates
Version: 1.0
Author: Artem Stepanov
Author URI: http://example.com
*/

function active_calendar() {
    wp_enqueue_style( 'active_calendar_styles', plugin_dir_url( __FILE__ ) . 'styles.css' );

    $current_date = date('Y-m-d');
    $calendar_output = '<div id="calendar-wrap">';
    $calendar_output .= '<table id="calendar">';
    $calendar_output .= '<tr><th colspan="7">' . date('F Y') . '</th></tr>';
    $calendar_output .= '<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>';
    $calendar_output .= '<tr>';

    $month_start_day = date('N', strtotime(date('Y-m-01')));
    for ($i = 1; $i < $month_start_day; $i++) {
        $calendar_output .= '<td></td>';
    }

    $days_in_month = date('t');
    for ($day = 1; $day <= $days_in_month; $day++) {
        $date = date('Y-m-d', strtotime(date('Y-m-' . $day)));
        if ($date < $current_date) {
            $calendar_output .= '<td class="inactive">' . $day . '</td>';
        } else {
            $calendar_output .= '<td><a href="#" class="register-link" data-date="' . $date . '">' . $day . '</a></td>';
        }

        if (date('N', strtotime(date('Y-m-' . $day))) == 7) {
            $calendar_output .= '</tr><tr>';
        }
    }

    $month_end_day = date('N', strtotime(date('Y-m-' . $days_in_month)));
    if ($month_end_day < 7) {
        for ($i = $month_end_day; $i < 7; $i++) {
            $calendar_output .= '<td></td>';
        }
    }

    $calendar_output .= '</tr>';
    $calendar_output .= '</table>';
    $calendar_output .= '</div>';

    return $calendar_output;
}

add_shortcode('active_calendar', 'active_calendar');

function register_form_handler() {
    if (isset($_POST['register_date'])) {
        $name = sanitize_text_field($_POST['name']);
        $phone = sanitize_text_field($_POST['phone']);
        $email = sanitize_email($_POST['email']);
        $date = sanitize_text_field($_POST['register_date']);

        $to = get_option('admin_email');
        $subject = 'Регистрация на активную дату';
        $message = 'Имя: ' . $name . "\r\n";
        $message .= 'Телефон: ' . $phone . "\r\n";
        $message .= 'Email: ' . $email . "\r\n";
        $message .= 'Дата: ' . $date . "\r\n";

        wp_mail($to, $subject, $message);
    }
}

add_action('init', 'register_form_handler');

function enqueue_custom_scripts() {
    wp_enqueue_script('active_calendar_script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
