<?php


/**
 * Контроллер AdminProductController
 * Управление товарами в панели администратора
 * 
 */
class AdminProductController extends AdminBase {
    /**
     *  Action для страницы "Управление товарами"
     */
    public function actionIndex()
    {
        //Проверка доступа
        self::checkAdmin();
        
        //Получаем список товаров
        $productsList = Product::getProductsList();

        //Подключение отображения
        require_once(ROOT.'/views/admin_product/index.php');
        return true;
    }
    
    /**
     * Action для страницы "Добавить товар"
     * @return boolean
     */
    public function actionCreate(){
        //Проверка доступа
        self::checkAdmin();
        
        //Получение списка всех категорий для выпадающего списка
        $categoryList = Category::getCategoryListAdmin();
        
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена получение данных формы
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            
            //флаг ошибок в форме
            $errors = false;
            
            //При необходимости можно проводить более полную валидацию
            if(!isset($options['name'])||empty($options['name'])){
                $errors = 'заполните поля'; 
            }
            
            if($errors == false){
                //Если нет ошибок добавление товара
                $id = Product::createProduct($options);
                
                //Если запись добавлена
                if($id){
                    //Проверка, загружалось ли через форму изображение
                    if(is_uploaded_file($_FILES['image']['tmp_name'])){
                        //Если загружалось, то переместить его в нужное место, с определенным именем
                        move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/products/images/{$id}.jpg");
                    }
                }
                
                //Перенаправление пользователя на страницу управления продуктами
                header('Location:/admin/product');
            }
        }
        //Подключение вида
        require_once(ROOT . '/views/admin_product/create.php');
        return true;
    }

    /**
     * Action для страницы "Редактировать товар"
     * @return boolean
     */
    public function actionUpdate($id){
        //Проверка доступа
        self::checkAdmin();
        
        //Получение списка всех категорий для выпадающего списка
        $categoryList = Category::getCategoryListAdmin();
        
        //Получаем данные о конкретном товаре
        $product = Product::getProductById($id);
        
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена получение данных формы
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            
            //сохранение изменения
            if(Product::updateProductById($id,$options)){
                               
                //Если запись сохранена
                //Проверка, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    //Если загружалось, то переместить его в нужное место, с определенным именем
                    move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }

                //Перенаправление пользователя на страницу управления продуктами
                header('Location:/admin/product');
            }
        }
        //Подключение вида
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }
    
    /**
     * Удаление товара с указанным идентификатором
     * @param integer $id
     * @return boolean
     */
    public function actionDelete($id)
    {
        //Проверка доступа
        self::checkAdmin();
        
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена
            //Удаляеем товар
            Product::deleteProductById($id);
            
            //перенаправление на предыдущую страницу
            header("Location: /admin/product");
        }
        
        //подключение вида
        require_once(ROOT.'/views/admin_product/delete.php');
        return true;
    }
}
