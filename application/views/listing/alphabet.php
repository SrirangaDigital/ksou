<div class="container dynamic-page">
	<div class="row">
		<!-- Column 1 -->
		<div class="col-md-12 text-center">
			<ul class="list-unstyled alphabet">
				<li><a href="<?=BASE_URL?>listing/alphabet/A">A</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/B">B</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/C">C</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/D">D</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/E">E</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/F">F</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/G">G</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/H">H</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/I">I</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/J">J</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/K">K</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/L">L</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/M">M</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/N">N</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/O">O</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/P">P</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/R">R</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/S">S</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/T">T</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/U">U</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/V">V</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/W">W</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/Y">Y</a></li>
				<li><a href="<?=BASE_URL?>listing/alphabet/Z">Z</a></li>
            </ul>
		</div>
	</div>
	<div class="row">
        <div class="col-md-12 multi-column word-list">
<?php foreach ($data as $row) { ?>
            <a href="<?=BASE_URL?>describe/word/<?= $row->word ?>"><?= $row->word ?></a>
<?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	var columnCount = 2;
	var width = $(document).outerWidth();
	if (width > 1200) columnCount = 6;
	else if (width > 992) columnCount = 5;
	else if (width > 768) columnCount = 4;
	else if (width > 576) columnCount = 3;

	$('.multi-column').columnize({ columns: columnCount });
});
</script>