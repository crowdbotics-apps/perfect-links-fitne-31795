<?php
return [
  "driver" => "smtp",
  "host" => "smtp.mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "b875c0f408a901",
  "password" => "9ad6526ea7e26b",
  "sendmail" => "/usr/sbin/sendmail -bs"
];