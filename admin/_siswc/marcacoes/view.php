<?php
$AdminLevel = LEVEL_WC_SCHEDULES;
if (!APP_SCHEDULES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$ConsultationId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$ConsultationId):
    $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['user_name']}</b>, Você Tentou Visualizar Uma Marcação de Consulta Que Não Existe!", E_USER_NOTICE);
    header('Location: dashboard.php?wc=marcacoes/home');
    exit;
else:
    $Read->ExeRead(DB_CONSULTATIONS, "WHERE consultation_id = :id", "id={$ConsultationId}");
    if ($Read->getResult()):
        extract($Read->getResult()[0]);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Visualizar Uma Marcação de Consulta Que Não Existe!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=marcacoes/home');
        exit;
    endif;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-mail2">Marcações de Consultas</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Marcações de Consultas" href="dashboard.php?wc=marcacoes/home">Marcações de Consultas</a>
            <span class="crumb">/</span>
            Solicitação de <?= $consultation_name; ?>
        </p>
    </div>
</header>

<div class="dashboard_content">
    <article class="box box30">
        <div class="panel_header darkaquablue">
            <h2 class="icon-profile">Informações do Paciente</h2>
        </div>
        <div class="panel">
            <p><b class="icon-profile">Nome:</b> <?= $consultation_name; ?></p>
            <p><b class="icon-envelop">E-mail:</b> <?= $consultation_email; ?></p>
            <p><b class="icon-phone">Telefone:</b> <?= $consultation_telephone; ?></p>
            <p><b class="icon-pencil">Mensagem:</b> <?= $consultation_message; ?></p>
            <p><b class="icon-bubbles">Status:</b> <?= ($consultation_status == 1 ? "<span style='color: #D90000'>Não Respondeu</span>" : ($consultation_status == 2 ? "<span style='color: #2DB200'>Respondeu</span>" : null)); ?></p>
        </div>
    </article>
    
    <article class="box box70">
        <div class="panel_header darkaquablue">
            <h2 class="icon-envelop">Responder Mensagem</h2>
        </div>
        <div class="panel">
            <form class="auto_save" name="consultation_status" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Consultation"/>
                <input type="hidden" name="callback_action" value="status"/>
                <input type="hidden" name="consultation_id" value="<?= $ConsultationId; ?>"/>
                <span style="display:block;margin-bottom: 30px;"><b>Já Respondeu o E-mail? </b>
                    <label class="label_check label_publish <?= ($consultation_status == 2 ? 'active' : ''); ?>"><input style="margin-top: -1px;" type="checkbox" value="2" name="consultation_status" <?= ($consultation_status == 2 ? 'checked' : ''); ?>> Marcar como respondido!</label>
                </span>
            </form>
            <form name="consultation_response" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Consultation"/>
                <input type="hidden" name="callback_action" value="response"/>
                <input type="hidden" name="consultation_id" value="<?= $ConsultationId; ?>"/>

                <label class="label">
                    <span class="legend">Escrever E-mail:</span>
                    <textarea class="work_mce_basic" name="consultation_response"><?= empty($consultation_response) ? "<h3>Olá {$consultation_name},</h3><p>Escreva o E-mail..</p>" : $consultation_response; ?></textarea>
                </label>

                <div class="wc_actions" style="text-align: right">
                    <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">RESPONDER <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                </div>
            </form>
        </div>
    </article>
</div>