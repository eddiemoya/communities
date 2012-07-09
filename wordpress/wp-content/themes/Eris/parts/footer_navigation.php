<?php
/**
 * @author Carl Albrecht-Buehler 
 */
  $a_navigation = array(
    "Accounts & Orders" => array(
      "Access My Account" => "https://www.sears.com/shc/s/UserAccountView?storeId=10153&amp;catalogId=12605&amp;langId=-1",
      "Check Gift Card Balance" => "http://www.sears.com/shc/s/nb_10153_12605_NB_gclanding",
      "Order Status" => "javascript:fnShowLoginModal('TrackOrderStatus','http://www.sears.com/shc/s/TrackOrderStatus?langId=-1&amp;storeId=10153&amp;catalogId=12605&amp;sso_in_param=fromLink%3DorderStatus&amp;adCell=WF')",
      "Order Status" => "http://www.sears.com/shc/s/dap_10153_12605_DAP_International Order Tracking?adCell=WF&amp;adCell=WF",
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
      "Children's Privacy Policy" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSchildprivacy",
      "License Info" => "http://www.sears.com/shc/s/nb_10153_12605_NB_License Information",
      "Payment Methods" => "javascript:popUpWin('http://www.sears.com/shc/s/nb_10153_12605_NB_PaymentMethod',770,500);",
      "Price Match Policy" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSpricematch?adCell=WF&adCell=WF",
      "Privacy Policy" => "http://www.sears.com/shc/s/nb_10153_12605_NB_CSprivacy"
    ), 
    "More Sears Sites" => array()
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
        <li><a href="<?php echo $nav_url ?>"><?php echo htmlentities( $subnav, ENT_QUOTES ); ?></a></li>
      <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</nav>
