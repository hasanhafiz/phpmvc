<?php 
require ROOT_PATH . "/views/shared/header.php";
?>
<h1>Edit Product</h1>
<p><a href="/products/<?=$product->id;?>/show">Cancel</a></p>

<form action="/products/<?=$product->id?>/update" method="post">
    <label for="name"></label>
    <input type="text" name="name" id="name" value="<?=$product->name;?>">
        <?php if (isset( $errors['name'] )): ?> 
        <p><i><?=$errors['name']?></i> </p>
        <?php endif; ?>
    
    <label for="description"></label>
    <textarea name="description" id="description"><?=$product->description;?></textarea>
    
    <button>update</button>
    
</form>


</body>
</html>