<?php


class Cart {
    public static function addProduct($id)
    {
        $id=intval($id);
        
        //Пустой массив для товаров в корзине
        $productsInCart = array();
        
        //Если  в корзине уже есть товары (они хранятся в сессии)
        if(isset($_SESSION['products'])){
            //То заполним  наш массив товарами
            $productsInCart= $_SESSION['products'];
        }
        
        //Если товар есть в корзине, но был  добавлен еще раз, увиличим колличество
        if(array_key_exists($id, $productsInCart)){
            $productsInCart[$id]++;
        } else {
            //добавление нового товара в корзину
            $productsInCart[$id] = 1;
        }
        
        $_SESSION['products'] = $productsInCart;
        //echo '<pre>';print_r($_SESSION['products']);die();
        return self::countItems();
    }
    
    public static function deleteItem($id)
    {
        $productsInCart = self::getProducts();
        //проверка есть ли товар в сессии
        unset($productsInCart[$id]);
        $_SESSION['products']=$productsInCart;
        
        return self::countItems();
    }

    

    /**
     * Подсчет колличества товаров в корзине(в сесии)
     * @return int 
     */
    public static function countItems()
    {
        if(isset($_SESSION['products'])){
            $count = 0;
            foreach($_SESSION['products'] as $id=>$quantity){
                $count+=$quantity;
            }
            return $count;
        }
        else{
            return 0;
        }
    }
    
    public static function getProducts()
    {
        if(isset($_SESSION['products'])){
            return $_SESSION['products'];
        }
        return false;
    }
    
    public static function getTotalPrice($products)
    {
        $productsInCart = self::getProducts();
        
        $totalPrice=0;
        
        if($productsInCart){
            foreach ($products as $item){
                $totalPrice+=$item['price']*$productsInCart[$item['id']];
            }
        }
        return $totalPrice;
    }
    
    public static function clear()
    {
        if(isset($_SESSION['products'])){
            unset($_SESSION['products']);
        }
    }
}
