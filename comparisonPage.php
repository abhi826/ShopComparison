
<?php
   $cnx = new mysqli('localhost', 'aa2789', 'password123', 'ShopFinal');

    if ($cnx->connect_error)
            die('Connection failed: ' . $cnx->connect_error);
?>
<head>
<title> Comparison Page </title>
</head>
<body style="background-color:lightblue;">
<h1 style="text-align:center" > Comparison Page </h1>

<?php    
$id=$_GET["id"];
if($id>25){
$matchid=$id-25;
}
else{
$matchid=$id+25;
}

$sql="SELECT product_name,product_description,product_price,image_url,review_score FROM productDB WHERE product_id=$id";
$result=$cnx->query($sql);
$row=$result->fetch_assoc();
$sql2="SELECT product_name,product_description,product_price,image_url,review_score FROM productDB WHERE product_id=$matchid";
$result2=$cnx->query($sql2);
$row2=$result2->fetch_assoc();
$rowPrice=round($row["product_price"]*1.1,2);
$row2Price=round($row2["product_price"]*1.1,2);
//echo $row["product_name"],"<br>",$row2["product_name"];
if($rowPrice<=$row2Price){
	$low=$row;
	$high=$row2;
	$lowid=$id;
	$highid=$matchid;
}
else{
	$low=$row2;
	$high=$row;
	$lowid=$matchid;
	$highid=$id;
	
}



?>
<?php
$mapColumnToName=["product_name"=>"Product Name","product_description"=>"Description","product_price"=>"Price","image_url"=>"Image","review_score"=>"Review Score"]

?>
   <h3 style="text-align:center;color:red;"> ****Products shown in ascending order of cost**** </h3> 
    <table border=1>
    <?php foreach($low as $col=>$val) :?>
        <tr>
	<td><b><?php echo $mapColumnToName[$col]?></b></td>
         <?php if($col=="image_url"):?>
	      <td> <img src = <?php echo $val?> height="200px"/></td>
         <?php elseif($col=="product_price"):?>
	       <td> <?php 
	       $x=$val*1.1;
               $y=number_format(round($x,2),2);
                echo "$",$y
             ?></td>
         <?php elseif($col=="product_description"):?>
	     <td> 
             <?php
			if(strlen($val)>2000){
			   echo str_replace('\n',"",strip_tags(substr($val,0,2000))),"...";
			
			}
			else{
			  echo str_replace('\n',"",strip_tags($val));
			}
	      
	
	     ?>
             
             </td>

         <?php else: ?>
	<td><?php echo $val ?></td>
        <?php endif; ?>
	</tr>
    <?php endforeach; ?>
    </table>
<?php $locationLow="purchaseItem.php?id=$lowid"; ?>
<button style="height:50px;width:100px;" onclick="location.href='<?php echo $locationLow ?>'"> Purchase Item </button> 
<br>
 <table border=1>
    <?php foreach($high as $col=>$val) :?>
        <tr>
        <td><b><?php echo $mapColumnToName[$col]?></b></td>
         <?php if($col=="image_url"):?>
              <td> <img src = <?php echo $val?> height="200px"/></td>
         <?php elseif($col=="product_price"):?>
               <td> <?php
               $x=$val*1.1;
               $y=number_format(round($x,2),2);
                echo "$",$y
             ?></td>
         <?php elseif($col=="product_description"):?>
             <td>
             <?php
                        if(strlen($val)>2000){
                           echo str_replace('\n',"",strip_tags(substr($val,0,2000))),"...";

                        }
                        else{
                          echo str_replace('\n',"",strip_tags($val));
                        }


             ?>

             </td>

         <?php else: ?>
        <td><?php echo $val ?></td>
	<?php endif; ?>
        </tr>
    <?php endforeach; ?>
   
    </table>
    <?php $locationHigh="purchaseItem.php?id=$highid"; ?>
    <button style="height:50px;width:100px;" onclick="location.href='<?php echo $locationHigh ?>'"> Purchase Item </button>


</body>

<?php $cnx->close(); ?>



