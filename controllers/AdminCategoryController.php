<?php

/**
 * Контроллер AdminCategoryController
 * Управление категориями товаров
 */
class AdminCategoryController extends AdminBase {
    
    /*
     * Action для страницы Управление категориями
     */
    public function actionIndex()
    {
        //Проверка админ прав
        self::checkAdmin();
        
        //Получение списка категорий
        $categoryList = Category::getCategoryListAdmin();
        
        //Подключение вида
        require_once(ROOT.'/views/admin_category/index.php');
        return true;
    }
    
    /**
     * Action для страницы "Добавить категорию"
     * @return boolean
     */
    public function actionCreate(){
        //Проверка доступа
        self::checkAdmin();
        
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена получение данных формы
            $name = $_POST['name'];
            $sort_order = $_POST['sort_order'];
            $status = $_POST['status'];
            
            //флаг ошибок в форме
            $errors = false;
            
            //При необходимости можно проводить более полную валидацию
            if(!isset($name)||empty($name)){
                $errors = 'заполните поля'; 
            }
            
            if($errors == false){
                //Если нет ошибок добавление категории
                Category::createCategory($name,$sort_order,$status);
                                
                //Перенаправление пользователя на страницу управления продуктами
                header('Location:/admin/category');
            }
        }
        //Подключение вида
        require_once(ROOT . '/views/admin_category/create.php');
        return true;
    }

    /**
     * Action для страницы "Редактировать категорию"
     * @return boolean
     */
    public function actionUpdate($id){
        //Проверка доступа
        self::checkAdmin();
        
        //Получение списка всех категорий для выпадающего списка
        $category = Category::getCategoryById($id);
        //обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена получение данных формы
            $name = $_POST['name'];
            $sort_order = $_POST['sort_order'];
            $status = $_POST['status'];
            
            //флаг ошибок в форме
            $errors = false;
            
            //При необходимости можно проводить более полную валидацию
            if(!isset($name)||empty($name)){
                $errors = 'заполните поля'; 
            }
            
            if($errors == false){
                //Если нет ошибок добавление категории
                Category::updateCategoryById($id,$name,$sort_order,$status);
                                
                //Перенаправление пользователя на страницу управления продуктами
                header('Location:/admin/category');
            }
        }
        //Подключение вида
        require_once(ROOT . '/views/admin_category/update.php');
        return true;
    }
    
    /**
     * Удаление категории с указанным идентификатором
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
            Category::deleteCategoryById($id);
            
            //перенаправление на предыдущую страницу
            header("Location: /admin/category");
        }
        
        //подключение вида
        require_once(ROOT.'/views/admin_category/delete.php');
        return true;
    }
}
