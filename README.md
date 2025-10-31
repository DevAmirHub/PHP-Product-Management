# PHP Product Management System

A simple and complete PHP and MySQL product management system with full CRUD (Create, Read, Update, Delete) capabilities.

This project is perfect for learning basic web programming concepts, working with MySQL databases, and implementing CRUD operations.

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL Version](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![License](https://img.shields.io/badge/License-MIT-green)

## ✨ Features

- 📦 Complete product management (Add, Edit, Delete)
- 🔍 Search and filter products
- 📊 Sort products by different columns
- 📄 Pagination
- 🖼️ Product thumbnail upload
- 💰 Price and discount management
- 📦 Stock inventory management
- 🎨 Modern UI with dark mode
- 🔒 High security with SQL Injection and XSS protection
- 📱 Responsive design

## 🛠️ Technologies Used

- **PHP** - Server-side programming language
- **MySQL** - Database
- **HTML/CSS** - Structure and styling
- **JavaScript/jQuery** - Client-side interactions
- **JDF (Jalali Date Functions)** - Persian calendar functions

## 📋 Prerequisites

Before installation and setup, make sure you have the following:

### Required Software:
- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher (or MariaDB 10.2+)
- **Apache** or **Nginx** web server
- **phpMyAdmin** (optional, for database management)

### Required PHP Modules:
- `mysqli` - For MySQL database connection
- `mbstring` - For working with multibyte strings (Persian text support)
- `gd` or `imagick` - For image processing (optional)

### How to Check Installed Modules:

```bash
# In command line
php -m

# Or create phpinfo.php file
<?php phpinfo(); ?>
```

If any module is not installed, you can use the following commands:

**Ubuntu/Debian:**
```bash
sudo apt-get install php-mysqli php-mbstring
```

**Windows (XAMPP/WAMP):**
Usually all modules are pre-installed.

## 🚀 Installation & Setup

### Method 1: Using Git (Recommended)

#### Step 1: Clone the Project

```bash
# Clone the project from GitHub
git clone https://github.com/DevAmirHub/php-product-management.git

# Navigate to project directory
cd php-product-management
```

#### Step 2: Copy Configuration File

```bash
# Windows
copy includes\config.example.php includes\config.php

# Linux/Mac
cp includes/config.example.php includes/config.php
```

#### Step 3: Database Configuration

Open the `includes/config.php` file with an editor and enter your database information:

```php
<?php
define( 'DB_NAME', 'digishop' );        // Database name
define( 'DB_HOST', 'localhost' );       // Host address (usually localhost)
define( 'DB_USER', 'root' );            // Database username
define( 'DB_PASSWORD', '' );            // Database password
```

#### Step 4: Create Database

```bash
# Access MySQL
mysql -u root -p

# In MySQL
CREATE DATABASE digishop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digishop;
```

#### Step 5: Create products Table

Execute the following SQL code in MySQL:

```sql
CREATE TABLE IF NOT EXISTS `products` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `price` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `sale_price` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `status` enum('publish','draft','presale','expire') NOT NULL DEFAULT 'draft',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Or you can execute the SQL code in **phpMyAdmin** or any other tool.

#### Using phpMyAdmin:

1. Access phpMyAdmin (usually `http://localhost/phpmyadmin`)
2. Select the `digishop` database
3. Go to the **SQL** tab
4. Copy and paste the SQL code above
5. Click the **Go** button

#### products Table Structure:

| Column | Data Type | Description |
|--------|-----------|-------------|
| `ID` | BIGINT UNSIGNED | Unique product identifier (Auto Increment) |
| `title` | VARCHAR(255) | Product title |
| `description` | TEXT | Full product description |
| `thumbnail` | VARCHAR(500) | Product thumbnail image path |
| `price` | BIGINT UNSIGNED | Original product price (in Toman) |
| `sale_price` | BIGINT UNSIGNED | Sale price (in Toman) |
| `stock` | INT | Inventory stock |
| `status` | ENUM | Product status: `publish`, `draft`, `presale`, `expire` |
| `created_at` | DATETIME | Creation date and time |
| `updated_at` | DATETIME | Last update date and time |

#### Step 6: Set uploads Folder Permissions

```bash
# Linux/Mac
chmod 755 uploads/
chown www-data:www-data uploads/

# Windows (usually automatic)
# Just make sure the uploads/ folder exists
```

### Method 2: Download ZIP File

1. From the GitHub page, click the green **Code** button
2. Select **Download ZIP**
3. Extract the ZIP file
4. Follow steps 2 to 6 above

### Installation Test

1. Place the project in your web server directory (XAMPP, WAMP, Laragon, etc.)
2. Open your browser and navigate to `http://localhost/php-product-management/`
3. If the product list page is displayed, installation was successful! 🎉

---

**Note**: Replace the project name in the paths above with your actual repository name on GitHub.

## 📁 Project Structure

```
php-product-management/
├── css/
│   └── style.css              # Project styles (includes dark/light theme)
├── images/
│   ├── error-filled.svg       # Error icon
│   └── success-filled.svg      # Success icon
├── includes/                   # PHP include files
│   ├── config.php              # Database configuration (needs to be created)
│   ├── config.example.php      # Configuration file template
│   ├── database.php            # MySQL database connection
│   ├── form-process.php        # Form processing (create/edit/delete)
│   ├── functions.php           # Helper functions (discount calculation, sorting, etc.)
│   ├── functions-database.php  # Database connection access function
│   └── jdf.php                 # Jalali (Persian) date functions
├── js/
│   ├── jquery-3.7.1.min.js    # jQuery library
│   └── script.js               # Project JavaScript scripts
├── partial/
│   └── product-row.php         # Template for displaying each product row in table
├── uploads/                     # Image upload folder (created automatically)
│   └── YYYY/MM/                 # Folder structure based on date
├── header.php                   # Shared HTML header (CSS loading, etc.)
├── footer.php                   # Shared HTML footer (JavaScript loading)
├── init.php                     # Initialization file (loads all required files)
├── index.php                    # Main page (product list with filters and search)
├── product-edit.php             # Add/Edit product page
├── error-404.php                # Error page (if database connection fails)
├── .gitignore                   # Files ignored by Git
└── README.md                    # Project documentation (this file)
```

### Important Files Description:

#### `index.php`
Main project page that displays the product list. Includes:
- Display products in a table
- Filter by status, price
- Search in title and description
- Sort by columns
- Pagination (20 products per page)

#### `product-edit.php`
Add and edit product form page. This page is used for both adding new products and editing existing ones.

#### `includes/form-process.php`
This file contains all form processing logic:
- Input validation
- Image upload
- Database storage
- Error handling

#### `includes/functions.php`
Contains helper functions:
- `get_discount_price()` - Calculate discount percentage
- `get_sort_url()` - Generate URL for sorting
- `get_pagination_page_url()` - Generate URL for pagination
- `get_product()` - Get single product information

## 🎨 Dark Mode

The project includes **automatic dark mode** that intelligently activates based on the user's operating system settings.

### How It Works:

- If the user's system is in **Dark Mode**, dark theme automatically activates
- If the user's system is in **Light Mode**, light theme is displayed
- This is done using CSS Media Query `@media (prefers-color-scheme: dark)`

### Dark Mode Features:

✅ **Dark background** to reduce eye strain  
✅ **Light text** for better readability  
✅ **Proper color scheme** for all elements  
✅ **Smooth transitions** when switching themes  
✅ **Full compatibility** with all website sections  

### Testing Dark Mode:

**Windows:**
- Settings → Personalization → Colors → Dark mode

**Mac:**
- System Preferences → General → Appearance → Dark

**Linux:**
- Varies by distribution (usually in system settings)

## 🔒 Security

This project uses various methods to maintain security:

### 1. Protection Against SQL Injection

```php
// Using mysqli_real_escape_string()
$title = mysqli_real_escape_string(db(), trim($_POST['title']));

// Numeric validation
$product_id = intval($_GET['id']);

// Whitelist for sort columns
$allowed_orderby = ['title', 'price', 'discount', 'stock', 'created_at', 'status'];
if (!in_array($orderby, $allowed_orderby)) {
    $orderby = 'created_at';
}
```

### 2. Protection Against XSS (Cross-Site Scripting)

```php
// Using htmlspecialchars() for output
echo htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8');
```

### 3. Input Validation

- String length check (minimum 5 characters)
- Data type check (numeric for price)
- Logical data validation (sale price less than original price)

### 4. Error Handling

- Database errors are logged in `db-error.txt` file
- General errors are logged in `error.txt` file
- Errors are displayed to users only when necessary

### Additional Security Notes:

⚠️ **Warning**: This project is for educational and learning purposes. For use in a Production environment, it's better to:
- Use Prepared Statements (PDO or mysqli_prepare)
- Add authentication system
- Add CSRF Protection
- Add Rate Limiting to prevent Brute Force attacks

## 📝 Usage Guide

### Adding a New Product

1. On the main page, click the **"+ ثبت محصول جدید"** button
2. Enter product information:
   - **Product Name**: Minimum 5 characters
   - **Description**: Minimum 5 characters
   - **Thumbnail**: JPG or PNG format (optional)
   - **Price**: Original product price
   - **Sale Price**: Final price (must be less than original price)
   - **Stock**: Inventory quantity
   - **Status**: Select product status
3. Click the **"ثبت محصول"** button

### Editing a Product

1. On the main page, click the **Edit icon** (green icon) next to the desired product
2. Edit the information
3. Click the **"ویرایش محصول"** button

### Deleting a Product

1. On the main page, click the **Delete icon** (red icon) next to the desired product
2. In the confirmation message, select **OK**
3. The product will be deleted

### Search and Filter

- **Search**: Use the search field to find products by name or description
- **Status Filter**: Use the status dropdown to display products with specific status:
  - All
  - Published
  - Expired
  - Draft
  - Presale
- **Price Filter**: Use "From" and "To" fields to limit price range

### Sorting

- Click on any column header to sort by that column
- Click again to change sort direction

### Pagination

- At the bottom of the page, you can navigate between different pages
- Each page displays a maximum of 20 products

## 🤝 Contributing

Your contributions are welcome! Please:

1. Fork the project
2. Create a new branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 👤 Author

- **DevAmir** - [@DevAmirHub](https://github.com/DevAmirHub)

## 🙏 Acknowledgments

- Thanks to the JDF library for Persian calendar functions
- YekanBakh font is used

## 🐛 Troubleshooting

### Issue: Database Connection Error

**Cause:** Database information in `config.php` is incorrect or the database doesn't exist.

**Solution:**
1. Check the `includes/config.php` file
2. Make sure the database is created
3. Verify username and password
4. Check the `db-error.txt` file for error details

### Issue: Image Upload Not Working

**Cause:** The `uploads/` folder is not writable.

**Solution:**
```bash
# Linux/Mac
chmod 755 uploads/
chown www-data:www-data uploads/

# Or
sudo chmod -R 777 uploads/
```

**Windows:**
- Right-click on the `uploads/` folder
- Properties → Security
- Give Write permission to `Everyone` or `IIS_IUSRS` user

### Issue: Persian Date Not Displaying

**Cause:** The `jdf.php` file is not loaded or has an error.

**Solution:**
- Check that the `includes/jdf.php` file exists
- Check PHP errors in the `error.txt` file

### Issue: CSS or JavaScript Not Loading

**Cause:** File paths are incorrect or web server is not properly configured.

**Solution:**
- Check that the project is in the `htdocs` or `www` folder
- Check file paths in `header.php` and `footer.php`
- Clear browser cache (Ctrl+F5)

### Issue: White Page Displayed

**Cause:** PHP error has occurred.

**Solution:**
1. Check the `error.txt` file
2. Enable PHP errors in `php.ini`:
```ini
display_errors = On
error_reporting = E_ALL
```

## 📚 Learning Resources

If you want to improve this project or learn from it:

- [PHP Manual](https://www.php.net/manual/en/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)

## 🔄 Changelog

### Version 1.0
- ✅ Complete CRUD implementation
- ✅ Filter and search
- ✅ Image upload
- ✅ Dark mode
- ✅ Basic security

## 📧 Contact

For any questions or issues:

- 📧 **Create an Issue** at [GitHub Issues](https://github.com/DevAmirHub/php-product-management/issues)
- 💬 **Ask questions** in Discussions
- ⭐ **Star the project** if it was helpful to you

---

**Made with ❤️ by [DevAmir](https://github.com/DevAmirHub)**
