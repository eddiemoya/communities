<?php
    /**
    * @package WordPress
    * @subpackage Eris
    */ 

// !!! Add conditional check to make sure that this page
// is only rendered when profile_type = 'myprofile'


    get_template_part('parts/header');

    loop();

    $current_tab = "About Me";
    // $current_tab = "Community Activity";
    $current_nav = "1";
    $birthday = 323395200;

    $seconds_in_day = 24 * 60 * 60;

    $actions = array( "", "Asked", "Answered", "Commented", "Followed", "Voted", "Wrote" );

    $categories = array( "Appliance", "Kitchen", "Garden", "Automotive", "Industrial Machinery" );

    $user = array(
        "screenname" => "optimus123",
        "first_name" => "Optimus",
        "last_name" => "Prime",
        "city" => "Chicago",
        "state" => "IL",
        "zip" => 60640,
        "email" => "optimus@autobots.com",
        "birthdate" => 323395200
    );

    # format the address
        $a_address = array();
    $i = 0;
    $address = '&nbsp;';
    if ( $user["city"] != '' )  { $a_address[] = $user["city"]; }
    if ( $user["state"] != '' ) { $a_address[] = $user["state"]; }
    if ( $user["zip"] != '' )   { $a_address[] = $user["zip"]; }
    if ( !empty( $a_address ) ) {
        $address = '';
        while ( $i < count( $a_address ) ) {
            $address .= $a_address[ $i ];
            # add a comma after the first address part if there is a second one
            if ( ( $i == 0 ) && ( count( $a_address ) > 1 ) ) { $address .= ','; }
            $address .= ' ';
            $i++;
        }
    }
?>

    <section class="span8 profile">

        <?php include('parts/profile_nav.php'); ?>

        <section class="personal_info clearfix">
            <div class="span3 badge">
                <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
                <a href="#">Upload a photo</a>
            </div>
            <div class="span9 info">
                <h4>Optimus Prime</h4>
            </div>
            <dl class="span12 vitals">
                <dt>Screen Name</dt>
                <dd><?php echo $user["screenname"] != '' ? $user["screenname"] : '&nbsp;'; ?></dd>
                <dt>Email</dt>
                <dd><?php echo $user["email"] != '' ? $user["email"] : '&nbsp;'; ?></dd>
                <dt>Location</dt>
                <dd><address><?php echo $address; ?></address></dd>
                <dt>Birthday</dt>
                <dd><time class="content-date" datetime="<?php echo date( "Y-m-d", $user["birthdate"] ); ?>" pubdate="pubdate"><?php echo date( "F j, Y", $user["birthdate"] ); ?></time></dd>
            </dl>
        </section>

    </section>

    <section class="span4">
        <div style="background: orange;">&nbsp;</div>
    </section>

<?php
    get_template_part('parts/footer');