<!-- Widget Area -->
<aside itemscope itemtype="http://schema.org/WPSideBar" id="" class="widget-area col-md-4 col-sm-4 col-xs-12">
    <!-- Widget Search -->
    <div id="search" class="widget widget_search">
        <h3 class="widget-title">Pesquisar</h3>
        <form class="search" name="search" action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="text" name="s" value="Pesquisar..." class="form-control" required>
                <span class="input-group-btn">
                    <button class="btn btn-search" title="Pesquisar" type="button"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
    </div>
    <!-- Widget Search /- -->

    <!-- Widget Accordion -->
    <div id="widget_accordion" class="widget widget_accordion">
        <h3 class="widget-title">Categorias</h3>
        <div class="accordion-box">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <?php
                    $Read->ExeRead(DB_CATEGORIES, "WHERE category_parent IS NULL AND category_id IN(SELECT post_category FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC");
                    if (!$Read->getResult()):
                        echo Erro("Ainda NПлкo Existe Categorias Cadastradas!", E_USER_NOTICE);
                    else:
                        $i = 1;
                        foreach ($Read->getResult() as $Ses):
                            $Read->FullRead('SELECT COUNT(post_category) AS total FROM ' . DB_POSTS . ' WHERE post_category = :id', "id={$Ses['category_id']}");
                            $totalPosts = (!empty($Read->getResult()) && $Read->getResult()[0]['total'] >= 1 ? $Read->getResult()[0]['total'] : 0);
                            $Read->ExeRead(DB_CATEGORIES, "WHERE category_parent = :pr AND category_id IN(SELECT post_category_parent FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC", "pr={$Ses['category_id']}");
                            ?>
                            <div class="panel-heading" role="tab" id="faqheading<?= $i; ?>">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse"
                                       data-parent="#accordion" href="#faqcontent<?= $i; ?>" aria-expanded="true">
                                        <?= $Ses['category_title']; ?>
                                    </a>
                                </h4>
                            </div>
                            <?php
                            if ($Read->getResult()):
                                foreach ($Read->getResult() as $Cat):
                                    $Read->FullRead('SELECT COUNT(post_category_parent) AS total FROM ' . DB_POSTS . ' WHERE post_category_parent = :id', "id={$Cat['category_id']}");
                                    $totalPosts = (!empty($Read->getResult()) && $Read->getResult()[0]['total'] >= 1 ? $Read->getResult()[0]['total'] : 0);
                                    ?>
                                    <div id="faqcontent<?= $i; ?>" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="faqheading<?= $i; ?>">
                                        <div class="panel-body">
                                            <p><a title="<?= $Cat['category_title']; ?>" href="<?= BASE; ?>/categorias/<?= $Cat['category_name']; ?>"> <?= $Cat['category_title']; ?> (<?= $totalPosts; ?>)</a></p>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                            endif;
                            $i++;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div><!-- Widget Accordion -->

    <!-- Widget Latest Posts -->
    <div id="widget_latestposts" class="widget widget_latestposts">
        <div class="latest-detail-tab">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs wc-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#recent" role="tab" data-toggle="tab">Recentes</a></li>
                <li role="presentation">
                    <a href="#popular" role="tab" data-toggle="tab">Mais Vistos</a>
                </li>
            </ul>

            <!-- Tab Panes -->
            <div itemscope itemtype="http://schema.org/Blog" class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="recent">
                    <?php
                    $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_date DESC LIMIT 3");
                    if (!$Read->getResult()):
                        echo Erro("Ainda NПлкo Existem Posts Cadastrados! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Posts):
                            extract($Posts);
                            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                            date_default_timezone_set('America/Sao_Paulo');
                            ?>
                            <div class="latest-content">
                                <a href="#">
                                    <i>
                                        <img itemprop="image" src="<?= BASE; ?>/tim.php?src=uploads/<?= $post_cover; ?>&w=100&h=100" title="<?= $post_title; ?>" alt="<?= $post_title; ?>">
                                    </i>
                                </a>
                                <h5>
                                    <a itemprop="url" title="<?= $post_title; ?>" href="<?= BASE; ?>/artigo/<?= $post_name; ?>">
                                        <span itemprop="name"><?= $post_title; ?></span>
                                    </a>
                                </h5>
                                <span itemprop="dateCreated" class="date">
                                    <a>
                                        <i class="fa fa-calendar-o"></i>
                                        <?= date('d', strtotime($post_date)); ?> de
                                        <?= utf8_encode(strftime('%B', strtotime($post_date))); ?> de
                                        <?= date('Y', strtotime($post_date)); ?>
                                    </a>
                                </span>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <div itemscope itemtype="http://schema.org/Blog" role="tabpanel" class="tab-pane" id="popular">
                    <?php
                    $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_views ASC LIMIT 3");
                    if (!$Read->getResult()):
                        echo Erro("Ainda NПлкo Existem Posts Cadastrados! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Posts):
                            extract($Posts);
                            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                            date_default_timezone_set('America/Sao_Paulo');
                            ?>
                            <div class="latest-content">
                                <a itemprop="url" title="<?= $post_title; ?>" href="<?= BASE; ?>/artigo/<?= $post_name; ?>">
                                    <i>
                                        <img itemprop="image" src="<?= BASE; ?>/tim.php?src=uploads/<?= $post_cover; ?>&w=100&h=100" title="<?= $post_title; ?>" alt="<?= $post_title; ?>">
                                    </i>
                                </a>
                                <h5>
                                    <a itemprop="url" title="<?= $post_title; ?>" href="<?= BASE; ?>/artigo/<?= $post_name; ?>">
                                        <span itemprop="name"><?= $post_title; ?></span>
                                    </a>
                                </h5>
                                <span itemprop="dateCreated" class="date">
                                    <a>
                                        <i class="fa fa-calendar-o"></i>
                                        <?= date('d', strtotime($post_date)); ?> de
                                        <?= utf8_encode(strftime('%B', strtotime($post_date))); ?> de
                                        <?= date('Y', strtotime($post_date)); ?>
                                    </a>
                                </span>
                            </div>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Tab Panes /- -->
        </div>
    </div>
    <!-- Widget Latest Posts /- -->

    <!-- Widget Tag Cloud -->
    <div id="tag_cloud" class="widget widget_tag_cloud">
        <h3 class="widget-title">Links</h3>
        <div class="tagcloud">
            <a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>">Home</a>
            <a href="<?= BASE; ?>/sobre" title="Sobre">Sobre</a>
            <a href="<?= BASE; ?>/especialidades" title="Especialidades">Especialidades</a>
            <a href="<?= BASE; ?>/medicos" title="Médicos">Médicos</a>
            <a href="<?= BASE; ?>/galeria" title="Galeria">Galeria</a>
            <a href="<?= BASE; ?>/artigos" title="Blog">Blog</a>
            <a href="<?= BASE; ?>/contato" title="Contato">Contato</a>
        </div>
    </div>
    <!-- Widget Tag Cloud /- -->
</aside><!-- Widget Area /- -->