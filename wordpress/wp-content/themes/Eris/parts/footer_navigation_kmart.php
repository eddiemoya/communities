<?php
  # @author Carl Albrecht-Buehler 
  # Create an array of all the Sears footer links
  # Scraped from Kmart.com July 16, 2012
  $a_navigation = array(
    "Kmart Community" => array(
      "Facebook" => "http://www.facebook.com/kmart",
      "Twitter" => "http://www.twitter.com/kmartdeals",
      "YouTube" => "http://www.youtube.com/kmart",
      "LinkedIn" => "http://www.linkedin/com/companies/kmart"
    ),
    "Kmart Resources" => array(
      "Store Finder" => "http://www.sears.com/shc/s/StoreLocatorView?storeId=10151&catalogId=10104&langId=-1&adCell=AF&adCell=WF&adCell=WF",
      "Gift Cards" => "http://www.sears.com/shc/s/p_10151_10104_6174302200000000P?adCell=WF&adCell=WF",
      "Pharmacy" => "http://www.sears.com/shc/s/dap_10151_10104_DAP_Kmart+Pharmacy+Microsite?adCell=WF",
      "Order History" => "http://www.sears.com/shc/s/UPOrderHistoryView?langId=-1&storeId=10151&catalogId=10104&adCell=WF",
      "My Account" => "http://www.sears.com/shc/s/UserAccountView?storeId=10151&catalogId=10104&langId=-1",
      "Need help finding something?" => "http://www.sears.com/shc/s/nb_10151_10104_NB_personalShopper?adCell=WF&adCell=WF"
      
    ),
    "Kmart Deals" => array(
      "Weekly Ad" => "http://kmart.shoplocal.com/kmart/new_user_entry.aspx?adref=homepage_footer",
      "Sign up for email savings!" => "http://www.sears.com/s/nb_10151_10104_NB_EmailSignup?adCell=WF&adCell=WF",
      "Shop Your Way Rewards SM" => "https://www.shopyourwayrewards.com",
      "Coupon Center" => "http://www.sears.com/s/dap_10151_10104_DAP_Coupons+Inc?adCell=WF",
      "Kmart Mobile" => "http://www.sears.com/s/dap_10151_10104_DAP_Kmart+Mobile?adCell=WF"
    ),
    "About Kmart" => array(
      "Kmart Company Info" => "http://www.searsholdings.com/about/kmart",
      "Sears Holdings Corporation Info" => "http://www.searsholdings.com/",
      "Careers" => "http://www.searsholdings.com/careers",
      "FTC Gift Card Settlement" => "http://www.sears.com/shc/s/nb_10151_10104_NB_gift card settlement?adCell=WF",
      "Kmart Affiliate" => "http://www.sears.com/shc/s/nb_10151_10104_NB_Affiliate+Program?adCell=W4"
    ),
    "Customer Service" => array(
      "Customer Service Home" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSHome",
      "Buy Online Pick Up In Store" => "http://www.sears.com/shc/s/nb_10151_10104_NB_Store%20Pickup?adCell=WF",
      "Shipping Information" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSship?adCell=WF&adCell=WF",
      "Contact Us" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSemail?adCell=WF&adCell=WF",
      "Return Policy" => "http://www.sears.com/shc/s/nb_10151_10104_NB_CSreturns?adCell=WF&adCell=WF",
      "Credit Cards" => "http://www.sears.com/shc/s/nb_10151_10104_NB_speed+bump"
    )
  );
?>
<nav id="footer_navigation">
  <ul id="footer_nav" class="dropmenu clearfix">
    <?php foreach ( $a_navigation as $nav_item => $a_subnav ): ?>
    <li>
      <a href="#"><span><?php echo htmlentities( $nav_item, ENT_QUOTES ); ?></span></a>
      <?php if ( !empty( $a_subnav ) ): ?>
      <ul>
      <?php foreach ( $a_subnav as $subnav => $nav_url ): ?>
        <li><a href="<?php echo $nav_url ?>" rel="nofollow"><?php echo htmlentities( $subnav, ENT_QUOTES ); ?></a></li>
      <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</nav>
