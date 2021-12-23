<?php
   $cnx = new mysqli('localhost', 'aa2789', 'password123', 'ShopFinal');

    if ($cnx->connect_error)
            die('Connection failed: ' . $cnx->connect_error);
?>
<?php 
    $product_id=$_GET["id"];
    $query="SELECT * from productDB where product_id = $product_id";
    $result=$cnx->query($query);
    $row=$result->fetch_assoc();
    $markedUpPrice= number_format(round(1.1*$row["product_price"],2),2);
    $query="INSERT INTO purchaseDB (product_id,price) VALUES ($product_id,$markedUpPrice)";

?>
<body style="background-color:lightblue;" >
<h1 style="text-align:center"> Order Confirmation Page </h1>
<?php if($cnx->query($query)===TRUE): ?>
<h2> <?php echo $row["product_name"]  ?> <span style="color:red";>Successfully Purchased!</span> </h2>
<?php else: ?> 
<h2> Failed to purchase product. </h2>
<?php endif; ?>

 
<h3 style="text-align:center"> <a href="home.php" >Continue Shopping</a> </h3>
</body>
<?php $cnx->close(); ?>
