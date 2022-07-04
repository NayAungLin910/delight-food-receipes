<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->

    <!-- Bootstarp -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" 
    crossorigin="anonymous">

    <title>Delight Verification Code</title>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Thank you for visiting Delight!</h3>
            </div>
            <div class="card-body">
                <p>Your verification code</p>
                <p>
                    <h4>{{ $data['vercode'] }}</h4>
                </p>
            </div>
        </div>
    </div>
</body>
</html>