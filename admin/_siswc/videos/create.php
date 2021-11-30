<?php
$AdminLevel = LEVEL_WC_VIDEOS;
if (!APP_VIDEOS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$PostId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PostId):
    $Read->ExeRead(DB_GALLERY_VIDEOS, "WHERE videos_id = :id", "id={$PostId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Post Que Não Existe ou Que Foi Removido Recentemente!";
        header('Location: dashboard.php?wc=videos/home');
    endif;
else:
    $PostCreate = ['videos_date' => date('Y-m-d H:i:s'), 'videos_status' => 0, 'videos_author' => $Admin['user_id']];
    $Create->ExeCreate(DB_GALLERY_VIDEOS, $PostCreate);
    header('Location: dashboard.php?wc=videos/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-video-camera"><?= $videos_title; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=videos/home">Vídeos</a>
            <span class="crumb">/</span>
            Novo Vídeo
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver Vídeo No Site" href="<?= BASE; ?>/video/<?= $videos_name; ?>" class="wc_view btn_header btn_darkaquablue icon-eye">Ver Vídeo No Site</a>
    </div>
</header>

<div class="workcontrol_imageupload none" id="post_control">
    <div class="workcontrol_imageupload_content">
        <form name="workcontrol_post_upload" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Videos"/>
            <input type="hidden" name="callback_action" value="sendimage"/>
            <input type="hidden" name="videos_id" value="<?= $PostId; ?>"/>
            <div class="upload_progress none" style="padding: 5px; background: #00B594; color: #fff; width: 0%; text-align: center; max-width: 100%;">0%</div>
            <div style="overflow: auto; max-height: 300px;">
                <img class="image image_default" alt="Nova Imagem" title="Nova Imagem" src="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>
            <div class="workcontrol_imageupload_actions">
                <input class="wc_loadimage" type="file" name="image" required/>
                <span class="workcontrol_imageupload_close icon-cancel-circle btn btn_red" id="post_control" style="margin-right: 8px;">Fechar</span>
                <button class="btn btn_aquablue icon-image">Enviar e Inserir <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<div class="dashboard_content">
    <form class="auto_save" name="post_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Videos"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="videos_id" value="<?= $PostId; ?>"/>

        <article class="box box70">
            <div class="panel_header darkaquablue">
                <h2 class="icon-video-camera">Dados Sobre o Vídeo</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título:</span>
                    <input style="font-size: 1.2em;" type="text" name="videos_title" value="<?= $videos_title; ?>" placeholder="Informe o Título" required/>
                </label>

                <label class="label">
                    <span class="legend">Subtítulo:</span>
                    <textarea name="videos_subtitle" rows="3" placeholder="Informe o Subtítulo" required><?= $videos_subtitle; ?></textarea>
                </label>

                <label class="label">
                    <span class="legend">Youtube: (https://www.youtube.com/watch?v=<b>2cjbSgy3vSw</b>)</span>
                    <input type="text" name="videos_link" value="<?= $videos_link ?>" placeholder="Informe o Link do Vídeo" required/>
                </label>

                <label class="label">
                    <span class="legend">Mensagem Final:</span>
                    <input type="text" name="videos_message" value="<?= $videos_message; ?>" placeholder="Informe a Mensagem"/>
                </label>
                
                <label class="label">
                    <span class="legend">Tags:</span>
                    <input type="text" name="videos_tags" value="<?= $videos_tags; ?>" placeholder="Informe as Tags" required/>
                </label>

                <label class="label">
                    <span class="legend">Descrição:</span>
                    <textarea class="work_mce" rows="10" name="videos_content"><?= $videos_content; ?></textarea>
                </label>
            </div>
        </article>

        <article class="box box30">
            <div class="panel_header aquablue">
                <h2 class="icon-image">Imagem de Capa</h2>
            </div>
            <div class="post_create_cover">
                <div class="upload_progress none">0%</div>
                <?php
                $VideoCover = (!empty($videos_cover) && file_exists("../uploads/{$videos_cover}") && !is_dir("../uploads/{$videos_cover}") ? "uploads/{$videos_cover}" : 'admin/_img/no_image.jpg');
                ?>
                <img class="videos_cover post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $VideoCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $VideoCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>

            <div class="panel">
                <label class="label">
                    <span class="legend">Capa: (JPG <?= IMAGE_W; ?>x<?= IMAGE_H; ?>px)</span>
                    <input type="file" class="wc_loadimage" name="videos_cover"/>
                </label>
            </div>

            <div class="panel_header aquablue m_top">
                <h2 class="icon-share">Publicar</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">DIA:</span>
                    <input type="text" class="jwc_datepicker" data-timepicker="true" readonly="readonly" name="videos_date" value="<?= $videos_date ? date('d/m/Y H:i', strtotime($videos_date)) : date('d/m/Y H:i'); ?>" required/>
                </label>

                <label class="label">
                    <span class="legend">AUTOR:</span>
                    <select name="videos_author" required>
                        <option value="<?= $Admin['user_id']; ?>"><?= $Admin['user_name']; ?> <?= $Admin['user_lastname']; ?></option>
                        <?php
                        $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_level >= :lv AND user_id != :uid", "lv=6&uid={$Admin['user_id']}");
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $PostAuthors):
                                echo "<option";
                                if ($PostAuthors['user_id'] == $videos_author):
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
                      <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='videos_status' <?= ($videos_status == 1 ? 'checked' : ''); ?>>
                      <label for="switch-shadow"></label>
                    </div>
                </div>    
                <div class="clear"></div>
            </div>
        </article>
    </form>
</div>