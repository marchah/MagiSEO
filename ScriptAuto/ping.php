<?php

print_r(parse_url('https://www.google.fr/search?q=php+convert+host+in+ip&ie=utf-8&oe=utf-8&aq=t&rls=org.mozilla:en-US:official&client=firefox-a&channel=sb&gfe_rd=cr&ei=bYNoVKiUA4ODbKTmgogI', PHP_URL_HOST));

echo gethostbyname('google.com') . "\n";
