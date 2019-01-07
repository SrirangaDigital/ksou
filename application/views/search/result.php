<?php 
$data['match'] = array_merge($data['exactMatch'], $data['partialMatch']);
$searchText = $data['word'];
$searchTextRegex = $viewHelper->getRegexText($searchText);
?>
<div class="container dynamic-page">
    <div class="row justify-content-start">
        <div class="col-md-12 resultSeparator">
            <p>Word matches (<?=sizeof($data['match'])?>)</p>
            <span id="searchText" class="hide"><?=$searchTextRegex?></span>
        </div>
<?php foreach ($data['match'] as $row) { ?>
        <div class="col-md-6">
            <div class="word search">
                <?php if($row['word']) { ?><h1 class="head-word"><a href="<?=BASE_URL?>describe/word/<?=$row['word']?>"><?=$viewHelper->highlight($row['word'], $searchText)?></a></h1><?php } ?>
                <?php if($row['wordNote']) { ?><h3 class="head-word-note"><?=$row['wordNote']?></h3><?php } ?>
                <p class="see-more"><a href="<?=BASE_URL?>describe/word/<?=$row['word']?>">More...</a></p>      
            </div>
        </div>
<?php } ?>
    </div>
    <div class="row justify-content-start">
        <div class="col-md-12 resultSeparator">
            <p>In-description matches (<?=sizeof($data['descriptionMatch'])?>)</p>
        </div>
<?php foreach ($data['descriptionMatch'] as $row) { ?>
        <div class="col-md-12">
            <div class="word search in-description">
                <?php if($row['word']) { ?><h1 class="head-word"><a href="<?=BASE_URL?>describe/word/<?=$row['word']?>"><?=$row['word']?></a></h1><?php } ?>
                <?php if($row['wordNote']) { ?><h3 class="head-word-note"><?=$row['wordNote']?></h3><?php } ?>
                <?php if($row['description']) { ?><div class="description"><?=$row['description']?></div><?php } ?>
                <p class="see-more"><a href="<?=BASE_URL?>describe/word/<?=$row['word']?>?search=<?=$searchTextRegex?>">More...</a></p>      
            </div>
        </div>
<?php } ?>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    var searchText = $('#searchText').html();

    $('.word.search.in-description .description').each(function(){

        var html = $(this).html();

        var re = new RegExp('(' + searchText + ')', "gi");
        html = html.replace(re, '<span class="highlight">' + "$1" + '</span>');

        $(this).html(html);
    });
});
</script>