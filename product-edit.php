<?php include('header.php'); ?>
<?php 
/**
 * Product Edit/Create Page
 * Handles both product creation and editing
 */

// Get product ID from URL parameter
$product_id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
$is_edit_page   = $product_id > 0;

// Initialize product variables
$title      = '';
$description= '';
$price      = '';
$sale_price = '';
$stock      = '';
$status     = '';
$thumbnail  = '';

// Load product data if editing
if( $product_id ){
    $products       = get_product( $product_id );
    
    // Debug: Uncomment to see product data
    // print_r($products);exit;
    
    $title          = $products['title'];
    $description    = $products['description'];
    $price          = $products['price'];
    $sale_price     = $products['sale_price'];
    $stock          = $products['stock'];
    $status         = $products['status'];
    $thumbnail      = $products['thumbnail'];

    // Debug: Uncomment to see status
    // print_r($status);exit;
}


?>
    <div class="box-container">
        <header>
            <div class="title">
                <h1>
                    <?php if( $is_edit_page ): ?>
                        ویرایش محصول
                    <?php else: ?>
                        ثبت محصول
                    <?php endif; ?>
                </h1>
                <p>از این بخش میتوانید محصولات فعلی را ویرایش یا محصول جدید ثبت کنید</p>
            </div>
        </header>
        <?php if( $save_product_error ): ?>
        <div class="message error">
            <?php echo $save_product_error; ?>
        </div>
        <?php endif; ?>
        <?php if( $save_product_success  ): ?>
        <div class="message success">
            <?php echo $save_product_success; ?>
        </div>
        <?php endif; ?>
        <form action="" id="product-register" method="post" enctype="multipart/form-data">
            <div class="form-right">
                <div class="form-group">
                    <label for="title">نام محصول</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="نام محصول" value="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">توضیحات</label>
                    <textarea rows="15" name="description" id="description" class="form-control" placeholder="توضیحات محصول"  required><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
            <div class="form-side">
                <div class="form-group">
                    <label for="thumbnail">تصویر شاخص محصول</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png" >
                </div>
                <img src="<?php echo $thumbnail ?>"
                     alt="" class="thumbnail-preview">
                <input type="hidden" name="thumbnail" value="<?php echo $thumbnail ?>">
                <div class="form-group">
                    <label for="price">قیمت</label>
                    <input type="number" name="price" id="price" class="form-control" value="<?php echo $price ?>" step="1" required>
                </div>
                <div class="form-group">
                    <label for="sale_price">قیمت فروش</label>
                    <input type="number" name="sale_price" id="sale_price" class="form-control" min="0" step="1" value="<?php echo $sale_price ?>" required>
                </div>
                <div class="form-group">
                    <label for="stock">موجودی انبار</label>
                    <input type="number" name="stock" id="stock" class="form-control" min="0" value="<?php echo $stock ?>" step="1" required>
                </div>
                <div class="form-group">
                    <label for="status">وضعیت</label>
                    <select name="status" id="status" class="form-control">
                        <option value="publish" <?php echo $status == 'publish' ? 'selected' : '' ?>>انتشار و فروش</option>
                        <option value="draft" <?php echo $status == 'draft' ? 'selected' : '' ?>>پیش نویس</option>
                        <option value="presale" <?php echo $status == 'presale' ? 'selected' : '' ?>>پیشفروش</option>
                        <option value="expire" <?php echo $status == 'expire' ? 'selected' : '' ?>>توقف فروش</option>
                    </select>
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button class="btn btn-primary w-100" name="save_product">
                    <?php if( $is_edit_page ): ?>
                        ویرایش محصول
                    <?php else: ?>
                        ثبت محصول
                    <?php endif; ?>
                </button>
            </div>
        </form>
    </div>
<?php include('footer.php'); ?>