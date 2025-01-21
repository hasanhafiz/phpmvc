<?php 
require ROOT_PATH . "/views/shared/header.php";
?>
<a href="/products/new">Add New Product</a>
<h1>Products</h1>    
    <p>Total Records: <?=$total?></p>
    <?php 
        foreach ($products as $key => $product) {
            ?>
            <h4><strong><a href="/products/<?=$product['id']?>/show"><?= $product['name']; ?></a></strong></h4>
            <?php 
        }
    ?>
</body>
</html>