<?php
if (!$Read):
    $Read = new Read;
endif;

$Read->ExeRead(DB_POSTS, "WHERE post_name = :nm", "nm={$URL[1]}");
if (!$Read->getResult()):
    require REQUIRE_PATH . '/404.php';
    return;
else:
    extract($Read->getResult()[0]);
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    $Update = new Update;
    $UpdateView = ['post_views' => $post_views + 1, 'post_lastview' => date('Y-m-d H:i:s')];
    $Update->ExeUpdate(DB_POSTS, $UpdateView, "WHERE post_id = :id", "id={$post_id}");

    $Read->FullRead("SELECT category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_id = :id", "id={$post_category}");
    $CategoryTitle = $Read->getResult()[0]['category_title'];
    $CategoryName = $Read->getResult()[0]['category_name'];

    $Read->FullRead("SELECT user_name, user_lastname, user_content, user_thumb, user_facebook, user_instagram, user_google, user_youtube, user_linkedin FROM " . DB_USERS . " WHERE user_id = :user", "user={$post_author}");
    $AuthorName = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
endif;
?>

<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3><?= Check::Chars($post_title, 35); ?></h3>
                </div>
                <div class="banner-content">
                    <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" title="<?= SITE_NAME; ?>" href="<?= BASE; ?>">
                                <span itemprop="name">Home</span>
                            </a>
                            <meta itemprop="position" content="1" />
                        </li>

                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" title="Blog" href="<?= BASE; ?>/artigos">
                                <span itemprop="name">Blog</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>

                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active">
                            <a title="<?= $post_title; ?>">
                                <span itemprop="name"><?= Check::Chars($post_title, 20); ?></span>
                            </a>
                            <meta itemprop="position" content="3" />
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- Blog Right Sidebar -->
        <div class="latest-news blog-2column blog-right-sidebar container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Row -->
                <div class="row">
                    <!-- Content Area -->
                    <div itemscope itemtype="http://schema.org/Article" class="content-area col-md-8 col-sm-8 col-xs-12">
                        <div class="type-post">
                            <div style="display:none;">
                                <span itemprop="headline"><?= Check::Chars($post_subtitle, 110); ?></span>
                                <span itemprop="datePublished"><?= date('d/m/Y H:i', strtotime($post_date)); ?></span>
                                <div itemscope itemtype="http://schema.org/Organization" itemprop="publisher">
                                    <img itemprop="logo" src="<?= INCLUDE_PATH; ?>/images/logo.png" title="<?= SITE_NAME; ?>" alt="<?= SITE_NAME; ?>">
                                    <span itemprop="name"><?= SITE_NAME; ?></span>
                                </div>
                            </div>
                            <div class="entry-cover">
                                <a itemprop="url" title="<?= $post_title; ?>" href="<?= BASE;?>/artigo/<?= $post_name; ?>">
                                    <?php
                                    if ($post_video):
                                        echo "<div class='embed-container'>";
                                        echo "<iframe itemprop='video' id='mediaview' width='640' height='360' src='https://www.youtube.com/embed/{$post_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe>";
                                        echo "</div>";
                                    else:
                                        echo "<img itemprop='image' title='{$post_title}' alt='{$post_title}' src='" . BASE . "/tim.php?src=uploads/" . $post_cover . "&w=" .  IMAGE_W . "&h=" . IMAGE_H . "''/>";
                                    endif;
                                    ?>
                                </a>
                                <div class="post-date-bg">
                                    <div class="post-date">
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
                                        <div itemprop="dateCreated"  class="post-time">
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
                                    <p itemprop="articleBody"><?= $post_content; ?></p>
                                </div>

                                <h3 class="entry-title-comment">Comentários</h3>
                                <div class="row">
                                    <section id="comments_section" class="section_border_top">
                                        <div id="comments">
                                            <div class="fb-comments" data-href="<?= BASE; ?>/artigo/<?= $post_name; ?>" data-width="100%" data-numposts="5"></div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Content Area /- -->
                    <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
                </div> <!-- Row /- -->
            </div> <!-- Container /- -->
        </div> <!-- Blog Right Sidebar /- -->
    </main>
</div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = 'https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.12&appId=<?= SITE_SOCIAL_FB_APP; ?>';
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
