<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <a href="/products/new">Add New Product</a>
    <h1>Products</h1>    
        <p>Total Records: {{ total }}</p>
        {% foreach ($products as $product):  %}             
                <h2><a href="/products/{{ product['id'] }}/show">{{ product['name'] }}</a></h2>
        {% endforeach; %}
</body>
</html>