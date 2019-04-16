<?php
namespace DayThreeApp\Main;

use DayThreeApp\Adapters\FtpAdapter as FtpAdapter;
use DayThreeApp\Adapters\HttpAdapter as HttpAdapter;
use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;
use DayThreeApp\DbConnect\MySQLDB as MySQLDB;
use DayThreeApp\Interfaces\DBInterface as DBInterface;

/**
 * Класс-обертка для работы с конфигами.
 */
class ConfigReader
{

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
    public function setConfigsQuery(DBInterface $conn, array $configs)
    {
        foreach ($configs as $configuration) {
            $conn->saveConfigToQuery($configuration);
        }
    }

    // /**
    //  * Вызывает функцию getFirstQueryRecordAsConfiguration класса БД.
    //  *
    //  * @param MySQLDB $conn
    //  * @return Configuration
    //  */
    // public function getConfigFromQuery(MySQLDB $conn): Configuration
    // {
    //     return $conn->getFirstQueryRecordAsConfiguration();
    // }

    // /**
    //  * Вызывает функцию deleteFirstQueryRecord класса БД.
    //  *
    //  * @param MySQLDB $conn
    //  */
    // public function deleteQueryRecord(MySQLDB $conn)
    // {
    //     $conn->deleteFirstQueryRecord();
    // }

    // /**
    //  * Вызывает функцию moveConfFromQueryToHistory класса БД.
    //  *
    //  * @param MySQLDB $conn
    //  */
    // public function moveConfFromQueryToHistory(MySQLDB $conn)
    // {
    //     $conn->moveConfFromQueryToHistory();
    // }

    // /**
    //  * Вызывает функцию moveConfFromQueryToHistory класса БД.
    //  *
    //  * @param MySQLDB $conn
    //  * @param ConfigurationHistory $confHistory
    //  *
    //  */
    // public function addConfHistory(MySQLDB $conn, ConfigurationHistory $confHistory)
    // {
    //     $conn->addConfHistory($confHistory);
    // }

    /**
     * Подключение к БД.
     */
    public function connectToDB()
    {
        $this->conn = new MySQLDB();
        $this->conn->connect("localhost", "root", "", "three");
        return $this->conn;
    }

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
        $this->connectToDB();
        // $conn = new MySQLDB();
        // $conn->connect("localhost", "root", "", "three");
        $configurations = AdapterBase::getConfig($path);
        echo "<hr>Обновление конфигурационных файлов:<hr>";
        $this->setConfigsQuery($this->conn, $configurations);
        echo "<hr>";
        $queryCount = $this->conn->getQueryCount();
        for ($i = 0; $i < $queryCount; $i++) {
            //print_r($this->getConfigFromQuery($conn));
            $configFromQuery = $this->conn->getFirstQueryRecordAsConfiguration();
            echo "Название прайслиста: " . $configFromQuery->getData()['title'] . "<br>";
            echo "Источник прайслиста: " . $configFromQuery->getData()['source'] . "<br>";
            $adapter = $this::initAdapter($configFromQuery);
            $loader = $adapter->setLoader();
            // print_r($loader);
            echo "Перезапись конфигурации прайслиста...<br>";
            if ($confHistory = $loader->rewriteConfig()) {
                // $this->addConfHistory($conn, $confHistory);
                $this->conn->addConfHistory($confHistory);
                // $this->deleteQueryRecord($conn);
                $this->conn->deleteFirstQueryRecord();
                echo "Ошибки: " . $confHistory->getErrors() . "<br>";
                echo "Количество измененных линий: " . $confHistory->getChangedLines() . "<br>";
            } else {
                echo "Конфигурация не была переписана.";
            }
            echo "<hr>";
        }
        $this->closeConnection();
    }

    /**
     * Выводит каждую запись из таблицы history.
     */
    public function showHistory()
    {
        $this->connectToDB();
        echo "История изменений:<br>";
        $historyArray = $this->conn->getTableContentAsArrayAll('history');
        //print_r($historyArray);
        echo "<table style='width:600px;'>";
        echo "<tr><th>Название</th><th>Количество измененных строк</th><th>Количество ошибок</th></tr>";
        for($i = 0; $i<sizeof($historyArray); $i++){
            //print_r($historyArray[$i]);
            $config = unserialize($historyArray[$i]['configuration']);
            echo "<tr>";
            echo "<th>".$config->getData()['title']."</th>";
            echo "<th>" . $historyArray[$i]['changed_lines'] . "</th>";
            echo "<th>" . $historyArray[$i]['errors'] . "</th>";
            //print_r($config->getData());
            echo "</tr>";
        }
        echo "</table>";
        $this->closeConnection();
    }
    // // Версия с подключением через PDO
    // public function updateConfigs(string $path)
    // {
    //     $conn = new PDODB();
    //     $conn->connect("localhost", "root", "", "three");
    //     $configurations = AdapterBase::getConfig($path);
    //     $this->setConfigsQuery($conn, $configurations);
    //     echo "<hr>";
    //     $queryCount = $conn->getQueryCount();
    //     for ($i = 0; $i < $queryCount; $i++) {
    //         //print_r($this->getConfigFromQuery($conn));
    //         $configFromQuery = $conn->getFirstQueryRecordAsConfiguration();
    //         echo "Название прайслиста: " . $configFromQuery->getData()['title'] . "<br>";
    //         echo "Источник прайслиста: " . $configFromQuery->getData()['source'] . "<br>";
    //         $adapter = $this::initAdapter($configFromQuery);
    //         $loader = $adapter->setLoader();
    //         // print_r($loader);
    //         echo "Перезапись конфигурации прайслиста...<br>";
    //         if ($confHistory = $loader->rewriteConfig()) {
    //             // $this->addConfHistory($conn, $confHistory);
    //             $conn->addConfHistory($confHistory);
    //             // $this->deleteQueryRecord($conn);
    //             $conn->deleteFirstQueryRecord();
    //             echo "<br>Ошибки: " . $confHistory->getErrors();
    //             echo "<br>Количество измененных линий: " . $confHistory->getChangedLines() . "<br>";
    //         } else {
    //             echo "Конфигурация не была переписана.";
    //         }
    //         echo "<hr>";
    //     }
    // }
}
