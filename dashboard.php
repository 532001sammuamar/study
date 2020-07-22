<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 4 Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
  <h1 class="text-center text-danger mb-5"
  style="font-family:'abril fatface',cursive;">Online shopping cart PHP MYSQLi</h1>
<div class="row">
  <?php
  $con = mysqli_connect('localhost','root');
  mysqli_select_db($con,'dashboard');

  $query = " SELECT `Name`, `Image`, `Price`, `Discount` FROM `ec dashboard` order by ID asc ";
  $queryfire = mysqli_query($con, $query);
  $num = mysqli_num_rows($queryfire);
  if($num > 0){
    while($product = mysqli_fetch_array($queryfire)){ ?>
      <div class="card">
        <h6 class="card title bg-info text-white p-2 text-uppercase"> <?php echo $product['Name']; ?> </h6>
        <div class="card-body">
          <img src="<?php echo $product['Image']; ?>" alt="phone" class="img-fluid mb-2">
          <h6 style="color:black">
            &#8377; <?php echo $product['Price']; ?> (<?php echo $product['Discount']; ?>% off)
          </h6>
          <h6 class="badge badge-success"> 4.4 <i class="fa fa-star"></i> </h6>
          <input type="text" name="" class="form-control" placeholder="quantity">
        </div>
        <div class="btn-group d-flex">
          <button class="btn btn-success flex-fill"> Add to cart </button> <button class="btn btn-warning flex-fill text-white"> buy now </button>
        </div>
      </div>
  <?php }
  } ?>

</div>
</div>

</body>
</html>
