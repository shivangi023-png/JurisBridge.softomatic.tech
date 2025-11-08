<div class='footer'>
    @if (session('head_office'))
        <p>Head Office: {{ session('head_office') }}, Our Branches: {{ session('company_branch') }}</p>
    @endif
    <table class='footer-tbl'>
        <tr>
            @if (session('company_contact'))
                <td><img src='images/invoice_img/call.jpg'></td>
                <td>{{ session('company_contact') }}</td>
            @endif
            @if (session('company_email'))
                <td><img src='images/invoice_img/mail.jpg'></td>
                <td>{{ session('company_email') }}</td>
            @endif
            @if (session('website_url'))
                <td><img src='images/invoice_img/web.jpg'></td>
                <td>{{ session('website_url') }}</td>
            @endif
            @if (session('facebook_url'))
                <td><img src='images/invoice_img/f.jpg'></td>
                <td>{{ session('facebook_url') }}</td>
            @endif
            @if (session('youtube_url'))
                <td><img src='images/invoice_img/y.jpg'></td>
                <td>{{ session('youtube_url') }}</td>
            @endif
        </tr>
    </table>
</div>
