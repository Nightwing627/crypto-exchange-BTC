
<div class="section_page_bar" style="text-align: center;">
    <?php
    if ($totalRecords > 0) {
//        echo "Page:";
        if ($page > 1) {
            echo "<a href=\"?" . $currentGet . "page=" . ($page - 1) . "\">&lt;</a>";
            echo "<a href=\"?" . $currentGet . "page=1\">1</a>";
        }
        if ($page > 3) {
            echo "...";
        }
        if (($page - 1) > 1) {
            echo "<a href=\"?" . $currentGet . "page=" . ($page - 1) . "\">" . ($page - 1) . "</a>";
        }
        echo "<a href=\"?" . $currentGet . "page=" . ($page) . "\">" . ($page) . "</a>";
//        echo "<input type=\"TEXT\" class=\"page_go\" value=\"" . $page . "\" onchange=\"window.location.href='?" . $currentGet . "page='+this.value\"/>";
        if (($page + 1) < $totalPage) {
            echo "<a href=\"?" . $currentGet . "page=" . ($page + 1) . "\">" . ($page + 1) . "</a>";
        }
        if ($page < $totalPage - 2) {
            echo "...";
        }
        if ($page < $totalPage) {
            echo "<a href=\"?" . $currentGet . "page=" . $totalPage . "\">" . $totalPage . "</a>";
            echo "<a href=\"?" . $currentGet . "page=" . ($page + 1) . "\">&gt;</a>";
        }
    }
    ?>
</div>