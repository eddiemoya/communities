<?php
  # @author Carl Albrecht-Buehler 
  # Create an array of all the Sears footer links
  # Scraped from Kmart.com July 16, 2012
  $a_navigation = array(
    "Kmart Community" => array(
      "Facebook" => "http://www.facebook.com/kmart",
      "Twitter" => "http://www.twitter.com/kmartdeals",
      "YouTube" => "http://www.youtube.com/kmart",
      "LinkedIn" => "http://www.linkedin.com/company/kmart"
    ),
    "Kmart Resources" => array(
      "Store Finder" => "http://www.sears.com/shc/s/StoreLocatorView?storeId=10151&catalogId=10104&langId=-1&adCell=AF&adCell=WF&adCell=WF",
      "Gift Cards" => "http://www.sears.com/shc/s/p_10151_10104_6174302200000000P?adCell=WF&adCell=WF",
      "Pharmacy" => "    http://www.kmart.com/kmart-pharmacy-microsite/dap-100000000036522?adCell=WF",
      "Order History" => "https://www.kmart.com/shc/s/OrderHistoryNewView",
      "My Account" => "http://www.sears.com/shc/s/UserAccountView?storeId=10151&catalogId=10104&langId=-1",
      "Need help finding something?" => "http://www.kmart.com/personalshopper/nb-100000000214562?adCell=WF&adCell=WF"
    ),
    "Kmart Deals" => array(
      "Weekly Ad" => "http://kmart.shoplocal.com/kmart/new_user_entry.aspx?adref=homepage_footer",
      "Sign up for email savings!" => "http://www.kmart.com/emailsignup/nb-100000000005013?adCell=WF&adCell=WF",
      "Shop Your Way Rewards SM" => "https://www.shopyourwayrewards.com",
      "Coupon Center" => "http://www.kmart.com/coupons-inc/dap-100000000467002?adCell=WF",
      "Kmart Mobile" => "http://www.kmart.com/kmart-mobile/dap-100000000464003?adCell=WF"
    ),
    "About Kmart" => array(
      "Kmart Company Info" => "http://www.searsholdings.com/about/kmart",
      "Sears Holdings Corporation Info" => "http://www.searsholdings.com/",
      "Careers" => "http://www.searsholdings.com/careers",
      "FTC Gift Card Settlement" => "http://www.sears.com/shc/s/nb_10151_10104_NB_gift card settlement?adCell=WF",
      "Kmart Affiliate" => "http://www.kmart.com/affiliate-program/nb-100000000227511?adCell=W4"
    ),
    "Customer Service" => array(
      "Customer Service Home" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSHome",
      "Buy Online Pick Up In Store" => "http://www.sears.com/shc/s/nb_10151_10104_NB_Store%20Pickup?adCell=WF",
      "Shipping Information" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSship?adCell=WF&adCell=WF",
      "Contact Us" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSemail?adCell=WF&adCell=WF",
      "Return Policy" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSreturns?adCell=WF&adCell=WF",
      "Credit Cards" => "http://www.kmart.com/speed-bump/nb-100000000194510"
    )
  );
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
