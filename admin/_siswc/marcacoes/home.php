<?php
$AdminLevel = LEVEL_WC_SCHEDULES;
if (!APP_SCHEDULES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
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
            Gerencie as Marcações de Consultas
        </p>
    </div>
</header>

<div class="dashboard_content">
    <div class="marketing">
        <div class="marketing__box">
            <form class="marketing__filter" autocomplete="off">
                <label class="marketing__search">
                    <input class="js-marketing-search" type="search" name="search" placeholder="Digite o Nome, E-mail ou Procedimento..."/>

                    <button title="Pesquisar" type="button">
                        <i class="icon-search icon-notext j-marketing-load"></i>
                    </button>
                </label>

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
                        <p>Nome</p>
                        <p>E-mail</p>
                        <p>Telefone</p>
                        <p>Data</p>
                        <p>Ações</p>
                    </div>
                </article>

                <div class="js-marketing-content">
                    <?php
                    $Read->FullRead(
                    "SELECT "
                    . "c.consultation_id, "
                    . "c.consultation_name, "
                    . "c.consultation_email, "
                    . "c.consultation_telephone, "
                    . "c.date, "
                    . "c.time "
                    . "FROM " . DB_CONSULTATIONS . " c "
                    . "ORDER BY date DESC LIMIT :limit OFFSET :offset", "limit=10&offset=0"
                    );
                    if (!$Read->getResult()):
                        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Marcações de Consultas Realizadas Pelo Site!</span>", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Consultation):
                            extract($Consultation);
        
                            echo "<article class='marketing__table js-marketing-table js-rel-to' id='{$consultation_id}'> <div class='marketing__data'> <p>" . Check::Chars($consultation_name, 25) . "</p> <p class='icon-envelop'>" . Check::Chars($consultation_email, 25) . "</p> <p class='icon-phone'>{$consultation_telephone}</p> <p class='icon-calendar'>" . date('d/m/Y', strtotime($date)) . "</p> <p> <a title='Responder' class='btn_header btn_darkaquablue icon-pencil icon-notext' href='dashboard.php?wc=marcacoes/view&id={$consultation_id}'></a> <span title='Excluir' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Consultation' callback_action='delete' id='{$consultation_id}'></span> </p> </div> </article>";
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>