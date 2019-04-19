<?php
namespace DayThreeApp\Main;

use DayThreeApp\Adapters\FtpAdapter as FtpAdapter;
use DayThreeApp\Adapters\HttpAdapter as HttpAdapter;
use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;
use DayThreeApp\DbConnect\MySQLDB as MySQLDB;
use DayThreeApp\DbConnect\PDODB as PDODB;
use DayThreeApp\Adapters\MysqlDbAdapter as MysqlDbAdapter;
use DayThreeApp\Interfaces\DbAdapterInterface as DbAdapterInterface;


/**
 * Класс-обертка для работы с конфигами.
 */
class ConfigReader
{
    const DB_CONFIG_PATH = "config/DbConfig.php";

    private $conn;

    /**
     * Возвращает определенный адаптер.
     *
     * Возвращает нужный класс-адаптер исходя из значения массива по ключу 'source' из полученного объекта класса Configuration.
     *
     * @param Configuration $configuration
     * @return AdapterBase
     */
    private static function initAdapter(Configuration $configuration): AdapterBase
    {
        if ($configuration->getData()['source'] == "ftp") {
            echo "<br>Адаптер - FTPAdapter<br>";
            return new FtpAdapter($configuration);
        } elseif ($configuration->getData()['source'] == "http") {
            echo "<br>Адаптер - HTTPAdapter<br>";
            return new HttpAdapter($configuration);
        }
    }

    /**
     * Помещает элементы массива в таблицу query_table.
     *
     * @param MySQLDB $conn Объект MySQLDB.
     * @param array $configs Массив объектов Configuration.
     */
    public function setConfigsQuery(array $configs)
    {
        foreach ($configs as $configuration) {
            $this->conn->saveConfigToQuery($configuration);
        }
    }

    public function setAdapter()
    {
        $config = include $this::DB_CONFIG_PATH;
        if($config['adapter'] == 'mysqli'){
            return new MysqlDbAdapter($config);
        }
    }

    public function setConnection(DbAdapterInterface $adapter)
    {
        $this->conn = $adapter->setConnection();
    }

    /**
     * Подключение к указанной БД (PDO или MySQL).
     *
     * @param string $db
     */
    public function connectToDB(string $db)
    {
        switch ($db) {
            case 'PDO':
                $this->conn = new PDODB();
                $this->conn->connect("localhost", "root", "", "three");
                break;
            case 'MySQL':
                $this->conn = new MySQLDB();
                $this->conn->connect("localhost", "root", "", "three");
                break;

            default:
                break;
        }
    }

    /**
     * Закрывает соединение с базой данных.
     */
    public function closeConnection()
    {
        $this->conn->close();
    }

    /**
     * Функция обновления конфигов. Вызывает функции записи конфига в таблицу очередей,
     * обработку и перенос в таблицу истории записей.
     *
     * Создается подключение к БД.
     * Из статического метода получает содержимое конфига. Каждый элемент конфига
     * представляет собой объект класса Configuration.
     * Получает объект Configuration из первой записи в таблице очереди.
     * Исходя из полученного конфига определяется нужный адаптер.
     * Создается новый загрузчик.
     * При успешном выполнении "перезаписи" вызываются функция записи
     * конфига класса ConfigurationHistor в таблицу истории конфигов и
     * функция удаления первой записи в таблице очередт.
     *
     * @param string $path Путь до нужного конфиг-файла, начиная с директории /config.
     */
    public function updateConfigs(string $path)
    {
        $adapter = $this->setAdapter();
        $this->setConnection($adapter);
        //$this->connectToDB("MySQL");
        //print_r($this->conn);

        $dbUse = new Logic($this->conn);
        $dbUse->getFirstQueryRecordAsConfiguration();


        // $configurations = AdapterBase::getConfig($path);
        // echo "<hr>Обновление конфигурационных файлов:<hr>";
        // $this->setConfigsQuery($configurations);
        // echo "<hr>";
        // $queryCount = $this->conn->getQueryCount();
        // for ($i = 0; $i < $queryCount; $i++) {
        //     $configFromQuery = $this->conn->getFirstQueryRecordAsConfiguration();
        //     echo "<br>Название прайслиста: " . $configFromQuery->getData()['title'];
        //     echo "<br>Источник прайслиста: " . $configFromQuery->getData()['source'];
        //     $adapter = $this::initAdapter($configFromQuery);
        //     $loader = $adapter->setLoader();
        //     echo "<br>Перезапись конфигурации прайслиста...";
        //     if ($confHistory = $loader->rewriteConfig()) {
        //         $this->conn->addConfHistory($confHistory);
        //         $this->conn->deleteFirstQueryRecord();
        //         echo "<br>Ошибки: " . $confHistory->getErrors();
        //         echo "<br>Количество измененных линий: " . $confHistory->getChangedLines();
        //     } else {
        //         echo "Конфигурация не была переписана.";
        //     }
        //     echo "<hr>";
        // }
        // $this->closeConnection();
    }
    /*
     * Выводит каждую запись из таблицы history.
     */
    public function showHistory()
    {
        $this->connectToDB("MySQL");
        echo "История изменений:<br>";
        $historyArray = $this->conn->getTableContentAsArrayAll('history');
        $arrObj = new \ArrayObject($historyArray);
        $it = $arrObj->getIterator();
        echo "Iterating over: " . $arrObj->count() . " values\n";

        echo "<table style='width:600px;border:1px solid black'>";
        echo "<tr style='border:1px solid black'>
            <th style='width:200px;border:1px solid black'>Название</th>
            <th style='border:1px solid black'>Количество измененных строк</th>
            <th style='border:1px solid black'>Количество ошибок</th>
            <th style='width:200px;border:1px solid black'>Время изменения</th>
        </tr>";
        // while($it->valid()){
        //     print_r($it->current()['configuration']);
        //     echo "<br><br>";
        //     $it->next();
        // }
        while ($it->valid()) {
            //print_r($it->current()['configuration']);
            $config = unserialize($it->current()['configuration']);
            echo "<tr style='border:1px solid black'>";
            echo "<th style='width:200px;border:1px solid black'>" . $config->getData()['title'] . "</th>";
            echo "<th style='border:1px solid black'>" . $it->current()['changed_lines'] . "</th>";
            echo "<th style='border:1px solid black'>" . $it->current()['errors'] . "</th>";
            echo "<th style='width:200px;border:1px solid black'>" . $it->current()['created_at'] . "</th>";
            echo "</tr>";
            $it->next();
        }
        // for ($i = 0; $i < sizeof($historyArray); $i++) {
        //     $config = unserialize($historyArray[$i]['configuration']);
        //     echo "<tr style='border:1px solid black'>";
        //     echo "<th style='border:1px solid black'>" . $config->getData()['title'] . "</th>";
        //     echo "<th style='border:1px solid black'>" . $historyArray[$i]['changed_lines'] . "</th>";
        //     echo "<th style='border:1px solid black'>" . $historyArray[$i]['errors'] . "</th>";
        //     echo "</tr>";
        // }
        echo "</table>";
        $this->closeConnection();
    }
}
