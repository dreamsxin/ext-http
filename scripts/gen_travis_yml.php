#!/usr/bin/env php
# autogenerated file; do not edit
language: c
sudo: false
dist: trusty

addons:
 apt:
  packages:
   - php5-cli
   - php-pear
   - libcurl4-openssl-dev
   - libidn11-dev
   - libidn2-0-dev
   - libicu-dev
   - libevent-dev

compiler:
 - gcc
 - clang

cache:
 directories:
  - $HOME/cache
before_cache:
 - find $HOME/cache -name '*.gcda' -o -name '*.gcno' -delete

env:
<?php

$gen = include "./travis/pecl/gen-matrix.php";
$cur = "7.2";
$env = $gen([
// most useful for all additional versions except current
	"PHP" => ["7.0", "7.1", "7.3", "master"],
	"enable_debug" => "yes",
	"enable_maintainer_zts" => "yes",
	"enable_json" => "yes",
	"enable_hash" => "yes",
	"enable_iconv" => "yes",
	"with_http_libbrotli_dir" => "/home/travis/brotli"
], [
// everything disabled for current
	"PHP" => $cur,
	"with_http_libicu_dir" => "no",
	"with_http_libidn_dir" => "no",
	"with_http_libidn2_dir" => "no",
	"with_http_libcurl_dir" => "no",
	"with_http_libevent_dir" => "no",
], [
// everything enabled for current, switching on debug/zts
	"PHP" => $cur,
	"enable_debug",
	"enable_maintainer_zts",
	"enable_json" => "yes",
	"enable_hash" => "yes",
	"enable_iconv" => "yes",
	"with_http_libbrotli_dir" => "/home/travis/brotli",
], [
// once everything enabled for current, with coverage
	"CFLAGS" => "'-O0 -g --coverage'",
	"CXXFLAGS" => "'-O0 -g --coverage'",
	"PHP" => $cur,
	"enable_json" => "yes",
	"enable_hash" => "yes",
	"enable_iconv" => "yes",
	"with_http_libbrotli_dir" => "/home/travis/brotli",
	[
		"with_http_libicu_dir",
		"with_http_libidn_dir",
		"with_http_libidn2_dir",
	],
]);
foreach ($env as $grp) {
	foreach ($grp as $e) {
		printf(" - %s\n", $e);
	}
}

?>

install:
 - ./travis/brotli.sh v1.0.2
 - |
   if test "$PHP" = master; then \
     make -f travis/pecl/Makefile reconf; \
     make -f travis/pecl/Makefile pecl-rm pecl-clean PECL=ext-raphf.git:raphf:master; \
     make -f travis/pecl/Makefile pecl-rm pecl-clean PECL=ext-propro.git:propro:master; \
   fi
 - make -f travis/pecl/Makefile php || make -f travis/pecl/Makefile clean php
 - make -f travis/pecl/Makefile pecl PECL=ext-raphf.git:raphf:master
 - make -f travis/pecl/Makefile pecl PECL=ext-propro.git:propro:master

script:
 - make -f travis/pecl/Makefile ext PECL=http
 - make -f travis/pecl/Makefile test

after_script:
 - make -f travis/pecl/Makefile cppcheck
after_failure:
 - test -e tests/helper/server.log && cat tests/helper/server.log
after_success:
 - test -n "$CFLAGS" && cd src/.libs && bash <(curl -s https://codecov.io/bash) -X xcode -X coveragepy

notifications:
 webhooks:
  urls:
   - https://webhooks.gitter.im/e/28d35158ac7e385bd14d
  on_success: change
  on_failure: always
  on_start: never
