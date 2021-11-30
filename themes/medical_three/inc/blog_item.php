<div class="type-post">
    <div class="entry-cover">
        <a itemprop="url" title="<?= $post_title; ?>" href="<?= BASE;?>/artigo/<?= $post_name; ?>">
            <img itemprop="image" src="<?= BASE; ?>/tim.php?src=uploads/<?= $post_cover; ?>&w=<?= IMAGE_W ;?>&h=<?= IMAGE_H; ?>" title="<?= $post_title; ?>" alt="<?= $post_title; ?>" />
        </a>
        <div class="post-date-bg">
            <div itemprop="dateCreated" class="post-date">
                <?= date('d', strtotime($post_date)); ?>
                <span><?= utf8_encode(strftime('%B', strtotime($post_date))); ?></span>
            </div>
        </div>
    </div>
    <div class="latest-news-content">
        <div class="entry-header">
            <h3 class="entry-title">
                <a itemprop="url" title="<?= $post_title; ?>" href="<?= BASE;?>/artigo/<?= $post_name; ?>">
                    <span itemprop="name"><?= $post_title; ?></span>
                </a>
            </h3>
            <div class="entry-meta">
                <div class="byline">
                    <a href="#" title="Por: <?= $AuthorName; ?>">
                        <i class="fa fa-user-o"></i>
                        Por: <span itemscope itemtype="http://schema.org/Person" itemprop="author">
                        <span itemprop="name"> <?= $AuthorName; ?></span></span>
                    </a>
                </div>
                <div class="post-time">
                    <a href="#" title="<?= date('d/m/Y', strtotime($post_date)); ?>">
                        <i class="fa fa-calendar"></i>
                        <?= date('d/m/Y', strtotime($post_date)); ?>
                    </a>
                </div>
                <div class="post-comment">
                    <a href="#" title="<?= $post_views; ?> Visualizações">
                        <i class="fa fa-eye"></i>
                        <?= $post_views; ?> Visualizações
                    </a>
                </div>
            </div>
        </div>
        <div class="entry-content">
            <p itemprop="description"><?= Check::Words($post_content, 45); ?></p>
        </div>
        <a itemprop="url" title="Ver Mais" href="<?= BASE;?>/artigo/<?= $post_name; ?>" class="read-more">
            VER MAIS
        </a>
    </div>
</div>