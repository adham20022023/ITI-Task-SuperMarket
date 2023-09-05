<?php

require_once 'print.php';
global $table;
if (isset($_POST['enter_products'])) {

    $table = drawTable($_POST['product_number']);
}
if (isset($_POST['view_invoice'])) {
    global $table;
    $table = drawInvoice($_POST['product_number']);


}
function calc_delivery($city)
{
    switch ($_POST['city']) {
        case 'cairo':
            return 0;
        case 'alex':
            return 50;
        case 'giza':
            return 30;
        default:
            return 100;
    }
}
function calculateDiscount($total)
{
    if ($total < 1000) {
        return 0;
    } elseif ($total < 3000 && $total > 1000) {
        return 0.1; //10%
    } elseif ($total < 4500 && $total > 3000) {
        return 0.15; //15%
    } else {
        return 0.2; //20%
    }
}
// function setvalues(){
//     global $name;
//     global $city;
//     global $numberOfProducts;
//     $name = $_POST['name'];
//     $city = $_POST['city'];
//     $numberOfProducts = $_POST['product_number'];
// }
function drawTable($numberOfProducts)
{
    $table = <<<HTML
    <table class="table black-table ">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
    HTML;

    while ($numberOfProducts--) {
        // Inside the heredoc, you can directly use the PHP code.
        $table .= <<<HTML
            <tr>
                <td><input type="text" name="prod_name$numberOfProducts" id="prod_name" class="form-control "></td>
                <td><input type="text" name="price$numberOfProducts" id="prod_name" class="form-control "></td>
                <td><input type="text" name="Quantity$numberOfProducts" id="prod_name" class="form-control "></td>
            </tr>
    HTML;
    }
    $table .= <<<print
    <tr> <td colspan=3> <button class='btn btn-dark rounded form-control' name='view_invoice'> View Invoice </button></td> </tr>
    print;
    $table .= <<<HTML
        </tbody>
    </table>
    HTML;
    return $table;
}
function drawInvoice($numberOfProducts)
{
    $total = 0;
    $table = <<<invoice
    <table class="table ">
        <thead class="text-danger">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
    invoice;
    while ($numberOfProducts--) {
        $subtotal = $_POST['price' . $numberOfProducts] * $_POST['Quantity' . $numberOfProducts];
        $total += $subtotal;
        $table .= <<< sec2
                     <tr>
                          <td>
                              {$_POST['prod_name' .$numberOfProducts]}
                          </td>
                          <td>
                              {$_POST['price' .$numberOfProducts]}
                          </td>
                          <td>
                              {$_POST['Quantity' .$numberOfProducts]}
                          </td>
                          <td>
                              $subtotal
                          </td>
                      </tr>
          sec2;
    }
    $discount = calculateDiscount($total);
    $discountPercentage = $discount * 100;
    $discount_value = $total * $discount;
    $total_after_discount = $total - $discount_value;
    $delivery = calc_delivery($_POST['city']);
    $netTotal = $delivery + $total_after_discount;
    $table .= <<<all
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            Client Name
                        </td>
                        <td colspan=2>
                            {$_POST['name']}
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            City
                        </td>
                        <td colspan=2>
                            {$_POST['city']}
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            Total
                        </td>
                        <td colspan=2>
                            $total <b>EGP</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            Discount Percentage
                        </td>
                        <td colspan=2>
                            $discountPercentage  <b>%</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            Discount Value
                        </td>
                        <td colspan=2>
                            $discount_value <b>EGP</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            Total After Discount
                        </td>
                        <td colspan=2>
                            $total_after_discount <b>EGP</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold'>
                            Delivery
                        </td>
                        <td colspan=2>
                            $delivery <b>EGP</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 class='font-weight-bold tex'>
                            Net Total
                        </td>
                        <td colspan=2>
                            <b> $netTotal EGP</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        all;
   print_table($table);
    return $table;
}
?>






<!doctype html>
<html lang="en">

<head>
    <title>Super Market</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="col-13 mt-5">
            <h1 class="text-dark text-center ">
                Super Market Task
            </h1>
        </div>
        <div class="col-7 offset-3 mt-5">
            <form method="post">
                <div class="form-group">
                    <label for="name"></label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php if (isset($_POST['name'])) {
                                                                                                echo $_POST['name'];
                                                                                            } ?>" aria-describedby="helpId" placeholder="Enter Name">
                </div>
                <div class="form-group">
                    <label for="city"></label>
                    <select name="city" class="form-control" id="city">
                        <option <?php if (isset($_POST['city']) && $_POST['city'] == 'cairo') {
                                    echo 'selected';
                                } ?> value="cairo">cairo</option>
                        <option <?= (isset($_POST['city']) && $_POST['city'] == 'giza') ? 'selected' : '' ?> value="giza">giza</option>
                        <option <?php if (isset($_POST['city']) && $_POST['city'] == 'alex') {
                                    echo 'selected';
                                } ?> value="alex">alex</option>
                        <option <?= (isset($_POST['city']) && $_POST['city'] == 'others') ? 'selected' : '' ?> value="others">others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_number"></label>
                    <input type="text" name="product_number" value="<?= isset($_POST['product_number'])  ? $_POST['product_number'] : '' ?>" id="product_number" class="form-control" placeholder="Enter Number Of Products" aria-describedby="helpId">
                </div>
                <div class="form-group">
                    <button class="btn btn-dark rounded form-control" name="enter_products"> Enter Products </button>
                </div>
                <?= isset($table) ? $table : '' ?>




            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>