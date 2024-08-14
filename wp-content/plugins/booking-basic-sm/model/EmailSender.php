<?php

namespace model;

class EmailSender
{

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @return void
     */
    public function send($to, $subject, $body): void
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: Sanamente.co <info@sanamente.co>'
        );

        wp_mail( $to, $subject, $body, $headers );
    }
}