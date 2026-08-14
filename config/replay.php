<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Replay Access Token TTL (seconds)
    |--------------------------------------------------------------------------
    | How long a replay access token is valid for. After expiry the token is
    | rejected even if unused. Defaults to 5 minutes (300 seconds).
    | Tokens are single-use: once /stream is hit the token is considered spent
    | (though it is NOT revoked — it simply expires naturally or is revoked
    | explicitly by the issuing service).
    |
    | NOTE: These tokens control access to the Konn3ct API. They do NOT
    | revoke the underlying BigBlueButton playback URL, which is permanently
    | public at the BBB server. BBB protection is PARTIAL — API-layer only.
    */
    'access_token_ttl_seconds' => (int) env('REPLAY_ACCESS_TOKEN_TTL_SECONDS', 300),

];
