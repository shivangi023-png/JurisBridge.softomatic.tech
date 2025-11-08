<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Welcome</title>
</head>


<body>

    <div
        style=" margin: auto;width: 60%;border: 3px solid #f9faf8;padding: 10px;box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

        <div>
            <img src="{{asset('images/pages/logo.png')}}" alt="branding logo">
            <h3 style="   
            padding: 10px;
            text-align: right;
            padding-top: 2px;
            color: #fff;
            margin: 0;
            float: right;
            width: 55%;
            font-size: 26px;
            font-family: 'arial narrow';"></h3>
        </div>

        <div class="content">

            <h4>Your OTP is - {{$generated_otp}}</h4>
            <div style="width: max-content;
            margin: unset;
            float: left;">

            </div>
        </div><br>



    </div>

</body>

</html>