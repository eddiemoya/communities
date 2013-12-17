<?php
    # @author Carl Albrecht-Buehler 
    # Create an array of all the Sears footer links
    # Scraped from Sears.com July 9, 2012; September 18, 2012
    $a_navigation = array(
        "Contact Us" => array(
            "Sears Stores \n 1-800-549-4505 \n Sears.com" => null,
            "Email Us" => "http://www.sears.com/csemail/nb-100000000020508?adCell=W3",
            "1-800-697-3277 \n Website Feedback" => null,
            "Share Your Thoughts" => "http://searshc.us2.qualtrics.com/SE/?SID=SV_cSKkEcdWJleEGc4&shc_tleaf_cv=null&shc_jSessionId=0000qnKOeM0Ii0_XIp2Arn5zRpd:15eoftdbu&shc_userId=&shc_envir=prod-vX-&shc_time1=1321378762087&shc_time2=1321379899256&shc_prev=http%3A%2F%2Fwww.sears.com%2Fshc%2Fs%2Fnb_10153_12605_NB_CSHome&shc_referer=" . urlencode( get_permalink() )
        ),
        "Accounts & Orders" => array(
            "Access My Account" => "https://www.sears.com/shc/s/UserAccountView?storeId=10153&amp;catalogId=12605&amp;langId=-1",
            "Check Gift Card Balance" => "http://www.sears.com/shc/s/nb_10153_12605_NB_gclanding",
            "Order Status" => "http://www.sears.com/shc/s/TrackOrderStatus?langId=-1&amp;storeId=10153&amp;catalogId=12605&amp;sso_in_param=fromLink%3DorderStatus&amp;adCell=WF",
            "Check My Rewards Points" => "https://www.sears.com/shc/s/FetchMembershipDetails?storeId=10153&amp;catalogId=12605&amp;langId=-1",
            "Pay Sears Credit Card Bill" => "https://www.citibank.com/us/cards/srs/index.jsp?BAC=SearsMemberOffers",
            "Return or Exchange Items" => "http://www.sears.com/shc/s/ReturnMyOrder?langId=-1&amp;storeId=10153&amp;catalogId=12605&amp;adCell=BF&amp;adCell=WF"
        ),
        "Customer Service" => array(
            "Buy Online Pick-up In Store" => "http://www.sears.com/shc/s/dap_10153_12605_DAP_Buy-Online-Pick-Up-In-Store?adCell=W3",
            "Customer Service Home" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSHome",
            "International Shipping" => "http://www.sears.com/international-help",
            "Return & Cancellations" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSreturns?adCell=W4",
            "Shipping & Delivery" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSshipping?adCell=W4"
        ),
        "Sell on Sears" => array(
            "Sell on Sears" => "https://seller.marketplace.sears.com/SellerPortal/d/index.jsp",
            "About Marketplace" => "https://seller.marketplace.sears.com/SellerPortal/d/help/about_marketplace.jsp",
            "Seller Portal Login" => "https://seller.marketplace.sears.com/SellerPortal/d/login.jsp"
        ),
        "About Sears" => array(
            "Affiliate Program" => "http://www.sears.com/shc/s/nb_10153_12605_NB_Sears Affiliate",
            "Careers" => "http://www.searsholdings.com/careers",
            "Corporate Website" => "http://www.searsholdings.com/",
            "Investor Relations" => "http://www.searsholdings.com/invest/",
            "Military Support" => "http://www.searsholdings.com/communityrelations/hero/military.htm",
            "News Center" => "http://www.searsmedia.com/"
        ),
        "Legal" => array(
            "California Privacy Policy" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CScaliforniaprivacy",
            "License Info" => "http://www.sears.com/shc/s/nb_10153_12605_NB_License Information",
            "Payment Methods" => "http://www.sears.com/paymentmethod/nb-100000000041010",
            "Price Match Policy" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSpricematch?adCell=WF&amp;adCell=WF",
            "Privacy Policy" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSprivacy",
            "Interested based ads" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSIntBasedAds"
        ),
        "More Sears Sites" => array(
            "Manage My Life" => "http://www.managemylife.com/?sid=MMLxSearsxFooterxInspiration",
            "Sears Commercial" => "http://www.searscommercial.com",
            "Sears Home Services" => "http://services.sears.com",
            "Sears International" => "http://www.sears.com/international",
            "Sears Optical" => "http://www.searsoptical.com/webapp/wcs/stores/servlet/GenContent|-1|11251|10051|/searsopticalus/home/searsushomepage1?intcmp=xsite_Sears",
            "Sears Portrait Studio" => "http://www.searsportrait.com/?intcmp=xsite_Sears",
            "See More" => "http://www.sears.com/shc/s/nb_10153_12605_NB_see+it+all?storeId=10153&amp;vName=see+it+all&amp;catalogInd=NB&amp;catalogId=12605"
            )
    );
   // $footer_html = curl_it("http://localhost:3000/navigators/sears-footer-tree");
?>
<nav id="footer_navigation">
    <ul id="footer_nav" class="dropmenu clearfix">
        <?php foreach ( $a_navigation as $nav_item => $a_subnav ): ?>
            <li>
                <span><span><?php echo htmlentities( $nav_item, ENT_QUOTES ); ?></span></span>
                <?php if ( !empty( $a_subnav ) ): ?>
                    <ul>
<?php
                        foreach ( $a_subnav as $subnav => $nav_url ):
                            $the_item = str_replace( "\n", "<br />", htmlentities( $subnav, ENT_QUOTES ) );
                            $print_item = $nav_url ? '<a href="' . $nav_url . '" rel="nofollow">' . $the_item . '</a>' : $the_item;
?>
                            <li><?php echo $print_item; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
