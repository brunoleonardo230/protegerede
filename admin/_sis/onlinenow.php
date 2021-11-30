<?php
$AdminLevel = 6;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$Where = "";
$ParseString = "";

$filter = filter_input_array(INPUT_GET, FILTER_DEFAULT);
if (!empty($filter['user'])):
    $Where = "AND online_user = :user";
    $ParseString = "user={$filter['user']}";
endif;

if (!empty($filter['cidade'])):
    $Where = "AND online_city = :ct";
    $ParseString = "ct={$filter['cidade']}";
endif;
if (!empty($filter['estado'])):
    $Where = "AND online_state = :st";
    $ParseString = "st={$filter['estado']}";
endif;
if (!empty($filter['pais'])):
    $Where = "AND online_country = :count";
    $ParseString = "count={$filter['pais']}";
endif;
if (!empty($filter['dispositivo'])):
    $Where = "AND online_device = :dev";
    $ParseString = "dev={$filter['dispositivo']}";
endif;

unset($filter['wc'])
?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-earth">Usuários Online Agora</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Online Agora
        </p>
    </div>
</header>

<div class="dashboard_content">

    <div class="box box100">

        <!--<a class="btn btn_blue icon-cross">Remover Filtro</a>-->
        <div class="panel_header darkaquablue">
            <span>
                <a href="javascript:void(0)" class="btn_header btn_aquablue icon-loop icon-notext" id="loopOnlineNow"></a>
            </span>
            <?php $Read->ExeRead(DB_VIEWS_ONLINE, "WHERE online_endview >= NOW() {$Where} ORDER BY online_endview DESC", "{$ParseString}"); ?>
            <h2 class="icon-earth jwc_onlinenow">ONLINE AGORA: <?= str_pad($Read->getRowCount(), 4, 0, 0); ?></h2>

            <?php
            if (!empty($filter)):
                $filtro_ativo = array_keys($filter);
                echo "<h2 class='icon-filter'>FILTRANDO POR : " . strtoupper($filtro_ativo[0]) . " <a style='text-decoration:none;color:#C54550;margin-left:10px!important;font-size:1.6em' title='Remover Filtro' class='icon-bin2 icon-notext' href='" . BASE . "/admin/dashboard.php?wc=onlinenow'></a></h2>";
            endif;
            ?>
        </div>
        <div class="panel">
            <div class="table-responsive scrollbar">
                <table class="table table-luna">
                    <thead>
                        <tr>
                            <th style="min-width:200px">Usuário</th>
                            <th style="min-width:180px">Horário de Acesso</th>
                            <th style="min-width:250px">URL</th>
                            <th style="min-width:160px">Dispositivo</th>
                            <th style="min-width:200px">Cidade</th>
                            <th style="min-width:200px">Estado</th>
                            <th style="min-width:200px">País</th>
                            <th style="min-width:130px">IP</th>
                        </tr>
                    </thead>
                    <tbody class="wc_onlinenow">
                        <?php
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $Online):
                                if (!is_null($Online['online_user'])):
                                    $Read->FullRead("SELECT CONCAT(user_name, ' ', user_lastname) AS Name FROM " . DB_USERS . " WHERE user_id = {$Online['online_user']}");
                                    $Name = $Read->getResult()[0]['Name'];
                                else:
                                    $Name = "Visitante";
                                endif;
                                $filter_url = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&url={$Online['online_url']}' title='Filtrar Por URL' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                                $filter_device = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&dispositivo={$Online['online_device']}' title='Filtrar Por Dispositivo' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                                $filter_city = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&cidade={$Online['online_city']}' title='Filtrar Por Cidade' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                                $filter_state = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&estado={$Online['online_state']}' title='Filtrar Por Estado' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                                $filter_country = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&pais={$Online['online_country']}' title='Filtrar Por País' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                                ?>
                                <tr>
                                    <td><?= $Name ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($Online['online_startview'])) ?></td>
                                    <td><?= $filter_url ?> <a class="table-link" target='_blank' href="<?= BASE . "/" . $Online['online_url'] ?>" title='Ver Destino'><?= ($Online['online_url'] ? $Online['online_url'] : 'home') ?></a></td>
                                    <td><?= $filter_device ?> <?= $Online['online_device'] ?></td>
                                    <td><?= $filter_city ?> <?= $Online['online_city'] ?></td>
                                    <td><?= $filter_state ?> <?= $Online['online_state'] ?></td>
                                    <td><?= $filter_country ?> <?= $Online['online_country'] ?></td>
                                    <td><?= $Online['online_ip'] ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    //ICON REFRESH IN DASHBOARD
    $('#loopOnlineNow').click(function () {
        OnlineNow();
    });

    //DASHBOARD REALTIME
    setInterval(function () {
        OnlineNow(
    <?= (!empty($filter['user']) ? $filter['user'] : "0"); ?>,
    <?= (!empty($filter['url']) ? "'{$filter['url']}'" : "0"); ?>,
    <?= (!empty($filter['cidade']) ? "'{$filter['cidade']}'" : "0"); ?>,
    <?= (!empty($filter['estado']) ? "'{$filter['estado']}'" : "0"); ?>,
    <?= (!empty($filter['pais']) ? "'{$filter['pais']}'" : "0"); ?>
            );
        }, 5000);
</script>