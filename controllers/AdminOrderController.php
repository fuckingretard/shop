<?php

/**
 * Контроллер AdminCategoryController
 * Управление заказами товаров
 */
class AdminOrderController extends AdminBase {
    
    /*
     * Action для страницы Управление заказами
     */
    public function actionIndex()
    {
        //Проверка админ прав
        self::checkAdmin();
        
        //Получение списка категорий
        $ordersList = Order::getOrderList();
        
        //Подключение вида
        require_once(ROOT.'/views/admin_order/index.php');
        return true;
    }
    
    /**
     * Action для страницы "Редактировать заказ"
     * @return boolean
     */
    public function actionUpdate($id){
        //Проверка доступа
        self::checkAdmin();
        
        //Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);
        
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена получение данных формы
            $userName= $_POST['userName'];
            $userPhone= $_POST['userPhone'];
            $userComment= $_POST['userComment'];
            $date= $_POST['date'];
            $status= $_POST['status'];
            
            
            //сохранение изменения
            Order::updateOrderById($id,$userName,$userPhone,$userComment,$date,$status);
            
            //перенаправление пользователя на страницу управления заказами
            header("Location:/admin/order/view/$id");
        }
        //Подключение вида
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }
    
    /**
     * Action для страницы "Просмотреть заказ"
     * @return boolean
     */
    public function actionView($id){
        //Проверка доступа
        self::checkAdmin();
        
        //Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);
        
        //Получаем массив с id и колличеством товаров
        $productsQuantity = json_decode($order['products'],true);
        
        //Получаем массив с идентификаторами товаров
        $productsIds = array_keys($productsQuantity);
        
        //Получаем список товаров в заказе
        $products = Product::getProductsbyIds($productsIds);
        
        //Подключение вида
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }
    
    /**
     * Action для страницы "Удалить заказ"
     * @return boolean
     */
    public function actionDelete($id){
        //Проверка доступа
        self::checkAdmin();
        
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена
            //Удаляеем товар
            Order::deleteOrderById($id);
            
            //перенаправление на предыдущую страницу
            header("Location: /admin/order");
        }
        
        //подключение вида
        require_once(ROOT.'/views/admin_order/delete.php');
        return true;
    }
}
