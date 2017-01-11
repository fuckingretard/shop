<?php


class Product 
{
    const SHOW_BY_DAEFAULT = 6;
    /**
     * returns an array of products
     */
    public static function getLatestProducts($count=  self::SHOW_BY_DAEFAULT)
    {
        $count=intval($count);
        
        $db=Db::getConnection();
        
        $productList = array();
        
        $result = $db->query('SELECT id, name, price, image, is_new FROM product '
                . 'WHERE status = "1"'
                . 'ORDER BY id DESC '                
                . 'LIMIT ' . $count);
        $i=0;
        
        while ($row = $result->fetch()){
            $productList[$i]['id'] = $row['id'];
            $productList[$i]['name'] = $row['name'];
            $productList[$i]['price'] = $row['price'];
            $productList[$i]['image'] = $row['image'];
            $productList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        
        return $productList;
    }
    
    public static function getProductListByCategory($categoryId= false,$page=1){
        
        if($categoryId){
            $page =  intval($page);
            $offset = ($page-1)*self::SHOW_BY_DAEFAULT;
            
            
            $db= Db::getConnection();
            $productList = array();
            $result = $db->query('SELECT id, name, price, image, is_new FROM product '
                    . "WHERE status = '1' AND category_id = '$categoryId'"
                    . 'ORDER BY id DESC '
                    . 'LIMIT ' . self::SHOW_BY_DAEFAULT
                    . ' OFFSET '. $offset);
            $i = 0;

            while ($row = $result->fetch()) {
                $productList[$i]['id'] = $row['id'];
                $productList[$i]['name'] = $row['name'];
                $productList[$i]['price'] = $row['price'];
                $productList[$i]['image'] = $row['image'];
                $productList[$i]['is_new'] = $row['is_new'];
                $i++;
            }
            return $productList;
        }
        
    }
    
    /**
     * Returns product item by id
     * @param integer $id
     */
    public static function getProductById($id)
    {
        $id = intval($id);

        if ($id) {                        
            $db = Db::getConnection();
            
            $result = $db->query('SELECT * FROM product WHERE id=' . $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            
            return $result->fetch();
        }
    }
    
    /**
     * Returns Totatl products
     */
    public static function getTotalProductsInCategory($categoryId)
    {
        //подключение к БД
        $db = Db::getConnection();

        //получение и возврат результатов
        $result = $db->query('SELECT count(id) AS count FROM product '
                . 'WHERE status="1" AND category_id ="'.$categoryId.'"');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $row = $result->fetch();

        return $row['count'];
    }
    
    /**
     * Возвращает список рекомендуемых товаров
     * @return array <p>Массив с товарами</p>
     */
    public static function getRecommendedProducts()
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Получение и возврат результатов
        $result = $db->query('SELECT id, name, price, is_new FROM product '
                . 'WHERE status = "1" AND is_recommended = "1" '
                . 'ORDER BY id DESC');
        $i = 0;
        $productsList = array();
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productsList;
    }

    /**
     * @return products
     */
    public static function getProductsbyIds($idsArray)
    {
        $products = array();
        
        $db =  Db::getConnection();
        
        $idsString = implode(',', $idsArray);
        
        $sql="SELECT * FROM product WHERE status='1' AND id IN ($idsString)";
        
        $result= $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        $i=0;
        while($row= $result->fetch()){
            $products[$i]['id']=$row['id'];
            $products[$i]['code']=$row['code'];
            $products[$i]['name']=$row['name'];
            $products[$i]['price']=$row['price'];
            $i++;
        }
        
        return $products;
    }
    
    /**
     * Возвращает список товаров
     * @return array <p>Массив с товарами</p>
     */
    public static function getProductsList()
    {
        //подключение к БД
        $db = Db::getConnection();
        
        //получение и возврат результатов
        $result=$db->query('SELECT id, name, price, code FROM product '
                .   'ORDER BY id ASC');
        $i=0;
        $productsList=array();
        while($row= $result->fetch()){
            $productsList[$i]['id']=$row['id'];
            $productsList[$i]['name']=$row['name'];
            $productsList[$i]['price']=$row['price'];
            $productsList[$i]['code']=$row['code'];
            $i++;
        }
        
        return $productsList;
    }
    
    /**
     * Удаляет товар с указанім ID
     * @param integer $id <p>ID товара</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function deleteProductById($id)
    {
        //соединение с БД
        $db = Db::getConnection();
        
        //Текст запроса к БД
        $sql = 'DELETE FROM product WHERE id = :id';
        
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id',$id,PDO::PARAM_INT);
        return $result->execute();
    }
    
    /**
     * Функция для добавления продукта в базу данных
     * @param array $options <p>Массив с параметрами продуката</p>
     * @return int <p>Возвращает id добавленного товара</p>
     */
    public static function createProduct($options)
    {
        //соединение с БД
        $db = Db::getConnection();
        
        //Текст запроса к БД
        $sql = 'INSERT INTO product'
                . '(name, code, price, category_id, brand, availability, '
                . 'description, is_new, is_recommended, status) '
                . 'VALUES '
                . '(:name, :code, :price, :category_id, :brand, :availability, '
                . ':description, :is_new, :is_recommended, :status)';
        
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        if($result->execute()){
            //если запрос выполнен успешно, возвращение id записи
            return $db->lastInsertId();
        }
        //иначе возврат нуля
        return 0;
    }
    
    /**
     * Функция для обновления продукта в базе данных
     * @param integer $id <p>ID товара</p>
     * @param array $options <p>Массив с параметрами продуката</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function updateProductById($id,$options)
    {
        //соединение с БД
        $db = Db::getConnection();
        
        //Текст запроса к БД
        $sql = "UPDATE product
            SET
                name = :name,
                code = :code,
                price = :price,
                category_id = :category_id,
                brand = :brand,
                availability = :availability,
                description = :description,
                is_new = :is_new,
                is_recommended = :is_recommended,
                status = :status 
            WHERE id = :id";
        
        //Получение и возврат результата. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id,PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        return $result->execute();
    }
    
    /**
     * Функция для получения ссылки на изображение товара по ID
     * @param integer $id <p>Идентификатор товара</p>
     * @return string <p>Возвращет путь к изображению</p>
     */
    public static function getImage($id)
    {
        //Название картинки "нет фото"
        $noImage = 'no_image.png';
        
        //Путь к папке с товарами
        $path = '/products/images/';
        
        //Путь к изображению товара
        $pathToProductImage = $path. $id . '.jpg';
        
        if(file_exists($_SERVER['DOCUMENT_ROOT']. $pathToProductImage)){
            //Если изображение для товара существует, то возрат пути к товару
            return $pathToProductImage;
        }
        
        //Возвращение изображения "нет фото"
        return $path. $noImage;
    }
}
