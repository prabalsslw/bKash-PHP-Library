<?php $config = include(__DIR__.'/../config/bkash.php'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bkash Checkout</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script id = "myScript" src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>
    </head>
    <body>
        <div class="container">
            <br><br><br><br><br><br><br><br><br><br><br><br>
            <div class="panel panel-warning col-md-6 col-md-offset-3">
                <div class="col-md-6">
                    <img src="bklogo.png" class="img-rounded" alt="Cinque Terrekash">
                </div>
                <div class="col-md-6"><br><br><br><br>
                    <button id="bKash_button" class="btn btn-primary pull-right" style="border-radius: 0px;">Pay With bKash</button>
                </div>
            </div>
        </div>
    </body>

    <script type="text/javascript">
        $(document).ready(function() {
            var is_capture = "<?= $config['is_capture'] ?>";
            var paymentID = '';
            var intnt = '';
            if(is_capture == true) {
                intnt = 'authorization';
            } else {
                intnt = 'sale';
            }

            bKash.init({
                paymentMode: 'checkout', //fixed value ‘checkout’ 
                //paymentRequest format: {amount: AMOUNT, intent: INTENT} 
                //intent options 
                //1) ‘sale’ – immediate transaction (2 API calls) 
                //2) ‘authorization’ – deferred transaction (3 API calls) 
                paymentRequest: {
                    amount: "20", //max two decimal points allowed 
                    intent: intnt
                },
                createRequest: function(request) { //request object is basically the paymentRequest object, automatically pushed by the script in createRequest method 
                    console.log(request);
                    $.ajax({
                        url: 'createPayment.php',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(request),
                        success: function(data) {
                            console.log(data);
                            data = JSON.parse(data);
                            if (data && data.paymentID != null) {
                                paymentID = data.paymentID;
                                bKash.create().onSuccess(data); //pass the whole response data in bKash.create().onSucess() method as a parameter 
                            } else {
                                bKash.create().onError();
                            }
                        },
                        error: function() {
                            bKash.create().onError();
                        }
                    });
                },
                executeRequestOnAuthorization: function() { 
                  $.ajax({ 
                    url: 'executePayment.php', 
                    type: 'POST', 
                    contentType: 'application/json', 
                    data: JSON.stringify({ 
                      "paymentID": paymentID 
                    }), 
                    success: function(data) { 
                        console.log('got data from execute  ..');
                        console.log('data ::=>');
                        console.log(JSON.stringify(data));
                        data = JSON.parse(data);
                        console.log(data);

                        if (data && data.paymentID != null) { 
                            if(is_capture == true) {
                                $.ajax({ 
                                    url: 'capturePayment.php', 
                                    type: 'POST', 
                                    contentType: 'application/json', 
                                    data: JSON.stringify({ 
                                      "paymentID": paymentID 
                                    }), 
                                    success: function(data) { 
                                        console.log('got data from capture  ..');
                                        console.log('data ::=>');
                                        console.log(JSON.stringify(data));
                                        data = JSON.parse(data);
                                        console.log(data);
                                        if (data && data.paymentID != null) { 
                                            //window.location.href = "success.php";//Merchant’s success page 
                                        } else { 
                                            bKash.execute().onError(); 
                                        } 
                                    }, 
                                    error: function() { 
                                        bKash.execute().onError(); 
                                    } 
                                    });
                                } else {
                                    alert("Not captured");
                                } 
                            //window.location.href = "success.php";//Merchant’s success page 
                        } else { 
                            bKash.execute().onError(); 
                        } 
                    }, 
                    error: function() { 
                        bKash.execute().onError(); 
                    } 
                    }); 
                } 
            });
        });
    </script>
</html>