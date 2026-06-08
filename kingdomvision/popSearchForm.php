<?php

echo '<div class="popSearchBox">';
    echo '<a href="javascript:;" class="close"><i class="fa fa-times" aria-hidden="true"></i></a>';
    echo '<div class="inner_wrapper">';
        echo '<h2>search</h2>';
        echo '<form method="get" id="searchform" action="'.home_url().'/">';
            echo '<div class="search-text">';
                echo '<input type="text" placeholder="Search" value="'.get_search_query().'" name="s" id="s" autocomplete="off" />';
                echo '<input type="submit" id="searchsubmit" value="" />';
            echo '</div>';
        echo '</form>';
    echo '</div>';
echo '</div>';

?>