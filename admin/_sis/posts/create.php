<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Esta Logado<br>ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

$PostId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PostId):
    $Read->ExeRead(DB_POSTS, "WHERE post_id = :id", "id={$PostId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Post Que Não Existe ou Que Foi Removido Recentemente!";
        header('Location: dashboard.php?wc=posts/home');
    endif;
else:
    $PostCreate = ['post_date' => date('Y-m-d H:i:s'), 'post_type' => 'post', 'post_status' => 0, 'post_author' => $Admin['user_id']];
    $Create->ExeCreate(DB_POSTS, $PostCreate);
    header('Location: dashboard.php?wc=posts/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-new-tab"><?= $post_title ? $post_title : "Novo Post"; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/home">Posts</a>
            <span class="crumb">/</span>
            Gerenciar Post
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver Artigo No Site" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" class="wc_view btn_header btn_darkaquablue icon-eye">Ver Artigo No Site</a>
    </div>
</header>

<div class="workcontrol_imageupload none" id="post_control">
    <div class="workcontrol_imageupload_content">
        <form name="workcontrol_post_upload" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Posts"/>
            <input type="hidden" name="callback_action" value="sendimage"/>
            <input type="hidden" name="post_id" value="<?= $PostId; ?>"/>
            <div class="upload_progress none" style="padding: 5px; background: #00B594; color: #fff; width: 0%; text-align: center; max-width: 100%;">0%</div>
            <div style="overflow: auto; max-height: 300px;" class="scrollbar">
                <img class="image image_default" alt="Nova Imagem" title="Nova Imagem" src="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>
            <div class="workcontrol_imageupload_actions">
                <input class="wc_loadimage" type="file" name="image" required/>
                <span title="Fechar" class="workcontrol_imageupload_close icon-cancel-circle btn btn_red" id="post_control" style="margin-right: 8px;">Fechar</span>
                <button title="Enviar e Inserir" class="btn btn_aquablue icon-image">Enviar e Inserir <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<div class="dashboard_content">

    <form class="auto_save" name="post_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Posts"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="post_id" value="<?= $PostId; ?>"/>

        <div class="box box70">
            <div class="panel_header darkaquablue">
                <h2 class="icon-blog">Dados Sobre o Post</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título:</span>
                    <input style="font-size: 1.2em;" type="text" name="post_title" value="<?= $post_title; ?>" placeholder="Informe o Título do Post" required/>
                </label>

                <label class="label">
                    <span class="legend">Subtítulo:</span>
                    <textarea name="post_subtitle" rows="3" placeholder="Informe o Subtítulo do Post"><?= $post_subtitle; ?></textarea>
                </label>
                
                <div class="category_selection">
                    <span class="category_selection_legend">Categoria:</span>
                    <span class="category_selection_title j_open_category_selection">
                        <?php
                        if (empty($post_category_parent)):
                            echo 'Selecione a(s) Categoria(s)';
                        else:
                            $Read->FullRead("SELECT category_title FROM " . DB_CATEGORIES . " WHERE FIND_IN_SET(category_id, :category) ORDER BY category_title ASC", "category={$post_category_parent}");
                            if ($Read->getResult()):
                                $cats = array();
                                foreach ($Read->getResult() as $POST):
                                    $cats[] = $POST['category_title'];
                                endforeach;

                                echo implode(', ', $cats);
                            endif;
                        endif;
                        ?>
                    </span>

                    <div class="category_selection_content j_category_selection_content">
                        <?php
                        $Read->FullRead("SELECT category_id, category_parent, category_title FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL ORDER BY category_title ASC");

                        function loopCat() {
                            global $Read, $post_category_parent;
                            if ($Read->getResult()):
                                foreach ($Read->getResult() as $CAT):
                                    $Read->FullRead("SELECT category_id, category_parent, category_title FROM " . DB_CATEGORIES . " WHERE category_parent = :parent ORDER BY category_title ASC", "parent={$CAT['category_id']}");
                                    $checked = (!empty($post_category_parent) && in_array($CAT['category_id'], explode(',', $post_category_parent)) ? ' checked="checked"' : '');

                                    echo "<li>";
                                    echo "<span>";
                                    echo "<i class='" . ($Read->getResult() ? "icon-plus icon-notext" : "icon-minus icon-notext") . "'></i>";
                                    echo "<input id='checkbox-category-{$CAT['category_id']}' class='j_category_selection multiple' type='checkbox' name='post_category_parent[]' value='{$CAT['category_id']}' data-title='{$CAT['category_title']}'" . (empty($CAT['category_parent']) ? ' disabled="disabled"' : '') . $checked . "/>";
                                    echo "<label" . (empty($CAT['category_parent']) ? ' class="disabled"' : '') . " for='checkbox-category-{$CAT['category_id']}'>{$CAT['category_title']}</label>";
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
                        loopCat();
                        echo "</ul>";
                        ?>
                    </div>
                </div>

                <label class="label">
                    <span class="legend">TAGS:</span>
                    <input type="text" name="post_tags" value="<?= $post_tags; ?>" list="tags" placeholder="Informe as Tags do Post"/>

                    <datalist id="tags">
                        <?php
                        $Read->FullRead("SELECT DISTINCT upper(post_tags) as post_tags FROM " . DB_POSTS . " WHERE post_tags IS NOT NULL AND post_tags != ''");
                        foreach ($Read->getResult() as $tags):
                            echo '<option value="' . $tags['post_tags'] . '">';
                        endforeach;
                        ?>
                    </datalist>
                </label>

                <?php if (APP_LINK_POSTS): ?>
                    <label class="label">
                        <span class="legend">Link Alternativo (Opcional):</span>
                        <input type="text" name="post_name" value="<?= $post_name; ?>" placeholder="Informe o Link do Artigo"/>
                    </label>
                <?php endif; ?>

                <label class="label">
                    <span class="legend">Vídeo: (Opcional)</span>
                    <input type="text" name="post_video" value="<?= $post_video; ?>" placeholder="Informe o Link do Vídeo"/>
                </label>

                <label class="label">
                    <span class="legend">Post:</span>
                    <textarea class="work_mce" rows="50" name="post_content"><?= $post_content; ?></textarea>
                </label>
            </div>
        </div>

        <div class="box box30">
            <div class="panel_header aquablue">
                <h2 class="icon-image">Imagem de Capa</h2>
            </div>
            <div class="post_create_cover">
                <div class="upload_progress none">0%</div>
                <?php
                $PostCover = (!empty($post_cover) && file_exists("../uploads/{$post_cover}") && !is_dir("../uploads/{$post_cover}") ? "uploads/{$post_cover}" : 'admin/_img/no_image.jpg');
                ?>
                <img class="post_thumb post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $PostCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $PostCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>

            <div class="panel">
                <label class="label">
                    <span class="legend">Capa: (JPG <?= IMAGE_W; ?>x<?= IMAGE_H; ?>px)</span>
                    <input type="file" class="wc_loadimage" name="post_cover"/>
                </label>
            </div>

            <div class="panel_header aquablue m_top">
                <h2 class="icon-share">Publicar</h2>
            </div>

            <div class="panel">

                <?php
                if (APP_POSTS_INSTANT_ARTICLE):
                    ?>
                <label class="label">
                    <span class="legend">INSTANT ARTICLE:</span>
                    <select name="post_instant_article" required>
                        <option value="0" <?= ($post_instant_article != '0' ? "selected='selected'" : ''); ?>>Não</option>
                        <option value="1" <?= ($post_instant_article == '1' ? "selected='selected'" : ''); ?>>Sim</option>
                    </select>
                </label>
                <?php 
                endif; 
                
                if (APP_POSTS_AMP):
                    ?>
                    <label class="label">
                        <span class="legend">AMP:</span>
                        <select name="post_amp" required>
                            <option value="0" <?= ($post_amp != '0' ? "selected='selected'" : ''); ?>>Não</option>
                            <option value="1" <?= ($post_amp == '1' ? "selected='selected'" : ''); ?>>Sim</option>
                        </select>
                    </label>
                <?php endif; ?>

                <label class="label">
                    <span class="legend">DIA:</span>
                    <input type="text" class="jwc_datepicker" data-timepicker="true" readonly="readonly" name="post_date" value="<?= $post_date ? date('d/m/Y H:i', strtotime($post_date)) : date('d/m/Y H:i'); ?>" required/>
                </label>

                <label class="label">
                    <span class="legend">AUTOR:</span>
                    <select name="post_author" required>
                        <option value="<?= $Admin['user_id']; ?>"><?= $Admin['user_name']; ?> <?= $Admin['user_lastname']; ?></option>
                        <?php
                        $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_level >= :lv AND user_id != :uid", "lv=6&uid={$Admin['user_id']}");
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $PostAuthors):
                                echo "<option";
                                if ($PostAuthors['user_id'] == $post_author):
                                    echo " selected='selected'";
                                endif;
                                echo " value='{$PostAuthors['user_id']}'>{$PostAuthors['user_name']} {$PostAuthors['user_lastname']}</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>

                <div class="m_top">&nbsp;</div>
                <div class="wc_actions" style="text-align: center">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    
                    <div class="switch__container" style="margin-bottom: 10px;">
                      <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='post_status' <?= ($post_status == 1 ? 'checked' : ''); ?>>
                      <label for="switch-shadow"></label>
                    </div>
                    
                    <!-- SWITCH 
                    <div class="switch_content_box_header">
                        <input id="switch-id-<?= $post_id; ?>" class="wc_switch_input" type="checkbox"
                               name="post_status"<?= ($post_status ? ' checked="checked"' : ''); ?>>
                        <label for="switch-id-<?= $post_id; ?>" class="wc_switch_label">
                        </label>
                    </div> -->
                </div>
                <div class="clear"></div>

                <?php
                $URLSHARE = "/artigo/{$post_name}";
                require '_tpl/Share.wc.php';
                ?>
            </div>
        </div>
    </form>
</div>