<?php
	require('mysqli_connect.php');                   //connection to database
		$errors=[];

if($_SERVER['REQUEST_METHOD']=='POST'){
if((empty($_POST['name']))
||(empty($_POST['email']))
||(empty($_POST['phone']))||(empty($_POST['total']))||(empty($_POST['payment'])))        //if condition to check for empty values
{
$errors[]= "please fill all the details";	           //declaring error array
}else{
	if(isset($_POST['name'])){
$fullname = filter_var($_POST['name'], FILTER_SANITIZE_STRING);              //removes special character from strig
                $fullname_regexp="/[a-zA-Z]{4,}/";                           //regex expression for the name
				if(preg_match($fullname_regexp,$fullname)){
					
				}
				else {
					$errors[]="name must be number and atleast length 4";     //error message
				}
	}
    
    //validating email
if(isset($_POST['email'])){
				if(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)){
					$email=$_POST['email'];
				}
				else {
					$errors[]="email not valid";
				}
	}
    
    //validating phone number
	if(isset($_POST['phone'])){
$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
                $phone_regexp="/^[0-9]/";
				if(preg_match($phone_regexp,$phone)){
					
				}
				else {
					$errors[]="phone must be 111-111-1111";
				}
	}
	
    //retrieving the total price 
	if(isset($_POST['total'])){
$total=$_POST['total'];
	}
	
}
    
    
    //printing of error messages if the required validations are voilated
if(empty($errors)){
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['phone'];
	$totalprice=$_POST['total'];
	$payment=$_POST['payment'];
	$q= "insert into customer (name, email, phone, totalprice,payment) values ('$name', '$email','$phone','$totalprice','$payment')";
	                        $r=@mysqli_query($dbc,$q);
							$q1="select itemName,quantityName from orders;";      //selecting iten name and quantity from orders table 
	                        $r1=@mysqli_query($dbc,$q1);
							while($row1=mysqli_fetch_array($r1,MYSQLI_ASSOC)){
					        $oname=$row1['itemName'];
							$oquantity=$row1['quantityName'];
        	$q2="select quantity from items where name='$oname'";   //selecting iten quantity from items table 
                                $r2=@mysqli_query($dbc,$q2);
while($row2=mysqli_fetch_array($r2,MYSQLI_ASSOC))
{
                              		$iquantity=$row2['quantity'];
     //updating items table 
				$q3="update items SET quantity=$iquantity-$oquantity where name='$oname'";
				                  $r3=@mysqli_query($dbc,$q3);
														}
							}
     //selecting customerid from customers table based on column totalprice 
				$q4="select customerid from customer where totalprice=$_POST[total]";
				 $r4=@mysqli_query($dbc,$q4);
						while($row4=mysqli_fetch_array($r4,MYSQLI_ASSOC)){
                            	$cookie=$row4['customerid'];            
		echo "<script type='text/javascript'>alert('your customer id is $cookie');</script>";
														}											
}
else{
	foreach($errors as $error){
		echo "<script type='text/javascript'>alert('$error');</script>";

	}
}
}

?>

<html>
    <head>
        <title>Furniture Store</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>    
    <body>
        <header>
 
            <div class="main">
                <div class="logo">
                    
                    
                </div>
                <ul>
                    <li class="active"><a href="index.html">HOME</a></li>
                    <li><a href="store.php">STORE</a></li>
                    <li><a href="cart.php">CART</a></li>     
                </ul>
                <div class="title">
                <h1>Online Furniture Store</h1>
                
            </div>
            </div>
            
            <div class="cartcontainer">
             <form class="cartform" method="post" action="cart.php">
			 <label for="name">Full Name</label>
			 <input type="text" id="name" name="name" placeholder="Enter your Full Name">
			 <label for="email">Email</label>
			 <input type="text" id="email" name="email" placeholder="Enter your Email ID">
			 <label for="phone">Telephone</label>
			 <input type="text" id="phone" name="phone" maxlength="10" minlength="10" placeholder="Enter your Telephone Number">
			 			 <label for="total">Total Price</label>

			 <label class="total" for="total">
                 <?php 
                 $q="select SUM(totalprice) as SUM from orders";
                 $r=@mysqli_query($dbc,$q);
                    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
							echo $row['SUM'];
						}
						  ?>
                 </label>
						  <input class="hidden" type="hidden" name="total" for="total" value="<?php $q="select SUM(totalprice) as SUM from orders";
						$r=@mysqli_query($dbc,$q);
                        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
							echo $row['SUM'];
						}
						  ?>">
			 <input type="radio" name="payment" value="debit" class="radio">
			 <label for="debit">Debit</label>
			 <input type="radio" name="payment" value="credit" class="radio">
			 <label for="credit">Credit</label>
             <input type="submit" class="submit" name="submit" value="Final Checkout">
			 </form>
            
			<table>
			<tr>
		
			<th>NAME</th>
			<th>QUANTITY</th>
			<th>EACH PRICE</th>
			<th>TOTAL</th>
			</tr>
			<?php
		
            $q= "select itemName,quantityName,price,totalprice from orders";
                        $r=@mysqli_query($dbc,$q);
                        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
							echo "<tr><td>".$row['itemName']."</td><td>".$row['quantityName']."</td><td>".$row['price']."</td><td>".$row['totalprice']."</td></tr>";
						}
						
						
						
						
			?>
			</table>
				</div>
            
		
        </header>
    </body>
</html>    