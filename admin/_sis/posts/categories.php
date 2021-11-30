<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!
</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_CATEGORIES, "WHERE category_title IS NULL AND category_parent IS NULL AND category_id >= :st", "st=1");
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags">Categorias</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/home">Posts</a>
            <span class="crumb">/</span>
            Categorias
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Nova Categoria" href="dashboard.php?wc=posts/category" class="btn_header btn_darkaquablue icon-plus">Nova Categoria</a>
    </div>

</header>

<div class="dashboard_content">
    <?php
    $Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL ORDER BY category_title ASC");
    if (!$Read->getResult()):
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Categorias Cadastradas. Comece Agora Mesmo Cadastrando a Primeira Categoria!</span>", E_USER_NOTICE);
    else:

    function loopCat() {
        global $Read;
        if ($Read->getResult()):
            foreach ($Read->getResult() as $CAT):
                $Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent = :parent ORDER BY category_title ASC", "parent={$CAT['category_id']}");

                echo "<li class='single_category_item js-rel-to' id={$CAT['category_id']}>";
                echo "<div class='j_cat_open_end_close'>";
                echo "<div>" . ($Read->getResult() ? "<i class='icon-plus icon-notext'></i>" : "<i class='icon-minus icon-notext'></i>") . "</div>";
                echo "<div>{$CAT['category_title']}</div>";
                echo "<div>";
                echo "<a title='Editar Categoria' href='dashboard.php?wc=posts/category&id={$CAT['category_id']}' class='btn_header btn_darkaquablue icon-pencil icon-notext'></a> ";
                echo "<span title='Excluir Categoria' rel='single_category_item' callback='Posts' callback_action='category_remove' class='j_delete_action btn_header btn_red icon-bin icon-notext' id='{$CAT['category_id']}'></span>";
                echo "</div>";
                echo "</div>";

                if ($Read->getResult()):
                    echo "<ul>";
                    loopCat();
                    echo "</ul>";
                endif;
                echo "</li>";
            endforeach;
        endif;
    }

    echo "<ul class='single_category'>";
    loopCat();
    echo "</ul>";
    endif;
    ?>
</div>