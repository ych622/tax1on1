× close edit hex	TEXT viewer: /tax1on1.org/wp-content/themes/atahualpa/search.php

<?php

/*

Template Name: cse

*/

?>

<?php get_header(); ?>



<div id="cse" style="width: 100%;">Loading</div>

<script src="http://www.google.com/jsapi" type="text/javascript"></script>

<script type="text/javascript">

  google.load('search', '1', {language : 'zh-CN'});

  google.setOnLoadCallback(function(){

        var customSearchControl = new google.search.CustomSearchControl('007697786490199755787:w5kk2z2fj-0');

        customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);

        customSearchControl.draw('cse');

        var match = location.search.match(/q=([^&]*)(&|$)/);

        if(match && match[1]){

            var search = decodeURIComponent(match[1]);            



            customSearchControl.execute(search);

        }

    });

</script>

<link rel="stylesheet" href="http://tax1on1.org/wp-content/themes/atahualpa/minimalist.css" type="text/css" />


<?php phpinfo(); ?>
<?php get_footer(); ?>
