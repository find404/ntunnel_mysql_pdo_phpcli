<?php
declare(strict_types=1);
/**
 * 数据库链接相关
 * @author      zhy    find404@foxmail.com
 * @createTime  2020年9月19日 17:50:26
 * @version     0.1.0 版本号
 */

class ntunnelMysqlPdoPhpcliFpm
{

    /**
     * 是否PHPCLI模式
     * @void
     */
    private $PHPCLIModel = true;

    /**
     * 传递参数
     * @array
     */
    private $params = [];

    /**
     * CLI模式下头部方法
     * @function
     */
    private $cliHeaderFun = [];

    /**
     * CLI模式下输出方法
     * @function
     */
    private $cliEchoFun = [];

    /**
     * 设置传参
     * @param $params array 查询
     * @return void
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * 设置传参
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function setPHPCLIModel()
    {
        if (php_sapi_name() == 'cli') {
            $this->PHPCLIModel = true;
        } else {
            $this->PHPCLIModel = false;
        }
    }

    /**
     * 设置CLI模式下，特殊传递请求头方法
     * @param function $fun 设置响应头
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月21日 15:40:32
     */
    public function setCliHeaderFun($fun)
    {
        $this->cliHeaderFun = $fun;
    }

    /**
     * 设置CLI模式下，特殊返回方法
     * @param function $fun 设置响应头
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月21日 15:40:32
     */
    public function setCliEchoFun($fun)
    {
        $this->cliEchoFun = $fun;
    }

    /**
     * 设置返回头
     * @return void
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function initializationDefaultParams()
    {
        error_reporting(0);
        set_time_limit(0);
        $this->setPHPCLIModel();
        if ($this->PHPCLIModel) {
            $cliHeaderFun = $this->cliHeaderFun;
            $cliHeaderFun('Content-type', 'text/plain; charset=x-user-defined');
        } else {
            header("Content-Type: text/plain; charset=x-user-defined");
        }
    }

    /**
     * 获取PHP版本INT号
     * @return void
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function getPhpVersionInt()
    {
        list($maVer, $miVer, $edVer) = preg_split("(/|\.|-)", phpversion());
        return $maVer * 10000 + $miVer * 100 + $edVer;
    }

    /**
     * 获取传入值的长二进制
     * @param $number int
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function getLongBinary($number)
    {
        return pack("N", $number);
    }

    /**
     * 获取传入值的短二进制
     * @param $number int
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function getShortBinary($number)
    {
        return pack("n", $number);
    }

    /**
     * 获取补位传参
     * @param $number int
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function getDummy($number)
    {
        $binaryString = "";
        for ($i = 0; $i < $number; $i++) {
            $binaryString .= "\x00";
        }
        return $binaryString;
    }

    /**
     * 获取组合块
     * @param $value string
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function getBlock($value)
    {
        $valueLength = strlen($value);
        if ($valueLength < 254)
            return chr($valueLength) . $value;
        else
            return "\xFE" . $this->getLongBinary($valueLength) . $value;
    }

    /**
     * 输出头部
     * @param $errorCode mysql错误码
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function outputHeader($errorCode)
    {
        $outputHeaderString = $this->getLongBinary(1111);
        $outputHeaderString .= $this->getShortBinary(202);
        $outputHeaderString .= $this->getLongBinary($errorCode);
        $outputHeaderString .= $this->getDummy(6);
        $this->outputInformation($outputHeaderString);
    }

    /**
     * 输出连接信息  Connection information
     * PDO::ATTR_AUTOCOMMIT: 1
     * PDO::ATTR_ERRMODE: 0
     * PDO::ATTR_CASE: 0
     * PDO::ATTR_CLIENT_VERSION: mysqlnd 5.0.12-dev - 20150407 - $Id: 3591daad22de08524295e1bd073aceeff11e6579 $
     * PDO::ATTR_CONNECTION_STATUS: db5 via TCP/IP
     * PDO::ATTR_ORACLE_NULLS: 0
     * PDO::ATTR_PERSISTENT:
     * PDO::ATTR_PREFETCH:
     * PDO::ATTR_SERVER_INFO: Uptime: 256447  Threads: 3  Questions: 3710  Slow queries: 0  Opens: 264  Flush tables: 1  Open tables: 257  Queries per second avg: 0.014
     * PDO::ATTR_SERVER_VERSION: 5.7.25
     * PDO::ATTR_TIMEOUT:
     * @param object $connection mysql错误码
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function outoptConnectionInformation($connection)
    {
        $connectionBlockString = $this->getBlock($connection->getAttribute(\PDO::ATTR_CONNECTION_STATUS));
        $connectionBlockString .= $this->getBlock($connection->getAttribute(\PDO::ATTR_CLIENT_VERSION));
        $connectionBlockString .= $this->getBlock($connection->getAttribute(\PDO::ATTR_SERVER_VERSION));
        $this->outputInformation($connectionBlockString);
    }

    /**
     * 输出结果
     * @param int/string $errorCode mysql错误码
     * @param int/string $affectrows 影响行
     * @param int/string $insertid 插入ID
     * @param int/string $numberFields 字段行
     * @param int/string $numberRows 行数
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function outputResult($errorCode, $affectrows, $insertid, $numberFields, $numberRows)
    {
        $resultBinaryString = $this->getLongBinary($errorCode);
        $resultBinaryString .= $this->getLongBinary($affectrows);
        $resultBinaryString .= $this->getLongBinary($insertid);
        $resultBinaryString .= $this->getLongBinary($numberFields);
        $resultBinaryString .= $this->getLongBinary($numberRows);
        $resultBinaryString .= $this->getDummy(12);
        $this->outputInformation($resultBinaryString);
    }

    /**
     * 输出字段头部
     * @param array $result 操作结果
     * @param array $numberFields 字段行
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function outputFieldsHeader($result, $numberFields)
    {
        $fieldsHeaderStr = "";
        for ($i = 0; $i < $numberFields; $i++) {
            $finfo = $result->getColumnMeta($i);
            $fieldsHeaderStr .= $this->getBlock($finfo['name']);
            $fieldsHeaderStr .= $this->getBlock($finfo['table']);
            // 与现有的类型不一致，需要优化
            $type = strtolower($finfo['native_type']);
            // php官方说 len 除了浮点型外，都是-1
            $length = $finfo['len'];
            switch ($type) {
                case "int":
                    if ($length > 11) $type = 8;
                    else $type = 3;
                    break;
                case "real":
                    if ($length == 12) $type = 4;
                    elseif ($length == 22) $type = 5;
                    else $type = 0;
                    break;
                case "null":
                    $type = 6;
                    break;
                case "timestamp":
                    $type = 7;
                    break;
                case "date":
                    $type = 10;
                    break;
                case "time":
                    $type = 11;
                    break;
                case "datetime":
                    $type = 12;
                    break;
                case "year":
                    $type = 13;
                    break;
                case "blob":
                    if ($length > 16777215) $type = 251;
                    elseif ($length > 65535) $type = 250;
                    elseif ($length > 255) $type = 252;
                    else $type = 249;
                    break;
                default:
                    $type = 253;
            }
            $fieldsHeaderStr .= $this->getLongBinary($type);

            $flags = $finfo['flags'];
            $intflag = 0;

            $keyMaps = [
                'not_null' => 1,
                'primary_key' => 2,
                'unique_key' => 4,
                'multiple_key' => 8,
                'blob' => 16,
                'unsigned' => 32,
                'zerofill' => 64,
                'binary' => 128,
                'enum' => 256,
                'auto_increment' => 512,
                'timestamp' => 1024,
                'set' => 2048,
            ];
            foreach ($keyMaps as $key => $val) {
                if (in_array($key, $flags)) {
                    $intflag += $val;
                }
            }
//            if (in_array("not_null", $flags)) $intflag += 1;
//            if (in_array("primary_key", $flags)) $intflag += 2;
//            if (in_array("unique_key", $flags)) $intflag += 4;
//            if (in_array("multiple_key", $flags)) $intflag += 8;
//            if (in_array("blob", $flags)) $intflag += 16;
//            if (in_array("unsigned", $flags)) $intflag += 32;
//            if (in_array("zerofill", $flags)) $intflag += 64;
//            if (in_array("binary", $flags)) $intflag += 128;
//            if (in_array("enum", $flags)) $intflag += 256;
//            if (in_array("auto_increment", $flags)) $intflag += 512;
//            if (in_array("timestamp", $flags)) $intflag += 1024;
//            if (in_array("set", $flags)) $intflag += 2048;
            $fieldsHeaderStr .= $this->getLongBinary($intflag);
            $fieldsHeaderStr .= $this->getLongBinary($length);
        }
        $this->outputInformation($fieldsHeaderStr);
    }

    /**
     * 输出查询数据
     * @param array $result sql连接信息
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function outputData($result)
    {
        while ($row = $result->fetch(\PDO::FETCH_NUM)) {
            $dataRow = '';
            foreach ($row as $item) {
                if (is_null($item))
                    $dataRow .= "\xFF";
                else
                    $dataRow .= $this->getBlock($item);
            }
            $this->outputInformation($dataRow);
        }
    }

    /**
     * 用户权限服务相关
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月21日 15:33:12
     */
    public function userService()
    {

    }


    /**
     * 执行查询SQL
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月19日 17:55:15
     */
    public function main()
    {
        $allowTestMenu = true;
        if ($this->getPhpVersionInt() < 40005) {
            $this->outputHeader(201);
            $this->outputInformation($this->getBlock("unsupported php version"));
            return;
        }

        $testMenu = false;
        if (!isset($this->params["actn"]) || !isset($this->params["host"]) || !isset($this->params["port"]) || !isset($this->params["login"])) {
            $testMenu = $allowTestMenu;
            if (!$testMenu) {
                $this->outputHeader(202);
                $this->outputInformation($this->getBlock("invalid parameters"));
                return;
            }
        }

        if (!$testMenu) {
            if (isset($this->params["encodeBase64"]) && $this->params["encodeBase64"] == '1') {
                for ($i = 0; $i < count($this->params["q"]); $i++)
                    $this->params["q"][$i] = base64_decode($this->params["q"][$i]);
            }


            if (!class_exists("PDO")) {
                $this->outputHeader(203);
                $this->outputInformation($this->getBlock("MySQL not supported on the server"));
                return;
            }

            if (!in_array('mysql', pdo_drivers())) {
                $this->outputHeader(203);
                $this->outputInformation($this->getBlock("pdo_mysql not install on the server"));
                return;
            }

            $errno = 0;

            try {
                $connectObj = new \PDO('mysql:host=' . $this->params['host'] . ';port=' . $this->params['port']  , $this->params['login'] ,$this->params['password']);
            } catch (\PDOException $e) {
                $errno = $e->getCode();
                $error = $e->getMessage();
            }
            if ($errno > 0) {
                $this->outputHeader($errno);
                $this->outputInformation($this->getBlock($error));
                return;
            }

            if (($errno <= 0) && ($this->params["db"] != "")) {
                $connectObj->exec('use ' . $this->params["db"]);
                $errno = $connectObj->errorCode();
                if ($errno > 0) {
                    $this->outputInformation($this->getBlock($connectObj->errorInfo()[2]));
                }
            }

            $this->outputHeader($errno);
            if ($this->params["actn"] == "C") {
                $this->outoptConnectionInformation($connectObj);
            } elseif ($this->params["actn"] == "Q") {
                for ($i = 0; $i < count($this->params["q"]); $i++) {
                    $query = $this->params["q"][$i];
                    if ($query == "") continue;
                    if ($this->getPhpVersionInt() < 50400) {
                        if (get_magic_quotes_gpc())
                            $query = stripslashes($query);
                    }

                    $result = $connectObj->prepare($query);
                    if ($result->execute()) {
                        $numfields = $result->columnCount();
                        $numrows = $result->rowCount();
                        $affectedrows = $numrows;
                        $insertid = $connectObj->lastInsertId();
                        $errno = 0;
                        $error = '';
                    } else {
                        $errorInfo = $result->errorInfo();
                        $errno = $errorInfo[1];
                        $error = $errorInfo[2];
                        $numfields = $numrows = $affectedrows = $insertid = 0;
                    }

                    $this->outputResult($errno, $affectedrows, $insertid, $numfields, $numrows);
                    if ($errno > 0) {
                        $this->outputInformation($this->getBlock($error));
                    } else {
                        if ($numfields > 0) {
                            $this->outputFieldsHeader($result, $numfields);
                            $this->outputData($result);
                        } else {
                            $this->outputInformation($this->getBlock(""));
                        }
                    }
                    if ($i < (count($this->params["q"]) - 1))
                        $this->outputInformation("\x01");
                    else
                        $this->outputInformation("\x00");
                }
            }
            return;
        }

    }


    /**
     * 输出结果集
     * @param string $information 输出信息
     * @return string
     * @author     zhy    find404@foxmail.com
     * @createTime 2020年9月21日 14:36:46
     */
    public function outputInformation(string $information)
    {
        if ($this->PHPCLIModel) {
            $cliEchoFun = $this->cliEchoFun;
            $cliEchoFun($information);
        } else {
            echo $information;
        }
    }


}