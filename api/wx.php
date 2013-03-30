<?php
include ('../config.php');
include (LIBPATH.'wechat_delegate.class.php');
include (LIBPATH.'wechat_server.class.php');
include (LIBPATH.'wechat_test.class.php');

$delegate = new WechatTest();
$wechat = new WechatServer();
$wechat->run($delegate);