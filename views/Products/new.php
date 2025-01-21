<?php 
require ROOT_PATH . "/views/shared/header.php";
?>
<h1>New Product</h1>

<form action="/products/create" method="post">
    <label for="name"></label>
    <input type="text" name="name" id="name" >
        <?php if (isset( $errors['name'] )): ?> 
        <p><i><?=$errors['name']?></i> </p>
        <?php endif; ?>
    
    <label for="description"></label>
    <textarea name="description" id="description"></textarea>
    
    <button>Save</button>
    
</form>