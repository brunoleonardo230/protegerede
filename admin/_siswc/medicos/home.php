<?php
$AdminLevel = LEVEL_WC_DOCTORS;
if (!APP_DOCTORS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$Read->ExeRead(DB_DOCTORS);
$Doctors = $Read->getResult();
$Total = count($Doctors);
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-user-tie">Médicos</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Todos os Médicos" href="dashboard.php?wc=medicos/home">Médicos</a>
        </p>
    </div>
    
    <div class="dashboard_header_search">
        <a title="Novo Médico" href="dashboard.php?wc=medicos/create" class="btn_header btn_darkaquablue icon-plus">Novo Médico</a>
    </div>
</header>

<div class="bs_ajax_modal js-marketing-mail" style="display: none;">
    <div class="bs_ajax_modal_box">
        <p class="bs_ajax_modal_title aquablue"><span class="icon-envelop">Nova Mensagem</span></p>
        <span title="Fechar" class="bs_ajax_modal_close icon-cross icon-notext js-modal-close" data-modal="js-marketing-mail"></span>
        <div class="bs_ajax_modal_content scrollbar">        

            <form method="post">
                <input type="hidden" name="callback" value="Doctors"/>
                <input type="hidden" name="callback_action" value="send"/>
                <input class="js-total-users" type="hidden" value="<?= $Total; ?>"/>
                <input class="js-users-checked" type="hidden" name="doctors" value=""/>
    
                <div class="modal__wrapper">
                    <div class="modal__label">
                        <span class="modal__group">
                            <i class="icon-user-tie icon-notext modal__icon"></i>
                        </span>
    
                        <span class="modal__count js-modal-toggle">
                            Selecione o(s) Médico(s)
                        </span>
    
                        <div class="modal__message js-modal-message">
                            <input class="modal__search js-modal-search" type="text" placeholder="Pesquisar">
    
                            <div class="modal__buttons">
                                <button class="modal__mark js-modal-mark" type="button">Marcar Todos</button>
                                <button class="modal__unmark js-modal-unmark" type="button">Desmarcar Todos</button>
                            </div>
    
                            <div class="modal__users">
                                <p class="modal__legend">Médicos</p>
    
                                <ul class="modal__list js-modal-content">
                                    <?php foreach ($Doctors as $Doctor):
                                        extract($Doctor); ?>
                                        <li class="modal__item">
                                            <span class="modal__link js-user-toggle" data-name="<?= $doctor_name; ?>" data-id="<?= $doctor_id; ?>">
                                                <?= $doctor_name; ?> <i class="icon-checkmark icon-notext modal__check"></i>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
    
                    <label class="modal__label">
                        <span class="modal__group">
                            <i class="icon-pencil icon-notext modal__icon"></i>
                        </span>
    
                        <input class="modal__field" type="text" name="subject" placeholder="Assunto da Mensagem">
                    </label>
    
                    <label class="modal__label">
                        <textarea name="body" class="work_mce_basic" rows="10"></textarea>
                    </label>
                </div>
    
                <div class="wc_actions" style="text-align: right;">
                    <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                </div>
            </form>
        </div>
                            
        <div class="bs_ajax_modal_footer">
            <p>Escolha os Médicos e Envie o E-mail Com a Mensagem Desejada!</p>
        </div>    
        <div class="clear"></div>
    </div>
</div>
<!-- FECHA MODAL DE ENVIO DE E-MAIL -->    


<div class="dashboard_content">
    <div class="marketing">
        <div class="marketing__box">
            <form class="marketing__filter" autocomplete="off">
                <label class="marketing__search">
                    <input class="js-marketing-search" type="search" name="search" placeholder="Digite o Nome, E-mail ou CRM do Médico..."/>

                    <button title="Pesquisar" type="button">
                        <i class="icon-search icon-notext j-marketing-load"></i>
                    </button>
                </label>
                
                <button title="Enviar E-mail" class="marketing__selected js-modal-open" type="button" data-modal="js-marketing-mail">
                    <i class="icon-envelop icon-notext"></i>
                </button>

                <div class="marketing__pagination">
                    <button title="Anterior" class="js-marketing-back" type="button" data-offset="0">
                        <i class="icon-arrow-left icon-notext"></i>
                    </button>

                    <button title="Recarregar" class="js-marketing-initial" type="button" data-offset="0">
                        <i class="icon-radio-checked icon-notext"></i>
                    </button>

                    <button title="Próximo" class="js-marketing-next" type="button" data-offset="0">
                        <i class="icon-arrow-right icon-notext"></i>
                    </button>
                </div>
            </form>

            <div class="marketing__content">

                <article class="marketing__table marketing__table--header">
                    <div class="marketing__data">
                        <p></p>
                        <p>Nome</p>
                        <p>E-mail</p>
                        <p>CRM</p>
                        <p>Telefone</p>
                        <p>Ações</p>
                    </div>
                </article>

                <div class="js-marketing-content">
                    <?php
                    $Read->ExeRead(DB_DOCTORS, 'LIMIT :limit OFFSET :offset', 'limit=10&offset=0');
                    $Doctors = $Read->getResult();
  
                    if (!$Read->getResult()):
                        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Médicos Cadastrados. Comece Agora Mesmo Cadastrando o Primeiro Dentista!</span>", E_USER_NOTICE);
                    else:
                        foreach ($Doctors as $Doctor):
                            extract($Doctor);
                            
                            if(empty($doctor_cover) && $doctor_genre == 1):
                                $DoctorImage = "../tim.php?src=admin/_img/avatarm.png&w=40&h=40";  
                            elseif(empty($doctor_cover) && $doctor_genre == 2):
                                $DoctorImage = "../tim.php?src=admin/_img/avatarf.png&w=40&h=40";   
                            else:
                                $DoctorImage = BASE . "/tim.php?src=uploads/{$doctor_cover}&w=40&h=40";    
                            endif;    
                            
                            $DoctorLink = "dashboard.php?wc=medicos/create&id={$doctor_id}";
        
                            echo "<article class='marketing__table js-marketing-table js-rel-to' id='{$doctor_id}'> <div class='marketing__data'> <p class='payment'> <span class='img'> <img src='{$DoctorImage}'/> </span> </p> <p>" . Check::Chars($doctor_name, 25) . "</p> <p class='icon-envelop'>" . Check::Chars($doctor_email, 25) . "</p> <p class=' icon-file-text'>{$doctor_number_advice}</p> <p class='icon-phone'>{$doctor_cell}</p> <p> <a title='Editar Médico' class='btn_header btn_darkaquablue icon-pencil icon-notext' href='{$DoctorLink}'></a> <span title='Excluir Médico' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Doctors' callback_action='delete' id='{$doctor_id}'></span> </p> </div> </article>";
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>