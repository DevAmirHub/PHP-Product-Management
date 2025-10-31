<?php include('header.php'); ?>
<?php

$search     = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';
$status     = isset($_GET['status']) ? mysqli_real_escape_string($db, $_GET['status']) : '';
$price_from = isset($_GET['price_from']) ? intval( $_GET['price_from'] ) : '';
$price_to   = isset($_GET['price_to']) ? intval( $_GET['price_to'] ) : '';
$order      = isset($_GET['order']) ? strtoupper( $_GET['order'] ) : 'DESC';
$orderby    = isset($_GET['orderby']) ? mysqli_real_escape_string($db, $_GET['orderby']) : 'created_at';

// Whitelist for allowed orderby columns to prevent SQL injection
$allowed_orderby = ['title', 'price', 'discount', 'stock', 'created_at', 'status'];
if (!in_array($orderby, $allowed_orderby)) {
    $orderby = 'created_at';
}
if ($order !== 'ASC' && $order !== 'DESC') {
    $order = 'DESC';
}
$page       = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
$per_page   = 20;

$offset     = ( $page - 1 ) * $per_page;

$where = 'WHERE 1 = 1';

if( $search ){
    $where.= " AND CONCAT(title, ' ', description) LIKE '%$search%'";
}

if( $status ){
    $where.= " AND status = '$status'";
}

if( $price_from ){
    $where.= " AND price >= $price_from";
}

if( $price_to ){
    $where.= " AND price <= $price_to";
}




$params = [];
if( $status ){
    $params['status'] = $status;
}
if( $search ){
    $params['search'] = $search;
}
if( $price_from ){
    $params['price_from'] = $price_from;
}
if( $price_to ){
    $params['price_to'] = $price_to;
}





$sql = "SELECT *,( CASE WHEN price > 0 AND price >= sale_price THEN (CAST(price AS SIGNED) - CAST(sale_price AS SIGNED)) / price * 100 ELSE 0 END ) AS discount FROM products $where ORDER BY $orderby $order LIMIT $per_page OFFSET $offset";


$result = @mysqli_query($db, $sql);
if( $result ){
  $count      = $result->num_rows;
  $products    = mysqli_fetch_all($result , MYSQLI_ASSOC);
  
  $sql_count    = "SELECT COUNT(*) AS total FROM products $where";
  $total_result = mysqli_query( db(), $sql_count );
  
  if( $total_result ){
    $row = mysqli_fetch_assoc( $total_result );
    $total_result = (int) $row['total'];
    $total_page   = ceil( $total_result / $per_page );
    // Debug: Uncomment to see total count
    // print_r( $row );exit;
  }

// Debug: Uncomment to see products array
// print_r($products);exit;
}else{
    die( mysqli_error($db) );
}
?>
  <div class="box-container">
      <!-- Debug: Query parameters -->
      <!-- <?php echo http_build_query($params); ?> -->
    <header>
      <div class="title">
        <h1>لیست محصولات</h1>
        <p>از این بخش میتوانید محصولات فعلی را ویرایش یا محصول جدید ثبت کنید</p>
      </div>
      <div class="table-button">
        <a href="product-edit.php" class="btn btn-secondary">
          + ثبت محصول جدید
        </a>
      </div>
    </header>
  <?php if( $deleted_success ):?>
    <div class="message success">
          <?php echo $deleted_success; ?>
    </div>
  <?php endif; ?>
  <?php if( $deleted_error ):?>
      <div class="message error">
          <?php echo $deleted_error; ?>
      </div>
  <?php endif; ?>

    <form class="table-filter" action="">
      <div class="filter">
        <label for="search">جستجو</label>
        <input type="search" id="search" name="search" placeholder="جستجو" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" class="form-control">
      </div>
      <div class="filter">
        <label for="status">وضعیت</label>
        <select name="status" id="status" class="form-control">
          <option value=""> - همه - </option>
          <option value="publish" <?php echo $status == "publish" ? 'selected' : ''; ?>>درحال فروش</option>
          <option value="expire" <?php echo $status == 'expire' ? 'selected' : ''; ?>>توقف فروش</option>
          <option value="draft" <?php echo $status == 'draft' ? 'selected' : ''; ?>>پیش نویس</option>
          <option value="presale" <?php echo $status == 'presale' ? 'selected' : ''; ?>>پیشفروش</option>
        </select>
      </div>
      <div class="filter filter-price">
        <label for="search">قیمت</label>
        <div>
          از
          <input type="search" name="price_from" placeholder="از" value="<?php echo htmlspecialchars($price_from, ENT_QUOTES, 'UTF-8') ?>" class="form-control">
          تا
          <input type="search" name="price_to" placeholder="تا" value="<?php echo htmlspecialchars($price_to, ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>
      </div>
      <div class="filter btn-filter">
        <button class="btn btn-primary ">
          فیلتر کردن
        </button>
      </div>
    </form><!--.table-filter-->
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>
            <a href="<?php echo get_sort_url( 'title' , $params ) ?>" class="<?php echo $orderby == 'title' ? 'order-' . strtolower($order) : ''; ?>" class="<?php echo $orderby == 'title' ? 'order-' . strtolower($order) : ''; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="17.505" height="18.5" viewBox="0 0 17.505 18.5">
                <g id="arrow-swap" transform="translate(-3.252 -2.75)">
                  <path id="Path_15" data-name="Path 15" d="M-943.99-509.75h-.02a.739.739,0,0,1-.511-.219l-5.01-5.01a.755.755,0,0,1,0-1.06.755.755,0,0,1,1.06,0l3.73,3.731V-527.5a.754.754,0,0,1,.75-.75.754.754,0,0,1,.75.75v17a.736.736,0,0,1-.06.292.735.735,0,0,1-.159.238q-.025.023-.052.044a.744.744,0,0,1-.478.175Z" transform="translate(953 531)" fill="#c1c1c1"/>
                  <path id="Path_14" data-name="Path 14" d="M-949.748-510.5v-17a.755.755,0,0,1,.751-.75.735.735,0,0,1,.146.015.742.742,0,0,1,.393.205l5.01,5.01a.754.754,0,0,1,0,1.059.742.742,0,0,1-.53.22.742.742,0,0,1-.53-.22l-3.74-3.739v15.2a.749.749,0,0,1-.75.75A.756.756,0,0,1-949.748-510.5Z" transform="translate(963.988 531)" fill="#c1c1c1"/>
                </g>
              </svg>
              محصول
            </a>
          </th>
          <th>
            <a href="<?php echo get_sort_url( 'price' , $params ) ?>" class="<?php echo $orderby == 'price' ? 'order-' . strtolower($order) : ''; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="17.505" height="18.5" viewBox="0 0 17.505 18.5">
                <g id="arrow-swap" transform="translate(-3.252 -2.75)">
                  <path id="Path_15" data-name="Path 15" d="M-943.99-509.75h-.02a.739.739,0,0,1-.511-.219l-5.01-5.01a.755.755,0,0,1,0-1.06.755.755,0,0,1,1.06,0l3.73,3.731V-527.5a.754.754,0,0,1,.75-.75.754.754,0,0,1,.75.75v17a.736.736,0,0,1-.06.292.735.735,0,0,1-.159.238q-.025.023-.052.044a.744.744,0,0,1-.478.175Z" transform="translate(953 531)" fill="#c1c1c1"/>
                  <path id="Path_14" data-name="Path 14" d="M-949.748-510.5v-17a.755.755,0,0,1,.751-.75.735.735,0,0,1,.146.015.742.742,0,0,1,.393.205l5.01,5.01a.754.754,0,0,1,0,1.059.742.742,0,0,1-.53.22.742.742,0,0,1-.53-.22l-3.74-3.739v15.2a.749.749,0,0,1-.75.75A.756.756,0,0,1-949.748-510.5Z" transform="translate(963.988 531)" fill="#c1c1c1"/>
                </g>
              </svg>
              قیمت
            </a>
          </th>
          <th>
            <a href="<?php echo get_sort_url( 'discount' , $params ) ?>" class="<?php echo $orderby == 'discount' ? 'order-' . strtolower($order) : ''; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="17.505" height="18.5" viewBox="0 0 17.505 18.5">
                <g id="arrow-swap" transform="translate(-3.252 -2.75)">
                  <path id="Path_15" data-name="Path 15" d="M-943.99-509.75h-.02a.739.739,0,0,1-.511-.219l-5.01-5.01a.755.755,0,0,1,0-1.06.755.755,0,0,1,1.06,0l3.73,3.731V-527.5a.754.754,0,0,1,.75-.75.754.754,0,0,1,.75.75v17a.736.736,0,0,1-.06.292.735.735,0,0,1-.159.238q-.025.023-.052.044a.744.744,0,0,1-.478.175Z" transform="translate(953 531)" fill="#c1c1c1"/>
                  <path id="Path_14" data-name="Path 14" d="M-949.748-510.5v-17a.755.755,0,0,1,.751-.75.735.735,0,0,1,.146.015.742.742,0,0,1,.393.205l5.01,5.01a.754.754,0,0,1,0,1.059.742.742,0,0,1-.53.22.742.742,0,0,1-.53-.22l-3.74-3.739v15.2a.749.749,0,0,1-.75.75A.756.756,0,0,1-949.748-510.5Z" transform="translate(963.988 531)" fill="#c1c1c1"/>
                </g>
              </svg>
              تخفیف
            </a>
          </th>
          <th>
              <a href="<?php echo get_sort_url( 'stock' , $params ) ?>" class="<?php echo $orderby == 'stock' ? 'order-' . strtolower($order) : ''; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="17.505" height="18.5" viewBox="0 0 17.505 18.5">
                      <g id="arrow-swap" transform="translate(-3.252 -2.75)">
                          <path id="Path_15" data-name="Path 15" d="M-943.99-509.75h-.02a.739.739,0,0,1-.511-.219l-5.01-5.01a.755.755,0,0,1,0-1.06.755.755,0,0,1,1.06,0l3.73,3.731V-527.5a.754.754,0,0,1,.75-.75.754.754,0,0,1,.75.75v17a.736.736,0,0,1-.06.292.735.735,0,0,1-.159.238q-.025.023-.052.044a.744.744,0,0,1-.478.175Z" transform="translate(953 531)" fill="#c1c1c1"/>
                          <path id="Path_14" data-name="Path 14" d="M-949.748-510.5v-17a.755.755,0,0,1,.751-.75.735.735,0,0,1,.146.015.742.742,0,0,1,.393.205l5.01,5.01a.754.754,0,0,1,0,1.059.742.742,0,0,1-.53.22.742.742,0,0,1-.53-.22l-3.74-3.739v15.2a.749.749,0,0,1-.75.75A.756.756,0,0,1-949.748-510.5Z" transform="translate(963.988 531)" fill="#c1c1c1"/>
                      </g>
                  </svg>
                  موجودی
              </a>
          </th>
          <th style="width: 110px;">وضعیت</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </thead>
      <tbody>
      <?php if( $count ): ?>
    <?php foreach ( $products as $index => $product ): ?>
        <?php include('partial/product-row.php') ?>
    <?php endforeach; ?>
    <?php else: ?>
      <tr>
          <td colspan="8">
                نتیجه ای یافت نشد!
          </td>
      </tr>
    <?php endif;?>

      </tbody>
    </table>

    <div class="table-footer">
      <div class="result">
        کل نتایج: 
        <?php echo number_format( $total_result ); ?>
        | صفحه 
        <?php echo $page ?> 
        از 
        <?php echo number_format( $total_page ); ?>
      </div>
      <div class="pagination">
        <?php if( $page > 1 ): ?>
        <a href="<?php echo get_pagination_page_url( $page - 1 , $params ) ?>" class="prev">
          <svg xmlns="http://www.w3.org/2000/svg" width="8.597" height="17.337" viewBox="0 0 8.597 17.337">
            <path id="arrow-right-3" d="M16.012,20.67a.742.742,0,0,0,.53-.22.754.754,0,0,0,0-1.06l-6.52-6.52a1.231,1.231,0,0,1,0-1.74l6.52-6.52a.75.75,0,0,0-1.06-1.06l-6.52,6.52a2.724,2.724,0,0,0-.8,1.93,2.683,2.683,0,0,0,.8,1.93l6.52,6.52A.786.786,0,0,0,16.012,20.67Z" transform="translate(-8.162 -3.333)" fill="#292d32"/>
          </svg>
        </a>
        <?php endif; ?>
        <?php for( $i = 1; $i <= $total_page; $i++ ): ?>
          <?php if( $page == $i ): ?>
            <span>
              <?php echo $i; ?>
            </span>
            <?php else: ?>
        <a href="<?php echo get_pagination_page_url( $i , $params ) ?>">
          <?php echo $i; ?>
        </a>
        <?php endif; ?>
        <?php endfor; ?>
        <?php if( $page != $total_page ): ?>
        <a href="<?php echo get_pagination_page_url( $i , $params )?>" class="next">
          <svg xmlns="http://www.w3.org/2000/svg" width="8.597" height="17.337" viewBox="0 0 8.597 17.337">
            <path id="arrow-right-3" d="M8.91,20.67a.742.742,0,0,1-.53-.22.754.754,0,0,1,0-1.06l6.52-6.52a1.231,1.231,0,0,0,0-1.74L8.38,4.61A.75.75,0,1,1,9.44,3.55l6.52,6.52a2.724,2.724,0,0,1,.8,1.93,2.683,2.683,0,0,1-.8,1.93L9.44,20.45A.786.786,0,0,1,8.91,20.67Z" transform="translate(-8.162 -3.333)" fill="#292d32"/>
          </svg>
        </a>
        <?php endif;  ?>
      </div><!--.pagination-->
    </div><!--.table-footer-->

  </div><!--.table-container-->
<?php include('footer.php'); ?>