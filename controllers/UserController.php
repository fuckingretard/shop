<?php

class UserController {
    public function actionRegister()
    {
        $name='';
        $email='';
        $password='';
        $result=false;
        if(isset($_POST['submit'])){
            $name=$_POST['name'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            
            $errors=false;
            
            //Валидация данных
            if(!User::checkName($name)){
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if(!User::checkEmail($email)){
                $errors[] = 'Неправильный email';
            }
            if(!User::checkPassword($password)){
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            if(User::checkEmailExists($email)){
                $errors[] = 'Такой email уже существует';
            }
            
            if($errors == false){
                $result = User::register($name, $email, $password);
            }
            
        }
        require_once (ROOT. '/views/user/register.php');
        
        return true;
    }
    public function actionLogin()
    {
        $email='';
        $password='';
        
        if(isset($_POST['submit'])){
            $email=$_POST['email'];
            $password=$_POST['password'];
            
            $errors=false;
            //Валидация данных
            if(!User::checkEmail($email)){
                $errors[] = 'Неправильный email';
            }
            if(!User::checkPassword($password)){
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            //Проверка существует ли пользователь
            $userId = User::checkUserData($email,$password);
            
            if($userId == false){
                //Если данные неправильные показываем ощибку
                $errors[]="Неправильные данные для входа на сайт";
            }
            else{
                //Если данные правильные, то запоминаем пользователя в сессию
                User::auth($userId);
                
                //Перенаправляем пользователя в личный кабинет
                header("Location: /cabinet/");
            }
        }
        
        require_once (ROOT. '/views/user/login.php');
        
        return true;
    }
    
    public function actionLogout()
    {
        session_start();
        unset($_SESSION['user']);
        header("Location: /");
    }
}
