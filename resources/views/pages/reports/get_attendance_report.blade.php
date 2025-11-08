<style>
    body {
        font-family: sans-serif;
        background: #fff;
    }

    .text-primary {
        color: #4caf50;
        text-align: left;
    }

    h1 {
        text-align: center;
        text-transform: uppercase;
    }

    .container {
        width: 1400px;
        margin: auto;
    }

    .timeline {
        counter-reset: test;
        position: relative;
        margin-left: 50px;
    }

    .timeline li {
        list-style: none;
        float: left;
        width: 33.3333%;
        position: relative;
        text-align: center;
        text-transform: capitalize;
        margin: 10px auto 10px;

    }

    ul:nth-child(n) {
        color: #4caf50;
        overflow: hidden;
    }

    .timeline li:before {

        counter-increment: test;
        content: counter(test);
        width: 30px;
        height: 30px;
        border: 3px solid #2c9730;
        border-radius: 50%;
        display: block;
        text-align: center;
        line-height: 50px;
        margin: 0 auto 10px auto;
        background: #4caf50;
        color: #4caf50;
        font-size: 2px;
        transition: all ease-in-out .3s;
        cursor: pointer;

    }

    .li {
        color: black;
    }

    .timeline li:after {

        content: "";
        position: absolute;
        width: 100%;
        height: 1px;
        background-color: grey;
        top: 20px;
        left: 20px;
        z-index: -999;
        transition: all ease-in-out .3s;
    }

    .timeline li:first-child:after {
        content: none;
    }

    .timeline li.active-tl {
        color: black;

    }

    .timeline li.active-tl:before {
        background: #81cae2;
        color: black;
        border: 3px solid #5bacc7;
    }

    h4.titleDate {
        font-size: 20px;
        color: #4264a4;
        margin: 20px 0px 4px 0px;
        padding: 6px 6px 6px 10px;
        border-left: 4px solid #e5e8ef;
        text-align: left;
        background-color: #f7f3f3;
    }

    h4.title {
        color: #475f7b;
        text-align: left;
        font-size: 20px;
        margin-left: 20px;
    }

    .RTime {
        width: 90px;
        margin: auto;
        background-color: #e5e8ef;
        padding: 6px 20px;
        border-radius: 4px;
    }

    .RAddress {
        margin: auto;
        background-color: #e5e8ef;
        padding: 6px 100px;
        border-radius: 4px;
        margin-top: 10px;
        margin-left: 4px;
        margin-right: 4px;
    }

    .RLocation {
        margin: auto;
        background-color: #e5e8ef;
        padding: 6px 100px;
        border-radius: 4px;
        margin-left: 4px;
        margin-right: 4px;
    }

    .RHead {
        width: 50px;
        margin: 0 auto;
        background-color: #daf2fa;
        padding: 6px 34px;
        border-radius: 4px;
    }
</style>

<body>
    <img width="50px" id="logo" src="images/invoice_img/logo.png">
    <h2 style="text-align:center;">Attendance Report ({{$FilterDate}})</h2>
    <div class="container">
        @foreach ($staff as $val)
        <h4 class="title"><strong>Staff - {{$val->name}}</strong></h4><br>
        @foreach ($dateArr as $date) <ul class="timeline">
            <h4 class="titleDate"><strong>{{date('d M Y',strtotime($date))}}</strong></h4><br>
            <li class="active-tl">
                <h5 class="RHead">SIGNIN</h5>
                <h5 class="RLocation1"> @if (isset($val->signin_location) && is_array($val->signin_location) && array_key_exists($date, $val->signin_location))
                    {{$val->signin_location[$date]}}
                    @else

                    @endif
                </h5>
            </li>
            @if(!empty($val->visit_list))
            @foreach($val->visit_list as $row)
            @if(strtotime($row->signin_date)==strtotime($date))
            <li class="li">
                <h5 class="RTime">{{date("h:i A", strtotime($row->created_at))}}</h5>
                <h5 class="RAddress">{{$row->address}}</h5>
                <h5 class="RLocation"> ({{$row->location}})</h5>
            </li>
            @endif
            @endforeach
            @endif
            <li class="active-tl">
                <h5 class="RHead">SIGNOUT</h5>
                <h5 class="RLocation1">{{$val->signout_location[$date]}}
                    @if (isset($val->signout_location) && is_array($val->signout_location) && array_key_exists($date, $val->signout_location))
                    {{$val->signout_location[$date]}}
                    @else

                    @endif
                </h5>
            </li>&nbsp;
        </ul>
        @endforeach
        @endforeach
    </div>


</body>