<?php
$Search = urldecode($URL[1]);
$SearchPage = urlencode($Search);

if (empty($_SESSION['search']) || !in_array($Search, $_SESSION['search'])):
    $Read->FullRead("SELECT search_id, search_count FROM " . DB_SEARCH . " WHERE search_key = :key", "key={$Search}");
    if ($Read->getResult()):
        $Update = new Update;
        $DataSearch = ['search_count' => $Read->getResult()[0]['search_count'] + 1];
        $Update->ExeUpdate(DB_SEARCH, $DataSearch, "WHERE search_id = :id", "id={$Read->getResult()[0]['search_id']}");
    else:
        $Create = new Create;
        $DataSearch = ['search_key' => $Search, 'search_count' => 1, 'search_date' => date('Y-m-d H:i:s'), 'search_commit' => date('Y-m-d H:i:s')];
        $Create->ExeCreate(DB_SEARCH, $DataSearch);
    endif;
    $_SESSION['search'][] = $Search;
endif;
?>

<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Blog</h3>
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
                            <a title="<?= $Search; ?>">
                                <span itemprop="name"><?= Check::Chars($Search, 25); ?></span>
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
                <div itemscope itemtype="http://schema.org/Blog" class="row">
                    <!-- Content Area -->
                    <div class="content-area col-md-8 col-sm-8 col-xs-12">
                        <?php
                        $Page = (!empty($URL[1]) ? $URL[1] : 1);
                        $Pager = new Pager(BASE . "/artigos/", "<i class='fa fa-angle-double-left'></i>", "<i class='fa fa-angle-double-right'></i>", 2);
                        $Pager->ExePager($Page, 6);
                        $Read->FullRead(
                            "SELECT "
                            . "p.post_cover, "
                            . "p.post_title, "
                            . "p.post_name, "
                            . "p.post_content, "
                            . "p.post_tags, "
                            . "p.post_video, "
                            . "p.post_author, "
                            . "p.post_views, "
                            . "p.post_date, "
                            . "c.category_title, "
                            . "c.category_name "
                            . "FROM " . DB_POSTS . " p "
                            . "INNER JOIN " . DB_CATEGORIES . " c ON c.category_id = p.post_category "
                            . "WHERE p.post_status = 1 "
                            . "AND (post_title LIKE '%' :s '%' OR post_subtitle LIKE '%' :s '%')"
                            . "AND p.post_date <= NOW()"
                            . "ORDER BY post_date DESC "
                            . "LIMIT :limit OFFSET :offset ", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&s={$Search}"
                        );
                        if (!$Read->getResult()):
                            echo Erro("Ainda Não Existe Posts Cadastrados! :)", E_USER_NOTICE);
                        else:
                            foreach ($Read->getResult() as $Posts):
                                extract($Posts);
                                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                date_default_timezone_set('America/Sao_Paulo');

                                $Read->ExeRead(DB_USERS, "WHERE user_id = :pa", "pa={$post_author}");
                                $AuthorName = $Read->getResult() ? $Read->getResult()[0]['user_name'] . " " . $Read->getResult()[0]['user_lastname'] : null;

                                require REQUIRE_PATH . '/inc/blog_item.php';
                            endforeach;
                        endif;
                        ?>

                        <nav>
                            <?php
                            $Pager->ExePaginator(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND (post_title LIKE '%' :s '%' OR post_subtitle LIKE '%' :s '%')", "s={$Search}");
                            echo $Pager->getPaginator();
                            ?>
                        </nav>
                    </div> <!-- Content Area /- -->

                    <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
                </div> <!-- Row /- -->
            </div> <!-- Container /- -->
        </div> <!-- Blog Right Sidebar /- -->
    </main>
</div>

