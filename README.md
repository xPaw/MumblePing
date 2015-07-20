# PHP Mumble Ping

Mumble supports querying server information by sending a ping
packet to the target server. This includes: server version,
currently connected users, max users allowed, allowed bandwidth.
Read more about it [here](http://wiki.mumble.info/wiki/Protocol#UDP_Ping_packet).

### How do use it

```php
<?php

use xPaw\Mumble;

// require or your favourite autoloader
require __DIR__ . '/MumblePing.php';

$Info = MumblePing( 'example.com', 64738 );

echo 'Users: ' . $Info[ 'Users' ] . ' / ' . $Info[ 'MaxUsers' ] . '<br>';
echo 'Version: ' . $Info[ 'Version' ] . '<br>';
echo 'Bandwidth: ' . $Info[ 'Bandwidth' ] . ' (bytes)<br>';
```
