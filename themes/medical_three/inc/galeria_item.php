<li class="col-md-4 col-sm-4 col-xs-6 <?= $gallery_name; ?>">
    <div class="content-image-block">
        <img itemprop="image" src="<?= BASE; ?>/uploads/<?= $gallery_file; ?>" title="<?= $gallery_image_legend; ?>"
             alt="<?= $gallery_file_legend; ?>">
        <div class="content-block-hover">
            <h5><?= $gallery_image_legend; ?></h5>
            <a class="zoom-in" href="<?= BASE; ?>/uploads/<?= $gallery_file; ?>">
                <i class="fa fa-search"></i></a>
        </div>
    </div>
</li>