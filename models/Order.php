<?php

class Order {
    /**
     * Сохранение заказа
     * @param type $name
     * @param type $phone
     * @param type $email
     * @param type $password
     * @return type 
     */
    public static function save($userName,$userPhone,$userComment,$userId,$products)
    {
        $products = json_encode($products);
        
        $db= Db::getConnection();
        
        $sql = 'INSERT INTO product_order (user_name, user_phone, user_comment, user_id, products) '
                . 'VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)';
        
        
        
        $result = $db->prepare($sql);
        $result->bindParam(':user_name',$userName,PDO::PARAM_STR);
        $result->bindParam(':user_phone',$userPhone,PDO::PARAM_STR);
        $result->bindParam(':user_comment',$userComment,PDO::PARAM_STR);
        $result->bindParam(':user_id',$userId,PDO::PARAM_INT);
        $result->bindParam(':products',$products,PDO::PARAM_STR);
        
        return $result->execute();
    }
    
    /**
     * Возвращает список всех заказов
     * @return array <p>Массив заказов</p>
     */
    public static function getOrderList()
    {
        //подключение к БД
        $db = Db::getConnection();
        
        //получение и возврат результатов
        $result=$db->query('SELECT id, user_name, user_phone, date, status FROM product_order '
                .   'ORDER BY id DESC');
        $i=0;
        $ordersList=array();
        while($row= $result->fetch()){
            $ordersList[$i]['id']=$row['id'];
            $ordersList[$i]['user_name']=$row['user_name'];
            $ordersList[$i]['user_phone']=$row['user_phone'];
            $ordersList[$i]['date']=$row['date'];
            $ordersList[$i]['status']=$row['status'];
            $i++;
        }
        
        return $ordersList;
    }
    
    /**
     * 
     * @return array 
     */
    public static function getOrderById($id)
    {
        //подключение к БД
        $db= Db::getConnection();
        
        //Текст запроса
        $sql = 'SELECT * FROM product_order WHERE id = :id';
        
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id',$id,PDO::PARAM_INT);
        //получение данных в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        //выполнение запроса
        $result->execute();
        //возвращение данных
        
        return $result->fetch();
    }
    
    public static function getStatusText($status)
    {
        switch ($status){
            case 1 : 
                return 'Новый заказ';
                break;
            case 2 : 
                return 'В обработке';
                break;
            case 3 : 
                return 'Доставляется';
                break;
            case 4 : 
                return 'Закрыт';
                break;
        }
    }

    /**
     * Обновление заказа
     * @param integer $id <p>Id товара</p>
     * @param string $userName <p>Имя клиента</p>
     * @param string $userPhone <p>Телефон клиента</p>
     * @param string $userComment <p>Комментарий клиента</p>
     * @param string $date <p>Дата оформления заказа</p>
     * @param integer $status <p>Статус заказа</p>
     * @return boolean <p>Результат выполнения  метода</p>
     */
    public static function updateOrderById($id,$userName,$userPhone,$userComment,$date,$status)
    {
        //подключение к БД
        $db= Db::getConnection();
        
        //Текст запроса
        $sql = "UPDATE product_order
            SET
                user_name = :user_name,
                user_phone = :user_phone,
                user_comment = :user_comment,
                date = :date,
                status = :status
            WHERE id = :id";
        
        
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_name',$userName,PDO::PARAM_STR);
        $result->bindParam(':user_phone',$userPhone,PDO::PARAM_STR);
        $result->bindParam(':user_comment',$userComment,PDO::PARAM_STR);
        $result->bindParam(':id',$id,PDO::PARAM_INT);
        $result->bindParam(':date',$date,PDO::PARAM_STR);
        $result->bindParam(':status',$status,PDO::PARAM_INT);
        
        return $result->execute();
    }
    
    public static function deleteOrderById($id)
    {
        //подключение к БД
        $db= Db::getConnection();
        
        //Текст запроса
        $sql = "DELETE FROM product_order WHERE id = :id";
        
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id',$id,PDO::PARAM_INT);
        
        return $result->execute();
    }
}
