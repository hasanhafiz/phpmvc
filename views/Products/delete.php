<?php 
require ROOT_PATH . "/views/shared/header.php";
?>
<h1>Delete Product</h1>
<form action="/products/<?=$product->id?>/destroy" method="post">
    <label for="name"></label>
    
    <p>Are you sure to Delete? <i> <?=$product->name;?> </i></p>
    
    <button>Yes</button>
    
</form>

<p><a href="/products/<?=$product->id;?>/show">Cancel</a></p>

</body>
</html>