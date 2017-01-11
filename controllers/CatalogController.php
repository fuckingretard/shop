<?php


class CatalogController{
    
    public function actionIndex()
    {
        $categories=array();
        $categories=  Category::getCategoryList();
        
        $latestProducts=array();
        $latestProducts= Product::getLatestProducts(6);
        
        require_once(ROOT. '/views/catalog/index.php');
        
        return true;
    }
    
    public function actionCategory($categoryId,$page=1){
        
        $categories = array();
        $categories = Category::getCategoryList();
        
        $categoryProducts = array();
        $categoryProducts = Product::getProductListByCategory($categoryId,$page);
        
        $total = Product::getTotalProductsInCategory($categoryId);
        
        //создаем объект Pagination - постраничная навигация
        $pagination= new Pagination($total,$page,  Product::SHOW_BY_DAEFAULT,'page-');
        
        require_once(ROOT. '/views/catalog/category.php');
        
        return true;
    }
}
