<?php



class SiteController{
    
    /**
     * 
     * Action для главной страницы
     */
    public function actionIndex()
    {
        //список категорий
        $categories=array();
        $categories=  Category::getCategoryList();
        
        //список последних товаров
        $latestProducts=array();
        $latestProducts= Product::getLatestProducts(10);
        
        //список товаров для слайдера
        $sliderProducts=array();
        $sliderProducts= Product::getRecommendedProducts();
        
        require_once(ROOT. '/views/site/index.php');
        
        return true;
    }
    
    /**
     * 
     * Action для страницы "Контакты"
     */
    public function actionContact(){
        //переменные для формы
        $userEmail="";
        $userText="";
        $result=false;
        
        //обработка формы
        if(isset($_POST['submit'])){
            //если форма отправлена получение данных из формы
            $userEmail=$_POST['userEmail'];
            $userText=$_POST['userText'];
            
            //флаг ошибок
            $errors=false;
            
            //validation of input data
            if(!User::checkEmail($userEmail)){
                $errors[] = "Неправильный email";
            }
            
            if($errors==false){
                $adminEmail="grabchuk7@gmail.com";
                $message="Текст:'{$userText}. От {$userEmail}";
                $subject="Тема письма";
                $headers = 'From: serhii@phptest1.zzz.com.ua' . "\r\n" ;
                $result=mail($adminEmail,$subject,$message,$headers);
                $result=true;
            }
        }
        require_once (ROOT.'/views/site/contact.php');
        return true;
    }
    
    /**
     * Action для страницы "О магазине"
     */
    public function actionAbout()
    {
        // Подключаем вид
        require_once(ROOT . '/views/site/about.php');
        return true;
    }
    
    
}
