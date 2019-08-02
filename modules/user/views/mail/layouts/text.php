<?php

/*
 * 
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * .md
 * 
 */

/**
 * @var string $content main view render result
 */
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>
