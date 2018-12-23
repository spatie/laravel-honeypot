<?php

use Spatie\Honeypot\SpamResponder\BlankPageResponse;

return [
    'name_field_name' => 'my_name',

    'valid_from_field_name' => 'valid_from',

    /* If the form is submitted faster then this amout of seconds
     * the form submission will be considered invalid.
     */
    'amount_of_seconds' => 1,

    'enabled' => true,

    'respond_to_spam_with' => BlankPageResponse::class,
];