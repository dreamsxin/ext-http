#!/usr/bin/env php
# autogenerated file; do not edit
language: c

addons:
 apt:
  packages:
   - php5-cli
   - php-pear
   - libcurl4-openssl-dev
   - zlib1g-dev
   - libidn11-dev
   - libevent-dev

env:
<?php

$gen = include "./travis/pecl/gen-matrix.php";
$env = $gen([
	"PHP" => ["7.0", "master"],
	"enable_debug",
	"enable_maintainer_zts",
	"enable_json",
	"enable_hash" => ["yes"],
	"enable_iconv" => ["yes"],
]);
foreach ($env as $e) {
	printf(" - %s\n", $e);
}

?>

before_script:
 - make -f travis/pecl/Makefile php
 - make -f travis/pecl/Makefile pecl PECL=raphf:raphf:2.0.0
 - make -f travis/pecl/Makefile pecl PECL=propro:propro:2.0.1
 - make -f travis/pecl/Makefile ext PECL=http

script:
 - make -f travis/pecl/Makefile test

after_script:
 - test -e tests/helper/server.log && cat tests/helper/server.log

sudo: false
notifications:
 webhooks:
  urls:
   - https://webhooks.gitter.im/e/28d35158ac7e385bd14d
  on_success: change
  on_failure: always
  on_start: never
