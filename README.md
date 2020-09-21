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
        
PHPFPM模式下使用：

        $ntunnelMysqlPdoPhpcliFpmClass = new ntunnelMysqlPdoPhpcliFpm();
        $ntunnelMysqlPdoPhpcliFpmClass->initializationDefaultParams();
        $ntunnelMysqlPdoPhpcliFpmClass->setParams($this->jsonParams);
        $ntunnelMysqlPdoPhpcliFpmClass->userService();
        $ntunnelMysqlPdoPhpcliFpmClass->main();
