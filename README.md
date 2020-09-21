# ntunnel_mysql_pdo_phpcli-fpm
PHPCLI，PHPFPM，navicat Socket 

PHP管理navicat套接层，PHP管理用户，支持PHPFPM，PHPCLI

PHPCLI模式下使用：

        $ntunnelMysqlPdoPhpcliFpmClass = new ntunnelMysqlPdoPhpcliFpm();
        $ntunnelMysqlPdoPhpcliFpmClass->setCliHeaderFun(function ($headerName, $headerValue) {
            $this->response()->withHeader($headerName, $headerValue);
        });
        $ntunnelMysqlPdoPhpcliFpmClass->setCliEchoFun(function ($information) {
            $this->response()->write($information);
        });
        $ntunnelMysqlPdoPhpcliFpmClass->initializationDefaultParams();
        $ntunnelMysqlPdoPhpcliFpmClass->setParams($this->jsonParams);
        $ntunnelMysqlPdoPhpcliFpmClass->userService();
        $ntunnelMysqlPdoPhpcliFpmClass->main();
        
        $this->response()->write
        $this->response()->withHeader
        这2个方法是PHPCLI模式下，当前你使用的设置头部，输出方法，按实际情况替换
        
PHPFPM模式下使用：

        $ntunnelMysqlPdoPhpcliFpmClass = new ntunnelMysqlPdoPhpcliFpm();
        $ntunnelMysqlPdoPhpcliFpmClass->initializationDefaultParams();
        $ntunnelMysqlPdoPhpcliFpmClass->setParams($this->jsonParams);
        $ntunnelMysqlPdoPhpcliFpmClass->userService();
        $ntunnelMysqlPdoPhpcliFpmClass->main();
