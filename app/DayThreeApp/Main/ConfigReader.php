<?php
namespace DayThreeApp\Main;

use DayThreeApp\Adapters\FtpAdapter as FtpAdapter;
use DayThreeApp\Adapters\HttpAdapter as HttpAdapter;
use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;
use DayThreeApp\DbConnect\MySQLDB as MySQLDB;

/**
 * Класс-обертка для работы с конфигами.
 */
class ConfigReader
{
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
            echo "<br>Загрузчик FTPloader<br>";
            return new FtpAdapter($configuration);
        } elseif ($configuration->getData()['source'] == "http") {
            echo "<br>Загрузчик Httploader<br>";
            return new HttpAdapter($configuration);
        }
    }

    /**
     * Помещает элементы конфиг-файла в таблицу query_table.
     *
     * @param MySQLDB $conn Объект MySQLDB.
     * @param array $configs Массив объектов Configuration.
     */
    public function setConfigsQuery(MySQLDB $conn, array $configs)
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
        $conn = new MySQLDB();
        $conn->connect("localhost", "root", "", "three");
        $configurations = AdapterBase::getConfig($path);
        $this->setConfigsQuery($conn, $configurations);
        echo "<hr>";
        $count = $conn->getQueryCount();
        for ($i = 0; $i < $count; $i++) {
            echo "Название прайслиста: " . $configurations[$i]->getData()['title'] . "<br>";
            echo "Источник прайслиста: " . $configurations[$i]->getData()['source'] . "<br>";
            //print_r($this->getConfigFromQuery($conn));
            $adapter = $this::initAdapter($conn->getFirstQueryRecordAsConfiguration());
            $loader = $adapter->setLoader();
            //print_r($loader);
            echo "Перезапись конфигурации прайслиста...<br>";
            if ($confHistory = $loader->rewriteConfig()) {
                // $this->addConfHistory($conn, $confHistory);
                $conn->addConfHistory($confHistory);
                // $this->deleteQueryRecord($conn);
                $conn->deleteFirstQueryRecord();
                echo "Ошибки: " . $confHistory->getErrors() . "<br>";
                echo "Количество измененных линий: " . $confHistory->getChangedLines() . "<br>";
            } else {
                echo "Конфигурация не была переписана.";

            }
            echo "<hr>";
        }
    }
}
