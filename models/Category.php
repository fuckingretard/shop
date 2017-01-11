<?php

class Category
{
    /**
     * Возвращает список категорий из БД
     * @return массив 
     */
    public static function getCategoryList()
    {
        //Соединение с БД
        $db=Db::getConnection();
        
        //Создание массива категорий
        $categoryList=array();
        //запрос к БД и возврат результатов
        $result=$db->query('SELECT id, name FROM category '
                . 'ORDER BY sort_order ASC');
        $i=0;
        while ($row=$result->fetch())
        {
            $categoryList[$i]['id']=$row['id'];
            $categoryList[$i]['name']=$row['name'];
            $i++;
        }
        
        return $categoryList;
    }
    
    /**
     * Возвращает массив категорий для списка в панели администратора <br/>
     * (при этом в состав попадают включенные и выключенные категории)
     * @return array <p>Массив категорий</p>
     */
    public static function getCategoryListAdmin(){
        //Соединение с БД
        $db = Db::getConnection();
        
        //запрос к БД
        $result = $db->query('SELECT id, name, sort_order, status FROM category ORDER BY sort_order ASC');
        
        //получение и возврат результатов
        $categoryList = array();
        $i=0;
        while($row = $result->fetch()){
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
        
        return $categoryList;
    }
    
    /**
     * Добавляет новую категорию
     * @param String $name <p>Название категории</p>
     * @param integer $sort_order <p>Порядковый номер</p>
     * @param integer $status <p>Статус</p>
     * @return boolean <p>Возвращает результат выполнения функции</p>
     */
    public static function createCategory($name,$sort_order,$status)
    {
        //Соединение с БД
        $db = Db::getConnection();
        
        //Текст запроса к БД
        $sql = 'INSERT INTO category'
                . '(name, sort_order, status) '
                . 'VALUES '
                . '(:name, :sort_order, :status)';
        
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sort_order, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }
    
    /**
     * Возвращает категорию с указанным Id
     * @param integer $id <p>Id категории</p>
     * @return array <p>Массив с параметрами категории</p>
     */
    public static function getCategoryById($id)
    {
        //Соединение с БД
        $db = Db::getConnection();
        
        //запрос к БД
        $sql = 'SELECT * FROM category WHERE id = :id';
        
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        //получение данных в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        //выполнение запроса
        $result->execute();
        //возвращение данных
        
        return $result->fetch();
    }
    
    /**
     * Редактирование категории с заданым id
     * @param integer $id <p>Id категории</p>
     * @param String $name <p>Название категории</p>
     * @param integer $sort_order <p>Порядковый номер</p>
     * @param integer $status <p>Статус</p>
     * @return boolean <p>Возвращает результат выполнения функции</p>
     */
    public static function updateCategoryById($id,$name,$sort_order,$status)
    {
        //Соединение с БД
        $db = Db::getConnection();
        
        //Текст запроса к БД
        $sql = "UPDATE category
            SET
                name = :name,
                sort_order = :sort_order,
                status = :status
            WHERE id = :id";
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id',$id,  PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sort_order, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }
    
    /**
     * Возвращает текст статуса по численомму идентификатору
     * @param int $status <p>Статус категории</p>
     * @return string <p>Результирующая строка</p>
     */
    public static function getStatusText($status)
    {
        switch ($status){
            case '1':
                return 'Отображается';
                break;
            case '0':
                return 'Скрыта';
                break;
        }
    }

    /**
     * Удаление категории по заданому Id
     * @param integer $id <p>Id категории</p>
     * @return boolean <p>Возвращает результат выполнения функции</p>
     */
    public static function deleteCategoryById($id)
    {
        //Соединение с БД
        $db = Db::getConnection();
        
        //Текст запроса к БД
        $sql = "DELETE FROM category WHERE id = :id";
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id',$id,  PDO::PARAM_INT);
        return $result->execute();
    }
}
