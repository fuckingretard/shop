<?php

/**
 * Description of AdminBase
 *
 * @author grabc
 */
abstract class AdminBase {
    /*
     * Проверка является ли пользователь администратором
     */

    public static function checkAdmin() {
        //Проверка авторизован ли пользователь. Если нет - переадрессация
        $userId = User::checkLogged();
        //Получаем информацию о текущем пользователе
        $user = User::getUserById($userId);
        //Если параметр роли соответствует администратору то все ок
        if($user['role']=='admin'){
            return true;
        }
        //Иначе завершение работы с выдачей ошибки
        die('Access denied');
    }

}
