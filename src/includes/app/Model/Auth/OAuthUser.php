<?php

namespace Model\Auth;

class OAuthUser extends OAuth
{
    public function __construct()
    {
        $redirectUrl = site_url('/' . OAUTH_USER . '?webo_oauth=1');
        $scopes = [
            'email',
            'public_profile',
            'user_friends',
            'user_birthday',
            'user_gender',
            'user_location',
            'user_hometown',
            'user_link',
            'user_photos',
            'user_posts',
            'user_videos',
            'user_likes',
            'user_age_range',
        ];
        parent::__construct($redirectUrl, $scopes, false);
    }
}
