<!DOCTYPE HTML>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!------ Include the above in your HEAD tag ---------->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="form/form.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-8 pb-5">
                <!--Form with header-->
                <form name="payitmonthly" method="post" action="/form/payment-process.php">
                    <input type="hidden" name="callbackurl" value="<?= $callbackUrl; ?>">
                    <input type="hidden" name="order-total-value" value="<?= $order_value; ?>">
                    <input type="hidden" id="todays-amount" name="todays-amount" value="">
                    <input type="hidden" id="product-description" name="product-description" value="<?= $item_description; ?>">
                    <input type="hidden" id="currency" name="currency" value="<?= $currency; ?>">

                    <div class="card border-primary rounded-0">
                        <div class="card-header p-0">
                            <div class="bg-info text-white text-center py-2">
                                <h3>Payit Monthly</h3>
                                <p class="m-0">Apply for finance</p>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <p>First Name</p>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="payment[firstName]" placeholder="First Name" required value="<?= $firstName; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <p>Last Name</p>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="payment[lastName]" placeholder="Last Name" required value="<?= $lastName; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <p>Email</p>
                                        </div>
                                    </div>
                                    <input type="email" class="form-control" name="payment-email" placeholder="Email" required value="<?= $customer_email; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <p>Total Order Value</p>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="payment-ordervalue" name="payment-ordervalue" placeholder="Order Value" value="<?= $order_value . ' ' . $currency; ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <p>Number of Installments</p>
                                        </div>
                                    </div>
                                    <select id="installments" name="installment-months">
                                        <option value="3"> 3 Months</option>
                                        <option value="6"> 6 Months</option>
                                        <option value="12"> 12 Months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <p> Todays Deposit Amount</p>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="desposit-amount" name="desposit-amount" placeholder="Deposit amount" disabled>
                                </div>
                            </div>

                            <div class="text-center">
                                <input type="submit" value="Submit" class="btn btn-info btn-block rounded-0 py-2">
                            </div>
                        </div>

                    </div>
                </form>
                <!--Form with header-->
            </div>
        </div>
    </div>
</body>