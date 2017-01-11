<?php

class User
{
    public static function register($name, $email, $password){
        
        $db = Db::getConnection();
        
        $sql = 'INSERT INTO user (name, email, password) '
                . 'VALUES (:name, :email, :password)';
        
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }
    /**
     * 
     * Checking Name length >=2
     * @return boolean
     */
    public static function checkName($name){
        if(strlen($name)>=2){
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 
     * Checking password length >=6
     * @return boolean
     */
    public static function checkPassword($password){
        if(strlen($password)>=6){
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 
     * Checking email by standart mask
     * @return boolean
     */
    public static function checkEmail($email){
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            return TRUE;
        }
        return FALSE;
    }
    
    public static function checkEmailExists($email){
        
        $db = Db::getConnection();
        
        $sql='SELECT COUNT(*) FROM user WHERE email = :email';
        
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        
        if($result->fetchColumn())
            return true;
        return false;
    }
    /*
     * Проверка введеного пароля и email на наличие в БД
     */
    public static function checkUserData($email,$password){
        $db = Db::getConnection();
        
        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';
        $result= $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_INT);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();
        
        $user = $result->fetch();
        if($user) {
            return $user['id'];
        }
        return false;
    }
    /*
     * добавление в сессию индетификатора пользователя из БД
     */
    public static function auth($userId)
    {
        
        $_SESSION['user']=$userId;
    }
    /**
     * Функция, которая проверяет залогинен ли пользователь
     */
    public static function checkLogged(){
        
        //Если сессия есть вернем идентификатор пользователя
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }
        
        header("Location: /user/login");
    }
    /**
     * Функция, проверки гость ли пользователь
     */
    public static function isGuest()
    {
        
        if(isset($_SESSION['user'])){
            return false;
        }
        return true;
    }
    
    /**
     * Returns user by id
     * @param integer $id 
     */
    public static function getUserById($id)
    {
        if($id){
            $db = Db::getConnection();
            $sql="SELECT * FROM user WHERE id = :id";
            
            $result = $db->prepare($sql);
            $result->bindParam(":id", $id,PDO::PARAM_INT);
            
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
            
            return $result->fetch();
        }
    }
    
    /**
     * Функция для сохранения изменений данных в БД
     */
    public static function edit($userId, $name, $password)
    {
        $db =  Db::getConnection();
        
        $sql = "UPDATE user "
                . "SET name = :name, password = :password "
                . "WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        
        return $result->execute();
    }
    
    /**
     * Проверяет телефон: не меньше, чем 10 символов
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }
}