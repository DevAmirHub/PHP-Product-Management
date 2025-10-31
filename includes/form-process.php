<?php
/**
 * Form Process Handler
 * Handles product creation and update operations
 */

$save_product_error = '';
$save_product_success = '';

if( isset($_POST['save_product']) ){

    // Sanitize and validate input data
    $product_id  = intval( $_POST['product_id'] );
    $title       = mysqli_real_escape_string(db(), trim( $_POST['title'] ));
    $description = mysqli_real_escape_string(db(), trim( $_POST['description'] ));
    $thumbnail   =  '';
    $price       = isset($_POST['price']) ? abs( intval($_POST['price']) ) : 0;
    $sale_price  = !empty($_POST['sale_price']) ? abs( intval($_POST['sale_price']) ) : 0;
    $stock       = isset($_POST['stock']) ? abs( intval($_POST['stock']) ) : 0;
    $status      = isset($_POST['status']) ? mysqli_real_escape_string(db(), $_POST['status']) : 'draft';
    $created_at  = date('Y-m-d H:i:s');
    $updated_at  = date('Y-m-d H:i:s');
    $thumbnail   = isset( $_POST['thumbnail'] ) ? mysqli_real_escape_string(db(), $_POST['thumbnail']) : '' ;

    if( in_array( $status, ['presale','publish'] ) && $price <= 0 ){
        $save_product_error = 'تواند صفر باشد  ';
    }

    if( $sale_price > $price ){
        $save_product_error = 'قیمت فروش نمیتواند بیشتر از قیمت محصول باشد ';
    }

    if( mb_strlen( $title ) < 5 ){
        $save_product_error = 'عنوان نباید کمتر از 5 کاراکتر باشد ';
    }

    if( mb_strlen( $description ) < 5 ){
        $save_product_error = 'توضیحات نباید کمتر از 5 کاراکتر باشد ';
    }

    // Debug: Uncomment to see uploaded files
    // print_r($_FILES);exit;
    
    // Handle file upload
    if( isset( $_FILES['thumbnail'] ) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK ){

        $upload_dir = 'uploads/' . date('Y/m/');
        if( ! file_exists( $upload_dir ) ){
            mkdir( directory:  $upload_dir, recursive: true );
        }

        $file_info = pathinfo( $_FILES['thumbnail']['name'] );
        $file_name = 'thumbnail_' . rand( 1000000, 10000000 ) . '.' . $file_info['extension'];
        $file_path = $upload_dir . $file_name;
        $moved = move_uploaded_file( $_FILES['thumbnail']['tmp_name'], $file_path );

        if( $moved ){
            $thumbnail = $file_path;
        }else{
            $product_id = mysql_insert_id( db() );
            header('Location: product-edit.php?id=' . $product_id);
            exit;
        }
    }

    // Build SQL query based on operation type (update or insert)
    if( $product_id ){
        // Update existing product
    $query =    "UPDATE products SET 
                    title = '$title', description = '$description', thumbnail = '$thumbnail',
                    price = $price, sale_price = $sale_price, stock = $stock, updated_at = '$updated_at', status = '$status'
                WHERE ID = {$product_id}";
    }else{
        // Insert new product
        $query = "
        INSERT INTO products 
        (title, description, thumbnail, price, sale_price, stock, status, created_at, updated_at)
        VALUES
        (
          '$title','$description','$thumbnail',$price,$sale_price,$stock,'$status','$created_at','$updated_at'
        )
     ";
    }


    if( ! $save_product_error ){
        $saved = @mysqli_query(db(), $query);
        if( $saved ){
            if( $product_id ){
                $save_product_success = 'محصول با موفقعیت بروز شد';
            }else{
                $product_id = mysqli_insert_id( db() );
                header('Location: product-edit.php?id=' . $product_id);
                exit;
            }
        }else{
            if( $product_id ){
                $save_product_error ='خطا در بروزرسانی اطلاعات ';
            }else{
                $save_product_error ='خطا در ثبت اطلاعات ';
            }
            $error = mysqli_error(db());
            file_put_contents( 'error.txt' ,  'error at ' . ' => ' . __FILE__ . ' #Line:  ' .  __LINE__ . ' ' . jdate('Y-m-d h:i:s ')  . ' => ' . $error  . "\n", FILE_APPEND );
        }
    }

    // Debug: Uncomment to see result
    // var_dump($result);exit;
}

/**
 * Delete Product Handler
 * Handles product deletion operations
 */
$deleted_error = '';
$deleted_success = '';

if( isset( $_GET['action'] ) && $_GET['action'] == 'delete'&& $_GET['product_id']  ){
    $product_id = intval( $_GET['product_id'] );
    $query =    "DELETE FROM products WHERE ID = {$product_id}";
    $result = @mysqli_query(db(), $query);
    if( $result ){
        $deleted = mysqli_affected_rows( db() );
        if( $deleted ){
            $deleted_success = "deleted {$product_id} success !";
        }else{
            $deleted_error = "deleted {$product_id} error !";
        }
    }
    // Debug: Check if there are any errors
    // if( ! $save_product_error ){}
}