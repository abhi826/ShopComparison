
<?php 
   $cnx = new mysqli('localhost', 'aa2789', 'password123', 'ShopFinal');

    if ($cnx->connect_error)
	    die('Connection failed: ' . $cnx->connect_error);
?> 
<head> 
<h1 style="color:yellow;text-align:center;"> Shop </h1>
</head>
<body style="background-color:lightblue;">
<?php
$sql="SELECT product_id,product_name,image_url FROM productDB";
$result=$cnx->query($sql);
$rows=$result->fetch_all(MYSQLI_ASSOC);

?>
<?php foreach($rows as $row): ?>
      <tr>
      <td> 
      <a href="comparisonPage.php?id=<?php echo $row["product_id"]?>">   <img height="100px" src=<?php echo $row["image_url"]?>> </a>

</td>
      </tr>
      <tr>
      <td align="center" > <a href="comparisonPage.php?id=<?php echo $row["product_id"]?>"> <?php echo $row["product_name"]?></a></td>
      </tr> 

<?php endforeach; ?>
</body>
<?php $cnx->close(); ?>
