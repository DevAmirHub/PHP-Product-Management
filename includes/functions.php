<?php
/**
 * Helper Functions
 * Contains utility functions for the application
 */

/**
 * Calculate discount percentage
 * 
 * @param int $price Original price
 * @param int $sale_price Sale price
 * @return int Discount percentage (0-100)
 */
function get_discount_price( $price , $sale_price )
{
    if( ! $price ){
        return 0;
    }
    $discount = ( $price - $sale_price ) / $price * 100;
    return round( $discount );
}

/**
 * Generate sort URL with parameters
 * 
 * @param string $orderby Column name to sort by
 * @param array $extra_params Additional query parameters
 * @return string Generated URL with sort parameters
 */
function get_sort_url( $orderby , $extra_params )
{
    $order_url = "?orderby=$orderby&order=";
    if( isset( $_GET['order'] ) && $_GET['order'] == "asc" ){
        $order_url .= "desc";
    }else{
        $order_url .= "asc";
    }
    if( ! empty( $extra_params ) ){
        $order_url .= '&' . http_build_query( $extra_params );
    }
    return $order_url;
}

/**
 * Generate pagination URL
 * 
 * @param int $page_number Page number
 * @param array $params Query parameters to preserve
 * @return string Generated pagination URL
 */
function get_pagination_page_url( $page_number , $params )
{
    $url = '?page=' . $page_number;
    if( ! empty( $params ) ){
        $url .= '&' . http_build_query( $params );
    }

    return $url;
}

/**
 * Get single product by ID
 * 
 * @param int $id Product ID
 * @return array|false Product data or false if not found
 */
function get_product( $id )
{
    $id = intval($id);
    $sql     = "SELECT * FROM products WHERE ID = $id";
    $result  = @mysqli_query( db() , $sql );

    if( $result && $result->num_rows ){
        return mysqli_fetch_assoc( $result );
    }else{
        file_put_contents( 'db-error.txt' ,  'error at ' . jdate('Y-m-d h:i:s ')  . ' => ' . $sql  . "\n", FILE_APPEND );
        return false;
    }

}