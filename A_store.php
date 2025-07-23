<?php include('./conn/db_connect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

   <!-- Sidebar -->
   <div class="sidebar">
        <a class="navbar-brand" href="home.php">Admin Dashboard</a>
          <a href="Home.php" ><i class="fas fa-users-cog" ></i> Manage Users</a>
    <a href="A_ticket.php" ><i class="fas fa-ticket-alt"></i> Tickets</a>
    <a href="admin_view.php"><i class="fas fa-calendar-check"></i> Services</a>
    <a href="A_order.php"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="A_store.php" class="active"><i class="fas fa-store"></i> Store</a>
        <a href="A_FAQ.php"><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="A_Dashboard.php" ><i class="fas fa-user"></i> Profile</a>
    <a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h4>Store Products</h4>

            <!-- Success and Error Messages -->
            <?php
                if (isset($_GET['success']) && $_GET['success'] == '1') {
                    echo "<div class='alert success'>Product updated successfully!</div>";
                } elseif (isset($_GET['error']) && $_GET['error'] == '1') {
                    echo "<div class='alert error'>Error updating product. Please try again!</div>";
                }
            ?>

            <!-- Search Bar and Add Product Button -->
            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by product name, category, or price..." onkeyup="searchProducts()">
                <button class="btn" onclick="openAddProductModal()">Add Product</button>
            </div>

            <!-- Product Table -->
            <table id="productTable">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Query to get product details from the 'products' table
                        $stmt = $conn->prepare("SELECT * FROM `products`");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Fetch all rows as associative arrays
                        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        if (empty($products)) {
                            echo "<tr><td colspan='6'>No products found</td></tr>";
                        } else {
                            foreach ($products as $row) {
                                $productID = $row['id'];
                                $name = htmlspecialchars($row['name']);
                                $category = htmlspecialchars($row['category']);
                                $price = htmlspecialchars($row['price']);
                                $image = htmlspecialchars($row['image']);
                    ?>
                    <tr data-search="<?= strtolower($name . ' ' . $category . ' ' . $price) ?>">
                        <td id="productID-<?= $productID ?>"><?php echo $productID ?></td>
                        <td id="name-<?= $productID ?>"><?php echo $name ?></td>
                        <td id="category-<?= $productID ?>"><?php echo $category ?></td>
                        <td id="price-<?= $productID ?>"><?php echo $price ?></td>
                        <td id="image-<?= $productID ?>">
                            <img src="<?= $image ?>" alt="<?= $name ?>" style="width: 50px; height: 50px;">
                        </td>
                        <td>
                            <button onclick="updateProduct(<?php echo $productID ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProduct(<?php echo $productID ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Update Product Modal -->
    <div class="modal" id="updateProductModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="close" onclick="closeUpdateProductModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/edit_product.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" id="updateProductID">
                    <input type="text" class="form-control" id="updateName" name="name" placeholder="Product Name">
                    <select class="form-control" id="updateCategory" name="category">
                        <option value="GPU">GPU</option>
                        <option value="CPU">CPU</option>
                        <option value="RAM">RAM</option>
                        <option value="Storage">Storage</option>
                    </select>
                    <input type="number" class="form-control" id="updatePrice" name="price" placeholder="Price">
                    <input type="file" class="form-control" id="updateImage" name="image" accept="image/*">
                    <button type="submit" class="btn">Update</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal" id="addProductModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="close" onclick="closeAddProductModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/add_product.php" method="POST" enctype="multipart/form-data">
                    <input type="text" class="form-control" id="addName" name="name" placeholder="Product Name" required>
                    <select class="form-control" id="addCategory" name="category" required>
                        <option value="GPU">GPU</option>
                        <option value="CPU">CPU</option>
                        <option value="RAM">RAM</option>
                        <option value="Storage">Storage</option>
                    </select>
                    <input type="number" class="form-control" id="addPrice" name="price" placeholder="Price" required>
                    <input type="file" class="form-control" id="addImage" name="image" accept="image/*" required>
                    <button type="submit" class="btn">Add Product</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Open Add Product Modal
        function openAddProductModal() {
            document.getElementById('addProductModal').classList.add('show');
        }

        // Close Add Product Modal
        function closeAddProductModal() {
            document.getElementById('addProductModal').classList.remove('show');
        }

        // Open Update Product Modal
        function updateProduct(id) {
            document.getElementById('updateProductModal').classList.add('show');
            document.getElementById('updateProductID').value = document.getElementById('productID-' + id).innerText;
            document.getElementById('updateName').value = document.getElementById('name-' + id).innerText;
            document.getElementById('updateCategory').value = document.getElementById('category-' + id).innerText;
            document.getElementById('updatePrice').value = document.getElementById('price-' + id).innerText;
        }

        // Close Update Product Modal
        function closeUpdateProductModal() {
            document.getElementById('updateProductModal').classList.remove('show');
        }

        // Delete Product Confirmation
        function deleteProduct(id) {
    if (confirm("Do you want to delete this product?")) {
        window.location = "./endpoint/delete_product.php?id=" + id;
    }
}

        // Search Functionality
        function searchProducts() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var table = document.getElementById('productTable');
            var rows = table.getElementsByTagName('tr');
            
            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var text = row.getAttribute('data-search');
                if (text.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>