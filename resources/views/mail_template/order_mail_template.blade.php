<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FFF5F2" style="font-family:'DM Sans', sans-serif;font-weight: 500;font-size: 1rem;line-height: 1.3;color: #000000;">
    <tbody>
    <tr>
        <td valign="top" bgcolor="#FFF5F2" width="100%" style="padding: 2rem 0;">
            <table width="100%" role="content-container" align="center" cellpadding="0" cellspacing="0" border="0">
                <tbody>
                <tr>
                    <td width="100%">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px" align="center">
                                        <tbody>
                                        <tr>
                                            <td role="modules-container" style="padding:0px 0px 0px 0px;color:#000000;text-align:left" bgcolor="#ffffff" width="100%" align="left">
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="display:none!important;opacity:0;color:transparent;height:0;width:0">
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <p>Let us know if this was a mistake. You may submit another order to purchase.</p>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:0px 0px 30px 0px" bgcolor="">
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td height="100%" valign="top">
                                                            <table border="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td></td>
                                                                    <td width="210" style="text-align: center;">
                                                                        <img src="https://ysabelles.ph/cakeShop/website/images/logo-transparent.png" style="max-width:210px!important;display:block;margin:auto" class="CToWUd" data-bit="iit">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td height="100%" valign="top">
                                                            <div style="padding:32px 18px 24px">
                                                                <table style="width:100%">
                                                                    <tbody>
                                                                        <tr>
                                                                            @if($mailType == "status")
                                                                                @if($order->status == "rejected")
                                                                                    <td><span style="font-size:1.45rem;font-family:'DM Sans', sans-serif;font-weight: 600;">Sorry, your order was rejected by the seller</span></td>
                                                                                    <td align="right"><span style="font-size:1.15rem;font-family:'DM Sans', sans-serif;color:#000000;font-weight:bold">{{isset($user->name) && !empty($user->name) ? $user->name : "";}}</span></td>
                                                                                @elseif($order->status == "pending")
                                                                                    <td><span style="font-size:1.45rem;font-family:'DM Sans', sans-serif;font-weight: 600;">Your order is currently pending. Please wait for confirmation.</span></td>
                                                                                @else
                                                                                     <td><span style="font-size:1.45rem;font-family:'DM Sans', sans-serif;font-weight: 600;">Your order has been successfully paid. Thank you for your purchase!</span></td>
                                                                                @endif
                                                                            @elseif($mailType == "success")
                                                                                <td><span style="font-size:1.45rem;font-family:'DM Sans', sans-serif;font-weight: 600;">Your order has been successfully placed. Thank you for your purchase!</span></td>
                                                                            @endif
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                @if($mailType == "status" && $order->status == "rejected")
                                                    <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                        <tbody>
                                                        <tr>
                                                            <td style="padding:0px 20px 30px 20px;text-align:inherit;" height="100%" valign="top" bgcolor="">
                                                                <div>
                                                                    <div style="font-family:'DM Sans', sans-serif;text-align:inherit"><span style="color:#635c5c;font-family:'DM Sans', sans-serif;font-size:1rem;line-height:1.3;font-weight: 500;">If you think this was a mistake, please contact the seller at </span><span style="color:#635c5c;font-family:'DM Sans', sans-serif;font-size:1rem;line-height:1.3;font-weight: 700;">{{isset($user->phone) && !empty($user->phone) ? $user->phone : "";}}</span><span style="color:#635c5c;font-family:'DM Sans', sans-serif;font-size:1rem;line-height:1.3;font-weight: 500;">.</span></div>
                                                                    <div></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                
                                                    <table border="0" cellpadding="0" cellspacing="0" role="module" style="table-layout:fixed" width="100%">
                                                        <tbody>
                                                        <tr>
                                                            <td align="center" bgcolor="" style="padding:0px 0px 22px 0px">
                                                                <table border="0" cellpadding="0" cellspacing="0" style="text-align:center">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td align="center" bgcolor="#FFBC00" style="border-radius:30px;font-size:1rem;text-align:center;background-color:inherit"><a href="{{route('home')}}" style="font-family: 'Quicksand', sans-serif;background-color: #F04924;border: 0px solid #F04924;border-color: #F04924;border-radius: 30px;box-shadow: 0px 17px 27px rgba(240, 73, 36, 0.27);border-width:0px;color: #ffffff;display:inline-block;font-size:1rem;font-weight: 400;letter-spacing:0px;line-height:normal;padding: 13px 37px;text-align:center;text-decoration:none;border-style:solid;" target="_blank">Order again</a></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                @endif
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:0px 20px 18px 20px;text-align:inherit" height="100%" valign="top" bgcolor="">
                                                            <div>
                                                                <div style="font-family:'DM Sans', sans-serif;text-align:inherit"><span style="color:#635c5c;font-family:'DM Sans', sans-serif;font-weight: 500;font-size:1rem;line-height:1.3;">Here's what you ordered:</span></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:0px 0px 0px 0px" height="100%" valign="top" bgcolor="">
                                                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="2px" style="line-height:2px;font-size:2px">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="padding:0px 0px 2px 0px;background-color: rgba(240,73,36,0.1);"></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td height="100%" valign="top">
                                                            <div style="padding:18px 18px 24px;font-size: 1.2rem;line-height: 1.3;color: #000000;font-family: 'DM Sans', sans-serif;font-weight: 500;">
                                                                <table style="width:100%">
                                                                    <tbody>
                                                                        @if(isset($orderItems) && !empty($orderItems))
                                                                            @foreach($orderItems as $key => $value)
                                                                                <tr>
                                                                                    <td style="padding-bottom:16px"><span style="font-weight:600;white-space:pre-wrap">{{$value->name}}</span></td>
                                                                                    <td style="padding-bottom:16px" align="right"><span>₱</span>{{number_format($value->total,2, '.', '')}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td><span style="font-weight:600;">Total</span></td>
                                                                            <td align="right"><span>₱</span>{{number_format($order->order_total,2, '.', '')}}</td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td height="100%" valign="top">
                                                            <div style="padding:18px 18px 24px;font-family:'DM Sans', sans-serif;font-weight: 500;font-size: 1rem;line-height: 1.3;color: #635c5c;">
                                                                <div style="font-family:'DM Sans', sans-serif;font-weight: 700;font-size: 1.4rem;line-height: 1.3;color: #000000;text-align:inherit;">Instructions</div>
                                                                <div style="font-family:'DM Sans', sans-serif;text-align:inherit"><br></div>
                                                                <div style="font-family:'DM Sans', sans-serif;text-align:inherit;white-space:pre-wrap">To avoid delays, schedule your courier in advance via Lalamove. <br>And send us your tracking:
                                                                </div>
                                                                <div style="font-family:'DM Sans', sans-serif;text-align:inherit;white-space:pre-wrap"><img width="16" height="16px" style="object-fit: contain;margin-right: 6px;" data-emoji="⌚️" class="an1" alt="⌚️" aria-label="⌚️" src="https://fonts.gstatic.com/s/e/notoemoji/15.0/231a_fe0f/72.png" loading="lazy"> Pickup Time Hours: 9am to 6pm only<br><img width="16" height="16px" style="object-fit: contain;margin-right: 6px;" data-emoji="⚠️" class="an1" alt="⚠️" aria-label="⚠️" src="https://fonts.gstatic.com/s/e/notoemoji/15.0/26a0_fe0f/72.png" loading="lazy"> Click BAG for cake safety (under ADD-ONS)<br><img width="16" height="16px" style="object-fit: contain;margin-right: 10px;" data-emoji="📍" class="an1" alt="📍" aria-label="📍" src="https://fonts.gstatic.com/s/e/notoemoji/15.0/1f4cd/72.png" loading="lazy">Lalamove: 7 Dominador, Quezon City.<br><img width="16" height="16px" style="object-fit: contain;margin-right: 6px;" data-emoji="☎️" class="an1" alt="☎️" aria-label="☎️" src="https://fonts.gstatic.com/s/e/notoemoji/15.0/260e_fe0f/72.png" loading="lazy"> 09060649461
                                                                </div>
                                                                <div style="font-family:'DM Sans', sans-serif;text-align:inherit;white-space:pre-wrap">Pls tell your rider to knock on the Green Gate and give your name and code</div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:0px 0px 0px 0px" height="100%" valign="top" bgcolor="">
                                                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="2px" style="line-height:2px;font-size:2px">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="padding:0px 0px 2px 0px;background-color: rgba(240,73,36,0.1);"></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td height="100%" valign="top">
                                                            <div style="padding:18px 18px 10px;font-size: 1.2rem;line-height: 1.3;color: #000000;font-family: 'DM Sans', sans-serif;font-weight: 500;">
                                                                <table style="width:100%">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td style="padding-bottom:30px"><span style="font-weight:600;">Payment Method</span></td>
                                                                        <td align="right" style="padding-bottom:30px;white-space:pre-wrap">{{ucfirst($order->payment_type)}}</td>
                                                                    </tr>
                                                                    <!-- <tr>
                                                                        <td style="padding-bottom:30px"><span style="font-weight:600;">Fulfillment Method</span></td>
                                                                        <td align="right" style="padding-bottom:30px;white-space:pre-wrap">Quezon City</td>
                                                                    </tr> -->
                                                                    <tr>
                                                                        <td style="padding-bottom:30px"><span style="font-weight:600;">Your Name</span></td>
                                                                        <td align="right" style="padding-bottom:30px;white-space:pre-wrap">{{$order->customer_name}}</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                @if(isset($order->short_notes) && !empty($order->short_notes))
                                                    <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                        <tbody>
                                                        <tr>
                                                            <td style="padding:0px 20px 18px 20px;text-align:inherit" height="100%" valign="top" bgcolor="">
                                                                <div>
                                                                    <div style="font-family:'DM Sans', sans-serif;text-align:inherit"></div>
                                                                    <div style="font-size: 1rem;line-height: 1.3;color: #635c5c;font-family: 'DM Sans', sans-serif;font-weight: 500;text-align:inherit;white-space:pre-wrap"><b>Short Notes: </b>{{$order->short_notes}}</div>
                                                                    <div style="font-family:'DM Sans', sans-serif;text-align:inherit"><br></div>
                                                                    <div style="font-family:'DM Sans', sans-serif;text-align:inherit"></div>
                                                                    <div></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                @endif
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:0px 0px 0px 0px" height="100%" valign="top" bgcolor="">
                                                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="2px" style="line-height:2px;font-size:2px">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="padding:0px 0px 2px 0px;background-color: rgba(240,73,36,0.1);"></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:18px 20px 18px 20px;line-height:22px;text-align:inherit" height="100%" valign="top" bgcolor="">
                                                            <div>
                                                                <div style="font-family:'DM Sans', sans-serif;text-align:inherit"><span style="color:#000000;font-family:'DM Sans', sans-serif;font-weight: 500;font-size:1rem;line-height:1.3;">Have a nice day!</span></div>
                                                                <div></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td style="padding:0px 0px 0px 0px" height="100%" valign="top" bgcolor="">
                                                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="2px" style="line-height:2px;font-size:2px">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="padding:0px 0px 2px 0px;background-color: rgba(240,73,36,0.1);"></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table role="module" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
                                                    <tbody>
                                                    <tr>
                                                        <td height="100%" valign="top">
                                                            <div style="font-family:'DM Sans', sans-serif;text-align:center;padding:16px 0px 16px">
                                                                <a href="#" target="_blank" style="text-decoration:none;text-decoration-color: transparent;"><span style="color:#5E5E5E;font-family:'DM Sans', sans-serif;font-size:1rem;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:600;letter-spacing:normal;text-align:center;text-indent:0px;text-transform:none;white-space:normal;word-spacing:0px;background-color:transparent;line-height:1.3;">Powered by Cake Shop</span></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
