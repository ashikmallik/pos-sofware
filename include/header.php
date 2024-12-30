<script>
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML =
            h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }
        ;  // add zero in front of numbers < 10
        return i;
    }
</script>
<div class="row">

    <div class="col-md-4" style="padding-top:10px; color:#FFFFFF; font-size:20px; font-family:Lucida Sans Unicode; letter-spacing:1px;">
        <div style="float:left;">
            <img title="Visit Site" alt="Our logo" src="./logo.jpg" width="100" height="40">
        </div>
    </div>

    <div class="col-md-3" style="color:#CCCCCC; font-size:15px; font-family:Lucida Sans Unicode; text-align:right;">
        <h2 style="margin-top:12px !important;" id="clock"></h2>
    </div>
    <div class="col-md-2" style="padding:15px 10px 15px 15px; color:#CCCCCC; font-size:15px; font-family:Lucida Sans Unicode; text-align:right;"> Welcome : <?php echo $_SESSION['FullName']; ?>
    </div>

    <div class="col-md-2" style="padding:15px 20px 15px 15px; font-family:Lucida Sans Unicode; text-align:right; font-family: Lucida Sans Unicode;">
        <a id="logout" style="background:url(asset/img/logout.png) left center no-repeat; padding-left:20px; font-size:11px; color:#FFFFFF;" href="include/logout.php">
            <b>Log Out</b>
        </a>
    </div>
</div>