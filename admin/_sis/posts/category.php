<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

$CatId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($CatId):
    $Read->ExeRead(DB_CATEGORIES, "WHERE category_id = :id", "id={$CatId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Uma Categoria Que Não Existe ou Que Foi Removida Recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=posts/categories');
        exit;
    endif;
else:
    $Date = date('Y-m-d H:i:s');
    $Title = "Nova Categoria - {$Date}";
    $Name = Check::Name($Title);
    $CatCreate = ['category_name' => $Name, 'category_date' => $Date];
    $Create->ExeCreate(DB_CATEGORIES, $CatCreate);
    header('Location: dashboard.php?wc=posts/category&id=' . $Create->getResult());
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags"><?= $category_title ? $category_title : 'Nova Categoria'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/home">Posts</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/categories">Categorias</a>
            <span class="crumb">/</span>
            Gerenciar Categoria
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Categorias" href="dashboard.php?wc=posts/categories" class="btn_header btn_aquablue icon-eye">Ver Categorias</a>
        <a title="Nova Categoria" href="dashboard.php?wc=posts/category" class="btn_header btn_darkaquablue icon-plus">Nova Categoria</a>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box100">

        <div class="panel_header darkaquablue">
            <h2 class="icon-price-tags">Dados Sobre a Categoria</h2>
        </div>

        <div class="panel">
            <form class="auto_save" name="category_add" action="" method="post" enctype="multipart/form-data">
                <div class="callback_return"></div>
                <input type="hidden" name="callback" value="Posts"/>
                <input type="hidden" name="callback_action" value="category_add"/>
                <input type="hidden" name="category_id" value="<?= $CatId; ?>"/>
                
                <label class="label">
                    <span class="legend">Nome:</span>
                    <input style="font-size: 1.2em;" type="text" name="category_title" value="<?= $category_title; ?>" placeholder="Título da Categoria" required/>
                </label>
                
                <label class="label">
                    <span class="legend">Descrição:</span>
                    <textarea style="font-size: 1em;" name="category_content" rows="3" placeholder="Sobre a Categoria" required><?= $category_content; ?></textarea>
                </label>

                <div class="category_selection">
                    <span class="category_selection_legend">Setor:</span>
                    <span class="category_selection_title j_open_category_selection">
                        <?php
                        if (empty($category_parent)):
                            echo 'Esse é Um Setor!';
                        else:
                            $Read->LinkResult(DB_CATEGORIES, 'category_id', $category_parent, 'category_title');
                            echo $Read->getResult()[0]['category_title'];
                        endif;
                        ?>
                    </span>

                    <div class="category_selection_content j_category_selection_content">
                        <?php
                        $Read->FullRead("SELECT category_id, category_parent, category_tree, category_title FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL ORDER BY category_title ASC");

                        function loopCat() {
                            global $Read, $category_id, $category_parent;
                            if ($Read->getResult()):
                                foreach ($Read->getResult() as $CAT):
                                    $Read->FullRead("SELECT category_id, category_parent, category_tree, category_title FROM " . DB_CATEGORIES . " WHERE category_parent = :parent ORDER BY category_title ASC", "parent={$CAT['category_id']}");

                                    echo "<li>";
                                    echo "<span>";
                                    echo "<i class='" . ($Read->getResult() ? "icon-plus icon-notext" : "icon-minus icon-notext") . "'></i>";
                                    echo "<input id='radio-category-{$CAT['category_id']}' class='j_category_selection' type='radio' name='category_parent' value='{$CAT['category_id']}' data-title='{$CAT['category_title']}'" . ($CAT['category_id'] == $category_id || $CAT['category_parent'] == $category_id || in_array($category_id, explode(',', $CAT['category_tree'])) ? ' disabled="disabled"' : '') . ($CAT['category_id'] == $category_parent ? ' checked="checked"' : '') . "/>";
                                    echo "<label" . ($CAT['category_id'] == $category_id || $CAT['category_parent'] == $category_id || in_array($category_id, explode(',', $CAT['category_tree'])) ? ' class="disabled"' : '') . " for='radio-category-{$CAT['category_id']}'>{$CAT['category_title']}</label>";
                                    echo "</span>";

                                    if ($Read->getResult()):
                                        echo "<ul>";
                                        loopCat();
                                        echo "</ul>";
                                    endif;
                                    echo "</li>";
                                endforeach;
                            endif;
                        }

                        echo "<ul>";
                        echo "<li>";
                        echo "<span>";
                        echo "<i class='icon-plus icon-notext'></i>";
                        echo "<input id='radio-category-0' class='j_category_selection' type='radio' name='category_parent' value='' data-title='Esse é Um Setor!'" . (empty($category_parent) ? ' checked="checked"' : '') . "/>";
                        echo "<label for='radio-category-0'>Esse é Um Setor!</label>";
                        echo "</span>";
                        echo "</li>";
                        loopCat();
                        echo "</ul>";
                        ?>
                    </div>
                </div>
                
                <button title="ATUALIZAR" class="btn_big btn_aquablue icon-share fl_right">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>
