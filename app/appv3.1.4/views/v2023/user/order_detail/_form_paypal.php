<!DOCTYPE html>
<html lang="en">

<head>
    <title>THANH TOÁN ĐƠN HÀNG</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center">THANH TOÁN ĐƠN HÀNG</h2>
        <div class="border p-4 step-3-box shadow">
            <form action="ajax-call-api-pay" method="GET">
                <div class="mb-3">
                    <label for="input_card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="input_card_number" name="card_number" placeholder="Card Number" required="" value="012 345 6789">
                </div>
                
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label for="input_card_number" class="form-label">Card Expiration Date</label>
                        <div class="d-flex" style="gap:10px">
                            <div class="w-50">
                                <input type="number" class="form-control w-100 me-3" id="input_card_mm" name="card_mm" placeholder="MM" required="" value="05">
                            </div>
                            <div class="w-50">
                                <input type="number" class="form-control w-100" id="input_card_yy" name="card_yy" placeholder="YY" required="" value="28">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-3">
                        <label for="input_card_number" class="form-label">Card Security Code (CVV/CVC)</label>
                        <input type="number" class="form-control w-50 me-3" id="input_card_cvv" name="card_cvv" placeholder="CVV" required="" value="123">
                    </div>
                </div>

                <div class="mb-3">
                    <input type="hidden" value="<?=@$_GET['id_order']?>" name="id_orderr">
                    <input type="hidden" value="<?=@$_GET['amount']?>" name="amountt">
                    <input type="hidden" value="<?=@$_GET['l']?>" name="l">
                    <button type="submit" class="btn btn-lg btn-danger w-100">THANH TOÁN</button>
                </div>

                <small>By Placing an Order, You Agree to Stuccco's
                    <a class="link-color" target="_blank" href="terms-of-use">Terms of Use</a> and
                    <a class="link-color" target="_blank" href="pricacy-policy">Privacy Policy</a>
                </small>
            </form>
        </div>
    </div>

</body>

</html>