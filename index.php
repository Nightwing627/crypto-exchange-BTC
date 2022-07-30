<?php
$page_name = "news";
require("./header.php");
$sql = "SELECT count(*) FROM `" . TABLE_ADS . "`";
$totalRecordsAds = $db->query_first($sql);
$totalRecordsAds = $totalRecordsAds["count(*)"];
$perPageAds = 10;
$totalPageAds = ceil($totalRecordsAds / $perPageAds);
if (isset($_GET["pageAds"])) {
    $pageAds = $db->escape($_GET["pageAds"]);
    if ($pageAds < 1) {
        $pageAds = 1;
    } else if ($pageAds > $totalPageAds) {
        $pageAds = 1;
    }
} else {
    $pageAds = 1;
}
$sql = "SELECT * FROM `" . TABLE_ADS . "` ORDER BY ad_time DESC,ad_id DESC LIMIT " . (($pageAds - 1) * $perPageAds) . "," . $perPageAds;
$recordsAds = $db->fetch_all_array($sql);

$sql = "SELECT count(*) FROM `" . TABLE_NEWS . "`";
$totalRecords = $db->query_first($sql);
$totalRecords = $totalRecords["count(*)"];
$perPage = 10;
$totalPage = ceil($totalRecords / $perPage);
if (isset($_GET["page"])) {
    $page = $db->escape($_GET["page"]);
    if ($page < 1) {
        $page = 1;
    } else if ($page > $totalPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
$sql = "SELECT " . TABLE_NEWS . ".*, " . TABLE_USERS . ".user_id, " . TABLE_USERS . ".user_name FROM `" . TABLE_NEWS . "` LEFT JOIN `" . TABLE_USERS . "` ON " . TABLE_NEWS . ".news_author = " . TABLE_USERS . ".user_id ORDER BY " . TABLE_NEWS . ".news_time  DESC," . TABLE_NEWS . ".news_id DESC LIMIT " . (($page - 1) * $perPage) . "," . $perPage;
$records = $db->fetch_all_array($sql);
?>
<div id="main" class="news" style="min-height: 400px;">
    <div id="logos-and-stats" >
       <!--  <img width="322" height="74" src="./images/we-accept-logos-btw.png" alt="We accept Bitcoin, Perfect Money, Paymer, Western Union, MoneyGram, WebMoney" /><br /> -->
             <!--  <form action="/billing/weaccept" method="get" id="weaccept" style="width: 322px; height: 74px;">
                    <input type="image" src="./images/we-accept-logos-btw.png" alt="We accept Bitcoin, Perfect Money, Paymer, Western Union, MoneyGram, WebMoney" style="width: 322px; height: 74px; border: 0 none; padding: 0;">
                </form>-->

        <div id="stats-under-logos">
            <!--            <h2>Dumps:</h2>
                        <ul class="simple-list">
                            <li style="list-style: url(./images/flags/us.gif) none;">United States:&nbsp;<strong>2413286</strong></li>                                                <li style="list-style: url(./images/flags/ww.gif) none;">World Wide:&nbsp;<strong>230298</strong></li>                                                <li style="list-style: url(./images/flags/ca.gif) none;">Canada:&nbsp;<strong>199614</strong></li>                                                <li style="list-style: url(./images/flags/br.gif) none;">Brazil:&nbsp;<strong>184348</strong></li>                                                <li style="list-style: url(./images/flags/gb.gif) none;">United Kingdom:&nbsp;<strong>98383</strong></li>                                                <li style="list-style: url(./images/flags/kr.gif) none;">Korea:&nbsp;<strong>51458</strong></li>                                                <li style="list-style: url(./images/flags/fr.gif) none;">France:&nbsp;<strong>24590</strong></li>                                            </ul>
                        <br>-->
            <h2>Cards:</h2>
            <ul class="simple-list">
                <?php
                $sql_counts = "SELECT country_name, counts, flag_name  FROM `" . TABLE_COUNTRIES . "` c order by counts desc";
                $counts_history = $db->fetch_all_array($sql_counts);


                if (count($counts_history) > 0) {
                    foreach ($counts_history as $key => $value) {
                        echo "<li style=\"list-style: url(./images/flags/" . $value['flag_name'] . ".gif) none;\">" . $value['country_name'] . ":&nbsp;<strong>" . $value['counts'] . "</strong></li>";
                    }
                }
                ?>
<!--                <li style="list-style: url(./images/flags/us.gif) none;">United States:&nbsp;<strong>466501</strong></li>
                <li style="list-style: url(./images/flags/ww.gif) none;">World Wide:&nbsp;<strong>38388</strong></li>
                <li style="list-style: url(./images/flags/au.gif) none;">Australia:&nbsp;<strong>9041</strong></li>
                <li style="list-style: url(./images/flags/br.gif) none;">Brazil:&nbsp;<strong>8869</strong></li>
                <li style="list-style: url(./images/flags/gb.gif) none;">United Kingdom:&nbsp;<strong>8272</strong></li>
                <li style="list-style: url(./images/flags/de.gif) none;">Germany:&nbsp;<strong>3869</strong></li> 
                <li style="list-style: url(./images/flags/ca.gif) none;">Canada:&nbsp;<strong>3417</strong></li>                                                <li style="list-style: url(./images/flags/tr.gif) none;">Turkey:&nbsp;<strong>2954</strong></li>                                                <li style="list-style: url(./images/flags/id.gif) none;">Indonesia:&nbsp;<strong>1903</strong></li>                                                <li style="list-style: url(./images/flags/nz.gif) none;">New Zealand:&nbsp;<strong>1032</strong></li>                                            -->
            </ul>
            <br>

            <!--
                        <h2>Bulks:</h2>
                        <ul class="simple-list">
                            <li style="list-style: square outside;">Dumps:&nbsp;<strong>18</strong></li>                                                <li style="list-style: square outside;">Cards:&nbsp;<strong>1</strong></li>                            </ul>-->
        </div>
    </div>
    <?php /*
      <h1>FAST ALERTS</h1>
      <?php
      if (count($recordsAds) > 0) {
      foreach ($recordsAds as $key => $value) {
      ?>

      <div>
      <h6>Posted <?= date("H:i:s d/M/Y", $value['ad_time']) ?> by <?= $value['user_name'] ?></h6>
      <p><font color="#FF0000"><?= $value['ad_title'] ?>:</font><br></p>
      <div class="bb"><?= $value['ad_content'] ?></div>
      </div>
      <hr class="span">
      <?php
      }
      } else {
      ?>
      <table class="table" width="100%" style="clear: left;">
      <tbody>
      <tr>
      <td class="ad_title">
      <span class="red">No record found.</span>
      </td>
      </tr>
      </tbody>
      </table>
      <?php
      }
      ?>

      <hr class="span">	<hr class="span">
      <div class="section_page_bar">
      <?php
      if ($totalRecords > 0) {
      echo "Page:";
      if ($page > 1) {
      echo "<a href=\"?page=" . ($page - 1) . "&pageAds=" . $pageAds . "\">&lt;</a>";
      echo "<a href=\"?page=1&pageAds=" . $pageAds . "\">1</a>";
      }
      if ($page > 3) {
      echo "...";
      }
      if (($page - 1) > 1) {
      echo "<a href=\"?page=" . ($page - 1) . "&pageAds=" . $pageAds . "\">" . ($page - 1) . "</a>";
      }
      echo "<input type=\"TEXT\" class=\"page_go\" value=\"" . $page . "\" onchange=\"window.location.href='?page='+this.value+'&pageAds=" . $pageAds . "'\"/>";
      if (($page + 1) < $totalPage) {
      echo "<a href=\"?page=" . ($page + 1) . "&pageAds=" . $pageAds . "\">" . ($page + 1) . "</a>";
      }
      if ($page < $totalPage - 2) {
      echo "...";
      }
      if ($page < $totalPage) {
      echo "<a href=\"?page=" . $totalPage . "&pageAds=" . $pageAds . "\">" . $totalPage . "</a>";
      echo "<a href=\"?page=" . ($page + 1) . "&pageAds=" . $pageAds . "\">&gt;</a>";
      }
      }
      ?>
      </div>

     */
    ?>
    <h1>NEWS</h1>
    <?php
    if (count($records) > 0) {
        foreach ($records as $key => $value) {
            ?>

            <div>
                <h6>Posted <?= date("d/M/Y", $value['news_time']) ?> <span class="bold"></h6>
                <p><font color="#FF0000"><?= $value['news_title'] ?>:</font><br></p>		
                <div class="bb"><?= $value['news_content'] ?></div>
            </div>
            <hr class="span">
            <?php
        }
    } else {
        ?>
        <table class="content_table">
            <tbody>
                <tr>
                    <td class="news_title">
                        <span class="red">No record found.</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>
<?php
require("./footer.php");
?>