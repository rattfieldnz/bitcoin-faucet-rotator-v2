<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'url'                  => 'The :attribute format is invalid.',
    'valid_user_name'      => 'The user name is invalid, taken, or barred from being used.',
    'not_start_with_number' => 'The :attribute must not start with a number.',
    'no_punctuation' => 'The :attribute must not contain punctuation.',
    'no_numbers' => 'The :attribute must not contain numbers.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'payment_processors' => [
            'required' => 'At least 1 payment processor must be selected.',
        ],
        'url' => [
            'required' => 'A valid URL is required.',
            'unique' => 'That URL is already being used, please use another one.',
            'max' => 'The URL must be less than :max characters in length.',
            'active_url' => 'The URL must have valid DNS records (A, AAAA, valid IPv4 and/or IPv6 addresses).',
            'regex' => 'The faucet URL must be \'http\' or \'https\'.'
        ],
        'name' => [
            'required' => 'A name is required.',
            'unique' => 'That name has already been taken, please try another one.',
            'min' => 'The name must have at least :min characters.',
            'max' => 'The name must have less than or equal to :min characters.'
        ],
        'interval_minutes' => [
            'required' => 'A faucet\'s claim time is required.'
        ],
        'min_payout' => [
            'required' => 'A faucet\'s minimum per-claim payout is required.'
        ],
        'max_payout' => [
            'required' => 'A faucet\'s maximum per-claim payout is required.'
        ],
        'has_ref_program' => [
            'required' => 'Please select whether the faucet has a referral program or not.'
        ],
        'ref_payout_percent' => [
            'required_if' => 'The referral earnings percentage is required if the faucet has a referral program.',
            'min' => 'The minimum referral payout percentage must be greater than 0%.'
        ],
        'is_paused' => [
            'required' => 'Please select whether the faucet should be paused from displaying or not.'
        ],
        'meta_title' => [
            'max' => 'The meta title must have 70 characters or less.'
        ],
        'meta_description' => [
            'max' => 'The meta description must have 160 characters or less.'
        ],
        'meta_keywords' => [
            'max' => 'The keyword string must have 255 characters or less.'
        ],
        'has_low_balance' => [
            'required' => 'Please select whether the faucet has a low balance or not.'
        ],
        'comments' => [
            'max' => 'Comments must have less than or equal to 255 characters.'
        ],
        'user_name' => [
            'required' => 'A user name is required.',
            'unique' => 'That user name is already taken, please try another one.',
            'min' => 'The user name must have at least 5 characters.',
            'max' => 'The user name must be less than or equal to 25 characters.'
        ],
        'first_name' => [
            'required' => 'A first name is required.',
            'min' => 'The first name must have at least 1 character.',
            'max' => 'The first name must be between 1 and up to 50 characters in length.'
        ],
        'last_name' => [
            'required' => 'A last name is required.',
            'min' => 'The last name must have at least 1 character.',
            'max' => 'The last name must be between 1 and up to 50 characters in length.'
        ],
        'email' => [
            'required' => 'A valid email address is required.',
            'email' => 'An email address with a valid format is required (e.g. someone@example.com).',
            'unique' => 'That email address has already been taken, please try another one.'
        ],
        'password' => [
            'required' => 'A valid password is required. Must have at least: 2 lower and upper-case character, 2 numeric character, and 2 symbols.',
            'confirmed' => 'The confirmed password did not match the desired password that was entered.',
            'min' => 'The password must have at least 10 characters.',
            'max' => 'The password cannot have more than 20 characters.',
            'regex' => 'The password must have at least: 2 lower and upper-case character, 2 numeric character, and 2 symbold.',
        ],
        'bitcoin_address' => [
            'required' => 'A valid bitcoin address is required for registration. Bitcoin addresses are between 26 and 35 alpha-numeric characters in length.',
            'min' => 'The bitcoin address must have at least 26 alpha-numeric characters.',
            'max' => 'The bitcoin address must have less than or equal to 35 alpha-numeric characters.'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        //Faucet validation attributes//
        'name' => 'Name',
        'url' => 'URL',
        'interval_minutes' => 'Minutes Between Claims',
        'min_payout' => 'Min. Payout',
        'max_payout' => 'Max. Payout',
        'has_ref_program' => 'Has Referral Program',
        'ref_payout_percent' => 'Referral Payout Percent',
        'faucet_payment_processors' => 'Payment Processors',
        'comments' => 'Comments',
        'is_paused' => 'Is Paused',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'meta_keywords' => 'Meta Keywords',
        'has_low_balance' => 'Has Low Balance'

    ],

];
